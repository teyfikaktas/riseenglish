<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestCategory;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
        try {
            $tests = Test::with(['categories'])
                ->withCount(['questions', 'userTestResults'])
                ->orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return view('admin.tests.index', compact('tests'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Testler yüklenirken hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        try {
            $categories = TestCategory::active()->ordered()->get();
            return view('admin.tests.create', compact('categories'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Test oluşturma sayfası yüklenirken hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validation
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'difficulty_level' => 'nullable|string|max:50',
                'time_limit' => 'nullable|integer|min:1|max:180',
                'passing_score' => 'nullable|integer|min:1|max:100',
                'sort_order' => 'nullable|integer|min:0',
                'category_ids' => 'required|array|min:1',
                'category_ids.*' => 'exists:test_categories,id',
                'is_active' => 'nullable|boolean',
                'shuffle_questions' => 'nullable|boolean',
                'show_results' => 'nullable|boolean',
                'allow_retake' => 'nullable|boolean'
            ], [
                'title.required' => 'Test adı zorunludur',
                'title.max' => 'Test adı en fazla 255 karakter olabilir',
                'category_ids.required' => 'En az bir kategori seçmelisiniz',
                'category_ids.min' => 'En az bir kategori seçmelisiniz',
                'time_limit.min' => 'Test süresi en az 1 dakika olmalıdır',
                'time_limit.max' => 'Test süresi en fazla 180 dakika olabilir',
                'passing_score.min' => 'Geçme puanı en az 1 olmalıdır',
                'passing_score.max' => 'Geçme puanı en fazla 100 olabilir'
            ]);

            DB::beginTransaction();

            // Slug oluştur
            $slug = Str::slug($validatedData['title']);
            $originalSlug = $slug;
            $count = 1;
            while (Test::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            // Test oluştur
            $test = Test::create([
                'title' => $validatedData['title'],
                'slug' => $slug,
                'description' => $validatedData['description'],
                'difficulty_level' => $validatedData['difficulty_level'],
                'time_limit' => $validatedData['time_limit'],
                'passing_score' => $validatedData['passing_score'] ?? 60,
                'sort_order' => $validatedData['sort_order'] ?? 0,
                'is_active' => $request->has('is_active'),
                'shuffle_questions' => $request->has('shuffle_questions'),
                'show_results' => $request->has('show_results'),
                'allow_retake' => $request->has('allow_retake')
            ]);

            // Kategorileri attach et
            $test->categories()->attach($validatedData['category_ids']);

            DB::commit();

            return redirect()->route('admin.tests.index')
                ->with('success', '✅ Test başarıyla oluşturuldu! Test: ' . $test->title);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Form verilerinde hata var!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Test oluşturulurken hata oluştu: ' . $e->getMessage());
        }
    }

    public function show(Test $test)
    {
        try {
            $test->load(['categories', 'questions.categories']);
            $test->loadCount(['questions', 'userTestResults']);
            
            return view('admin.tests.show', compact('test'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Test detayları yüklenirken hata oluştu']);
        }
    }

    public function edit(Test $test)
    {
        try {
            $categories = TestCategory::active()->ordered()->get();
            $test->load('categories');
            
            return view('admin.tests.edit', compact('test', 'categories'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Test düzenleme sayfası yüklenirken hata oluştu']);
        }
    }

    public function update(Request $request, Test $test)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'difficulty_level' => 'nullable|string|max:50',
                'time_limit' => 'nullable|integer|min:1|max:180',
                'passing_score' => 'nullable|integer|min:1|max:100',
                'sort_order' => 'nullable|integer|min:0',
                'category_ids' => 'required|array|min:1',
                'category_ids.*' => 'exists:test_categories,id',
                'is_active' => 'nullable|boolean',
                'shuffle_questions' => 'nullable|boolean',
                'show_results' => 'nullable|boolean',
                'allow_retake' => 'nullable|boolean'
            ]);

            DB::beginTransaction();

            // Slug güncelleme (gerekirse)
            $slug = Str::slug($validatedData['title']);
            $originalSlug = $slug;
            $count = 1;
            while (Test::where('slug', $slug)->where('id', '!=', $test->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $test->update([
                'title' => $validatedData['title'],
                'slug' => $slug,
                'description' => $validatedData['description'],
                'difficulty_level' => $validatedData['difficulty_level'],
                'time_limit' => $validatedData['time_limit'],
                'passing_score' => $validatedData['passing_score'] ?? 60,
                'sort_order' => $validatedData['sort_order'] ?? 0,
                'is_active' => $request->has('is_active'),
                'shuffle_questions' => $request->has('shuffle_questions'),
                'show_results' => $request->has('show_results'),
                'allow_retake' => $request->has('allow_retake')
            ]);

            // Kategorileri sync et
            $test->categories()->sync($validatedData['category_ids']);

            DB::commit();

            return redirect()->route('admin.tests.show', $test)
                ->with('success', 'Test başarıyla güncellendi!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Form verilerinde hata var!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Test güncellenirken hata oluştu: ' . $e->getMessage());
        }
    }

    public function destroy(Test $test)
    {
        try {
            DB::beginTransaction();
            
            // Test sonuçları kontrol et
            if ($test->userTestResults()->count() > 0) {
                return back()->with('error', 'Bu test öğrenciler tarafından çözülmüş. Silinemez!');
            }
            
            // İlişkileri temizle
            $test->categories()->detach();
            $test->questions()->detach();
            
            // Test'i sil
            $test->delete();
            
            DB::commit();

            return redirect()->route('admin.tests.index')
                ->with('success', 'Test başarıyla silindi!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Test silinirken hata oluştu: ' . $e->getMessage());
        }
    }

    // Soru yönetimi metodları
    public function manageQuestions(Test $test)
    {
        try {
            $test->load(['questions' => function($query) {
                $query->with('categories')->orderBy('pivot_order_number');
            }]);
            
            // Test kategorilerine ait sorular
            $availableQuestions = Question::active()
                ->whereHas('categories', function($query) use ($test) {
                    $query->whereIn('test_categories.id', $test->categories->pluck('id'));
                })
                ->whereNotIn('id', $test->questions->pluck('id'))
                ->with('categories')
                ->paginate(20);

            return view('admin.tests.manage-questions', compact('test', 'availableQuestions'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Soru yönetimi sayfası yüklenirken hata oluştu']);
        }
    }

    public function addQuestion(Request $request, Test $test)
    {
        try {
            $request->validate([
                'question_id' => 'required|exists:questions,id',
                'order_number' => 'nullable|integer|min:1'
            ]);

            $questionId = $request->question_id;
            
            // Sorunun zaten ekli olup olmadığını kontrol et
            if ($test->questions()->where('question_id', $questionId)->exists()) {
                return back()->with('error', 'Bu soru zaten teste ekli!');
            }

            // Sıra numarası belirleme
            $orderNumber = $request->order_number ?? ($test->questions()->max('order_number') + 1);

            $test->questions()->attach($questionId, [
                'order_number' => $orderNumber,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return back()->with('success', 'Soru teste başarıyla eklendi!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Soru eklenirken hata oluştu: ' . $e->getMessage());
        }
    }

    public function removeQuestion(Test $test, Question $question)
    {
        try {
            $test->questions()->detach($question->id);
            
            return back()->with('success', 'Soru testten başarıyla kaldırıldı!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Soru kaldırılırken hata oluştu: ' . $e->getMessage());
        }
    }
}