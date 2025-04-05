<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PrivateLesson;
use App\Models\PrivateLessonSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrivateLessonCalendar extends Component
{
    public $weekStart;
    public $weekDates = [];
    public $calendarData = [];
    public $timeSlots = [];
    public $selectedTeacher = null;
    public $selectedStatus = null;
    public $viewType = 'week'; // 'week' veya 'day' olabilir
    public $selectedLesson = null; // Modal için seçilen ders
    public $compactView = false; // varsayılan olarak normal görünüm
    public $nextLesson = null;

    protected $listeners = [
        'dateChanged' => 'setWeek',
        'filterByTeacher' => 'filterByTeacher',
        'filterByStatus' => 'filterByStatus',
    ];
    
    public function getDebugInfo()
    {
        return [
            'weekDates' => array_map(function($date) {
                return $date->format('Y-m-d');
            }, $this->weekDates),
            'calendarData' => $this->calendarData,
            'hasData' => !empty($this->calendarData),
            'loadedSessionsCount' => collect($this->calendarData)->flatten(1)->flatten(1)->count(),
            'selectedTeacher' => $this->selectedTeacher,
            'selectedStatus' => $this->selectedStatus
        ];
    }
    
    public function toggleCompactView()
    {
        // Kompakt görünümü tersine çevir (toggle)
        $this->compactView = !$this->compactView;
    }
    
    public function mount()
    {
        // Carbon'un zaman dilimini açıkça ayarlayın
        Carbon::setLocale('tr');
        date_default_timezone_set('Europe/Istanbul');
        setlocale(LC_TIME, 'tr_TR.utf8', 'tr_TR', 'tr', 'turkish'); // PHP yerel ayarları
        // Varsayılan olarak bu haftayı göster
        $this->setWeek(Carbon::now('Europe/Istanbul'));
        $this->timeSlots = $this->getTimeSlots();
        $this->loadOccurrences();

    }
// Aşağıdaki yeni metodu sınıfa ekleyin
/**
 * Öğretmenin gelecek en yakın dersini bul
 */
public function getNextLesson()
{
    try {
        $query = PrivateLessonSession::with(['privateLesson', 'teacher', 'student'])
            ->where('start_date', '>=', Carbon::now('Europe/Istanbul')->format('Y-m-d'))
            ->where(function($q) {
                // Aynı gün içindeki dersler için saat kontrolü
                $q->where('start_date', '>', Carbon::now('Europe/Istanbul')->format('Y-m-d'))
                  ->orWhere(function($q2) {
                      $q2->where('start_date', '=', Carbon::now('Europe/Istanbul')->format('Y-m-d'))
                         ->where('start_time', '>=', Carbon::now('Europe/Istanbul')->format('H:i:s'));
                  });
            })
            ->orderBy('start_date', 'asc')
            ->orderBy('start_time', 'asc');
            
        // Eğer öğretmen olarak giriş yapılmışsa sadece onun derslerini göster
        if (Auth::check() && Auth::user()->hasRole('ogretmen')) {
            $query->where('teacher_id', Auth::id());
        }
        
        $nextSession = $query->first();
        
        if ($nextSession) {
            $this->nextLesson = [
                'id' => $nextSession->id,
                'title' => $nextSession->privateLesson ? $nextSession->privateLesson->name : 'Ders',
                'teacher' => $nextSession->teacher ? $nextSession->teacher->name : 'Öğretmen Atanmamış',
                'student' => $nextSession->student ? $nextSession->student->name : 'Öğrenci Atanmamış',
                'date' => Carbon::parse($nextSession->start_date, 'Europe/Istanbul')->locale('tr')->translatedFormat('d F Y, l'),
                'start_time' => substr($nextSession->start_time, 0, 5),
                'end_time' => substr($nextSession->end_time, 0, 5),
                'status' => $nextSession->status,
                'location' => $nextSession->location,
                'days_left' => Carbon::now('Europe/Istanbul')->diffInDays(Carbon::parse($nextSession->start_date, 'Europe/Istanbul')),
                'hours_left' => Carbon::now('Europe/Istanbul')->diffInHours(Carbon::parse($nextSession->start_date . ' ' . $nextSession->start_time, 'Europe/Istanbul')),
                'time_left_formatted' => $this->formatTimeLeft(
                    Carbon::now('Europe/Istanbul'), 
                    Carbon::parse($nextSession->start_date . ' ' . $nextSession->start_time, 'Europe/Istanbul')
                ),
            ];
            
            Log::info("En yakın ders bulundu: " . json_encode($this->nextLesson));
        } else {
            Log::info("Gelecek ders bulunamadı");
            $this->nextLesson = null;
        }
    } catch (\Exception $e) {
        Log::error("En yakın ders aranırken hata: " . $e->getMessage());
        $this->nextLesson = null;
    }
}
/**
 * Kalan süreyi daha anlaşılır bir formatta formatlar
 */
private function formatTimeLeft($currentTime, $futureTime)
{
    $diff = $currentTime->diff($futureTime);
    
    if ($diff->days > 0) {
        return $diff->days . ' gün ' . $diff->h . ' saat';
    } elseif ($diff->h > 0) {
        return $diff->h . ' saat ' . $diff->i . ' dakika';
    } else {
        return $diff->i . ' dakika';
    }
}
    public function setWeek($date)
    {
        $this->weekStart = Carbon::parse($date, 'Europe/Istanbul')->startOfWeek();
        $this->weekDates = [];
        
        // Görünüm tipine göre tarihleri ayarla
        if ($this->viewType === 'week') {
            for ($i = 0; $i < 7; $i++) {
                $this->weekDates[] = $this->weekStart->copy()->addDays($i);
            }
        } else {
            // Günlük görünüm için sadece seçilen tarihi göster
            $this->weekDates[] = Carbon::parse($date, 'Europe/Istanbul');
        }
    
        $this->loadOccurrences();
    }

    public function previousWeek()
    {
        if ($this->viewType === 'week') {
            $this->setWeek($this->weekStart->copy()->subWeek());
        } else {
            $this->setWeek($this->weekDates[0]->copy()->subDay());
        }
    }

    public function nextWeek()
    {
        if ($this->viewType === 'week') {
            $this->setWeek($this->weekStart->copy()->addWeek());
        } else {
            $this->setWeek($this->weekDates[0]->copy()->addDay());
        }
    }

    public function filterByTeacher($teacherId)
    {
        $this->selectedTeacher = $teacherId;
        $this->loadOccurrences();
    }

    public function filterByStatus($status)
    {
        $this->selectedStatus = $status;
        $this->loadOccurrences();
    }

    /**
     * Dersi tamamlama işlemi
     */
    public function completeLesson($lessonId)
    {
        try {
            // İşlem başlangıcını logla
            Log::info("Ders tamamlama işlemi başlatıldı. Ders ID: " . $lessonId);
            
            // Ders kaydını bul
            $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'student'])
                ->findOrFail($lessonId);
            
            // Ders zamanını kontrol et
            $currentTime = Carbon::now('Europe/Istanbul');
            $lessonEndTime = Carbon::parse($session->start_date . ' ' . $session->end_time, 'Europe/Istanbul');
            
            // Eğer ders zamanı henüz geçmediyse tamamlanamaz
            if ($currentTime->isBefore($lessonEndTime)) {
                $this->dispatch('lessonError', 'Ders henüz bitmedi. Tamamlamak için ders saatinin bitmesini beklemelisiniz.');
                Log::warning("Ders tamamlama işlemi başarısız: Ders zamanı henüz gelmedi. Ders ID: " . $lessonId);
                return;
            }
            
            // Ders durumunu zaten tamamlanmış mı kontrol et
            if ($session->status === 'completed') {
                $this->dispatch('lessonError', 'Bu ders zaten tamamlanmış durumda.');
                Log::info("Ders tamamlama işlemi atlandı: Ders zaten tamamlanmış. Ders ID: " . $lessonId);
                return;
            }
            
            // Dersi tamamla
            $session->status = 'completed';
            $session->save();
            
            // SMS gönderimi yapılacak kısım
            $this->sendCompletionSMS($session);
            
            // Kullanıcı arayüzünü güncelle
            $this->loadOccurrences(); // Takvimi yenile
            
            if ($this->selectedLesson && $this->selectedLesson['id'] === $lessonId) {
                // Seçili dersi güncelle
                $this->showLessonDetails($lessonId);
            }
            
            // Başarı bildirimi gönder
            $this->dispatch('lessonCompleted', 'Ders başarıyla tamamlandı! Veli ve öğrenciye SMS gönderildi.');
            Log::info("Ders tamamlama işlemi başarılı. Ders ID: " . $lessonId);
            
        } catch (\Exception $e) {
            // Hata durumunda
            Log::error("Ders tamamlama işleminde hata: " . $e->getMessage());
            Log::error("Hata detayı: " . $e->getTraceAsString());
            $this->dispatch('lessonError', 'Ders tamamlanırken bir hata oluştu: ' . $e->getMessage());
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
            // Örnek:
            // $this->smsService->send($studentPhone, $smsContent);
            // $this->smsService->send($parentPhone, $smsContent);
            
            Log::info("SMS içeriği hazırlandı: " . $smsContent);
            Log::info("SMS gönderilecek numaralar - Öğrenci: $studentPhone, Veli: $parentPhone");
            
            // Sadece log amaçlı, gerçek uygulamada burayı aktif SMS gönderimi ile değiştirin
            Log::info("SMS başarıyla gönderildi (simülasyon)");
            
        } catch (\Exception $e) {
            Log::error("SMS gönderimi sırasında hata: " . $e->getMessage());
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
    public function showLessonDetails($lessonId)
    {
        try {
            // Log başlangıç bilgisi
            Log::info("showLessonDetails fonksiyonu başlatıldı. Ders ID: " . $lessonId);
            
            // Ders kaydının var olup olmadığını kontrol et
            $exists = PrivateLessonSession::where('id', $lessonId)->exists();
            Log::info("Ders ID $lessonId için kayıt var mı: " . ($exists ? 'Evet' : 'Hayır'));
            
            if (!$exists) {
                throw new \Exception("Belirtilen ID ($lessonId) için ders kaydı bulunamadı.");
            }
            
            // Veritabanından ders bilgilerini çek
            Log::info("Ders bilgileri çekiliyor: $lessonId");
            $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'student'])
                ->findOrFail($lessonId);
            
            Log::info("Ders bilgileri başarıyla çekildi. Ders adı: " . 
                ($session->privateLesson ? $session->privateLesson->name : 'Ders adı bulunamadı'));
                
            // İlişkileri kontrol et ve logla
            Log::info("İlişkiler kontrol ediliyor:");
            Log::info("privateLesson ilişkisi: " . ($session->privateLesson ? 'Var' : 'Yok'));
            Log::info("teacher ilişkisi: " . ($session->teacher ? 'Var' : 'Yok'));
            Log::info("student ilişkisi: " . ($session->student ? 'Var' : 'Yok'));
            
            // Modal için veriyi formatla
            $this->selectedLesson = [
                'id' => $session->id,
                'lesson_date' => $session->start_date,
                'start_time' => $session->start_time,
                'end_time' => $session->end_time,
                'title' => $session->privateLesson ? $session->privateLesson->name : 'Ders',
                'teacher' => $session->teacher ? $session->teacher->name : 'Öğretmen Atanmamış',
                'teacher_id' => $session->teacher_id,
                'student' => $session->student ? $session->student->name : 'Öğrenci Atanmamış',
                'student_id' => $session->student_id,
                'status' => $session->status,
                'notes' => $session->notes,
                'location' => $session->location,
                'private_lesson_name' => $session->privateLesson ? $session->privateLesson->name : 'Ders',
                'price' => $session->fee ? $session->fee : ($session->privateLesson ? $session->privateLesson->price : 0),
                'fee' => $session->fee,
            ];
            
            Log::info("Ders detayları başarıyla yüklendi: " . json_encode($this->selectedLesson));
            
        } catch (\Exception $e) {
            // Detaylı hata kaydı
            Log::error("Ders bilgileri yüklenirken hata: " . $e->getMessage());
            Log::error("Hata yığını: " . $e->getTraceAsString());
            $this->dispatch('lessonError', 'Ders detayları yüklenirken hata oluştu.');

            // Kullanıcıya hata mesajı göster
            session()->flash('error', 'Ders bilgileri yüklenirken bir hata oluştu: ' . $e->getMessage());
            
            // Hata sonrası temizlik
            $this->selectedLesson = null;
        }
    }
    
    public function showToday()
    {
        // Bugünün tarihini ayarla (Türkiye saati ile)
        $this->weekStart = Carbon::now('Europe/Istanbul')->startOfDay();
        $this->viewType = 'day';
        $this->prepareCalendarData();
    }
    
    public function closeModal()
    {
        $this->selectedLesson = null;
    }

    public function loadOccurrences()
    {
        try {
            $this->dispatch('debug', ['message' => 'Yükleme başladı']);
            
            $startDate = $this->weekDates[0]->format('Y-m-d');
            $endDate = $this->viewType === 'week' 
                ? $this->weekDates[count($this->weekDates) - 1]->format('Y-m-d')
                : $startDate;
                
            $query = PrivateLessonSession::with(['privateLesson', 'teacher', 'student']);
            
            if ($this->viewType === 'week') {
                $query->whereBetween('start_date', [$startDate, $endDate]);
            } else {
                $query->where('start_date', $startDate);
            }
            
            if ($this->selectedTeacher) {
                $query->where('teacher_id', $this->selectedTeacher);
            }
            
            if ($this->selectedStatus) {
                $query->where('status', $this->selectedStatus);
            }
            
            if (Auth::check() && Auth::user()->hasRole('ogretmen')) {
                $query->where('teacher_id', Auth::id());
            }
            
            $sessions = $query->get();
            
            $this->calendarData = [];
            
            foreach ($sessions as $session) {
                $date = Carbon::parse($session->start_date)->format('Y-m-d');
                $startTime = Carbon::parse($session->start_time)->format('H:i');
                
                if (!isset($this->calendarData[$date])) {
                    $this->calendarData[$date] = [];
                }
                
                if (!isset($this->calendarData[$date][$startTime])) {
                    $this->calendarData[$date][$startTime] = [];
                }
                
                $occurrence = [
                    'id' => $session->id,
                    'title' => $session->privateLesson ? $session->privateLesson->name : 'Ders',
                    'teacher' => $session->teacher ? $session->teacher->name : 'Öğretmen Atanmamış',
                    'teacher_id' => $session->teacher_id,
                    'student' => $session->student ? $session->student->name : 'Öğrenci Atanmamış',
                    'student_id' => $session->student_id,
                    'start_time' => $session->start_time,
                    'end_time' => $session->end_time,
                    'status' => $session->status,
                    'notes' => $session->notes,
                    'location' => $session->location,
                    'lesson_date' => $session->start_date,
                    'fee' => $session->fee,
                    'price' => $session->fee !== null ? $session->fee : 
                        ($session->privateLesson && $session->privateLesson->price ? $session->privateLesson->price : 0),
                ];
                
                // Her occurrence'ı logla
                Log::info("Occurrence verisi: " . json_encode($occurrence));
                
                $this->calendarData[$date][$startTime][] = $occurrence;
            }
            $this->getNextLesson();
            $this->dispatch('debug', ['message' => "Takvim veri boyutu: " . count($this->calendarData)]);
            
        } catch (\Exception $e) {
            $this->dispatch('debug', ['message' => "Hata: " . $e->getMessage()]);
            session()->flash('error', 'Ders bilgileri yüklenirken hata oluştu: ' . $e->getMessage());
        }
    }
    private function getTimeSlots()
    {
        $slots = [];
        $start = 8; // Sabah 8
        $end = 23; // Akşam 11
        
        for ($i = $start; $i <= $end; $i++) {
            $slots[] = sprintf('%02d:00', $i);
            // 30 dakikalık dilimleri kaldırdık
        }
        
        return $slots;
    }
    
    public function changeViewType($type)
    {
        $this->viewType = $type;
        
        // 'day' görünümüne geçildiğinde bugüne odaklan
        if ($type === 'day') {
            $this->setWeek(Carbon::now('Europe/Istanbul')->startOfDay());
        } else {
            $this->setWeek($this->weekStart);
        }
    }

    public function render()
    {
        // Öğretmen listesini veritabanından çek
        $teachers = User::when(Auth::check() && !Auth::user()->hasRole('admin'), function($query) {
            // Admin değilse sadece öğretmenleri getir
            return $query->role('ogretmen');
        })->get()->map(function($teacher) {
            return [
                'id' => $teacher->id,
                'name' => $teacher->name
            ];
        });
        
        // Durum adlandırmaları - veritabanındaki gerçek değerlerle eşleştirildi
        $statuses = [
            'pending' => 'Beklemede',
            'approved' => 'Onaylandı',
            'active' => 'Aktif',
            'rejected' => 'Reddedildi',
            'cancelled' => 'İptal Edildi',
            'completed' => 'Tamamlandı',
        ];
        
        return view('livewire.private-lesson-calendar', [
            'teachers' => $teachers,
            'statuses' => $statuses,
        ]);
    }
}