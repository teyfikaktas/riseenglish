<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use App\Models\PrivateLessonSession;
use App\Models\PrivateLesson;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PrivateLessonsList extends Component
{
    use WithPagination;
    
    // Livewire 3 - URL parametreleri
    #[Url(history: true)]
    public $search = '';
    
    #[Url(history: true)]
    public $showAll = false;
    
    #[Url(history: true)]
    public $statusFilter = '';
    
    #[Url(history: true)]
    public $startDateFilter = '';
    
    #[Url(history: true)]
    public $endDateFilter = '';
    
    #[Url(history: true)]
    public $studentFilter = '';
    
    // Öğrenci filtre açık/kapalı durumu
    public $isStudentFilterOpen = false;
    
    // Sayfa güncellemesi için özellikleri resetle
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingShowAll()
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    
    // ShowAll değerini tersine çeviren yeni method
    public function toggleShowAll()
    {
        $this->showAll = !$this->showAll;
        $this->resetPage();
        
        // Debugging için
        $status = $this->showAll ? 'gösteriliyor' : 'gizleniyor';
        $this->dispatch('toast', [
            'message' => "Pasif dersler şu anda " . $status,
            'type' => 'info'
        ]);
    }
    
    #[On('open-student-filter')]
    public function openStudentFilter()
    {
        $this->isStudentFilterOpen = true;
    }
    
    #[On('filter-by-student')]
    public function filterByStudent($studentId)
    {
        $this->studentFilter = $studentId;
        $this->resetPage();
    }
    
    public function mount()
    {
        // URL'den showAll parametresini al
        $this->showAll = request()->has('show_all') ? true : false;
    }
    
    public function toggleLessonActive($lessonId)
    {
        $teacherId = Auth::id();
        
        try {
            // Öğretmenin yetkisi var mı kontrol et
            $sessionCheck = PrivateLessonSession::where('private_lesson_id', $lessonId)
                ->where('teacher_id', $teacherId)
                ->first();
                
            if (!$sessionCheck) {
                $this->dispatch('toast', ['message' => 'Bu dersi değiştirme yetkiniz bulunmuyor.', 'type' => 'error']);
                return;
            }
            
            // Dersi getir
            $lesson = PrivateLesson::findOrFail($lessonId);
            
            // Aktiflik durumunu değiştir
            $lesson->is_active = !$lesson->is_active;
            $lesson->save();
            
            $status = $lesson->is_active ? 'aktif' : 'pasif';
            $this->dispatch('toast', ['message' => "Ders başarıyla {$status} duruma getirildi.", 'type' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('toast', ['message' => 'Bir hata oluştu: ' . $e->getMessage(), 'type' => 'error']);
        }
    }
    
    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->startDateFilter = '';
        $this->endDateFilter = '';
        $this->studentFilter = '';
        $this->resetPage();
    }
    
    public function render()
    {
        $teacherId = Auth::id();
        
        // Temel sorgu - öğretmene ait dersler
        $query = PrivateLessonSession::with(['privateLesson', 'student'])
            ->where('teacher_id', $teacherId);
            
        // Arama filtresi
        if (!empty($this->search)) {
            $query->where(function ($query) {
                $query->whereHas('privateLesson', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('student', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            });
        }
        
        // Durum filtresi
        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }
        
        // Tarih filtreleri
        if (!empty($this->startDateFilter)) {
            $query->where('start_date', '>=', $this->startDateFilter);
        }
        
        if (!empty($this->endDateFilter)) {
            $query->where('start_date', '<=', $this->endDateFilter);
        }
        
        // Öğrenci filtresi
        if (!empty($this->studentFilter)) {
            $query->where('student_id', $this->studentFilter);
        }
            
        // Tarihe göre sırala
        $query->orderBy('start_date', 'asc')
            ->orderBy('start_time', 'asc');
            
        // Verileri al
        $sessions = $query->get();
        
        // Aktif/pasif ders filtresi ve gruplandırma
        $filteredSessions = $this->showAll 
            ? $sessions 
            : $sessions->filter(function($session) {
                return $session->privateLesson && $session->privateLesson->is_active;
            });
            
        // Dersleri private_lesson_id'ye göre gruplandır
        $groupedSessions = $filteredSessions->groupBy('private_lesson_id');
        
        // Durumları al - dropdown için
        $statuses = [
            'pending' => 'Beklemede',
            'approved' => 'Onaylandı',
            'cancelled' => 'İptal Edildi',
            'completed' => 'Tamamlandı',
        ];
        
        // Öğrencileri getir - dropdown için
        $students = PrivateLessonSession::where('teacher_id', $teacherId)
            ->with('student')
            ->select('student_id')
            ->distinct()
            ->get()
            ->pluck('student')
            ->filter()
            ->pluck('name', 'id');
        
        return view('livewire.private-lessons-list', [
            'groupedSessions' => $groupedSessions,
            'statuses' => $statuses,
            'students' => $students,
        ]);
    }
}