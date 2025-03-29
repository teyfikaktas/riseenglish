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
        // Filtreleme parametrelerini al
        $categoryId = $request->input('category');
        $subcategoryId = $request->input('subcategory');
        $typeId = $request->input('type');
        
        $query = Resource::query()->with(['category', 'type', 'tags']);
        
        // Ana kategori seçilmişse
        if ($categoryId) {
            // Alt kategori de seçilmişse, sadece o alt kategoriyi filtrele
            if ($subcategoryId) {
                $query->where('category_id', $subcategoryId);
            } else {
                // Alt kategori seçilmemişse, ana kategori ve tüm alt kategorilerini getir
                $query->where(function($q) use ($categoryId) {
                    // Ana kategori ile eşleşenler
                    $q->where('category_id', $categoryId)
                      // VEYA ana kategorinin alt kategorilerinden biriyle eşleşenler
                      ->orWhereHas('category', function($subquery) use ($categoryId) {
                          $subquery->where('parent_id', $categoryId);
                      });
                });
            }
        }
        
        // Tür filtresi varsa uygula
        if ($typeId) {
            $query->where('type_id', $typeId);
        }
        
        // Kaynakları paginate ile getir (sayfalama)
        $resources = $query->paginate(12);
        
        // Popüler kaynakları getir
        $popularResources = Resource::where('is_popular', true)
                                  ->where('is_free', true)
                                  ->with(['category', 'type'])
                                  ->take(8)
                                  ->get();
        
        // Ana kategorileri getir (dropdown için)
        $categories = ResourceCategory::whereNull('parent_id')->with('children')->get();
        
        // Kaynak türlerini getir
        $types = ResourceType::all();
        
        // Alt kategorileri için JavaScript ile doldurmak üzere seçilen kategorinin alt kategorilerini getir
        $subcategories = [];
        if ($categoryId) {
            $subcategories = ResourceCategory::where('parent_id', $categoryId)->get();
        }
        
        return view('public.resources.index', compact(
            'resources',
            'popularResources', 
            'categories',
            'subcategories',
            'types',
            'categoryId',
            'subcategoryId',
            'typeId'
        ));
    }
    
    public function show($slug)
    {
        $resource = Resource::where('slug', $slug)->with(['category', 'type', 'tags'])->firstOrFail();
        
        // İndirme sayısını artır
        $resource->increment('download_count');
        
        return view('public.resources.show', compact('resource'));
    }
    
    // Alt kategorileri getiren AJAX isteği için (opsiyonel)
    public function getSubcategories(Request $request)
    {
        $categoryId = $request->input('category_id');
        $subcategories = ResourceCategory::where('parent_id', $categoryId)->get();
        
        return response()->json($subcategories);
    }
}