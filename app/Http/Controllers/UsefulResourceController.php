<?php

namespace App\Http\Controllers;

use App\Models\UsefulResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UsefulResourceController extends Controller
{
    // Ana sayfa - tüm kaynakları listele
    public function index(Request $request)
    {
        $query = UsefulResource::active();
        
        // Kategori filtresi
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }
        
        // Arama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        // Sıralama
        switch ($request->get('sort', 'default')) {
            case 'popular':
                $query->mostViewed();
                break;
            case 'newest':
                $query->latest();
                break;
            case 'title':
                $query->orderBy('title');
                break;
            default:
                $query->ordered();
        }
        
        $resources = $query->paginate(12);
        
        // Kategoriler
        $categories = UsefulResource::active()
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('category')
            ->get();
            
        // Popüler kaynaklar
        $popularResources = UsefulResource::active()
            ->popular()
            ->mostViewed()
            ->limit(5)
            ->get();
        
        return view('useful-resources.index', compact('resources', 'categories', 'popularResources'));
    }
    
    // Kategoriye göre listele
    public function category($category, Request $request)
    {
        $query = UsefulResource::active()->byCategory($category);
        
        // Arama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        // Sıralama
        switch ($request->get('sort', 'default')) {
            case 'popular':
                $query->mostViewed();
                break;
            case 'newest':
                $query->latest();
                break;
            case 'title':
                $query->orderBy('title');
                break;
            case 'most-downloaded':
                $query->orderBy('download_count', 'desc');
                break;
            default:
                $query->ordered();
        }
        
        $resources = $query->paginate(12);
        $categoryTitle = $this->getCategoryTitle($category);
        
        return view('useful-resources.category', compact('resources', 'category', 'categoryTitle'));
    }
    
    // Kaynak detayı - SLUG ile
    public function show($slug)
    {
        $resource = UsefulResource::active()->where('slug', $slug)->firstOrFail();
        
        // Görüntülenme sayısını artır
        $resource->incrementViewCount();
        
        // İlgili kaynaklar (aynı kategoriden)
        $relatedResources = UsefulResource::active()
            ->byCategory($resource->category)
            ->where('slug', '!=', $slug)
            ->limit(4)
            ->get();
        
        return view('useful-resources.show', compact('resource', 'relatedResources'));
    }
    
    // Dosya indirme - SLUG ile
    public function download($slug)
    {
        $resource = UsefulResource::active()->where('slug', $slug)->firstOrFail();
        
        if (!$resource->exists()) {
            abort(404, 'Dosya bulunamadı');
        }
        
        // İndirme sayısını artır
        $resource->incrementDownloadCount();
        
        return Storage::disk('public')->download($resource->file_path, $resource->file_name);
    }
    
    // Popüler kaynaklar
    public function popular(Request $request)
    {
        $query = UsefulResource::active()->popular()->mostViewed();
        
        // Arama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        $resources = $query->paginate(12);
        
        return view('useful-resources.popular', compact('resources'));
    }
    
    // En çok görüntülenenler
    public function mostViewed(Request $request)
    {
        $query = UsefulResource::active()->mostViewed();
        
        // Arama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        $resources = $query->paginate(12);
        
        return view('useful-resources.most-viewed', compact('resources'));
    }
    
    // Arama sonuçları
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);
        
        $searchTerm = $request->q;
        
        $query = UsefulResource::active()
            ->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
            
        // Sıralama
        switch ($request->get('sort', 'relevance')) {
            case 'popular':
                $query->mostViewed();
                break;
            case 'newest':
                $query->latest();
                break;
            case 'title':
                $query->orderBy('title');
                break;
            default:
                $query->ordered();
        }
        
        $resources = $query->paginate(12);
        
        return view('useful-resources.search', compact('resources', 'searchTerm'));
    }
    
    // API - JSON response
    public function api(Request $request)
    {
        $query = UsefulResource::active();
        
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        $resources = $query->ordered()->get();
        
        return response()->json([
            'success' => true,
            'data' => $resources,
            'total' => $resources->count()
        ]);
    }
    
    // Kategorileri listele
    public function categories()
    {
        $categories = UsefulResource::active()
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('category')
            ->get()
            ->map(function($item) {
                return [
                    'slug' => $item->category,
                    'name' => $this->getCategoryTitle($item->category),
                    'count' => $item->count
                ];
            });
        
        return view('useful-resources.categories', compact('categories'));
    }
    
    // AJAX ile view count artırma
    public function incrementView(Request $request, $slug)
    {
        if ($request->ajax()) {
            $resource = UsefulResource::active()->where('slug', $slug)->first();
            
            if ($resource) {
                $resource->incrementViewCount();
                return response()->json(['success' => true]);
            }
        }
        
        return response()->json(['success' => false], 404);
    }
    
    // Kategori başlığı helper
    private function getCategoryTitle($category)
    {
        $titles = [
            'tenses' => 'Tenses (Zamanlar)',
            'modals' => 'Modal Verbs (Modal Fiiller)', 
            'adjectives' => 'Adjectives (Sıfatlar)',
            'pronouns' => 'Pronouns (Zamirler)',
            'grammar' => 'Advanced Grammar (İleri Gramer)',
            'conditionals' => 'Conditional Sentences (Koşul Cümleleri)',
            'vocabulary' => 'Vocabulary (Kelime Bilgisi)',
            'grammar-basics' => 'Grammar Basics (Temel Gramer)',
            'advanced' => 'Advanced Topics (İleri Konular)',
            'special' => 'Special Topics (Özel Konular)',
            'word-formation' => 'Word Formation (Kelime Türetme)',
            'patterns' => 'Language Patterns (Dil Kalıpları)'
        ];
        
        return $titles[$category] ?? ucfirst(str_replace('-', ' ', $category));
    }
    
    // İstatistikler
    public function stats()
    {
        $stats = [
            'total_resources' => UsefulResource::active()->count(),
            'total_downloads' => UsefulResource::active()->sum('download_count'),
            'total_views' => UsefulResource::active()->sum('view_count'),
            'categories_count' => UsefulResource::active()->distinct('category')->count(),
            'popular_count' => UsefulResource::active()->popular()->count(),
            'most_viewed' => UsefulResource::active()->mostViewed()->limit(5)->get(),
            'most_downloaded' => UsefulResource::active()->orderBy('download_count', 'desc')->limit(5)->get(),
            'recent_resources' => UsefulResource::active()->latest()->limit(5)->get(),
            'categories_stats' => UsefulResource::active()
                ->selectRaw('category, COUNT(*) as count, SUM(download_count) as downloads, SUM(view_count) as views')
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get()
        ];
        
        return response()->json($stats);
    }
}