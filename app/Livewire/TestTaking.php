<?php

namespace App\Livewire;

use App\Models\Test;
use App\Models\Question;
use App\Models\UserTestResult;
use App\Models\UserTestAnswer;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TestTaking extends Component
{
    public $test;
    public $questions;
    public $currentQuestionIndex = 0;
    public $answers = [];
    public $timeRemaining;
    public $isStarted = false;
    public $isCompleted = false;
    public $userTestResult;
    public $showResults = false;
    public $showCorrectAnswers = false;
    public $isCompleting = false; // Test bitirme işlemi için loading durumu
    
    // Bu property'yi ekleyelim - mevcut sorunun ID'sini tutmak için
    public $currentAnswer = null;

    public function mount($testSlug)
    {
        $this->test = Test::with(['questions.choices'])
            ->where('slug', $testSlug)
            ->where('is_active', true)
            ->firstOrFail();
            
        $this->questions = $this->test->questions->toArray(); // Collection'ı array'e çevir
        
        // Cevapları başlat - key'leri string olarak tut
        foreach ($this->questions as $question) {
            $this->answers[(string)$question['id']] = null;
        }
        
        // İlk sorunun cevabını set et
        if (!empty($this->questions)) {
            $this->currentAnswer = $this->answers[(string)$this->questions[0]['id']] ?? null;
        }
        
        // Süreyi ayarla
        if ($this->test->duration_minutes) {
            $this->timeRemaining = $this->test->duration_minutes * 60;
        }
    }

    public function startTest()
    {
        $this->isStarted = true;
        
        // Yeni test sonucu oluştur
        $this->userTestResult = UserTestResult::create([
            'user_id' => Auth::id(),
            'test_id' => $this->test->id,
            'total_questions' => count($this->questions),
            'status' => 'started',
            'started_at' => now(),
            'answers' => []
        ]);
        
        $this->dispatch('test-started');
    }

    public function selectAnswer($questionId, $choiceId)
    {
        if (!$this->isStarted || $this->isCompleted) {
            return;
        }
        
        // Key'i string olarak kullan
        $this->answers[(string)$questionId] = $choiceId;
        $this->currentAnswer = $choiceId;
        
        // Component'ı yeniden render etmek için
        $this->dispatch('answer-selected');
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
            $this->updateCurrentAnswer();
        }
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
            $this->updateCurrentAnswer();
        }
    }

    public function goToQuestion($index)
    {
        if ($index >= 0 && $index < count($this->questions)) {
            $this->currentQuestionIndex = $index;
            $this->updateCurrentAnswer();
        }
    }
    
    private function updateCurrentAnswer()
    {
        $currentQuestion = $this->getCurrentQuestion();
        if ($currentQuestion) {
            $this->currentAnswer = $this->answers[(string)$currentQuestion['id']] ?? null;
        }
    }

    public function completeTest()
    {
        if (!$this->isStarted || $this->isCompleted || $this->isCompleting) {
            return;
        }

        $this->isCompleting = true; // Loading durumu başlat

        try {
            $this->isCompleted = true;
            $this->calculateResults();
            $this->showResults = true;
            $this->showCorrectAnswers = true;
            
            $this->dispatch('test-completed');
        } catch (\Exception $e) {
            $this->isCompleting = false;
            $this->isCompleted = false;
            // Hata durumunda kullanıcıyı bilgilendir
            session()->flash('error', 'Test tamamlanırken bir hata oluştu. Lütfen tekrar deneyin.');
        }
    }

    public function calculateResults()
    {
        $correctAnswers = 0;
        $wrongAnswers = 0;
        $emptyAnswers = 0;
        $score = 0;

        foreach ($this->questions as $question) {
            $userAnswer = $this->answers[(string)$question['id']] ?? null;
            
            if ($userAnswer === null) {
                $emptyAnswers++;
            } else {
                // Doğru cevabı bul
                $correctChoice = collect($question['choices'])->where('is_correct', true)->first();
                if ($correctChoice && $userAnswer == $correctChoice['id']) {
                    $correctAnswers++;
                    $score += $question['points'] ?? 1;
                } else {
                    $wrongAnswers++;
                }
            }

            // Kullanıcı cevabını kaydet
            if ($userAnswer) {
                UserTestAnswer::create([
                    'user_test_result_id' => $this->userTestResult->id,
                    'question_id' => $question['id'],
                    'selected_choice_id' => $userAnswer,
                    'is_correct' => $correctChoice && $userAnswer == $correctChoice['id'],
                    'points_earned' => ($correctChoice && $userAnswer == $correctChoice['id']) ? ($question['points'] ?? 1) : 0
                ]);
            }
        }

        $percentage = ($correctAnswers / count($this->questions)) * 100;
        $duration = $this->test->duration_minutes ? 
            ($this->test->duration_minutes * 60) - $this->timeRemaining : 
            0;

        // Test sonucunu güncelle
        $this->userTestResult->update([
            'score' => $score,
            'correct_answers' => $correctAnswers,
            'wrong_answers' => $wrongAnswers,
            'empty_answers' => $emptyAnswers,
            'percentage' => $percentage,
            'duration_seconds' => $duration,
            'completed_at' => now(),
            'status' => 'completed',
            'answers' => $this->answers
        ]);
    }

    public function timeUp()
    {
        if ($this->isStarted && !$this->isCompleted && !$this->isCompleting) {
            $this->completeTest();
            $this->dispatch('time-up');
        }
    }

    public function getCurrentQuestion()
    {
        return $this->questions[$this->currentQuestionIndex] ?? null;
    }

    public function getProgress()
    {
        $answered = collect($this->answers)->filter()->count();
        return ($answered / count($this->questions)) * 100;
    }

    public function getAnsweredCount()
    {
        return collect($this->answers)->filter()->count();
    }

    public function render()
    {
        return view('livewire.test-taking', [
            'currentQuestion' => $this->getCurrentQuestion(),
            'progress' => $this->getProgress(),
            'answeredCount' => $this->getAnsweredCount()
        ]);
    }
    
    // Acil durum için reset metodu
    public function resetTest()
    {
        $this->isCompleting = false;
        $this->isCompleted = false;
        // Eğer gerçekten stuck olduysa bu metodu çağırabilirsin
    }
}