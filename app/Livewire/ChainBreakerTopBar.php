<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChainProgress;
use Illuminate\Support\Facades\Auth;

class ChainBreakerTopBar extends Component
{
    public $daysCompleted = 0;
    public $currentLevel = 'Bronz';
    public $motivationalText = 'Yolun başındasın!';
    public $levelImagePath = 'images/bronz.png';
    
    protected $listeners = [
        'refreshProgress' => 'refreshProgress',
        'dayCompleted' => 'refreshProgress'
    ];

    public function mount()
    {
        $this->refreshProgress();
    }

    public function refreshProgress()
    {
        if (!Auth::check() || !Auth::user()->hasRole('ogrenci')) {
            return;
        }

        $progress = ChainProgress::where('user_id', Auth::id())->first();

        if (!$progress) {
            return;
        }

        $this->daysCompleted = $progress->days_completed;
        $this->currentLevel = $progress->getCurrentLevel();
        $this->setMotivationalText();
        $this->setLevelImagePath();
    }

    protected function setMotivationalText()
    {
        switch ($this->currentLevel) {
            case 'Bronz':
                $this->motivationalText = 'Yolun başındasın!';
                break;
            case 'Demir':
                $this->motivationalText = 'İlerlemeye devam et!';
                break;
            case 'Gümüş':
                $this->motivationalText = 'Harika ilerliyorsun!';
                break;
            case 'Altın':
                $this->motivationalText = 'İnanılmazsın!';
                break;
            case 'Platin':
                $this->motivationalText = 'Muhteşem gidiyorsun!';
                break;
            case 'Zümrüt':
                $this->motivationalText = 'Olağanüstüsün!';
                break;
            case 'Elmas':
                $this->motivationalText = 'Efsane oluyorsun!';
                break;
            case 'MASTER':
                $this->motivationalText = 'Gerçek bir uzmansın!';
                break;
            default:
                $this->motivationalText = 'Yolun başındasın!';
        }
    }

    protected function setLevelImagePath()
    {
        switch ($this->currentLevel) {
            case 'Bronz':
                $this->levelImagePath = 'images/bronz.png';
                break;
            case 'Demir':
                $this->levelImagePath = 'images/demir.png';
                break;
            case 'Gümüş':
                $this->levelImagePath = 'images/gumus.png';
                break;
            case 'Altın':
                $this->levelImagePath = 'images/altin.png';
                break;
            case 'Platin':
                $this->levelImagePath = 'images/platin.png';
                break;
            case 'Zümrüt':
                $this->levelImagePath = 'images/zumrut.png';
                break;
            case 'Elmas':
                $this->levelImagePath = 'images/elmas.png';
                break;
            case 'MASTER':
                $this->levelImagePath = 'images/master.png';
                break;
            default:
                $this->levelImagePath = 'images/bronz.png';
        }
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

        // Bu kontrolü açabilirsiniz, şimdilik test amacıyla kapalı
        // if ($lastCompleted === $today) {
        //     $this->dispatch('show-error', message: 'Bugün zaten tamamlandı!');
        //     return;
        // }

        $previousLevel = $progress->getCurrentLevel();

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

        // Ana bileşene de bildir
        $this->dispatch('refreshProgress');

        $this->refreshProgress();
        
        $newLevel = $progress->getCurrentLevel();

        if ($previousLevel !== $newLevel) {
            $this->dispatch('level-up-animation');
            $this->dispatch('show-level-up-modal');
        } else {
            $this->dispatch('day-completed-animation');
        }

        $this->dispatch('show-success', message: 'Gün başarıyla tamamlandı!');
    }

    public function render()
    {
        return view('livewire.chain-breaker-top-bar');
    }
}