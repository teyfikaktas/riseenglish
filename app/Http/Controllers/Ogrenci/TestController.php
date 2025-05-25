<?php

namespace App\Http\Controllers\Ogrenci;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestCategory;

use App\Models\UserTestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TestController extends Controller
{
    // Test detayını göster
    public function show($slug)
    {
        $test = Test::bySlug($slug)
            ->active()
            ->with(['categories', 'questions'])
            ->withCount('questions')
            ->firstOrFail();

        // Kullanıcının bu teste ait önceki sonuçları
        $userResults = UserTestResult::byUser(Auth::id())
            ->byTest($test->id)
            ->completed()
            ->latest()
            ->take(5)
            ->get();

        return view('ogrenci.tests.show', compact('test', 'userResults'));
    }

    // Teste başla
    public function start($slug)
    {
        $test = Test::bySlug($slug)
            ->active()
            ->with(['questions' => function($query) {
                $query->active()->orderBy('pivot_order_number');
            }])
            ->firstOrFail();

        // Yeni test sonucu oluştur
        $testResult = UserTestResult::create([
            'user_id' => Auth::id(),
            'test_id' => $test->id,
            'total_questions' => $test->questions->count(),
            'started_at' => Carbon::now(),
            'status' => 'started'
        ]);

        return view('ogrenci.tests.take', compact('test', 'testResult'));
    }

    // Test cevaplarını kaydet
    public function submit(Request $request, $slug)
    {
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

    // Test sonucunu göster
    public function result($resultId)
    {
        $result = UserTestResult::with(['test.questions', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($resultId);

        return view('ogrenci.tests.result', compact('result'));
    }



public function history(Request $request)
{
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
}