<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\ResourceType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\ResourceTag;
class ResourceController extends Controller
{
    /**
     * Display a listing of the resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Sadece aktif kaynakları getir (is_active=1)
        $resources = Resource::where('is_active', 1)
                            ->with(['category', 'type', 'tags'])
                            ->paginate(15);
        
        // Popüler kaynakları da sadece aktiflerden getir
        $popularResources = Resource::where('is_active', 1)
                                ->where('is_popular', true)
                                ->take(8)
                                ->get();
        
        $categories = ResourceCategory::whereNull('parent_id')->with('children')->get();
        $types = ResourceType::all();
        
        return view('admin.resources.index', compact('resources', 'popularResources', 'categories', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ResourceCategory::all();
        $types = ResourceType::all();
        
        return view('admin.resources.create', compact('categories', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
/**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:resources',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:resource_categories,id',
        'type_id' => 'required|exists:resource_types,id',
        'file' => 'nullable|file|max:10240', // 10MB max dosya boyutu
        'image' => 'nullable|image|max:2048', // 2MB max görsel boyutu
        'is_free' => 'nullable|boolean',
        'is_popular' => 'nullable|boolean',
        'tags' => 'nullable|string',
    ]);

    $resource = new Resource();
    $resource->title = $request->title;
    $resource->slug = $request->slug ?? Str::slug($request->title);
    $resource->description = $request->description;
    $resource->category_id = $request->category_id;
    $resource->type_id = $request->type_id;
    $resource->is_free = $request->has('is_free');
    $resource->is_popular = $request->has('is_popular');
    $resource->is_active = $request->has('is_active') || true; // BURAYA EKLEYİN

    // Dosya yükleme
    if ($request->hasFile('file') && $request->file('file')->isValid()) {
        $filePath = $request->file('file')->store('resources/files', 'public');
        $resource->file_path = $filePath;
    }

    // Görsel yükleme
    if ($request->hasFile('image') && $request->file('image')->isValid()) {
        $imagePath = $request->file('image')->store('resources/images', 'public');
        $resource->image_path = $imagePath;
    }

    $resource->save();

    // Etiketleri işleme
    if ($request->filled('tags')) {
        $tags = explode(',', $request->tags);
        $tagIds = [];
        
        foreach ($tags as $tagName) {
            $tagName = trim($tagName);
            if (!empty($tagName)) {
                $tag = ResourceTag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
        }
        
        $resource->tags()->sync($tagIds);
    }

    return redirect()->route('admin.resources.index')
        ->with('success', 'Kaynak başarıyla oluşturuldu.');
}
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource)
    {
        return view('admin.resources.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource)
    {
        $categories = ResourceCategory::all();
        $types = ResourceType::all();
        
        return view('admin.resources.edit', compact('resource', 'categories', 'types'));
    }

/**
 * Update the specified resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \App\Models\Resource  $resource
 * @return \Illuminate\Http\Response
 */
public function update(Request $request, Resource $resource)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:resources,slug,' . $resource->id,
        'description' => 'nullable|string',
        'category_id' => 'required|exists:resource_categories,id',
        'type_id' => 'required|exists:resource_types,id',
        'file' => 'nullable|file|max:10240', // 10MB max dosya boyutu
        'image' => 'nullable|image|max:2048', // 2MB max görsel boyutu
        'is_free' => 'nullable|boolean',
        'is_popular' => 'nullable|boolean',
        'tags' => 'nullable|string',
        'remove_file' => 'nullable|boolean',
        'remove_image' => 'nullable|boolean',
    ]);

    $resource->title = $request->title;
    $resource->slug = $request->slug ?? Str::slug($request->title);
    $resource->description = $request->description;
    $resource->category_id = $request->category_id;
    $resource->type_id = $request->type_id;
    $resource->is_free = $request->has('is_free');
    $resource->is_popular = $request->has('is_popular');
    $resource->is_active = $request->has('is_active') || true; // BURAYA EKLEYİN

    // Dosyayı kaldır
    if ($request->has('remove_file') && $resource->file_path) {
        Storage::disk('public')->delete($resource->file_path);
        $resource->file_path = null;
    }

    // Görseli kaldır
    if ($request->has('remove_image') && $resource->image_path) {
        Storage::disk('public')->delete($resource->image_path);
        $resource->image_path = null;
    }

    // Yeni dosya yükleme
    if ($request->hasFile('file') && $request->file('file')->isValid()) {
        // Eski dosyayı sil (varsa)
        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }
        $filePath = $request->file('file')->store('resources/files', 'public');
        $resource->file_path = $filePath;
    }

    // Yeni görsel yükleme
    if ($request->hasFile('image') && $request->file('image')->isValid()) {
        // Eski görseli sil (varsa)
        if ($resource->image_path) {
            Storage::disk('public')->delete($resource->image_path);
        }
        $imagePath = $request->file('image')->store('resources/images', 'public');
        $resource->image_path = $imagePath;
    }

    $resource->save();

    // Etiketleri işleme
    if ($request->filled('tags')) {
        $tags = explode(',', $request->tags);
        $tagIds = [];
        
        foreach ($tags as $tagName) {
            $tagName = trim($tagName);
            if (!empty($tagName)) {
                $tag = ResourceTag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
        }
        
        $resource->tags()->sync($tagIds);
    } else {
        // Tüm etiketleri kaldır
        $resource->tags()->detach();
    }

    return redirect()->route('admin.resources.index')
        ->with('success', 'Kaynak başarıyla güncellendi.');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource)
    {
        $resource->delete();
        
        return redirect()->route('admin.resources.index')
            ->with('success', 'Kaynak başarıyla silindi.');
    }
}