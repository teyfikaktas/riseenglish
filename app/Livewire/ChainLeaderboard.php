<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChainProgress;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChainLeaderboard extends Component
{
    public $leaderboardData = [];
    public $filterType = 'total'; // current, longest, total
    public $topLimit = 10; // İlk kaç kişiyi göstereceğiz
    public $showAll = false;

    public function mount()
    {
        $this->loadLeaderboard();
    }

    public function loadLeaderboard()
    {
        $query = ChainProgress::with('user')
            ->whereHas('user', function($q) {
                $q->role('ogrenci'); // Sadece ogrenci rolüne sahip kullanıcılar
            });

        switch($this->filterType) {
            case 'current':
                $query->orderBy('current_streak', 'desc')
                      ->where('current_streak', '>', 0);
                break;
            case 'longest':
                $query->orderBy('longest_streak', 'desc')
                      ->where('longest_streak', '>', 0);
                break;
            case 'total':
                $query->orderBy('days_completed', 'desc')
                      ->where('days_completed', '>', 0);
                break;
        }

        if (!$this->showAll) {
            $query->limit($this->topLimit);
        }

        $this->leaderboardData = $query->get()->map(function($progress, $index) {
            return [
                'rank' => $index + 1,
                'user' => $progress->user,
                'current_streak' => $progress->current_streak,
                'longest_streak' => $progress->longest_streak,
                'days_completed' => $progress->days_completed,
                'level' => $progress->getCurrentLevel(),
                'level_color' => $progress->getLevelColor(),
                'avatar' => $progress->user->profile_photo_url ?? null,
                'icon_gender' => $progress->icon_gender ?? 'erkek', // İkon cinsiyeti eklendi
            ];
        });
    }

    public function changeFilter($type)
    {
        $this->filterType = $type;
        $this->loadLeaderboard();
    }

    public function toggleShowAll()
    {
        $this->showAll = !$this->showAll;
        $this->loadLeaderboard();
    }

    public function render()
    {
        return view('livewire.chain-leaderboard');
    }
}