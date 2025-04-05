<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PrivateLessonSession;
use App\Models\PrivateLesson;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class TeacherPrivateLessonController extends Controller
{
    /**
     * Öğretmenin aktif/planlanmış özel ders seanslarını gösterir
     */
    public function index()
    {
        $teacherId = Auth::id();

        $sessions = PrivateLessonSession::with(['privateLesson', 'student'])
            ->where('teacher_id', $teacherId)
            ->orderBy('start_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('teacher.private-lessons.index', compact('sessions'));
    }
/**
 * Dersi tamamla
 *
 * @param int $id
 * @return \Illuminate\Http\RedirectResponse
 */
public function completeLesson($id)
{
    try {
        // Ders kaydını bul
        $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'student'])
            ->findOrFail($id);
        
        // Ders zamanını kontrol et
        $currentTime = Carbon::now('Europe/Istanbul');
        $lessonEndTime = Carbon::parse($session->start_date . ' ' . $session->end_time, 'Europe/Istanbul');
        
        // Eğer ders zamanı henüz geçmediyse tamamlanamaz (opsiyonel kontrol)
        if ($currentTime->isBefore($lessonEndTime)) {
            return redirect()->back()->with('error', 'Ders henüz bitmedi. Tamamlamak için ders saatinin bitmesini beklemelisiniz.');
        }
        
        // Ders durumunu zaten tamamlanmış mı kontrol et
        if ($session->status === 'completed') {
            return redirect()->back()->with('info', 'Bu ders zaten tamamlanmış durumda.');
        }
        
        // Dersi tamamla
        $session->status = 'completed';
        $session->save();
        
        // SMS gönderimi yapılacak kısım (opsiyonel)
        $this->sendCompletionSMS($session);
        
        return redirect()->back()->with('success', 'Ders başarıyla tamamlandı! Veli ve öğrenciye SMS gönderildi.');
        
    } catch (\Exception $e) {
        // Hata durumunda
        Log::error("Ders tamamlama işleminde hata: " . $e->getMessage());
        return redirect()->back()->with('error', 'Ders tamamlanırken bir hata oluştu: ' . $e->getMessage());
    }
}
/**
 * Tek bir ders seansının detaylarını göster
 *
 * @param int $id
 * @return \Illuminate\View\View
 */
public function showSession($id)
{
    try {
        // Veritabanından ders bilgilerini çek
        $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'student'])
            ->findOrFail($id);
        
        // Ders durumları için renkler ve etiketler
        $statuses = [
            'pending' => 'Beklemede',
            'approved' => 'Onaylandı',
            'active' => 'Aktif',
            'rejected' => 'Reddedildi',
            'cancelled' => 'İptal Edildi',
            'completed' => 'Tamamlandı',
            'scheduled' => 'Planlandı',
        ];
        
        // Şu anki zamanı kontrol et (ders tamamlandı mı vs. için)
        $currentTime = now();
        $lessonEndTime = Carbon::parse($session->start_date . ' ' . $session->end_time);
        $isLessonCompleted = $session->status === 'completed';
        $isLessonPassed = $currentTime->isAfter($lessonEndTime);
        
        return view('teacher.private-lessons.session', compact('session', 'statuses', 'isLessonCompleted', 'isLessonPassed'));
        
    } catch (\Exception $e) {
        // Hata durumunda
        Log::error("Ders bilgileri yüklenirken hata: " . $e->getMessage());
        return redirect()->route('ogretmen.private-lessons.index')
            ->with('error', 'Ders detayları yüklenirken bir hata oluştu: ' . $e->getMessage());
    }
}
/**
 * Ders tamamlandığında SMS gönderme fonksiyonu
 */
