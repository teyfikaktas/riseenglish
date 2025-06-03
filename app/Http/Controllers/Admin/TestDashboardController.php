<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestCategory;
use App\Models\Question;
use App\Models\UserTestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
class TestDashboardController extends Controller
{
    public function index()
    {
        // İstatistikler
        $stats = [
            'total_categories' => TestCategory::count(),
            'active_categories' => TestCategory::where('is_active', true)->count(),
            'total_tests' => Test::count(),
            'active_tests' => Test::where('is_active', true)->count(),
            'total_questions' => Question::count(),
            'active_questions' => Question::where('is_active', true)->count(),
            'total_attempts' => UserTestResult::count(),
            'completed_attempts' => UserTestResult::where('status', 'completed')->count(),
        ];

        // Son eklenen kategoriler
        $recentCategories = TestCategory::latest()
            ->take(5)
            ->get();

        // Son eklenen testler
        $recentTests = Test::with('categories')
            ->latest()
            ->take(5)
            ->get();

        // Popüler testler (en çok çözülen)
        $popularTests = Test::withCount('userTestResults')
            ->orderBy('user_test_results_count', 'desc')
            ->take(5)
            ->get();

        // Son test sonuçları
        $recentResults = UserTestResult::with(['test', 'user'])
            ->where('status', 'completed')
            ->latest()
            ->take(10)
            ->get();

        // Kategori başına test sayıları
        $categoryStats = TestCategory::withCount('tests')
            ->orderBy('tests_count', 'desc')
            ->get();

        return view('admin.test-dashboard.index', compact(
            'stats',
            'recentCategories',
            'recentTests',
            'popularTests',
            'recentResults',
            'categoryStats'
        ));
    }
}