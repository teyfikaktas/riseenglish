@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.resources.index') }}" class="mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">{{ $resource->title }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Kaynak Bilgileri</h2>
                    
                    <div class="border-t border-gray-200 pt-4">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Başlık</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $resource->title }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Slug</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $resource->slug }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $resource->category->name ?? 'Belirtilmemiş' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tür</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $resource->type->name ?? 'Belirtilmemiş' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Durum</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($resource->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                    @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Pasif</span>
                                    @endif
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ücret</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($resource->is_free)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Ücretsiz</span>
                                    @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Ücretli</span>
                                    @endif
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Popüler</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($resource->is_popular)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Evet</span>
                                    @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Hayır</span>
                                    @endif
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">İndirme Sayısı</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $resource->download_count ?? 0 }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Görüntülenme Sayısı</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $resource->view_count ?? 0 }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Eklenme Tarihi</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $resource->created_at->format('d.m.Y H:i') }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Güncellenme Tarihi</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $resource->updated_at->format('d.m.Y H:i') }}</dd>
                            </div>
                            
                            @if($resource->tags->count() > 0)
                            <div class="col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Etiketler</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($resource->tags as $tag)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    
                    @if($resource->description)
                    <div class="mt-6">
                        <h3 class="text-md font-medium text-gray-700 mb-2">Kısa Açıklama</h3>
                        <div class="prose max-w-full bg-gray-50 p-4 rounded">
                            {{ $resource->description }}
                        </div>
                    </div>
                    @endif
                    
                    @if($resource->content)
                    <div class="mt-6">
                        <h3 class="text-md font-medium text-gray-700 mb-2">İçerik</h3>
                        <div class="prose max-w-full bg-gray-50 p-4 rounded">
                            {!! $resource->content !!}
                        </div>
                    </div>
                    @endif
                </div>
                
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Görsel ve Dosya</h2>
                    
                    @if($resource->image_path)
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-500 mb-2">Görsel</p>
                        <img src="{{ asset('storage/' . $resource->image_path) }}" alt="{{ $resource->title }}" class="w-full h-auto rounded-lg">
                    </div>
                    @endif
                    
                    @if($resource->file_path)
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-500 mb-2">Dosya</p>
                        <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>İndir</span>
                        </a>
                    </div>
                    @endif
                    
                    <div class="mt-4 flex space-x-2">
                        <a href="{{ route('admin.resources.edit', $resource) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Düzenle
                        </a>
                        
                        <form action="{{ route('admin.resources.destroy', $resource) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow flex items-center" onclick="return confirm('Bu kaynağı silmek istediğinize emin misiniz?')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Sil
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection