<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\TestCategory;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::with('categories');

        // Filtreler
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('difficulty')) {
            $query->byDifficulty($request->difficulty);
        }

        if ($request->filled('search')) {
            $query->where('question_text', 'like', '%' . $request->search . '%');
        }

        $questions = $query->latest()->paginate(20);
        $categories = TestCategory::active()->ordered()->get();

        return view('admin.questions.index', compact('questions', 'categories'));
    }

    public function create()
    {
        $categories = TestCategory::active()->ordered()->get();
        $tests = Test::active()->ordered()->get();
        
        return view('admin.questions.create', compact('categories', 'tests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,fill_blank,matching',
            'options' => 'required_if:question_type,multiple_choice,matching|array',
            'options.*' => 'required_with:options|string',
            'correct_answer' => 'required|string',
            'explanation' => 'nullable|string',
            'difficulty_level' => 'nullable|string|max:50',
            'points' => 'integer|min:1|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'categories' => 'required|array',
            'categories.*' => 'exists:test_categories,id',
            'tests' => 'nullable|array',
            'tests.*' => 'exists:tests,id'
        ]);

        $data = $request->except(['image', 'categories', 'tests']);

        // Görsel yükleme
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('questions', 'public');
            $data['image_path'] = $imagePath;
        }

        // Çoktan seçmeli sorular için options formatla
        if ($request->question_type === 'multiple_choice') {
            $options = [];
            foreach ($request->options as $key => $option) {
                if (!empty($option)) {
                    $options[chr(65 + $key)] = $option; // A, B, C, D
                }
            }
            $data['options'] = $options;
        }

        $question = Question::create($data);

        // Kategorileri ekle
        if ($request->filled('categories')) {
            $question->categories()->attach($request->categories);
        }

        // Testlere ekle
        if ($request->filled('tests')) {
            foreach ($request->tests as $testId) {
                $test = Test::find($testId);
                $orderNumber = $test->questions()->count() + 1;
                $question->tests()->attach($testId, ['order_number' => $orderNumber]);
                
                // Test soru sayısını güncelle
                $test->update(['question_count' => $test->questions()->count()]);
            }
        }

        return redirect()->route('admin.questions.index')
            ->with('success', 'Soru başarıyla oluşturuldu!');
    }

    public function show(Question $question)
    {
        $question->load(['categories', 'tests']);
        return view('admin.questions.show', compact('question'));
    }

    public function edit(Question $question)
    {
        $categories = TestCategory::active()->ordered()->get();
        $tests = Test::active()->ordered()->get();
        $question->load(['categories', 'tests']);
        
        return view('admin.questions.edit', compact('question', 'categories', 'tests'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,fill_blank,matching',
            'options' => 'required_if:question_type,multiple_choice,matching|array',
            'options.*' => 'required_with:options|string',
            'correct_answer' => 'required|string',
            'explanation' => 'nullable|string',
            'difficulty_level' => 'nullable|string|max:50',
            'points' => 'integer|min:1|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'categories' => 'required|array',
            'categories.*' => 'exists:test_categories,id',
            'tests' => 'nullable|array',
            'tests.*' => 'exists:tests,id'
        ]);

        $data = $request->except(['image', 'categories', 'tests']);

        // Görsel yükleme
        if ($request->hasFile('image')) {
            // Eski görseli sil
            if ($question->image_path) {
                Storage::disk('public')->delete($question->image_path);
            }
            
            $imagePath = $request->file('image')->store('questions', 'public');
            $data['image_path'] = $imagePath;
        }

        // Çoktan seçmeli sorular için options formatla
        if ($request->question_type === 'multiple_choice') {
            $options = [];
            foreach ($request->options as $key => $option) {
                if (!empty($option)) {
                    $options[chr(65 + $key)] = $option; // A, B, C, D
                }
            }
            $data['options'] = $options;
        }

        $question->update($data);

        // Kategorileri güncelle
        $question->categories()->sync($request->categories);

        // Testleri güncelle
        if ($request->filled('tests')) {
            // Mevcut test ilişkilerini kaldır
            foreach ($question->tests as $test) {
                $test->update(['question_count' => $test->questions()->count() - 1]);
            }
            $question->tests()->detach();

            // Yeni test ilişkilerini ekle
            foreach ($request->tests as $testId) {
                $test = Test::find($testId);
                $orderNumber = $test->questions()->count() + 1;
                $question->tests()->attach($testId, ['order_number' => $orderNumber]);
                
                // Test soru sayısını güncelle
                $test->update(['question_count' => $test->questions()->count()]);
            }
        } else {
            // Hiç test seçilmemişse tüm ilişkileri kaldır
            foreach ($question->tests as $test) {
                $test->update(['question_count' => $test->questions()->count() - 1]);
            }
            $question->tests()->detach();
        }

        return redirect()->route('admin.questions.index')
            ->with('success', 'Soru başarıyla güncellendi!');
    }

    public function destroy(Question $question)
    {
        // Görsel dosyasını sil
        if ($question->image_path) {
            Storage::disk('public')->delete($question->image_path);
        }

        // Test soru sayılarını güncelle
        foreach ($question->tests as $test) {
            $test->update(['question_count' => $test->questions()->count() - 1]);
        }

        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Soru başarıyla silindi!');
    }

    // Toplu soru ekleme
    public function bulkCreate()
    {
        $categories = TestCategory::active()->ordered()->get();
        $tests = Test::active()->ordered()->get();
        
        return view('admin.questions.bulk-create', compact('categories', 'tests'));
    }

    // Toplu soru kaydetme
    public function bulkStore(Request $request)
    {
        $request->validate([
            'questions_data' => 'required|string',
            'default_category' => 'required|exists:test_categories,id',
            'default_difficulty' => 'nullable|string',
            'default_points' => 'integer|min:1|max:10'
        ]);

        $questionsData = json_decode($request->questions_data, true);
        
        if (!$questionsData) {
            return back()->withErrors(['questions_data' => 'Geçersiz JSON formatı!']);
        }

        $createdCount = 0;

        foreach ($questionsData as $questionData) {
            try {
                $question = Question::create([
                    'question_text' => $questionData['question_text'],
                    'question_type' => $questionData['question_type'] ?? 'multiple_choice',
                    'options' => $questionData['options'] ?? null,
                    'correct_answer' => $questionData['correct_answer'],
                    'explanation' => $questionData['explanation'] ?? null,
                    'difficulty_level' => $questionData['difficulty_level'] ?? $request->default_difficulty,
                    'points' => $questionData['points'] ?? $request->default_points ?? 1,
                    'is_active' => true
                ]);

                // Varsayılan kategoriyi ekle
                $question->categories()->attach($request->default_category);
                $createdCount++;

            } catch (\Exception $e) {
                // Hata durumunda devam et
                continue;
            }
        }

        return redirect()->route('admin.questions.index')
            ->with('success', "{$createdCount} soru başarıyla oluşturuldu!");
    }

    // Soru kategorilerini güncelle
    public function updateCategories(Request $request, Question $question)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'exists:test_categories,id'
        ]);

        $question->categories()->sync($request->categories);

        return back()->with('success', 'Soru kategorileri güncellendi!');
    }

    // Soruyu klonla
    public function clone(Question $question)
    {
        $newQuestion = $question->replicate();
        $newQuestion->question_text = $question->question_text . ' (Kopya)';
        $newQuestion->save();

        // İlişkileri de kopyala
        $newQuestion->categories()->attach($question->categories->pluck('id'));

        return redirect()->route('admin.questions.edit', $newQuestion)
            ->with('success', 'Soru başarıyla kopyalandı!');
    }
}
