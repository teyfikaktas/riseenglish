<?php

namespace App\Http\Controllers;

use App\Models\ResourceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResourceCategoryController extends Controller
{
    /**
     * Display a listing of the resource categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ResourceCategory::with('parent')->orderBy('name')->get();
        $parentCategories = ResourceCategory::whereNull('parent_id')->orderBy('name')->get();
        
        return view('admin.resource-categories.index', compact('categories', 'parentCategories'));
    }

    /**
     * Show the form for creating a new resource category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentCategories = ResourceCategory::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.resource-categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:resource_categories',
            'parent_id' => 'nullable|exists:resource_categories,id',
        ]);

        $category = new ResourceCategory();
        $category->name = $request->name;
        $category->slug = $request->slug ?? Str::slug($request->name);
        $category->parent_id = $request->parent_id;
        $category->save();

        return redirect()->route('admin.resource-categories.index')
            ->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified resource category.
     *
     * @param  \App\Models\ResourceCategory  $resourceCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ResourceCategory $resourceCategory)
    {
        $parentCategories = ResourceCategory::whereNull('parent_id')
            ->where('id', '!=', $resourceCategory->id)
            ->orderBy('name')
            ->get();
            
        return view('admin.resource-categories.edit', [
            'category' => $resourceCategory,
            'parentCategories' => $parentCategories
        ]);
    }

    /**
     * Update the specified resource category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ResourceCategory  $resourceCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResourceCategory $resourceCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:resource_categories,slug,' . $resourceCategory->id,
            'parent_id' => 'nullable|exists:resource_categories,id',
        ]);

        // Kendisini kendi alt kategorisi yapmasını engelleme
        if ($request->parent_id == $resourceCategory->id) {
            return back()->withErrors(['parent_id' => 'Bir kategori kendisinin alt kategorisi olamaz.']);
        }

        $resourceCategory->name = $request->name;
        $resourceCategory->slug = $request->slug ?? Str::slug($request->name);
        $resourceCategory->parent_id = $request->parent_id;
        $resourceCategory->save();

        return redirect()->route('admin.resource-categories.index')
            ->with('success', 'Kategori başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource category from storage.
     *
     * @param  \App\Models\ResourceCategory  $resourceCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResourceCategory $resourceCategory)
    {
        // Alt kategorileri varsa, onları da üst kategorisiz yap
        if ($resourceCategory->children()->count() > 0) {
            foreach ($resourceCategory->children as $child) {
                $child->parent_id = null;
                $child->save();
            }
        }

        $resourceCategory->delete();
        return redirect()->route('admin.resource-categories.index')
            ->with('success', 'Kategori başarıyla silindi.');
    }
}