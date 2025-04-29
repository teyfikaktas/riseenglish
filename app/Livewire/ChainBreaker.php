<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChainProgress;
use Illuminate\Support\Facades\Auth;

class ChainBreaker extends Component
{
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

    protected $listeners = [
        'refreshProgress',
        'confirmReset'
    ];

    public function mount()
    {
        $this->isUserAuthenticated = Auth::check();
        $this->refreshProgress();
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
    }

    public function completeDay()
    {
        if (!Auth::check()) {
            $this->dispatch('show-error', message: 'Gün tamamlamak için giriş yapmalısınız.');
            return;
        }

        $progress = ChainProgress::firstOrCreate(
            ['user_id' => Auth::id()],
            ['days_completed' => 0, 'current_streak' => 0, 'longest_streak' => 0]
        );

        $today = now()->format('Y-m-d');
        $lastCompleted = $progress->last_completed_at ? date('Y-m-d', strtotime($progress->last_completed_at)) : null;

        // if ($lastCompleted === $today) {
        //     $this->dispatch('show-error', message: 'Bugün zaten tamamlandı!');
        //     return;
        // }

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

        // Güncellemeleri çek
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