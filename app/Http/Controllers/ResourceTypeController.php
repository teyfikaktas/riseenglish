<?php

namespace App\Http\Controllers;

use App\Models\ResourceType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResourceTypeController extends Controller
{
    /**
     * Display a listing of the resource types.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = ResourceType::orderBy('name')->get();
        return view('admin.resource-types.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource type.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.resource-types.create');
    }

    /**
     * Store a newly created resource type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
        ]);

        $type = new ResourceType();
        $type->name = $request->name;
        $type->description = $request->description;
        $type->icon = $request->icon;
        $type->save();

        return redirect()->route('admin.resource-types.index')
            ->with('success', 'Kaynak türü başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified resource type.
     *
     * @param  \App\Models\ResourceType  $resourceType
     * @return \Illuminate\Http\Response
     */
    public function edit(ResourceType $resourceType)
    {
        return view('admin.resource-types.edit', ['type' => $resourceType]);
    }

    /**
     * Update the specified resource type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ResourceType  $resourceType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResourceType $resourceType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
        ]);

        $resourceType->name = $request->name;
        $resourceType->description = $request->description;
        $resourceType->icon = $request->icon;
        $resourceType->save();

        return redirect()->route('admin.resource-types.index')
            ->with('success', 'Kaynak türü başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource type from storage.
     *
     * @param  \App\Models\ResourceType  $resourceType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResourceType $resourceType)
    {
        // İlişkili kaynakları kontrol et
        if ($resourceType->resources()->count() > 0) {
            return redirect()->route('admin.resource-types.index')
                ->with('error', 'Bu kaynak türüne bağlı kaynaklar olduğu için silinemez.');
        }

        $resourceType->delete();
        return redirect()->route('admin.resource-types.index')
            ->with('success', 'Kaynak türü başarıyla silindi.');
    }
}