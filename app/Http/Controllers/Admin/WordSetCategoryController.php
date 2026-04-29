<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WordSetCategory;
use App\Models\WordSet;
use Illuminate\Http\Request;

class WordSetCategoryController extends Controller
{
    // Tüm kategoriler (ağaç görünümü)
    public function index()
    {
        $tree = WordSetCategory::whereNull('parent_id')
            ->with('allChildren')
            ->orderBy('sort_order')
            ->get();

        return view('admin.word-set-categories.index', compact('tree'));
    }

    public function create()
    {
        // Sadece root ve 1. seviye kategoriler parent seçilebilir
        $categories = WordSetCategory::whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        return view('admin.word-set-categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:word_set_categories,id',
            'color'     => 'nullable|string|max:7',
            'sort_order'=> 'nullable|integer',
        ]);

        WordSetCategory::create([
            'user_id'    => 1, // admin/teacher
            'name'       => $request->name,
            'parent_id'  => $request->parent_id,
            'color'      => $request->color ?? '#3B82F6',
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.word-set-categories.index')
            ->with('success', 'Kategori oluşturuldu.');
    }

    public function edit(WordSetCategory $wordSetCategory)
    {
        $categories = WordSetCategory::whereNull('parent_id')
            ->with('children')
            ->where('id', '!=', $wordSetCategory->id)
            ->orderBy('sort_order')
            ->get();

        return view('admin.word-set-categories.edit', compact('wordSetCategory', 'categories'));
    }

    public function update(Request $request, WordSetCategory $wordSetCategory)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:word_set_categories,id',
            'color'     => 'nullable|string|max:7',
            'sort_order'=> 'nullable|integer',
        ]);

        // Kendisini parent olarak seçemesin
        if ($request->parent_id == $wordSetCategory->id) {
            return back()->withErrors(['parent_id' => 'Kendisi parent olamaz.']);
        }

        $wordSetCategory->update([
            'name'       => $request->name,
            'parent_id'  => $request->parent_id,
            'color'      => $request->color ?? '#3B82F6',
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.word-set-categories.index')
            ->with('success', 'Kategori güncellendi.');
    }

    public function destroy(WordSetCategory $wordSetCategory)
    {
        // Alt kategorisi varsa silme
        if ($wordSetCategory->children()->count() > 0) {
            return back()->withErrors(['error' => 'Alt kategorileri olan kategori silinemez.']);
        }

        // Bağlı word_set varsa category_id null yap
        WordSet::where('category_id', $wordSetCategory->id)
            ->update(['category_id' => null]);

        $wordSetCategory->delete();

        return redirect()->route('admin.word-set-categories.index')
            ->with('success', 'Kategori silindi.');
    }
}