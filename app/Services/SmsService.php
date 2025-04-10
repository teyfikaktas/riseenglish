<?php

namespace App\Services;

use App\Netgsm\Otp\otp;
use Illuminate\Support\Facades\Log;
use App\Models\Course;
use App\Models\User;
use App\Models\Homework;
use App\Models\Announcement;
use App\Models\HomeworkSubmission;
use App\Models\PrivateLessonSession;

class SmsService
{
    /**
     * Admin telefon numarası
     */
    const ADMIN_PHONE = '5541383539';

    /**
     * Varsayılan SMS başlık numarası
     */
    const DEFAULT_HEADER = '3326062804';

    /**
     * Telefon numarasını formatlama
     *
     * @param string $phoneNumber
     * @return string
     */
    public static function formatPhoneNumber($phoneNumber)
    {
        // Önce tüm özel karakterleri, boşlukları temizle
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Uluslararası formatı kontrol et (+90 veya 90 ile başlayanlar)
        if (substr($phoneNumber, 0, 2) === '90') {
            // Başındaki 90'ı kaldır
            $phoneNumber = substr($phoneNumber, 2);
        } else if (strlen($phoneNumber) > 10 && substr($phoneNumber, 0, 3) === '905') {
            // +905 veya 905 ile başlayan numaralar için baştan 90'ı kaldır
            $phoneNumber = substr($phoneNumber, 2);
        }
        
        // Başında 0 varsa kaldır
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = substr($phoneNumber, 1);
        }
        
        // Numaranın 10 haneli olduğunu doğrula (Türkiye için)
        if (strlen($phoneNumber) !== 10) {
            Log::warning('Geçersiz telefon numarası format: ' . $phoneNumber);
        }
        
