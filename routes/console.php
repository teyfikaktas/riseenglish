<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use App\Models\PrivateLessonSession;
use App\Models\User;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Öğrencilere ders hatırlatma SMS'i gönderme komutu
// Öğrencilere ders hatırlatma SMS'i gönderme komutu
Artisan::command('lessons:send-reminders', function () {
    /** @var ClosureCommand $this */
    $this->comment('Ders hatırlatma SMS\'leri gönderiliyor...');
    
    // Şu anki zaman
    $now = Carbon::now();
    
    // 30 dakika sonraki zaman
    $reminderTime = $now->copy()->addMinutes(30);
    
    // Bugünün haftanın hangi günü olduğunu alalım (1-7, 1:Pazartesi)
    $dayOfWeek = $now->dayOfWeek;
    if ($dayOfWeek == 0) $dayOfWeek = 7; // Carbon'da Pazar 0, biz 7 olarak kullanalım
    
    $this->comment('Şu anki tarih: ' . $now->format('Y-m-d H:i:s'));
    $this->comment('Hatırlatma zamanı: ' . $reminderTime->format('Y-m-d H:i:s'));
    
    // Sadece onaylı (approved) veya tamamlanmış (completed) olan, bugün gerçekleşecek dersleri bulalım
    $sessions = PrivateLessonSession::whereIn('status', ['approved', 'completed'])
        ->where('day_of_week', $dayOfWeek)
        ->where(function ($query) use ($now) {
            // Dersin başlangıç tarihinin bugünden önce veya bugün olması gerekiyor
            $query->where('start_date', '<=', $now->format('Y-m-d'));
            
            // Ve dersin bitiş tarihi ya belirtilmemiş ya da bugünden sonra olmalı
            $query->where(function ($q) use ($now) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $now->format('Y-m-d'));
            });
        })
        ->orderBy('created_at', 'desc') // En son oluşturulan dersleri önce getir
        ->get();
    
    $remindersSent = 0;
    $processedStudents = []; // İşlenmiş öğrencileri takip et
    
    foreach ($sessions as $session) {
        // Dersin başlama saatini al
        $sessionStartTime = Carbon::createFromFormat('H:i:s', $session->start_time);
        
        // Bugünün tarihini kullanarak bir tam datetime oluştur
        $sessionDateTime = Carbon::today()->setTime(
            $sessionStartTime->hour, 
            $sessionStartTime->minute, 
            $sessionStartTime->second
        );
        
        // Eğer ders saati şu andan belirli bir süre sonraya denk geliyorsa (tolerans ekli)
        $diffInMinutes = $now->diffInMinutes($sessionDateTime, false);
        
        if ($diffInMinutes >= 5 && $diffInMinutes <= 35) {
            // SADECE HER ÖĞRENCİ İÇİN İLK (SON) DERSİ İŞLE
            $studentSessionKey = $session->student_id . '_' . $session->start_time;
            
            if (in_array($studentSessionKey, $processedStudents)) {
                $this->comment("Öğrenci ID {$session->student_id} için bu saatte zaten SMS gönderildi, atlanıyor...");
                continue; // Bu öğrenci için bu saatte zaten işlem yapıldı
            }
            
            // Bu öğrenciyi işlenmiş olarak işaretle
            $processedStudents[] = $studentSessionKey;
            
            // Öğrenciyi bul
            $student = User::find($session->student_id);
            $teacher = User::find($session->teacher_id);
            
            if ($student && $teacher) {
                // Son günün tarihini kontrol et - aynı günde tekrar SMS göndermeyi önler
                $todayKey = $now->format('Y-m-d');
                $masterReminderKey = "lesson_reminder_master_{$session->student_id}_{$todayKey}_{$session->start_time}";
                $masterReminderSent = \Illuminate\Support\Facades\Cache::has($masterReminderKey);
                
                if ($masterReminderSent) {
                    $this->comment("Öğrenci {$student->name} için bugün bu saatte zaten SMS gönderilmiş.");
                    continue;
                }
                
                // SMS'leri gönder - hem öğrenciye hem de velilere
                $phones = [];
                $sentCount = 0;
                
                // Öğrenci telefonu
                if (!empty($student->phone)) {
                    $phones[] = [
                        'number' => $student->phone,
                        'type' => 'Öğrenci'
                    ];
                }
                
                // 1. Veli telefonu
                if (!empty($student->parent_phone_number)) {
                    $phones[] = [
                        'number' => $student->parent_phone_number,
                        'type' => 'Veli-1'
                    ];
                }
                
                // 2. Veli telefonu
                if (!empty($student->parent_phone_number_2)) {
                    $phones[] = [
                        'number' => $student->parent_phone_number_2,
                        'type' => 'Veli-2'
                    ];
                }
                
                // TOPLAM DERS SAYISINI BUL (Bu student için aynı private_lesson_id'ye sahip onaylı derslerin sayısı)
                $totalSessionCount = PrivateLessonSession::where('private_lesson_id', $session->private_lesson_id)
                    ->whereIn('status', ['approved', 'completed'])
                    ->where('student_id', $session->student_id)
                    ->count();
                
                if (count($phones) > 0) {
                    // Her numara için SMS gönder
                    foreach ($phones as $phone) {
                        // SMS içeriği - öğrenci veya veli için
                        $recipientType = $phone['type'] === 'Öğrenci' ? "Sayın {$student->name}" : "Sayın Veli";
                        
                        // Ders numarasını dinamik olarak hesapla: bu session'ın bu student için kaçıncı ders olduğunu bul
                        $currentSessionNumber = PrivateLessonSession::where('private_lesson_id', $session->private_lesson_id)
                            ->where('student_id', $session->student_id)
                            ->whereIn('status', ['approved', 'completed'])
                            ->where('created_at', '<=', $session->created_at)
                            ->count();
                        
                        $smsContent = "{$recipientType}, bugün saat {$session->start_time} - {$session->end_time} arasında {$teacher->name} hocamız ile {$currentSessionNumber}. dersiniz bulunmaktadır. Bilgilerinize Risenglish.";
                        
                        // SMS gönder
                        try {
                            $result = \App\Services\SmsService::sendSms($phone['number'], $smsContent);
                            
                            if ($result) {
                                $this->comment("SMS başarıyla gönderildi: {$phone['type']} - {$phone['number']}");
                                $sentCount++;
                                $remindersSent++;
                                
                                // Loglama
                                Log::info("Ders hatırlatma SMS'i gönderildi", [
                                    'student_id' => $student->id,
                                    'student_name' => $student->name,
                                    'recipient_type' => $phone['type'],
                                    'phone' => $phone['number'],
                                    'teacher_name' => $teacher->name,
                                    'session_id' => $session->id,
                                    'start_time' => $session->start_time,
                                    'current_session_number' => $currentSessionNumber,
                                    'total_sessions' => $totalSessionCount,
                                    'master_cache_key' => $masterReminderKey
                                ]);
                            } else {
                                $this->error("SMS gönderilemedi: {$phone['type']} - {$phone['number']}");
                                
                                // Hata logla
                                Log::error("Ders hatırlatma SMS'i gönderilemedi", [
                                    'student_id' => $student->id,
                                    'student_name' => $student->name,
                                    'recipient_type' => $phone['type'],
                                    'phone' => $phone['number']
                                ]);
                            }
                        } catch (\Exception $e) {
                            $this->error("SMS gönderimi sırasında hata: " . $e->getMessage());
                            Log::error("SMS gönderimi sırasında hata", [
                                'error' => $e->getMessage(),
                                'student_id' => $student->id,
                                'recipient_type' => $phone['type'],
                                'phone' => $phone['number']
                            ]);
                        }
                    }
                    
                    // Başarıyla SMS gönderildiyse, bu öğrenci için bugün bu saatte SMS gönderildiğini kaydet
                    if ($sentCount > 0) {
                        \Illuminate\Support\Facades\Cache::put($masterReminderKey, true, Carbon::now()->addHours(24));
                        $this->comment("Öğrenci {$student->name} için toplam {$sentCount} adet SMS gönderildi ve master cache kaydedildi.");
                    }
                } else {
                    $this->warn("Öğrenci {$student->name} (ID: {$student->id}) için hiçbir telefon numarası bulunamadı.");
                }
            }
        }
    }
    
    $this->comment("Toplam {$remindersSent} adet hatırlatma SMS'i gönderildi.");
})->purpose('Dersleri başlamadan 30 dakika önce öğrencilere SMS hatırlatması gönder - Her öğrenci için sadece son ders');

// SMS hatırlatma sistemini zamanla
Schedule::command('lessons:send-reminders')->everyFiveMinutes();

// Diğer zamanlanmış görevler buraya eklenebilir
// Schedule::call(function () {
//     Log::info('Şu anki dakika: ' . now()->format('Y-m-d H:i'));
// })->everyMinute();