<?php

namespace App\Livewire;

use Livewire\Component;

class WordMatchGame extends Component
{
    // Main game state
    public $gameWords = [];
    public $currentWord = null;
    public $currentOptions = [];
    public $score = 0;
    public $timeLeft = 60;
    public $gameStarted = false;
    public $gameFinished = false;
    public $streak = 0;
    public $maxStreak = 0;
    public $totalQuestions = 0;
    public $correctAnswers = 0;
    public $showResult = false;
    public $resultMessage = '';
    public $nextWordDelay = false;

    // Backward compatibility properties
    public $words = [];
    public $definitions = [];
    public $shuffledDefinitions = [];
    public $matches = [];
    public $selectedWord = null;
    public $selectedDefinition = null;
    public $correctMatches = 0;
    public $wrongAttempts = 0;
    public $level = 1;

    public function mount()
    {
        $this->initializeWords();
    }

    public function initializeWords()
    {
        $this->gameWords = [
            ['english' => 'Apple', 'turkish' => 'Elma'],
            ['english' => 'Book', 'turkish' => 'Kitap'],
            ['english' => 'Car', 'turkish' => 'Araba'],
            ['english' => 'Dog', 'turkish' => 'Köpek'],
            ['english' => 'House', 'turkish' => 'Ev'],
            ['english' => 'Water', 'turkish' => 'Su'],
            ['english' => 'Phone', 'turkish' => 'Telefon'],
            ['english' => 'School', 'turkish' => 'Okul'],
            ['english' => 'Friend', 'turkish' => 'Arkadaş'],
            ['english' => 'Happy', 'turkish' => 'Mutlu'],
            ['english' => 'Beautiful', 'turkish' => 'Güzel'],
            ['english' => 'Food', 'turkish' => 'Yemek'],
            ['english' => 'Music', 'turkish' => 'Müzik'],
            ['english' => 'Love', 'turkish' => 'Aşk'],
            ['english' => 'Time', 'turkish' => 'Zaman'],
        ];
    }

    public function startGame()
    {
        // Reset everything
        $this->gameStarted = true;
        $this->gameFinished = false;
        $this->timeLeft = 60;
        $this->score = 0;
        $this->streak = 0;
        $this->maxStreak = 0;
        $this->totalQuestions = 0;
        $this->correctAnswers = 0;
        $this->showResult = false;
        $this->nextWordDelay = false;
        $this->currentOptions = [];
        $this->currentWord = null;
        
        // Generate first question
        $this->generateNewQuestion();
        
        // Log başlangıç
        \Log::info('🎮 Oyun başlatıldı', [
            'timeLeft' => $this->timeLeft,
            'gameStarted' => $this->gameStarted
        ]);
    }

    public function generateNewQuestion()
    {
        if ($this->gameFinished) return;

        // Random word selection
        $randomIndex = array_rand($this->gameWords);
        $this->currentWord = $this->gameWords[$randomIndex];
        
        // Generate 4 options (1 correct + 3 wrong)
        $correctAnswer = $this->currentWord['turkish'];
        $wrongAnswers = collect($this->gameWords)
            ->where('turkish', '!=', $correctAnswer)
            ->pluck('turkish')
            ->shuffle()
            ->take(3)
            ->toArray();

        $allOptions = array_merge([$correctAnswer], $wrongAnswers);
        shuffle($allOptions);

        // DAHA GENIŞ POZISYON ALANLARI
        $basePositions = [
            ['x' => 20, 'y' => 20],   // Sol üst
            ['x' => 80, 'y' => 20],   // Sağ üst  
            ['x' => 20, 'y' => 80],   // Sol alt
            ['x' => 80, 'y' => 80],   // Sağ alt
        ];

        // Her pozisyona BÜYÜK rastgele sapma ekle
        $positions = [];
        foreach($basePositions as $basePos) {
            $positions[] = [
                'x' => $basePos['x'] + rand(-15, 15),
                'y' => $basePos['y'] + rand(-15, 15)
            ];
        }

        // Güvenli sınırlar içinde tut (baloncuk boyutunu hesaba katarak)
        foreach($positions as &$pos) {
            $pos['x'] = max(10, min(90, $pos['x']));
            $pos['y'] = max(10, min(90, $pos['y']));
        }

        // Pozisyonları karıştır
        shuffle($positions);

        // Options array'i oluştur
        $this->currentOptions = [];
        foreach($allOptions as $index => $option) {
            $this->currentOptions[] = [
                'text' => $option,
                'id' => $index,
                'x' => $positions[$index]['x'],
                'y' => $positions[$index]['y'],
                'color' => $this->getRandomBubbleColor()
            ];
        }

        $this->totalQuestions++;
        $this->showResult = false;
        $this->nextWordDelay = false;
        
        // DEBUG - Console'da pozisyonları görmek için
        \Log::info('🎯 Generated Question:', [
            'word' => $this->currentWord['english'] . ' -> ' . $this->currentWord['turkish'],
            'positions' => array_map(function($option) {
                return ['text' => $option['text'], 'x' => $option['x'], 'y' => $option['y']];
            }, $this->currentOptions)
        ]);
    }

