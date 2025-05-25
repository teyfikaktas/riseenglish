<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestCategoryController extends Controller
{
    public function index()
    {
        $categories = TestCategory::withCount(['tests', 'questions'])
            ->ordered()
            ->paginate(20);

        return view('admin.test-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.test-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'difficulty_level' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        TestCategory::create($data);

        return redirect()->route('admin.test-categories.index')
            ->with('success', 'Test kategorisi başarıyla oluşturuldu!');
    }

    public function show(TestCategory $testCategory)
    {
        $testCategory->load(['tests.questions', 'questions']);
        return view('admin.test-categories.show', compact('testCategory'));
    }

    public function edit(TestCategory $testCategory)
    {
        return view('admin.test-categories.edit', compact('testCategory'));
    }

    public function update(Request $request, TestCategory $testCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'difficulty_level' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        $testCategory->update($data);

        return redirect()->route('admin.test-categories.index')
            ->with('success', 'Test kategorisi başarıyla güncellendi!');
    }

    public function destroy(TestCategory $testCategory)
    {
        $testCategory->delete();

        return redirect()->route('admin.test-categories.index')
            ->with('success', 'Test kategorisi başarıyla silindi!');
    }
}