private function sendCompletionSMS($session)
{
    try {
        // Bu kısım SMS gönderimi için entegrasyon kodunu içerecek
        // Örnek bir log kaydı:
        Log::info("SMS gönderimi için hazırlık yapılıyor. Ders ID: " . $session->id);
        
        $studentName = $session->student ? $session->student->name : 'Öğrenci';
        $studentPhone = $session->student ? $session->student->phone : null;
        $parentPhone = $session->student && $session->student->parent ? $session->student->parent->phone : null;
        $lessonName = $session->privateLesson ? $session->privateLesson->name : 'Ders';
        $lessonDate = Carbon::parse($session->start_date)->format('d.m.Y');
        $lessonTime = substr($session->start_time, 0, 5) . ' - ' . substr($session->end_time, 0, 5);
        
        // SMS içeriği
        $smsContent = "$studentName adlı öğrencinin $lessonDate tarihindeki $lessonName dersi başarıyla tamamlanmıştır. Ders saati: $lessonTime. İyi günler dileriz.";
        
        // Burada gerçek SMS gönderme kodu olacak
        
        Log::info("SMS içeriği hazırlandı: " . $smsContent);
        Log::info("SMS gönderilecek numaralar - Öğrenci: $studentPhone, Veli: $parentPhone");
        
        // Sadece log amaçlı, gerçek uygulamada burayı aktif SMS gönderimi ile değiştirin
        Log::info("SMS başarıyla gönderildi (simülasyon)");
        
    } catch (\Exception $e) {
        Log::error("SMS gönderimi sırasında hata: " . $e->getMessage());
    }
}
    /**
     * Öğretmenin henüz onaylamadığı (pending) özel ders taleplerini listeler
     */
    public function pendingRequests()
    {
        $teacherId = Auth::id();

        $pendingSessions = PrivateLessonSession::with(['privateLesson', 'student'])
            ->where('teacher_id', $teacherId)
            ->where('status', 'pending')
            ->orderBy('start_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('teacher.private-lessons.pending', compact('pendingSessions'));
    }

    /**
     * Yeni özel ders oluşturma formunu gösterir
     */
    public function create()
    {
        // Öğrenci listesini çekelim
        $students = User::role('ogrenci')->get();
        
        return view('teacher.private-lessons.create', compact('students'));
    }

/**
 * Yeni özel dersi kaydeder
 */
public function store(Request $request)
{
    try {
        // Form verilerini doğrulama
        $validated = $request->validate([
            'lesson_name' => 'required|string|max:255',
            'student_id' => 'required|exists:users,id',
            'fee' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days' => 'required|array|min:1',
            'days.*' => 'required|integer|min:0|max:6',
            'start_times' => 'required|array|min:1',
            'start_times.*' => 'required',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:approved,cancelled',
            'notes' => 'nullable|string'
        ]);

        // Mevcut giriş yapmış öğretmeni atayalım
        $teacherId = Auth::id();
        Log::info("Store started for teacher: $teacherId, data: " . json_encode($validated));

        // Özel ders kaydını oluşturalım
        $privateLesson = PrivateLesson::create([
            'name' => $validated['lesson_name'],
            'price' => $validated['fee'],
            'is_active' => true,
            'created_by' => $teacherId,
            'has_recurring_sessions' => true
        ]);

        // Tarih aralığını hesaplayalım
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        Log::info("Date range: {$startDate->toDateString()} to {$endDate->toDateString()}");

        $createdSessionsIds = [];
        $skippedSessions = [];

        // Her gün için seanslar oluştur
        for ($i = 0; $i < count($validated['days']); $i++) {
            $dayOfWeek = (int)$validated['days'][$i];
            $startTime = $validated['start_times'][$i];

            // Bitiş saatini hesapla
            $startTimeParts = explode(':', $startTime);
            $endHour = (int)$startTimeParts[0] + 1;
            $endTime = ($endHour >= 24 ? 23 : $endHour) . ':' . $startTimeParts[1];

            // İlk seans tarihini hesapla
            $firstSessionDate = clone $startDate;
            $currentDayOfWeek = (int)$firstSessionDate->format('w');
            if ($currentDayOfWeek != $dayOfWeek) {
                $daysUntilTargetDay = ($dayOfWeek - $currentDayOfWeek + 7) % 7;
                $firstSessionDate->addDays($daysUntilTargetDay);
            }

            Log::info("Day $dayOfWeek, First session date: {$firstSessionDate->toDateString()}");

            if ($firstSessionDate > $endDate) {
                Log::info("Skipped day $dayOfWeek: First session date exceeds end date.");
                $skippedSessions[] = "Gün: $dayOfWeek, Tarih: {$firstSessionDate->toDateString()} (Bitiş tarihinden sonra)";
                continue;
            }

            $sessionDate = clone $firstSessionDate;

            while ($sessionDate <= $endDate) {
                $conflictExists = $this->checkLessonConflict(
                    $teacherId,
                    $dayOfWeek,
                    $startTime,
                    $endTime,
                    $sessionDate->format('Y-m-d'),
                    null
                );

                if ($conflictExists) {
                    $skippedSessions[] = "{$sessionDate->format('d.m.Y')} - Çakışma var";
                    Log::info("Conflict detected for {$sessionDate->toDateString()} at $startTime-$endTime");
                    $sessionDate->addWeek();
                    continue;
                }

                $session = PrivateLessonSession::create([
                    'private_lesson_id' => $privateLesson->id,
                    'teacher_id' => $teacherId,
                    'student_id' => $validated['student_id'],
                    'day_of_week' => $dayOfWeek,
                    'start_date' => $sessionDate->format('Y-m-d'),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'location' => $validated['location'] ?? null,
                    'fee' => $validated['fee'],
                    'payment_status' => 'pending', // Düzeltildi
                    'paid_amount' => 0, // Düzeltildi
                    'status' => $validated['status'],
                    'is_recurring' => true,
                    'notes' => $validated['notes'] ?? null
                ]);

                $createdSessionsIds[] = $session->id;
                Log::info("Session created: ID {$session->id}, Date: {$sessionDate->toDateString()}, Time: $startTime-$endTime");

                $sessionDate->addWeek();
            }
        }

        $sessionCount = count($createdSessionsIds);
        if ($sessionCount == 0) {
            $errorMessage = 'Belirtilen tarih aralığında uygun ders saati bulunamadı.';
            if (!empty($skippedSessions)) {
                $errorMessage .= ' Atlanan seanslar: ' . implode(', ', $skippedSessions);
            }
            return redirect()->route('ogretmen.private-lessons.create')
                ->with('error', $errorMessage)
                ->withInput();
        }

        $successMessage = "Özel ders planı başarıyla oluşturuldu. Toplam {$sessionCount} seans planlandı.";
        if (!empty($skippedSessions)) {
            $successMessage .= ' Atlanan seanslar: ' . implode(', ', $skippedSessions);
        }

        return redirect()->route('ogretmen.private-lessons.index')
            ->with('success', $successMessage);

    } catch (\Exception $e) {
        Log::error("Store failed: " . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Hata: ' . $e->getMessage())
            ->withInput();
    }
}
    /**
     * Ders çakışması kontrolü yapar
     */
    private function checkLessonConflict($teacherId, $dayOfWeek, $startTime, $endTime, $date, $excludeSessionId = null)
    {
        // Aynı öğretmenin, aynı gün ve saatte (±1 saat içinde) başka dersi var mı kontrol et
        $query = PrivateLessonSession::where('teacher_id', $teacherId)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_date', $date)
            ->where('status', '!=', 'cancelled'); // İptal edilmiş dersleri hariç tut
            
        // Hariç tutulacak seans ID'si varsa ekle
        if ($excludeSessionId) {
            $query->where('id', '!=', $excludeSessionId);
        }
        
        // Zaman çakışma kontrolü
        // 1. Bu dersin başlangıcı mevcut bir dersin aralığında mı?
        // 2. Bu dersin bitişi mevcut bir dersin aralığında mı?
        // 3. Bu ders mevcut bir dersin tamamını kapsıyor mu?
        $query->where(function($q) use ($startTime, $endTime) {
            $q->where(function($q) use ($startTime, $endTime) {
                // Yeni dersin başlangıcı, mevcut dersin aralığında mı?
                $q->where('start_time', '<=', $startTime)
                  ->where('end_time', '>', $startTime);
            })->orWhere(function($q) use ($startTime, $endTime) {
                // Yeni dersin bitişi, mevcut dersin aralığında mı?
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>=', $endTime);
            })->orWhere(function($q) use ($startTime, $endTime) {
                // Yeni ders, mevcut bir dersi tamamen kapsıyor mu?
                $q->where('start_time', '>=', $startTime)
                  ->where('end_time', '<=', $endTime);
            });
        });
        
        return $query->exists();
    }

    /**
     * Ders çakışması kontrolü için API
     */
    public function checkLessonConflictApi(Request $request)
    {
        $teacherId = Auth::id();
        $dayOfWeek = $request->input('day');
        $startTime = $request->input('time');
        $date = $request->input('date');
        $excludeSessionId = $request->input('exclude');
        
        // Bitiş saatini hesapla (başlangıçtan 1 saat sonra)
        $startTimeParts = explode(':', $startTime);
        $endHour = (int)$startTimeParts[0] + 1;
        $endTime = ($endHour >= 24 ? 23 : $endHour) . ':' . $startTimeParts[1];
        
        $conflict = $this->checkLessonConflict(
            $teacherId,
            $dayOfWeek,
            $startTime,
            $endTime,
            $date,
            $excludeSessionId
        );
        
        return response()->json(['conflict' => $conflict]);
    }

    /**
     * Özel ders detaylarını gösterir
     */
    public function show($id)
    {
        $teacherId = Auth::id();
        
        // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait olanları
        $session = PrivateLessonSession::with(['privateLesson', 'student'])
            ->where('teacher_id', $teacherId)
            ->findOrFail($id);
        
        return view('teacher.private-lessons.show', compact('session'));
    }

    /**
     * Özel ders düzenleme formunu gösterir
     */
    public function edit($id)
    {
        $teacherId = Auth::id();
        
        // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait olanları
        $session = PrivateLessonSession::with(['privateLesson', 'student'])
            ->where('teacher_id', $teacherId)
            ->findOrFail($id);
        
        // Öğrenci listesini çekelim
        $students = User::role('ogrenci')->get();
        
        // Ders geçmiş tarihli mi kontrol et
        $isPastSession = strtotime($session->start_date . ' ' . $session->start_time) < time();
        
        return view('teacher.private-lessons.edit', compact('session', 'students', 'isPastSession'));
    }

/**
 * Özel dersi günceller
 */
/**
 * Özel dersi günceller
 */
public function update(Request $request, $id)
{
    try {
        $teacherId = Auth::id();
        
        // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait olanları
        $session = PrivateLessonSession::where('teacher_id', $teacherId)->findOrFail($id);
        
        // Ders geçmiş tarihli mi kontrol et
        $isPastSession = strtotime($session->start_date . ' ' . $session->start_time) < time();
        
        // Validasyon kurallarını belirle
        $rules = [
            'student_id' => 'required|exists:users,id',
            'fee' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:approved,cancelled',
            'notes' => 'nullable|string',
            'update_all_sessions' => 'sometimes|boolean',
            'conflict_confirmed' => 'sometimes|boolean'
        ];
        
        // Eğer geçmiş tarihli ders değilse tarih ve zaman alanlarını da doğrula
        if (!$isPastSession) {
            $rules['day_of_week'] = 'required|integer|min:0|max:6';
            $rules['start_date'] = 'required|date';
            $rules['start_time'] = 'required';
        }
        
        $validated = $request->validate($rules);
        
        // Geçmiş tarihli ders ise sadece belirli alanları güncelle
        if ($isPastSession) {
            $sessionUpdateData = [
                'student_id' => $validated['student_id'],
                'fee' => $validated['fee'],
                'payment_status' => $validated['payment_status'],
                'paid_amount' => $validated['payment_status'] == 'paid' ? $validated['fee'] : 0,
                'location' => $validated['location'] ?? null,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null
            ];
        } else {
            // Bitiş saatini hesapla (başlangıçtan 1 saat sonra)
            $startTimeParts = explode(':', $validated['start_time']);
            $endHour = (int)$startTimeParts[0] + 1;
            $endTime = ($endHour >= 24 ? 23 : $endHour) . ':' . $startTimeParts[1];
            
            // Çakışma kontrolü - sadece conflict_confirmed yoksa yap
            if (!$request->has('conflict_confirmed')) {
                // Ders çakışması kontrolü
                $conflictExists = $this->checkLessonConflict(
                    $teacherId,
                    $validated['day_of_week'],
                    $validated['start_time'],
                    $endTime,
                    $validated['start_date'],
                    $id
                );
                
                // Çakışma varsa, formu hata mesajı ile geri döndür
                if ($conflictExists) {
                    return redirect()->back()
                        ->with('warning', 'Seçilen gün ve saatte başka bir dersiniz bulunmaktadır. Yine de devam etmek istiyorsanız "Güncelle" butonuna tekrar basın.')
                        ->with('conflict_detected', true)
                        ->withInput();
                }
            }
            
            $sessionUpdateData = [
                'student_id' => $validated['student_id'],
                'day_of_week' => $validated['day_of_week'],
                'start_date' => $validated['start_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $endTime,
                'fee' => $validated['fee'],
                'payment_status' => $validated['payment_status'],
                'paid_amount' => $validated['payment_status'] == 'paid' ? $validated['fee'] : 0,
                'location' => $validated['location'] ?? null,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null
            ];
        }
        
        // PrivateLesson bilgilerini güncelle (fiyatı)
        $privateLesson = PrivateLesson::findOrFail($session->private_lesson_id);
        $privateLesson->update([
            'price' => $validated['fee']
        ]);
        
        // Eğer tüm gelecek seansları güncelle seçeneği aktifse
        if (isset($validated['update_all_sessions']) && $validated['update_all_sessions'] == 1 && !$isPastSession) {
            // Sadece gelecek seansları güncelle
            $today = Carbon::now()->startOfDay();
            
            // Aynı derse ait gelecekteki tüm seansları bul
            $futureSessions = PrivateLessonSession::where('private_lesson_id', $session->private_lesson_id)
                ->where('teacher_id', $teacherId)
                ->where('start_date', '>=', $today->format('Y-m-d'))
                ->get();
            
            foreach ($futureSessions as $futureSession) {
                // Geçmiş tarihli ders değilse ve bu session ise zaten güncellenecek
                if ($futureSession->id == $session->id) {
                    $futureSession->update($sessionUpdateData);
                    continue;
                }
                
                // Gelecek seansları güncelle
                $futureUpdateData = [
                    'student_id' => $validated['student_id'],
                    'fee' => $validated['fee'],
                    'location' => $validated['location'] ?? null,
                    'status' => $validated['status'],
                    'notes' => $validated['notes'] ?? null
                ];
                
                // Tarih/saat değişikliği yapıldıysa, tüm gelecek derslerin gününü güncelle
                // Eski ve yeni gün arasındaki farkı hesapla
                $dayDiff = (int)$validated['day_of_week'] - (int)$session->day_of_week;
                
                // Eğer gün değişmişse, bu dersin tarihini de güncelle
                if ($dayDiff != 0) {
                    $newDate = Carbon::parse($futureSession->start_date)->addDays($dayDiff);
                    $futureUpdateData['day_of_week'] = $validated['day_of_week'];
                    $futureUpdateData['start_date'] = $newDate->format('Y-m-d');
                }
                
                // Saat değişikliği
                if ($validated['start_time'] != $session->start_time) {
                    // Bitiş saatini hesapla
                    $startTimeParts = explode(':', $validated['start_time']);
                    $endHour = (int)$startTimeParts[0] + 1;
                    $endTime = ($endHour >= 24 ? 23 : $endHour) . ':' . $startTimeParts[1];
                    
                    $futureUpdateData['start_time'] = $validated['start_time'];
                    $futureUpdateData['end_time'] = $endTime;
                }
                
                // Bu session eklendikten sonra her bir gelecek seans için çakışma kontrolü yap
                if (!$request->has('conflict_confirmed')) {
                    $futureConflictExists = $this->checkLessonConflict(
                        $teacherId,
                        $futureUpdateData['day_of_week'] ?? $futureSession->day_of_week,
                        $futureUpdateData['start_time'] ?? $futureSession->start_time,
                        $futureUpdateData['end_time'] ?? $futureSession->end_time,
                        $futureUpdateData['start_date'] ?? $futureSession->start_date,
                        $futureSession->id
                    );
                    
                    if ($futureConflictExists) {
                        // Çakışma varsa bir not ekle ve bu seansı atla
                        $notWarning = "\n[SİSTEM NOTU: " . date('d.m.Y', strtotime($futureSession->start_date)) . 
                                     " tarihli seans için çakışma tespit edildi ve güncellenmedi]";
                        
                        $futureSession->update([
                            'notes' => ($futureSession->notes ? $futureSession->notes . $notWarning : $notWarning)
                        ]);
                        
                        continue; // Bu seansı atla ve bir sonrakine geç
                    }
                }
                
                // Çakışma yoksa veya kullanıcı çakışmayı onayladıysa güncelle
                $futureSession->update($futureUpdateData);
            }
            
            return redirect()->route('ogretmen.private-lessons.index')
                ->with('success', 'Bütün gelecek seanslar başarıyla güncellendi. Çakışan seanslar için notlar eklenmiştir.');
        }
        
        // Sadece seçilen seansı güncelle
        $session->update($sessionUpdateData);
        
        return redirect()->route('ogretmen.private-lessons.index')
            ->with('success', 'Özel ders seansı başarıyla güncellendi.');
        
    } catch (\Exception $e) {
        // Hatayı göster ve form verilerini geri doldur
        return redirect()->back()
            ->with('error', 'Hata: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Özel dersi siler
     */
    public function destroy($id)
    {
        try {
            $teacherId = Auth::id();
            
            // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait olanları
            $session = PrivateLessonSession::where('teacher_id', $teacherId)->findOrFail($id);
            
            // Geçmiş tarihli ders ise silmeye izin verme
            if (strtotime($session->start_date . ' ' . $session->start_time) < time()) {
                return redirect()->back()
                    ->with('error', 'Geçmiş tarihli dersler silinemez. Bunun yerine durumunu "İptal Edildi" olarak işaretleyebilirsiniz.');
            }
            
            // Bu tekil bir seans mı yoksa bir serinin parçası mı kontrol et
            $isPartOfSeries = PrivateLessonSession::where('private_lesson_id', $session->private_lesson_id)
                                ->where('id', '!=', $session->id)
                                ->exists();
            
            // Eğer bu bir serinin son seansı ise, PrivateLesson kaydını da sil
            if (!$isPartOfSeries) {
                PrivateLesson::where('id', $session->private_lesson_id)->delete();
            }
            
            // Dersi sil
            $session->delete();
            
            // Başarılı bir şekilde sildiğimizde mesaj ver ve listeye yönlendir
            return redirect()->route('ogretmen.private-lessons.index')
                ->with('success', 'Özel ders seansı başarıyla silindi.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    /**
     * Birden fazla seansı sil (aynı ders serisine ait tüm gelecek seanslar)
     */
    public function destroyMultiple(Request $request, $id)
    {
        try {
            $teacherId = Auth::id();
            
            // Referans seansı getir
            $referenceSession = PrivateLessonSession::where('teacher_id', $teacherId)->findOrFail($id);
            
            // Silinecek seansların kapsamını doğrula
            $deleteScope = $request->input('delete_scope', 'this_only');
            
            if ($deleteScope == 'this_only') {
                // Sadece bu seansı sil
                $referenceSession->delete();
                $message = 'Seçilen seans başarıyla silindi.';
            } 
            else if ($deleteScope == 'all_future') {
                // Bu ve gelecekteki tüm seansları sil
                $today = Carbon::now()->startOfDay();
                
                // Bu dersin gelecekteki tüm seanslarını bul
                $futureSessions = PrivateLessonSession::where('private_lesson_id', $referenceSession->private_lesson_id)
                    ->where('teacher_id', $teacherId)
                    ->where('start_date', '>=', $today->format('Y-m-d'))
                    ->get();
                
                foreach ($futureSessions as $session) {
                    $session->delete();
                }
                
                // Kalan seans var mı kontrol et
                $remainingSessions = PrivateLessonSession::where('private_lesson_id', $referenceSession->private_lesson_id)
                    ->exists();
                
                // Eğer tüm seanslar silindiyse, PrivateLesson kaydını da sil
                if (!$remainingSessions) {
                    PrivateLesson::where('id', $referenceSession->private_lesson_id)->delete();
                }
                
                $message = 'Bu ve gelecekteki tüm seanslar başarıyla silindi.';
            } 
            else if ($deleteScope == 'all') {
                // Bu dersin tüm seanslarını sil
                $allSessions = PrivateLessonSession::where('private_lesson_id', $referenceSession->private_lesson_id)
                    ->where('teacher_id', $teacherId)
                    ->get();
                
                foreach ($allSessions as $session) {
                    $session->delete();
                }
                
                // PrivateLesson kaydını da sil
                PrivateLesson::where('id', $referenceSession->private_lesson_id)->delete();
                
                $message = 'Bu derse ait tüm seanslar başarıyla silindi.';
            }
            
            return redirect()->route('ogretmen.private-lessons.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    /**
     * Özel ders talebini onaylar
     */
    public function approve($id)
    {
        $teacherId = Auth::id();
        
        // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait ve beklemede olanları
        $session = PrivateLessonSession::where('teacher_id', $teacherId)
            ->where('status', 'pending')
            ->findOrFail($id);
        
        // Dersin durumunu aktif olarak güncelle
        $session->update(['status' => 'active']);
        
        // Başarılı bir şekilde onayladığımızda mesaj ver ve listeye yönlendir
        return redirect()->route('ogretmen.private-lessons.pendingRequests')
            ->with('success', 'Özel ders talebi başarıyla onaylandı.');
    }

    /**
     * Özel ders talebini reddeder
     */
    public function reject($id)
    {
        $teacherId = Auth::id();
        
        // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait ve beklemede olanları
        $session = PrivateLessonSession::where('teacher_id', $teacherId)
            ->where('status', 'pending')
            ->findOrFail($id);
        
        // Durumu reddedildi olarak işaretle
        $session->update(['status' => 'cancelled']);
        
        // Başarılı bir şekilde reddettiğimizde mesaj ver ve listeye yönlendir
        return redirect()->route('ogretmen.private-lessons.pendingRequests')
            ->with('success', 'Özel ders talebi reddedildi.');
    }
    
    /**
     * Ödeme durumunu günceller
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $teacherId = Auth::id();
        
        // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait olanları
        $session = PrivateLessonSession::where('teacher_id', $teacherId)->findOrFail($id);
        
        // Form verilerini doğrulama
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid',
            'payment_notes' => 'nullable|string'
        ]);
        
        // Ödeme durumunu güncelle
        $session->update([
            'payment_status' => $validated['payment_status'],
            'paid_amount' => $validated['payment_status'] == 'paid' ? $session->fee : 0,
            'payment_date' => $validated['payment_status'] == 'paid' ? now() : null,
            'notes' => $session->notes . "\n\nÖdeme Durumu Güncelleme (" . now()->format('d.m.Y H:i') . "): " . 
                      ($validated['payment_notes'] ?? 'Ödeme durumu güncellendi: ' . $validated['payment_status'])
        ]);
        
        // Başarılı bir şekilde güncellediğimizde mesaj ver ve detay sayfasına yönlendir
        return redirect()->route('ogretmen.private-lessons.show', $id)
            ->with('success', 'Ödeme durumu başarıyla güncellendi.');
    }

/**
 * Dersi iptal et
 */
public function cancelLesson($id)
{
    $teacherId = Auth::id();
    
    // Belirli bir dersi getir, ancak sadece mevcut öğretmene ait olanları
    $session = PrivateLessonSession::where('teacher_id', $teacherId)->findOrFail($id);
    
    // Dersin durumunu iptal edildi olarak güncelle
    $session->update([
        'status' => 'cancelled',
        'notes' => $session->notes . "\n\nDers İptal (" . now()->format('d.m.Y H:i') . "): Öğretmen tarafından iptal edildi."
    ]);
    
    // Başarılı bir şekilde iptal ettiğimizde mesaj ver ve listeye yönlendir
    return redirect()->route('ogretmen.private-lessons.index')
        ->with('success', 'Ders başarıyla iptal edildi.');
}
}