    public function getRandomBubbleColor()
    {
        $colors = [
            'bg-blue-600',
            'bg-indigo-600', 
            'bg-purple-600',
            'bg-pink-600',
            'bg-violet-600'
        ];
        
        return $colors[array_rand($colors)];
    }

    public function selectAnswer($selectedOptionEncoded)
    {
        if ($this->nextWordDelay || $this->gameFinished) return;

        // Decode the selected option
        $selectedOption = json_decode(base64_decode($selectedOptionEncoded), true);
        
        if (!$selectedOption || !isset($selectedOption['text'])) return;

        $isCorrect = $selectedOption['text'] === $this->currentWord['turkish'];
        
        if ($isCorrect) {
            $this->correctAnswers++;
            $this->streak++;
            $this->maxStreak = max($this->maxStreak, $this->streak);
            
            // Calculate points with bonuses
            $basePoints = 10;
            $streakBonus = min($this->streak * 2, 50);
            $timeBonus = $this->timeLeft > 50 ? 5 : 0;
            
            $earnedPoints = $basePoints + $streakBonus + $timeBonus;
            $this->score += $earnedPoints;
            
            $this->resultMessage = '+' . $earnedPoints . ' Puan! 🎉';
        } else {
            $this->streak = 0;
            $this->resultMessage = 'Yanlış! Doğrusu: ' . $this->currentWord['turkish'];
        }

        $this->showResult = true;
        $this->nextWordDelay = true;
    }

    public function proceedToNextQuestion()
    {
        $this->showResult = false;
        $this->nextWordDelay = false;
        $this->currentOptions = [];
        
        if ($this->gameFinished) return;
        
        $this->generateNewQuestion();
    }

    public function updateTimer()
    {
        if (!$this->gameStarted || $this->gameFinished) {
            \Log::info('❌ Timer durduruluyor', [
                'gameStarted' => $this->gameStarted,
                'gameFinished' => $this->gameFinished,
                'timeLeft' => $this->timeLeft
            ]);
            return;
        }

        if ($this->timeLeft <= 0) {
            $this->finishGame();
            return;
        }

        $this->timeLeft--;
        
        \Log::info('⏰ Timer güncellendi: ' . $this->timeLeft . 's');
        
        if ($this->timeLeft <= 0) {
            $this->finishGame();
        }
    }

    public function finishGame()
    {
        $this->gameFinished = true;
        $this->gameStarted = false;
        
        // Accuracy bonus
        if ($this->correctAnswers > 0 && $this->totalQuestions > 0) {
            $accuracyBonus = round(($this->correctAnswers / $this->totalQuestions) * 20);
            $this->score += $accuracyBonus;
        }
        
        \Log::info('🏁 Oyun bitti', [
            'finalScore' => $this->score,
            'correctAnswers' => $this->correctAnswers,
            'totalQuestions' => $this->totalQuestions
        ]);
    }

    public function resetGame()
    {
        $this->gameStarted = false;
        $this->gameFinished = false;
        $this->timeLeft = 60;
        $this->score = 0;
        $this->streak = 0;
        $this->maxStreak = 0;
        $this->totalQuestions = 0;
        $this->correctAnswers = 0;
        $this->currentWord = null;
        $this->currentOptions = [];
        $this->showResult = false;
        $this->nextWordDelay = false;
    }

    public function getAccuracy()
    {
        return $this->totalQuestions > 0 ? round(($this->correctAnswers / $this->totalQuestions) * 100) : 0;
    }

    public function render()
    {
        return view('livewire.word-match-game');
    }
}