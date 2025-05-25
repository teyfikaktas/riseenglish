<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestCategory;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::with('categories')
            ->withCount(['questions', 'results'])
            ->ordered()
            ->paginate(20);

        return view('admin.tests.index', compact('tests'));
    }

    public function create()
    {
        $categories = TestCategory::active()->ordered()->get();
        return view('admin.tests.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'difficulty_level' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
            'categories' => 'required|array',
            'categories.*' => 'exists:test_categories,id'
        ]);

        $data = $request->except('categories');
        $data['slug'] = Str::slug($request->title . '-' . time());

        $test = Test::create($data);
        $test->categories()->attach($request->categories);

        return redirect()->route('admin.tests.index')
            ->with('success', 'Test başarıyla oluşturuldu!');
    }

    public function show(Test $test)
    {
        $test->load(['categories', 'questions', 'results.user']);
        return view('admin.tests.show', compact('test'));
    }

    public function edit(Test $test)
    {
        $categories = TestCategory::active()->ordered()->get();
        $test->load('categories');
        return view('admin.tests.edit', compact('test', 'categories'));
    }

    public function update(Request $request, Test $test)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'difficulty_level' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
            'categories' => 'required|array',
            'categories.*' => 'exists:test_categories,id'
        ]);

        $data = $request->except('categories');
        if ($request->title !== $test->title) {
            $data['slug'] = Str::slug($request->title . '-' . time());
        }

        $test->update($data);
        $test->categories()->sync($request->categories);

        return redirect()->route('admin.tests.index')
            ->with('success', 'Test başarıyla güncellendi!');
    }

    public function destroy(Test $test)
    {
        $test->delete();

        return redirect()->route('admin.tests.index')
            ->with('success', 'Test başarıyla silindi!');
    }

    // Teste soru ekle/çıkar
    public function manageQuestions(Test $test)
    {
        $testQuestions = $test->questions()->with('categories')->get();
        $availableQuestions = Question::active()
            ->whereNotIn('id', $testQuestions->pluck('id'))
            ->with('categories')
            ->paginate(20);

        return view('admin.tests.manage-questions', compact('test', 'testQuestions', 'availableQuestions'));
    }

    // Teste soru ekle
    public function addQuestion(Request $request, Test $test)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'order_number' => 'integer|min:1'
        ]);

        $orderNumber = $request->order_number ?? ($test->questions()->count() + 1);

        $test->questions()->attach($request->question_id, [
            'order_number' => $orderNumber
        ]);

        // Test soru sayısını güncelle
        $test->update(['question_count' => $test->questions()->count()]);

        return back()->with('success', 'Soru teste başarıyla eklendi!');
    }

    // Testten soru çıkar
    public function removeQuestion(Test $test, Question $question)
    {
        $test->questions()->detach($question->id);

        // Test soru sayısını güncelle
        $test->update(['question_count' => $test->questions()->count()]);

        return back()->with('success', 'Soru testten başarıyla çıkarıldı!');
    }
}