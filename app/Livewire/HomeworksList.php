<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\HomeworkSubmission;
use Illuminate\Support\Facades\Auth;

class HomeworksList extends Component
{
    use WithPagination;
 
    // Tailwind için pagination stilini kullan
    protected $paginationTheme = 'tailwind';
    
    // Bu, sayfa numarasını URL'de gösterir ve tarayıcı geçmişini korur
    #[Url(as: 'hw_page')]
    public $page = 1;
    
    // Bu özellik sayfa değişiminde scroll pozisyonunun korunmasını sağlar
    protected $paginationOptions = ['disableScrollToTop' => true];
    
    // Filtreleme için özellikler
    public $studentName = '';
    public $courseName = '';
    
    // URL'de filtreleri göster
    protected $queryString = ['studentName', 'courseName'];
    
    public function getListeners()
    {
        return [
            'paginatorPageChanged' => 'preserveScroll'
        ];
    }

    public function preserveScroll()
    {
        // Scroll pozisyonunu koru
        $this->dispatch('preserveScroll');
    }

    public function updatedPage()
    {
        $this->dispatch('paginatorPageChanged');
    }
    
    // Filtre değiştiğinde sayfa numarasını sıfırla
    public function updatingStudentName()
    {
        $this->resetPage();
    }
    
    public function updatingPage()
    {
        $this->dispatch('pageChanging');
    }
    
    public function updatingCourseName()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        // Giriş yapmış öğretmeni al
        $teacher = Auth::user();
        
        // Sorguyu başlat ve ilişkileri yükle
        $query = HomeworkSubmission::query()
            ->with(['student', 'homework.course'])
            // ÖNEMLİ: Sadece giriş yapmış öğretmenin kurslarına ait ödevleri filtrele
            ->whereHas('homework.course', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            // Sadece aktif ödevleri getir
            ->whereHas('homework', function($q) {
                $q->where('is_active', true);
            });
            
        // Öğrenci adına göre filtrele
        if ($this->studentName) {
            $query->whereHas('student', function($q) {
                $q->where('name', 'like', '%' . $this->studentName . '%');
            });
        }
        
        // Kurs adına göre filtrele
        if ($this->courseName) {
            $query->whereHas('homework.course', function($q) {
                $q->where('name', 'like', '%' . $this->courseName . '%');
            });
        }
        
        // Ödevleri teslim tarihine göre sırala ve sayfalandır
        $recentHomeworks = $query->latest('submitted_at')->paginate(10);
        
        return view('livewire.homeworks-list', [
            'recentHomeworks' => $recentHomeworks
        ]);
    }
}