        return $phoneNumber;
    }

    /**
     * SMS gönderme işlemi
     *
     * @param string $phoneNumber Alıcının telefon numarası
     * @param string $message SMS içeriği
     * @param string|null $header SMS başlık numarası (gönderen ID)
     * @return array|bool Başarılı ise true, başarısız ise hata mesajını içeren dizi
     */
    public static function sendSms($phoneNumber, $message, $header = null)
    {
        try {
            // Başlık numarası için varsayılan değeri kullan
            $header = $header ?: self::DEFAULT_HEADER;
            
            // Telefon numarasını formatla
            $formattedPhone = self::formatPhoneNumber($phoneNumber);
            
            // Log bilgisi
            Log::info('SMS gönderimi başlatılıyor', [
                'telefon' => $phoneNumber,
                'formatlanmış telefon' => $formattedPhone,
                'mesaj' => $message
            ]);
            
            // SMS gönderme işlemi
            $otpService = new otp();
            
            $response = $otpService->otp([
                'message' => $message,
                'no' => $formattedPhone,
                'header' => $header
            ]);
            
            // Response'u loglama
            Log::info('SMS gönderim yanıtı', [
                'response' => $response
            ]);
            
            // İşlem başarılı mı kontrol et
            if (is_array($response) && isset($response['code'])) {
                if ($response['code'] === '00' || $response['code'] === 0) {
                    return ['success' => true, 'message' => 'SMS başarıyla gönderildi.', 'data' => $response];
                } else {
                    $errorMessage = isset($response['message']) ? $response['message'] : 'Bilinmeyen SMS hatası';
                    return ['success' => false, 'message' => $errorMessage, 'data' => $response];
                }
            }
            
            return ['success' => true, 'message' => 'SMS başarıyla gönderildi.', 'data' => $response];
            
        } catch (\Exception $e) {
            // Hata durumunda
            Log::error('SMS gönderim hatası', [
                'hata' => $e->getMessage(),
                'dosya' => $e->getFile(),
                'satır' => $e->getLine()
            ]);
            
            return [
                'success' => false, 
                'message' => 'SMS gönderilirken bir hata oluştu: ' . $e->getMessage()
            ];
        }
    }
        /**
     * Ders tamamlandığında SMS gönderme fonksiyonu
     */
    private function sendCompletionSMS($session)
    {
        try {
            // Seans numarasını hesapla - sadece iptal edilmemiş seansları dahil et
            $sessionNumber = PrivateLessonSession::where('private_lesson_id', $session->private_lesson_id)
                ->where('status', '!=', 'cancelled') // İptal edilmiş dersleri hariç tut
                ->where('start_date', '<=', $session->start_date)
                ->orderBy('start_date', 'asc')
                ->orderBy('start_time', 'asc')
                ->get()
                ->search(function($item) use ($session) {
                    return $item->id === $session->id;
                }) + 1; // 0-bazlı indekse +1 ekleyerek 1-bazlı numaralandırma yapıyoruz
            
            // Temel bilgileri hazırla
            $studentName = $session->student ? $session->student->name : 'Öğrenci';
            $studentPhone = $session->student ? $session->student->phone : null;
            
            // Veli telefon numaralarını al
            $parentPhone = null;
            $parentPhone2 = null;
            
            if ($session->student && $session->student->parent_phone_number) {
                $parentPhone = $session->student->parent_phone_number;
            }
            
            if ($session->student && $session->student->parent_phone_number_2) {
                $parentPhone2 = $session->student->parent_phone_number_2;
            }
            
            $lessonName = $session->privateLesson ? $session->privateLesson->name : 'Ders';
            $lessonDate = Carbon::parse($session->start_date)->format('d.m.Y');
            $lessonTime = substr($session->start_time, 0, 5) . ' - ' . substr($session->end_time, 0, 5);
            
            // Log kayıtları
            Log::info("SMS gönderimi için hazırlık yapılıyor. Ders ID: " . $session->id);
            Log::info("Bu dersin {$sessionNumber}. seansı tamamlandı (iptal edilenler hariç).");
            
            // SMS sonuçlarını takip et
            $smsResults = [];
            
            // Öğrenci için SMS içeriği
            if ($studentPhone) {
                $studentSmsContent = "Sayın Öğrenci, {$lessonDate} tarihindeki {$lessonName} dersinin {$sessionNumber}. seansı başarıyla tamamlanmıştır. Ders saati: {$lessonTime}. İyi günler dileriz.";
                
                // SMS servisini çağır
                $studentResult = \App\Services\SmsService::sendSms($studentPhone, $studentSmsContent);
                $smsResults[] = [
                    'recipient' => 'Öğrenci',
                    'phone' => $studentPhone,
                    'result' => $studentResult
                ];
            }
            
            // Veli için SMS içeriği
            $parentSmsContent = "Sayın Veli, {$studentName} adlı öğrencinin {$lessonDate} tarihindeki {$lessonName} dersinin {$sessionNumber}. seansı başarıyla tamamlanmıştır. Ders saati: {$lessonTime}. İyi günler dileriz.";
            
            // 1. Veliye SMS gönder
            if ($parentPhone) {
                $parentResult = \App\Services\SmsService::sendSms($parentPhone, $parentSmsContent);
                $smsResults[] = [
                    'recipient' => 'Veli-1',
                    'phone' => $parentPhone,
                    'result' => $parentResult
                ];
            }
            
            // 2. Veliye SMS gönder
            if ($parentPhone2) {
                $parent2Result = \App\Services\SmsService::sendSms($parentPhone2, $parentSmsContent);
                $smsResults[] = [
                    'recipient' => 'Veli-2',
                    'phone' => $parentPhone2,
                    'result' => $parent2Result
                ];
            }
            
            // Sonuçları logla
            foreach ($smsResults as $result) {
                $status = isset($result['result']['success']) && $result['result']['success'] ? 'Başarılı' : 'Başarısız';
                $message = isset($result['result']['message']) ? $result['result']['message'] : 'Bilinmeyen sonuç';
                
                Log::info("SMS gönderimi ({$result['recipient']} - {$result['phone']}): {$status} - {$message}");
            }
            
            // En az bir başarılı gönderim var mı kontrol et
            $anySuccess = false;
            foreach ($smsResults as $result) {
                if (isset($result['result']['success']) && $result['result']['success']) {
                    $anySuccess = true;
                    break;
                }
            }
            
            return [
                'success' => $anySuccess,
                'results' => $smsResults,
                'session_number' => $sessionNumber
            ];
            
        } catch (\Exception $e) {
            Log::error("SMS gönderimi sırasında hata: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    /**
     * Birden çok alıcıya aynı mesajı gönder
     *
     * @param array $phoneNumbers Alıcı telefon numaraları dizisi
     * @param string $message SMS içeriği
     * @param string|null $header SMS başlık numarası (gönderen ID)
     * @return array Sonuç bilgisi
     */
    public static function sendBulkSms($phoneNumbers, $message, $header = null)
    {
        $results = [];
        $success = 0;
        $failed = 0;
        
        foreach ($phoneNumbers as $phone) {
            $result = self::sendSms($phone, $message, $header);
            $results[] = [
                'phone' => $phone,
                'result' => $result
            ];
            
            if ($result['success']) {
                $success++;
            } else {
                $failed++;
            }
        }
        
        return [
            'success' => $success > 0,
            'message' => "Toplam: " . count($phoneNumbers) . ", Başarılı: $success, Başarısız: $failed",
            'details' => $results
        ];
    }
    
    /**
     * Sadece admin'e SMS gönder
     *
     * @param string $message SMS içeriği
     * @param string|null $header SMS başlık numarası (gönderen ID)
     * @return array Sonuç bilgisi
     */
    public static function sendToAdmin($message, $header = null)
    {
        Log::info('Admin\'e SMS gönderiliyor', [
            'admin_telefon' => self::ADMIN_PHONE,
            'mesaj' => $message
        ]);
        
        return self::sendSms(self::ADMIN_PHONE, $message, $header);
    }
    
    /**
     * Uygulama hatası durumunda admin'e bildirim gönder
     *
     * @param string $errorMessage Hata mesajı
     * @param array $context Bağlam bilgileri
     * @return array Sonuç bilgisi
     */
    public static function notifyAdminAboutError($errorMessage, $context = [])
    {
        $message = "HATA UYARISI: " . $errorMessage;
        
        // Bağlam bilgilerini kısaltarak mesaja ekle
        if (!empty($context)) {
            $contextStr = json_encode($context, JSON_UNESCAPED_UNICODE);
            if (strlen($contextStr) > 80) {
                $contextStr = substr($contextStr, 0, 77) . '...';
            }
            $message .= " | " . $contextStr;
        }
        
        // Mesaj uzunluğu kontrolü
        if (strlen($message) > 160) {
            $message = substr($message, 0, 157) . '...';
        }
        
        Log::warning('Admin\'e hata bildirimi gönderiliyor', [
            'hata' => $errorMessage,
            'bağlam' => $context
        ]);
        
        return self::sendToAdmin($message);
    }
    
    /**
     * Önemli sistem olayları için admin'e bildirim gönder
     *
     * @param string $eventType Olay tipi (sipariş, kayıt, vb.)
     * @param string $message Bildirim mesajı
     * @return array Sonuç bilgisi
     */
    public static function notifyAdminAboutEvent($eventType, $message)
    {
        $fullMessage = strtoupper($eventType) . ": " . $message;
        
        // Mesaj uzunluğu kontrolü
        if (strlen($fullMessage) > 160) {
            $fullMessage = substr($fullMessage, 0, 157) . '...';
        }
        
        Log::info('Admin\'e olay bildirimi gönderiliyor', [
            'olay_tipi' => $eventType,
            'mesaj' => $message
        ]);
        
        return self::sendToAdmin($fullMessage);
    }
     /**
     * Kurs duyurularında tüm öğrencilere ve velilere SMS gönder
     *
     * @param int $courseId Kurs ID
     * @param int $announcementId Duyuru ID
     * @param int $teacherId Öğretmen ID
     * @return array Sonuç bilgisi
     */
    public static function sendCourseAnnouncementNotification($courseId, $announcementId, $teacherId)
    {
        try {
            // Kursu ve duyuruyu getir
            $course = Course::findOrFail($courseId);
            $announcement = Announcement::findOrFail($announcementId);
            $teacher = User::findOrFail($teacherId);
            
            // Log bilgisi
            Log::info('Kurs duyuru SMS bildirimi başlatılıyor', [
                'kurs_id' => $courseId,
                'kurs_adı' => $course->name,
                'duyuru_id' => $announcementId,
                'duyuru_başlık' => $announcement->title,
                'öğretmen' => $teacher->name
            ]);
            
            // Kursa kayıtlı ve onaylı (approval_status=1) öğrencileri getir
            $students = $course->students()
                ->wherePivot('approval_status', 1)
                ->wherePivot('status_id', 1) // Aktif öğrenciler (status_id=1)
                ->get();
            
            if ($students->isEmpty()) {
                Log::warning('Bu kursta onaylanmış öğrenci bulunmamaktadır.', [
                    'kurs_id' => $courseId,
                    'kurs_adı' => $course->name
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Bu kursta bildirim gönderilecek onaylanmış öğrenci bulunmamaktadır.',
                    'sent_count' => 0,
                    'error_count' => 0
                ];
            }
            
            // SMS başarı ve hata sayaçları
            $sentCount = 0;
            $errorCount = 0;
            $results = [];
            
            // Bildirim için hazırlanan SMS mesajı
            $message = "Sevgili {ÖĞRENCİ}, {KURS} kursu için {ÖĞRETMEN} yeni bir duyuru oluşturmuştur. Detaylar için lütfen kurs sayfasını ziyaret ediniz.";
            
            // Her öğrenci için SMS gönder
            foreach ($students as $student) {
                // Öğrenciye özel mesaj
                $personalizedMessage = str_replace(
                    ['{ÖĞRENCİ}', '{KURS}', '{ÖĞRETMEN}'],
                    [$student->name, $course->name, $teacher->name],
                    $message
                );
                
                // Öğrenciye SMS gönder
                if ($student->phone) {
                    $studentResult = self::sendSms($student->phone, $personalizedMessage);
                    
                    if ($studentResult['success']) {
                        $sentCount++;
                    } else {
                        $errorCount++;
                    }
                    
                    $results[] = [
                        'type' => 'student',
                        'user_id' => $student->id,
                        'name' => $student->name,
                        'phone' => $student->phone,
                        'result' => $studentResult
                    ];
                }
                
                // Öğrencinin velisine SMS gönder (eğer parent_phone_number alanı varsa)
                if (isset($student->parent_phone_number) && !empty($student->parent_phone_number)) {
                    // Veliye özel mesaj
                    $parentMessage = str_replace(
                        ['{ÖĞRENCİ}', '{KURS}', '{ÖĞRETMEN}'],
                        [$student->name, $course->name, $teacher->name],
                        "Sevgili Veli, {ÖĞRENCİ} adlı öğrencinizin {KURS} kursunda {ÖĞRETMEN} yeni bir duyuru paylaşmıştır."
                    );
                    
                    $parentResult = self::sendSms($student->parent_phone_number, $parentMessage);
                    
                    if ($parentResult['success']) {
                        $sentCount++;
                    } else {
                        $errorCount++;
                    }
                    
                    $results[] = [
                        'type' => 'parent',
                        'student_id' => $student->id,
                        'student_name' => $student->name,
                        'phone' => $student->parent_phone_number,
                        'result' => $parentResult
                    ];
                }
            }
            
            // Tüm sonuçları log'a kaydet
            Log::info('Kurs duyuru bildirimi tamamlandı', [
                'kurs_id' => $courseId,
                'duyuru_id' => $announcementId,
                'gönderilen' => $sentCount,
                'hata' => $errorCount
            ]);
            
            return [
                'success' => $sentCount > 0,
                'message' => "Toplam {$sentCount} SMS başarıyla gönderildi. {$errorCount} hata oluştu.",
                'sent_count' => $sentCount,
                'error_count' => $errorCount,
                'details' => $results
            ];
            
        } catch (\Exception $e) {
            Log::error('Kurs duyuru bildirimi hatası', [
                'kurs_id' => $courseId,
                'duyuru_id' => $announcementId,
                'hata' => $e->getMessage(),
                'dosya' => $e->getFile(),
                'satır' => $e->getLine()
            ]);
            
            return [
                'success' => false,
                'message' => 'Bildirim gönderilirken bir hata oluştu: ' . $e->getMessage(),
                'sent_count' => 0,
                'error_count' => 0
            ];
        }
    }
}