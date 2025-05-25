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
        $categories = TestCategory::active()
            ->ordered()
            ->withCount(['tests', 'questions'])
            ->get();

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

        return view('ogrenci.test-categories.show', compact('category'));
    }
}