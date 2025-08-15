<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\ResourceType;
use Illuminate\Http\Request;

class PublicResourceController extends Controller
{
    public function index(Request $request)
    {
        // Tüm kaynakları getir (ücretsiz olanlar)
        $resources = Resource::where('is_free', true)
                           ->with(['category', 'type', 'tags'])
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        // Popüler kaynakları getir
        $popularResources = Resource::where('is_popular', true)
                                  ->where('is_free', true)
                                  ->with(['category', 'type'])
                                  ->take(10)
                                  ->get();
        
        // SADECE ANA KATEGORİLERİ getir (parent_id null olanlar)
        $categories = ResourceCategory::whereNull('parent_id')
                                    ->with('children')
                                    ->orderBy('name')
                                    ->get();
        
        // Kaynak türlerini getir
        $types = ResourceType::orderBy('name')->get();
        
        // Debug log'ları
        \Log::info('=== KAYNAK SAYILARI ===');
        \Log::info('Toplam ücretsiz kaynak: ' . $resources->count());
        \Log::info('Popüler kaynak: ' . $popularResources->count());
        \Log::info('Ana kategori sayısı: ' . $categories->count());
        
        foreach($categories as $category) {
            $directResources = $resources->where('category_id', $category->id)->count();
            $subCategoryIds = ResourceCategory::where('parent_id', $category->id)->pluck('id');
            $subResources = $resources->whereIn('category_id', $subCategoryIds)->count();
            
            \Log::info("Kategori: {$category->name} - Direkt: {$directResources}, Alt kategoriler: {$subResources}");
        }
        
        return view('public.resources.index', compact(
            'resources',
            'popularResources', 
            'categories',
            'types'
        ));
    }
    
    public function show($slug)
    {
        $resource = Resource::where('slug', $slug)
                          ->where('is_free', true)
                          ->with(['category', 'type', 'tags'])
                          ->firstOrFail();
        
        $resource->increment('download_count');
        
        return view('public.resources.show', compact('resource'));
    }
    
    public function getSubcategories(Request $request)
    {
        $categoryId = $request->input('category_id');
        $subcategories = ResourceCategory::where('parent_id', $categoryId)
                                       ->orderBy('name')
                                       ->get();
        
        return response()->json($subcategories);
    }
}