<?php

namespace App\Livewire;

use App\Models\Test;
use App\Models\UserTestAnswer;
use App\Models\UserTestResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

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
    public $isCompleting = false;

    // Güvenlik özellikleri
    public $securityViolations = 0;
    public $maxViolations = 2;
    public $lastViolationReason = '';
    public $isSecurityActive = false;

    public $currentAnswer = null;

    /**
     * Bu dizi, JS'ten emit edilen event'leri doğrudan bu metotlara yönlendirir.
     */
    protected $listeners = [
        'securityViolation'   => 'handleSecurityViolation',
        'forceCompleteTest'   => 'forceCompleteTest',
    ];

    public function mount($testSlug)
    {
        $this->test = Test::with(['questions.choices'])
            ->where('slug', $testSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $this->questions = $this->test->questions->toArray();

        // Cevapları başlat
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
        $this->isSecurityActive = true;

        // Yeni test sonucu oluştur
        $this->userTestResult = UserTestResult::create([
            'user_id' => Auth::id(),
            'test_id' => $this->test->id,
            'total_questions'     => count($this->questions),
            'status'              => 'started',
            'started_at'          => now(),
            'answers'             => [],
            'security_violations' => 0,
            'violation_details'   => []
        ]);

        Log::info('Test başlatıldı - Güvenlik aktif', [
            'user_id'             => Auth::id(),
            'test_id'             => $this->test->id,
            'user_test_result_id' => $this->userTestResult->id
        ]);

        // Frontend'e event gönder (JS tarafında Livewire.on('test-started') varsa yakalar)
        $this->dispatch('test-started');
    }

    public function selectAnswer($questionId, $choiceId)
    {
        if (!$this->isStarted || $this->isCompleted) {
            return;
        }

        $this->answers[(string)$questionId] = $choiceId;
        $this->currentAnswer = $choiceId;

        // Frontend'e event
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

    /**
     * JS'ten emit('securityViolation', reason) ile tetiklenecek.
     */
    public function handleSecurityViolation($reason = 'Bilinmeyen güvenlik ihlali')
    {
        if (!$this->isSecurityActive || $this->isCompleted) {
            return;
        }

        $this->securityViolations++;
        $this->lastViolationReason = $reason;

        Log::warning('Güvenlik ihlali tespit edildi', [
            'user_id'             => Auth::id(),
            'test_id'             => $this->test->id,
            'user_test_result_id' => $this->userTestResult->id,
            'reason'              => $reason,
            'violation_count'     => $this->securityViolations,
            'current_question'    => $this->currentQuestionIndex + 1,
            'timestamp'           => now()
        ]);

        // Veritabanını güncelle
        if ($this->userTestResult) {
            $violations = $this->userTestResult->violation_details ?? [];
            $violations[] = [
                'reason'         => $reason,
                'timestamp'      => now()->toISOString(),
                'question_index' => $this->currentQuestionIndex + 1,
                'ip_address'     => request()->ip(),
                'user_agent'     => request()->userAgent(),
            ];

            $this->userTestResult->update([
                'security_violations' => $this->securityViolations,
                'violation_details'   => $violations
            ]);
        }

        // Maksimum ihlal sayısına ulaşıldı mı?
        if ($this->securityViolations >= $this->maxViolations) {
            $this->forceCompleteTest($reason);
        }

        // Frontend'e event
        $this->dispatch('security-violation-logged', [
            'count'         => $this->securityViolations,
            'reason'        => $reason,
            'maxViolations' => $this->maxViolations
        ]);
    }

    /**
     * JS'ten emit('forceCompleteTest', reason) ile tetiklenecek.
     */
    public function forceCompleteTest($reason = 'Güvenlik ihlali')
    {
        if ($this->isCompleted) {
            return;
        }

        $this->isSecurityActive = false;
        $this->isCompleting = true;

        try {
            $this->isCompleted = true;
            $this->calculateResults();

            // Test sonucunu güvenlik ihlali ile güncelle
            $this->userTestResult->update([
                'status'             => 'terminated_security',
                'termination_reason' => $reason,
                'terminated_at'      => now()
            ]);

            Log::critical('Test güvenlik ihlali nedeniyle sonlandırıldı', [
                'user_id'             => Auth::id(),
                'test_id'             => $this->test->id,
                'user_test_result_id' => $this->userTestResult->id,
                'reason'              => $reason,
                'total_violations'    => $this->securityViolations
            ]);

            $this->showResults = true;
            $this->showCorrectAnswers = false;

            $this->dispatch('test-terminated-security', ['reason' => $reason]);
            session()->flash('error', "Test güvenlik ihlali nedeniyle sonlandırıldı: {$reason}");
        } catch (\Exception $e) {
            Log::error('Güvenlik sonlandırması sırasında hata', [
                'error'   => $e->getMessage(),
                'user_id' => Auth::id(),
                'test_id' => $this->test->id
            ]);

            $this->isCompleting = false;
            session()->flash('error', 'Test sonlandırılırken bir hata oluştu.');
        }
    }

    public function completeTest()
    {
        if (!$this->isStarted || $this->isCompleted || $this->isCompleting) {
            return;
        }

        $this->isCompleting = true;
        $this->isSecurityActive = false;

        try {
            $this->isCompleted = true;
            $this->calculateResults();
            $this->showResults = true;
            $this->showCorrectAnswers = true;

            Log::info('Test normal olarak tamamlandı', [
                'user_id'             => Auth::id(),
                'test_id'             => $this->test->id,
                'user_test_result_id' => $this->userTestResult->id,
                'security_violations' => $this->securityViolations
            ]);

            $this->dispatch('test-completed');
        } catch (\Exception $e) {
            $this->isCompleting = false;
            $this->isCompleted = false;
            $this->isSecurityActive = true;

            Log::error('Test tamamlama hatası', [
                'error'   => $e->getMessage(),
                'user_id' => Auth::id(),
                'test_id' => $this->test->id
            ]);

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
                $correctChoice = collect($question['choices'])->where('is_correct', true)->first();
                if ($correctChoice && $userAnswer == $correctChoice['id']) {
                    $correctAnswers++;
                    $score += $question['points'] ?? 1;
                } else {
                    $wrongAnswers++;
                }
            }

            if ($userAnswer) {
                UserTestAnswer::create([
                    'user_test_result_id' => $this->userTestResult->id,
                    'question_id'         => $question['id'],
                    'selected_choice_id'  => $userAnswer,
                    'is_correct'          => $correctChoice && $userAnswer == $correctChoice['id'],
                    'points_earned'       => ($correctChoice && $userAnswer == $correctChoice['id'])
                                            ? ($question['points'] ?? 1)
                                            : 0
                ]);
            }
        }

        $percentage = count($this->questions) > 0
            ? ($correctAnswers / count($this->questions)) * 100
            : 0;

        $duration = $this->test->duration_minutes
            ? ($this->test->duration_minutes * 60) - $this->timeRemaining
            : 0;

        $this->userTestResult->update([
            'score'            => $score,
            'correct_answers'  => $correctAnswers,
            'wrong_answers'    => $wrongAnswers,
            'empty_answers'    => $emptyAnswers,
            'percentage'       => $percentage,
            'duration_seconds' => $duration,
            'completed_at'     => now(),
            'status'           => $this->userTestResult->status === 'terminated_security'
                                ? 'terminated_security'
                                : 'completed',
            'answers'          => $this->answers
        ]);
    }

    public function timeUp()
    {
        if ($this->isStarted && !$this->isCompleted && !$this->isCompleting) {
            Log::info('Test süre doldu', [
                'user_id'             => Auth::id(),
                'test_id'             => $this->test->id,
                'user_test_result_id' => $this->userTestResult->id
            ]);

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
        return count($this->questions) > 0
            ? ($answered / count($this->questions)) * 100
            : 0;
    }

    public function getAnsweredCount()
    {
        return collect($this->answers)->filter()->count();
    }

    public function getSecurityStatus()
    {
        return [
            'violations'    => $this->securityViolations,
            'maxViolations' => $this->maxViolations,
            'isActive'      => $this->isSecurityActive,
            'lastViolation' => $this->lastViolationReason
        ];
    }

    public function render()
    {
        return view('livewire.test-taking', [
            'currentQuestion' => $this->getCurrentQuestion(),
            'progress'        => $this->getProgress(),
            'answeredCount'   => $this->getAnsweredCount(),
            'securityStatus'  => $this->getSecurityStatus()
        ]);
    }

    public function resetTest()
    {
        $this->isCompleting = false;
        $this->isCompleted = false;
        $this->isSecurityActive = true;

        Log::info('Test reset edildi', [
            'user_id'             => Auth::id(),
            'test_id'             => $this->test->id,
            'user_test_result_id' => $this->userTestResult->id ?? null
        ]);
    }
}
