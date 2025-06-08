<?php

namespace App\Http\Controllers\Ogrenci;

use App\Http\Controllers\Controller;
use App\Models\TestCategory;
use Illuminate\Http\Request;

class TestCategoryController extends Controller
{
    // Test kategorilerini listele
    public function index()
    {
        // withQuestionCounts static method'unu kullan
        $categories = TestCategory::withQuestionCounts()
            ->active()
            ->ordered()
            ->get();

        // Debug için log
        \Log::info('Categories with counts:', $categories->toArray());

        return view('ogrenci.test-categories.index', compact('categories'));
    }

    // Kategoriye ait testleri göster
    public function show($slug)
    {
        $category = TestCategory::bySlug($slug)
            ->active()
            ->with(['tests' => function($query) {
                $query->active()->ordered()->withCount('questions');
            }])
            ->firstOrFail();

        // Manuel soru sayısı hesaplama
        $category->questions_count = $category->tests->sum('questions_count');
        $category->tests_count = $category->tests->count();

        return view('ogrenci.test-categories.show', compact('category'));
    }
}