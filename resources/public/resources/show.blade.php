{{-- resources/views/public/resources/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-blue-50 min-h-screen py-10">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="md:flex">
                <div class="md:flex-shrink-0">
                    <img class="h-48 w-full object-cover md:w-48" src="{{ asset($resource->image_path) }}" alt="{{ $resource->title }}">
                </div>
                <div class="p-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="uppercase tracking-wide text-sm text-indigo-600 font-semibold">
                                {{ $resource->category->name }} - {{ $resource->type->name }}
                            </div>
                            <h1 class="mt-1 text-2xl font-semibold text-gray-900">{{ $resource->title }}</h1>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                {{ $resource->is_free ? 'Ücretsiz' : 'Ücretli' }}
                            </span>
                            <span class="text-gray-600 text-sm mt-2">{{ $resource->download_count }} indirme</span>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900">Açıklama</h3>
                        <p class="mt-2 text-gray-600">{{ $resource->description }}</p>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900">Etiketler</h3>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($resource->tags as $tag)
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-between items-center">
                        <a href="{{ route('public.resources.index') }}" class="text-indigo-600 hover:text-indigo-900">
                            ← Kaynaklara dön
                        </a>
                        <a href="{{ asset($resource->file_path) }}" download 
                           class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            İndir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection