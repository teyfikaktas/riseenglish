<?php

namespace App\Livewire;

use App\Models\Test;
use App\Models\UserTestAnswer;
use App\Models\UserTestResult;
use App\Models\GuestTestAttempt; // Yeni model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
    public $guestTestAttempt; // Misafir girişimi
    public $showResults = false;
    public $showCorrectAnswers = false;
    public $isCompleting = false;
    public $isGuest = false;
    public $guestSessionId;

    // Güvenlik özellikleri
    public $securityViolations = 0;
    public $maxViolations = 2;
    public $lastViolationReason = '';
    public $isSecurityActive = false;

    public $currentAnswer = null;

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

        // Misafir kontrolü
        $this->isGuest = !Auth::check();
        
        if ($this->isGuest) {
            $this->handleGuestAccess();
        }

        $this->questions = $this->test->questions->toArray();

        // Misafirler için soru sayısını sınırla (örn: 5 soru)
        if ($this->isGuest) {
            $this->questions = array_slice($this->questions, 0, 5);
        }

        // Cevapları başlat
        foreach ($this->questions as $question) {
            $this->answers[(string)$question['id']] = null;
        }

        if (!empty($this->questions)) {
            $this->currentAnswer = $this->answers[(string)$this->questions[0]['id']] ?? null;
        }

        // Süreyi ayarla - misafirler için daha kısa süre
        if ($this->test->duration_minutes) {
            $duration = $this->isGuest 
                ? min($this->test->duration_minutes, 10) // Max 10 dakika
                : $this->test->duration_minutes;
            $this->timeRemaining = $duration * 60;
        }
    }

    private function handleGuestAccess()
    {
        // Session kontrolü - aynı session'da daha önce test çözmüş mü?
        $this->guestSessionId = session()->getId();
        
        $existingAttempt = session()->get('guest_test_attempts', []);
        
        // Bu test için daha önce girişim var mı?
        if (isset($existingAttempt[$this->test->id])) {
            session()->flash('warning', 'Bu testi daha önce çözdünüz. Tüm testlere erişim için üye olun!');
            return redirect()->route('ogrenci.test-categories.index');
        }

        // IP bazlı kontrol (günlük limit)
        $today = now()->format('Y-m-d');
        $dailyAttempts = GuestTestAttempt::where('ip_address', request()->ip())
            ->whereDate('created_at', $today)
            ->count();

        // if ($dailyAttempts >= 2) { // Günde 1 test
        //     session()->flash('warning', 'Günlük ücretsiz test hakkınızı kullandınız. Daha fazla test için üye olun!');
        //     return redirect()->route('register');
        // }
    }

    public function startTest()
    {
        $this->isStarted = true;
        $this->isSecurityActive = true;

        if ($this->isGuest) {
            // Misafir girişimi kaydet
            $this->guestTestAttempt = GuestTestAttempt::create([
                'test_id' => $this->test->id,
                'session_id' => $this->guestSessionId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'total_questions' => count($this->questions),
                'status' => 'started',
                'started_at' => now(),
                'answers' => [],
                'security_violations' => 0,
                'violation_details' => []
            ]);

            // Session'a kaydet
            $attempts = session()->get('guest_test_attempts', []);
            $attempts[$this->test->id] = [
                'started_at' => now(),
                'attempt_id' => $this->guestTestAttempt->id
            ];
            session()->put('guest_test_attempts', $attempts);

            Log::info('Misafir test başlatıldı', [
                'test_id' => $this->test->id,
                'session_id' => $this->guestSessionId,
                'ip_address' => request()->ip(),
                'attempt_id' => $this->guestTestAttempt->id
            ]);
        } else {
            // Üye test başlatma (mevcut kod)
            $this->userTestResult = UserTestResult::create([
                'user_id' => Auth::id(),
                'test_id' => $this->test->id,
                'total_questions' => count($this->questions),
                'status' => 'started',
                'started_at' => now(),
                'answers' => [],
                'security_violations' => 0,
                'violation_details' => []
            ]);

            Log::info('Üye test başlatıldı', [
                'user_id' => Auth::id(),
                'test_id' => $this->test->id,
                'user_test_result_id' => $this->userTestResult->id
            ]);
        }

        $this->dispatch('test-started');
    }

    public function selectAnswer($questionId, $choiceId)
    {
        if (!$this->isStarted || $this->isCompleted) {
            return;
        }

        $this->answers[(string)$questionId] = $choiceId;
        $this->currentAnswer = $choiceId;
        $this->dispatch('answer-selected');
    }

    public function handleSecurityViolation($reason = 'Bilinmeyen güvenlik ihlali')
    {
        if (!$this->isSecurityActive || $this->isCompleted) {
            return;
        }

        $this->securityViolations++;
        $this->lastViolationReason = $reason;

        if ($this->isGuest) {
            // Misafir ihlali kaydet
            if ($this->guestTestAttempt) {
                $violations = $this->guestTestAttempt->violation_details ?? [];
                $violations[] = [
                    'reason' => $reason,
                    'timestamp' => now()->toISOString(),
                    'question_index' => $this->currentQuestionIndex + 1,
                    'ip_address' => request()->ip(),
                ];

                $this->guestTestAttempt->update([
                    'security_violations' => $this->securityViolations,
                    'violation_details' => $violations
                ]);
            }

            Log::warning('Misafir güvenlik ihlali', [
                'test_id' => $this->test->id,
                'session_id' => $this->guestSessionId,
                'reason' => $reason,
                'violation_count' => $this->securityViolations
            ]);
        } else {
            // Üye ihlali kaydet (mevcut kod)
            if ($this->userTestResult) {
                $violations = $this->userTestResult->violation_details ?? [];
                $violations[] = [
                    'reason' => $reason,
                    'timestamp' => now()->toISOString(),
                    'question_index' => $this->currentQuestionIndex + 1,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ];

                $this->userTestResult->update([
                    'security_violations' => $this->securityViolations,
                    'violation_details' => $violations
                ]);
            }

            Log::warning('Üye güvenlik ihlali', [
                'user_id' => Auth::id(),
                'test_id' => $this->test->id,
                'reason' => $reason,
                'violation_count' => $this->securityViolations
            ]);
        }

        if ($this->securityViolations >= $this->maxViolations) {
            $this->forceCompleteTest($reason);
        }

        $this->dispatch('security-violation-logged', [
            'count' => $this->securityViolations,
            'reason' => $reason,
            'maxViolations' => $this->maxViolations
        ]);
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
            
            // Misafirler doğru cevapları göremez (teaser için)
            $this->showCorrectAnswers = !$this->isGuest;

            if ($this->isGuest) {
                Log::info('Misafir test tamamlandı', [
                    'test_id' => $this->test->id,
                    'session_id' => $this->guestSessionId,
                    'attempt_id' => $this->guestTestAttempt->id ?? null
                ]);
            } else {
                Log::info('Üye test tamamlandı', [
                    'user_id' => Auth::id(),
                    'test_id' => $this->test->id,
                    'user_test_result_id' => $this->userTestResult->id
                ]);
            }

            $this->dispatch('test-completed');
        } catch (\Exception $e) {
            $this->isCompleting = false;
            $this->isCompleted = false;
            $this->isSecurityActive = true;

            Log::error('Test tamamlama hatası', [
                'error' => $e->getMessage(),
                'is_guest' => $this->isGuest,
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
        }

        $percentage = count($this->questions) > 0 
            ? ($correctAnswers / count($this->questions)) * 100 
            : 0;

        $duration = $this->test->duration_minutes 
            ? ($this->test->duration_minutes * 60) - $this->timeRemaining 
            : 0;

        if ($this->isGuest) {
            // Misafir sonuçları güncelle
            $this->guestTestAttempt->update([
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

            // Misafir için UserTestResult benzeri obje oluştur (view için)
            $this->userTestResult = (object) [
                'correct_answers' => $correctAnswers,
                'wrong_answers' => $wrongAnswers,
                'empty_answers' => $emptyAnswers,
                'percentage' => $percentage,
                'score' => $score,
                'id' => null // Misafirler için detay sayfası yok
            ];
        } else {
            // Üye sonuçları (mevcut kod)
            foreach ($this->questions as $question) {
                $userAnswer = $this->answers[(string)$question['id']] ?? null;
                if ($userAnswer) {
                    $correctChoice = collect($question['choices'])->where('is_correct', true)->first();
                    UserTestAnswer::create([
                        'user_test_result_id' => $this->userTestResult->id,
                        'question_id' => $question['id'],
                        'selected_choice_id' => $userAnswer,
                        'is_correct' => $correctChoice && $userAnswer == $correctChoice['id'],
                        'points_earned' => ($correctChoice && $userAnswer == $correctChoice['id']) 
                            ? ($question['points'] ?? 1) : 0
                    ]);
                }
            }

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
    }

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

            if ($this->isGuest) {
                $this->guestTestAttempt->update([
                    'status' => 'terminated_security',
                    'termination_reason' => $reason,
                    'terminated_at' => now()
                ]);
            } else {
                $this->userTestResult->update([
                    'status' => 'terminated_security',
                    'termination_reason' => $reason,
                    'terminated_at' => now()
                ]);
            }

            $this->showResults = true;
            $this->showCorrectAnswers = false;

            $this->dispatch('test-terminated-security', ['reason' => $reason]);
            session()->flash('error', "Test güvenlik ihlali nedeniyle sonlandırıldı: {$reason}");
        } catch (\Exception $e) {
            Log::error('Güvenlik sonlandırması hatası', [
                'error' => $e->getMessage(),
                'is_guest' => $this->isGuest,
                'test_id' => $this->test->id
            ]);

            $this->isCompleting = false;
            session()->flash('error', 'Test sonlandırılırken bir hata oluştu.');
        }
    }

    // Diğer metodlar aynı kalıyor...
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
        return count($this->questions) > 0 
            ? ($answered / count($this->questions)) * 100 
            : 0;
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
            'answeredCount' => $this->getAnsweredCount(),
            'isGuest' => $this->isGuest
        ]);
    }
}