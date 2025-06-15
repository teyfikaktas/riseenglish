<?php

namespace App\Http\Controllers\Ogrenci;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestCategory;
use App\Models\GuestTestAttempt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

use App\Models\UserTestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TestController extends Controller
{
    /**
     * Test detayını göster - Misafir ve üye desteği
     */
    public function show($slug)
    {
        $test = Test::bySlug($slug)
            ->active()
            ->with(['categories', 'questions'])
            ->withCount('questions')
            ->firstOrFail();

        $isGuest = !Auth::check();
        $userResults = null;

        if (!$isGuest) {
            // Kullanıcının bu teste ait önceki sonuçları
            $userResults = UserTestResult::byUser(Auth::id())
                ->byTest($test->id)
                ->completed()
                ->latest()
                ->take(5)
                ->get();
        } else {
            // Misafirler için sınırlı bilgi
            $test->questions = $test->questions->take(5);
        }

        return view('ogrenci.tests.show', compact('test', 'userResults', 'isGuest'));
    }

    /**
     * Test çözme sayfası - Misafir ve üye desteği
     */
    public function take($slug)
    {
        $test = Test::bySlug($slug)
            ->active()
            ->with(['categories', 'questions.choices'])
            ->withCount('questions')
            ->firstOrFail();

        // Misafir kontrolü
        if (!Auth::check()) {
            $limitCheck = $this->checkGuestLimits($test);
            if ($limitCheck !== true) {
                return $limitCheck; // Redirect response
            }
        }

        return view('ogrenci.tests.take', compact('test'));
    }

    /**
     * Teste başla - Eski yapı uyumluluğu için
     */
    public function start($slug)
    {
        // Artık hepsi take() metoduna yönlendirilecek
        return redirect()->route('ogrenci.tests.take', $slug);
    }

    /**
     * Test cevaplarını kaydet - Sadece üyeler
     */
    public function submit(Request $request, $slug)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Test göndermek için giriş yapmalısınız.');
            return redirect()->route('login');
        }

        $test = Test::bySlug($slug)->firstOrFail();
        $testResult = UserTestResult::where('user_id', Auth::id())
            ->where('test_id', $test->id)
            ->where('status', 'started')
            ->latest()
            ->firstOrFail();

        $answers = $request->input('answers', []);
        $questions = $test->questions;
        
        $correctAnswers = 0;
        $wrongAnswers = 0;
        $emptyAnswers = 0;
        $totalScore = 0;

        foreach ($questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            
            if (empty($userAnswer)) {
                $emptyAnswers++;
            } elseif ($question->isCorrectAnswer($userAnswer)) {
                $correctAnswers++;
                $totalScore += $question->points;
            } else {
                $wrongAnswers++;
            }
        }

        $percentage = $questions->count() > 0 ? ($correctAnswers / $questions->count()) * 100 : 0;
        $durationSeconds = Carbon::now()->diffInSeconds($testResult->started_at);

        $testResult->update([
            'score' => $totalScore,
            'correct_answers' => $correctAnswers,
            'wrong_answers' => $wrongAnswers,
            'empty_answers' => $emptyAnswers,
            'percentage' => $percentage,
            'duration_seconds' => $durationSeconds,
            'completed_at' => Carbon::now(),
            'status' => 'completed',
            'answers' => $answers
        ]);

        return redirect()->route('ogrenci.tests.result', $testResult->id)
            ->with('success', 'Test başarıyla tamamlandı!');
    }

    /**
     * Test sonucunu göster - Sadece üyeler
     */
    public function result($resultId)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Test sonuçlarını görmek için giriş yapmalısınız.');
            return redirect()->route('login');
        }

        $result = UserTestResult::with(['test.questions', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($resultId);

        return view('ogrenci.tests.result', compact('result'));
    }

    /**
     * Test PDF indirme - Sadece üyeler
     */
    public function downloadTestPdf($slug)
    {
        if (!Auth::check()) {
            session()->flash('error', 'PDF indirmek için giriş yapmalısınız.');
            return redirect()->route('login');
        }

        $test = Test::bySlug($slug)
            ->active()
            ->with(['categories', 'questions.choices'])
            ->firstOrFail();

        $pdf = PDF::loadView('ogrenci.tests.pdf', compact('test'))
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => true,
                'chroot' => public_path(),
                'defaultPaperSize' => 'a4',
            ]);
        
        $filename = 'test-' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Test sonucu PDF indirme - Sadece üyeler
     */
    public function downloadResultPdf($resultId)
    {
        if (!Auth::check()) {
            session()->flash('error', 'PDF indirmek için giriş yapmalısınız.');
            return redirect()->route('login');
        }

        $result = UserTestResult::with(['test.questions.choices', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($resultId);

        $pdf = PDF::loadView('ogrenci.tests.result-pdf', compact('result'));
        
        return $pdf->download('test-sonucu-' . $result->id . '.pdf');
    }

    /**
     * Test geçmişi PDF indirme - Sadece üyeler
     */
    public function downloadHistoryPdf(Request $request)
    {
        if (!Auth::check()) {
            session()->flash('error', 'PDF indirmek için giriş yapmalısınız.');
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        $results = UserTestResult::with(['test', 'test.categories'])
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_tests' => $results->count(),
            'average_score' => $results->avg('percentage') ?? 0,
            'best_score' => $results->max('percentage') ?? 0,
            'total_time_minutes' => round($results->sum('duration_seconds') / 60),
        ];

        $pdf = PDF::loadView('ogrenci.tests.history-pdf', compact('results', 'stats', 'user'));
        
        return $pdf->download('test-gecmisim.pdf');
    }

    /**
     * Test geçmişi - Sadece üyeler
     */
    public function history(Request $request)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Test geçmişinizi görmek için giriş yapmalısınız.');
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Filtreleme parametreleri
        $categorySlug = $request->get('category');
        $status = $request->get('status', 'completed'); // varsayılan olarak tamamlanan testler
        $sortBy = $request->get('sort', 'latest'); // latest, oldest, score_high, score_low
        $perPage = $request->get('per_page', 10);
        
        // Base query
        $query = UserTestResult::with(['test', 'test.categories'])
            ->where('user_id', $user->id);
        
        // Status filtresi
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        
        // Kategori filtresi
        if ($categorySlug) {
            $query->whereHas('test.categories', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }
        
        // Sıralama
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'score_high':
                $query->where('status', 'completed')
                      ->orderBy('percentage', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
            case 'score_low':
                $query->where('status', 'completed')
                      ->orderBy('percentage', 'asc')
                      ->orderBy('created_at', 'desc');
                break;
            default: // latest
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        // Sayfalama
        $results = $query->paginate($perPage);
        
        // Kategoriler listesi (filtreleme için)
        $categories = TestCategory::withCount(['tests' => function($q) use ($user) {
            $q->whereHas('userTestResults', function($subQuery) use ($user) {
                $subQuery->where('user_id', $user->id);
            });
        }])
        ->having('tests_count', '>', 0)
        ->orderBy('name')
        ->get();
        
        // İstatistikler
        $stats = [
            'total_tests' => UserTestResult::where('user_id', $user->id)->count(),
            'completed_tests' => UserTestResult::where('user_id', $user->id)->where('status', 'completed')->count(),
            'average_score' => UserTestResult::where('user_id', $user->id)
                ->where('status', 'completed')
                ->avg('percentage') ?? 0,
            'best_score' => UserTestResult::where('user_id', $user->id)
                ->where('status', 'completed')
                ->max('percentage') ?? 0,
            'total_time_minutes' => round(
                (UserTestResult::where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->sum('duration_seconds') ?? 0) / 60
            ),
            'success_rate' => UserTestResult::where('user_id', $user->id)
                ->where('status', 'completed')
                ->where('percentage', '>=', 60)
                ->count()
        ];
        
        // Başarı oranını hesapla
        if ($stats['completed_tests'] > 0) {
            $stats['success_rate_percentage'] = round(($stats['success_rate'] / $stats['completed_tests']) * 100, 1);
        } else {
            $stats['success_rate_percentage'] = 0;
        }
        
        return view('ogrenci.tests.history', compact(
            'results', 
            'categories', 
            'stats',
            'categorySlug',
            'status',
            'sortBy',
            'perPage'
        ));
    }

    /**
     * Misafir kullanıcılar için limit kontrolü
     */
    private function checkGuestLimits($test)
    {
        $today = now()->format('Y-m-d');
        $sessionId = session()->getId();
        $ipAddress = request()->ip();

        // Session kontrolü - aynı session'da bu testi çözmüş mü?
        $sessionAttempts = session()->get('guest_test_attempts', []);
        if (isset($sessionAttempts[$test->id])) {
            session()->flash('warning', 'Bu testi bu oturumda daha önce çözdünüz. Tekrar çözmek için üye olun!');
            return redirect()->route('ogrenci.test-categories.index');
        }

        // IP bazlı günlük limit kontrolü
        $dailyAttempts = GuestTestAttempt::where('ip_address', $ipAddress)
            ->whereDate('created_at', $today)
            ->count();

        // if ($dailyAttempts >= 2) {
        //     session()->flash('warning', 'Günlük ücretsiz test hakkınızı kullandınız. Daha fazla test için üye olun!');
        //     return redirect()->route('register')
        //         ->with('info', 'Ücretsiz üye olarak sınırsız test çözebilirsiniz!');
        // }

        // Limit kontrolü geçildi
        return true;
    }
}