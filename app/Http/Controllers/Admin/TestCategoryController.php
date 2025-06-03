<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TestCategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = TestCategory::withCount(['tests', 'questions'])
                ->ordered()
                ->paginate(20);

            return view('admin.test-categories.index', compact('categories'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Kategoriler yüklenirken hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        return view('admin.test-categories.create');
    }

    public function store(Request $request)
    {
        // DEBUG: Gelen veriyi kontrol et
        // dd('TestCategoryController store metodu çalışıyor!', $request->all());
        
        try {
            // Validation - Test Category için doğru alanlar
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'icon' => 'nullable|string|max:50',
                'difficulty_level' => 'nullable|string|max:50',
                'color' => 'nullable|string|max:20',
                'is_active' => 'nullable|boolean',
                'sort_order' => 'nullable|integer|min:0'
            ], [
                'name.required' => 'Kategori adı zorunludur',
                'name.max' => 'Kategori adı en fazla 255 karakter olabilir',
                'sort_order.min' => 'Sıralama 0\'dan küçük olamaz'
            ]);

            // Database transaction başlat
            DB::beginTransaction();

            // Slug oluştur
            $slug = Str::slug($validatedData['name']);
            
            // Eğer aynı slug varsa unique yap
            $originalSlug = $slug;
            $count = 1;
            while (TestCategory::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            // Test Kategorisi oluştur
            $category = TestCategory::create([
                'name' => $validatedData['name'],
                'slug' => $slug,
                'description' => $validatedData['description'] ?? null,
                'icon' => $validatedData['icon'] ?? null,
                'difficulty_level' => $validatedData['difficulty_level'] ?? null,
                'color' => $validatedData['color'] ?? 'blue',
                'is_active' => $request->has('is_active'),
                'sort_order' => $validatedData['sort_order'] ?? 0,
            ]);

            DB::commit();

            return redirect()->route('admin.test-categories.index')
                ->with('success', 'Test kategorisi başarıyla oluşturuldu! Kategori: ' . $category->name);

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
                ->with('error', 'Test kategorisi oluşturulurken hata oluştu: ' . $e->getMessage() . ' - Satır: ' . $e->getLine());
        }
    }

    public function show(TestCategory $testCategory)
    {
        try {
            $testCategory->load(['tests.questions', 'questions']);
            return view('admin.test-categories.show', compact('testCategory'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Kategori detayları yüklenirken hata oluştu']);
        }
    }

    public function edit(TestCategory $testCategory)
    {
        return view('admin.test-categories.edit', compact('testCategory'));
    }

    public function update(Request $request, TestCategory $testCategory)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'icon' => 'nullable|string|max:50',
                'difficulty_level' => 'nullable|string|max:50',
                'color' => 'nullable|string|max:20',
                'is_active' => 'nullable|boolean',
                'sort_order' => 'nullable|integer|min:0'
            ]);

            $slug = Str::slug($validatedData['name']);
            
            // Mevcut kategori dışında aynı slug kontrolü
            $originalSlug = $slug;
            $count = 1;
            while (TestCategory::where('slug', $slug)->where('id', '!=', $testCategory->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $testCategory->update([
                'name' => $validatedData['name'],
                'slug' => $slug,
                'description' => $validatedData['description'] ?? null,
                'icon' => $validatedData['icon'] ?? null,
                'difficulty_level' => $validatedData['difficulty_level'] ?? null,
                'color' => $validatedData['color'] ?? 'blue',
                'is_active' => $request->has('is_active'),
                'sort_order' => $validatedData['sort_order'] ?? 0,
            ]);

            return redirect()->route('admin.test-categories.index')
                ->with('success', 'Test kategorisi başarıyla güncellendi!');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Kategori güncellenirken hata oluştu: ' . $e->getMessage());
        }
    }

    public function destroy(TestCategory $testCategory)
    {
        try {
            $testCategory->delete();

            return redirect()->route('admin.test-categories.index')
                ->with('success', 'Test kategorisi başarıyla silindi!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Kategori silinirken hata oluştu: ' . $e->getMessage());
        }
    }
}