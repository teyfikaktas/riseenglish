<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChainProgress;
use App\Models\ChainActivity;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ChainBreaker extends Component
{
    use WithFileUploads;

    public $daysCompleted = 0;
    public $currentStreak = 0;
    public $longestStreak = 0;
    public $currentLevel = 'Bronz';
    public $levelColor = '#CD7F32';
    public $nextLevelProgress = 0;
    public $maxDays = 365;
    public $showLevelUpModal = false;
    public $previousLevel = '';
    public $isUserAuthenticated = false;
    public $showHistoryModal = false;
    public $selectedDate = null;
    public $selectedDateActivities = [];
    public $historicalDates = [];
    public $selectedMonth;
    public $selectedYear;
    // Yeni özellikler için
    public $showActivityForm = false;
    public $activityContent = '';
    public $activityFiles = [];
    public $todayActivities = [];
    public $teacherId = null;
    // Gender selection için
    public $showGenderModal = false;
    public $iconGender = null; // 'erkek' veya 'kadin'

    protected $listeners = [
        'refreshProgress' => 'refreshProgress',
        'confirmReset' => 'confirmReset',
        'setGender' => 'setGender',
    ];

    protected $rules = [
        'activityContent' => 'required_without:activityFiles',
        'activityFiles.*' => 'file|max:10240' // 10MB maks
    ];

    public function mount()
    {
        $this->isUserAuthenticated = Auth::check();
        $this->refreshProgress();
        
        if ($this->isUserAuthenticated) {
            $this->loadTodayActivities();
            $this->findTeacher();
            $this->selectedMonth = now()->month;
            $this->selectedYear = now()->year;
            
            // Gender seçimini kontrol et
            $progress = ChainProgress::where('user_id', Auth::id())->first();
            if ($progress && $progress->icon_gender) {
                $this->iconGender = $progress->icon_gender;
            } else {
                // Kullanıcı giriş yapmış ama cinsiyet seçimi yapmamış
                $this->showGenderModal = true;
            }
        }
    }

    public function setGender($gender)
    {
        if (!in_array($gender, ['erkek', 'kadin'])) {
            return;
        }

        $this->iconGender = $gender;
        $this->showGenderModal = false;

        $progress = ChainProgress::firstOrCreate(
            ['user_id' => Auth::id()],
            ['days_completed' => 0, 'current_streak' => 0, 'longest_streak' => 0]
        );

        $progress->icon_gender = $gender;
        $progress->save();

        $this->dispatch('show-success', message: 'İkon tercihiniz kaydedildi!');
    }

    // Livewire ayarlarını güncelle
    private function getIconVersion($baseName)
    {
        if (!$this->iconGender || $this->iconGender === 'erkek') {
            return str_replace('erkek', 'erkek', $baseName);
        } else {
            return str_replace('erkek', 'kadin', $baseName);
        }
    }

    // Diğer metodlar burada...
    // Existing methods from your code...
    public function toggleHistoryModal()
    {
        $this->showHistoryModal = !$this->showHistoryModal;
        if ($this->showHistoryModal) {
            $this->loadHistoricalDates();
        }
    }

    public function loadHistoricalDates()
    {
        if (!Auth::check()) {
            return;
        }

        $progress = ChainProgress::where('user_id', Auth::id())->first();
        
        if (!$progress) {
            $this->historicalDates = [];
            return;
        }

        // Seçili ay için tamamlanmış günleri getir
        $this->historicalDates = ChainActivity::where('chain_progress_id', $progress->id)
            ->whereYear('activity_date', $this->selectedYear)
            ->whereMonth('activity_date', $this->selectedMonth)
            ->distinct()
            ->pluck('activity_date')
            ->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('Y-m-d');
            })
            ->unique()
            ->toArray();
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->loadDateActivities($date);
    }

    public function loadDateActivities($date)
    {
        if (!Auth::check()) {
            return;
        }

        $progress = ChainProgress::where('user_id', Auth::id())->first();
        
        if (!$progress) {
            $this->selectedDateActivities = [];
            return;
        }

        $this->selectedDateActivities = ChainActivity::where('chain_progress_id', $progress->id)
            ->whereDate('activity_date', $date)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function changeMonth($direction)
    {
        $currentDate = \Carbon\Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1);
        
        if ($direction === 'prev') {
            $currentDate->subMonth();
        } else {
            $currentDate->addMonth();
        }
        
        $this->selectedMonth = $currentDate->month;
        $this->selectedYear = $currentDate->year;
        $this->loadHistoricalDates();
    }

    public function deleteActivity($activityId)
    {
        $activity = ChainActivity::find($activityId);
        
        if (!$activity) {
            $this->dispatch('show-error', message: 'Çalışma bulunamadı!');
            return;
        }
        
        // Kullanıcının kendi çalışmasını sildiğinden emin ol
        if ($activity->user_id !== Auth::id()) {
            $this->dispatch('show-error', message: 'Bu çalışmayı silemezsiniz!');
            return;
        }
        
        // Dosya varsa sil
        if ($activity->file_path) {
            Storage::disk('public')->delete($activity->file_path);
        }
        
        $activity->delete();
        
        // Güncel çalışmaları tekrar yükle
        $this->loadTodayActivities();
        
        $this->dispatch('show-success', message: 'Çalışma başarıyla silindi!');
    }

    public function confirmDeleteActivity($activityId)
    {
        $this->dispatch('confirm-delete-activity', activityId: $activityId);
    }
    
    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->selectedDate = null;
        $this->selectedDateActivities = [];
    }
    
    public function refreshProgress()
    {
        if (!Auth::check()) {
            $this->isUserAuthenticated = false;
            return;
        }

        $this->isUserAuthenticated = true;
        $progress = ChainProgress::where('user_id', Auth::id())->first();

        if (!$progress) {
            return;
        }

        $this->daysCompleted = $progress->days_completed;
        $this->currentStreak = $progress->current_streak;
        $this->longestStreak = $progress->longest_streak;
        $this->currentLevel = $progress->getCurrentLevel();
        $this->levelColor = $progress->getLevelColor();
        $this->nextLevelProgress = $progress->getNextLevelProgress();
        $this->iconGender = $progress->icon_gender ?? 'erkek'; // Default erkek
    }

    public function findTeacher()
    {
        // Öğrencinin öğretmenini bulma - öğrencinin kayıtlı olduğu kurslardan öğretmeni bulabilirsiniz
        // Şimdilik basit bir yaklaşım - ilk ders öğretmenini al
        $user = Auth::user();
        $course = $user->enrolledCourses()->first();
        if ($course) {
            $this->teacherId = $course->teacher_id;
        }
    }
    
    public function loadTodayActivities()
    {
        if (!Auth::check()) {
            $this->todayActivities = collect([]);
            return;
        }

        $today = now()->format('Y-m-d');
        
        // ChainProgress varsa onunla, yoksa user_id ile direkt ara
        $progress = ChainProgress::where('user_id', Auth::id())->first();
        
        if ($progress) {
            $this->todayActivities = ChainActivity::where('chain_progress_id', $progress->id)
                ->whereDate('activity_date', $today)
                ->get();
        } else {
            // Progress yoksa direkt user_id ile ara
            $this->todayActivities = ChainActivity::where('user_id', Auth::id())
                ->whereDate('activity_date', $today)
                ->get();
        }
        
        // Eğer sonuç null ise, boş collection yap
        if ($this->todayActivities === null) {
            $this->todayActivities = collect([]);
        }
    }

    public function toggleActivityForm()
    {
        $this->showActivityForm = !$this->showActivityForm;
    }

    public function addActivity()
    {
        $this->validate();

        $progress = ChainProgress::firstOrCreate(
            ['user_id' => Auth::id()],
            ['days_completed' => 0, 'current_streak' => 0, 'longest_streak' => 0]
        );

        // Dosya yükleme
        foreach ($this->activityFiles as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('activities', $fileName, 'public');
            
            ChainActivity::create([
                'user_id' => Auth::id(),
                'chain_progress_id' => $progress->id,
                'teacher_id' => $this->teacherId,
                'content' => $this->activityContent,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'activity_date' => now(),
            ]);
        }

        // Sadece text varsa ve dosya yoksa
        if (empty($this->activityFiles) && !empty($this->activityContent)) {
            ChainActivity::create([
                'user_id' => Auth::id(),
                'chain_progress_id' => $progress->id,
                'teacher_id' => $this->teacherId,
                'content' => $this->activityContent,
                'activity_date' => now(),
            ]);
        }

        $this->resetActivityForm();
        $this->loadTodayActivities();
        $this->dispatch('show-success', message: 'Çalışma başarıyla eklendi!');
    }

    public function resetActivityForm()
    {
        $this->activityContent = '';
        $this->activityFiles = [];
        $this->showActivityForm = false;
    }

    public function completeDay()
    {
        if (!Auth::check()) {
            $this->dispatch('show-error', message: 'Gün tamamlamak için giriş yapmalısınız.');
            return;
        }

        // Günün çalışmalarını yeniden yükle ve kontrol et
        $this->loadTodayActivities();
        
        // Collection kontrolü için count() kullan
        if ($this->todayActivities->count() === 0) {
            $this->dispatch('show-error', message: 'Günü tamamlamak için önce çalışma eklemelisiniz!');
            return;
        }

        $progress = ChainProgress::firstOrCreate(
            ['user_id' => Auth::id()],
            ['days_completed' => 0, 'current_streak' => 0, 'longest_streak' => 0]
        );

        $today = now()->format('Y-m-d');
        $lastCompleted = $progress->last_completed_at ? date('Y-m-d', strtotime($progress->last_completed_at)) : null;

        // Aynı gün tekrar tamamlanamaz
        if ($lastCompleted === $today) {
            $this->dispatch('show-error', message: 'Bugün zaten tamamlandı!');
            return;
        }

        $this->previousLevel = $progress->getCurrentLevel();

        $progress->days_completed++;

        if ($lastCompleted === null || $lastCompleted === now()->subDay()->format('Y-m-d')) {
            $progress->current_streak++;
        } else {
            $progress->current_streak = 1;
        }

        if ($progress->current_streak > $progress->longest_streak) {
            $progress->longest_streak = $progress->current_streak;
        }

        $progress->last_completed_at = now();
        $progress->save();

        $this->refreshProgress();

        $newLevel = $progress->getCurrentLevel();

        if ($this->previousLevel !== $newLevel) {
            $this->showLevelUpModal = true;
            $this->dispatch('level-up-animation');
        } else {
            $this->dispatch('day-completed-animation');
        }

        $this->dispatch('show-success', message: 'Gün başarıyla tamamlandı!');
    }

    public function resetChain()
    {
        if (!Auth::check()) {
            return;
        }

        $progress = ChainProgress::where('user_id', Auth::id())->first();

        if (!$progress) {
            return;
        }

        $this->dispatch('confirm-reset');
    }

    public function confirmReset()
    {
        if (!Auth::check()) {
            return;
        }

        $progress = ChainProgress::where('user_id', Auth::id())->first();

        if (!$progress) {
            return;
        }

        $longestStreak = $progress->longest_streak;

        $progress->days_completed = 0;
        $progress->current_streak = 0;
        $progress->last_completed_at = null;
        $progress->save();

        $this->daysCompleted = 0;
        $this->currentStreak = 0;
        $this->longestStreak = $longestStreak;
        $this->currentLevel = 'Bronz';
        $this->levelColor = '#CD7F32';
        $this->nextLevelProgress = 0;

        $this->dispatch('chain-break-animation');
        $this->dispatch('show-info', message: 'Zincir sıfırlandı. Yeniden başlayabilirsiniz!');
    }

    public function closeLevelUpModal()
    {
        $this->showLevelUpModal = false;
    }

    public function render()
    {
        return view('livewire.chain-breaker');
    }
}