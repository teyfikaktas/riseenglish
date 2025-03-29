@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.resources.index') }}" class="mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Kaynağı Düzenle: {{ $resource->title }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.resources.update', $resource) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Başlık -->
                <div class="col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Başlık</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $resource->title) }}" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="col-span-2">
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $resource->slug) }}" class="form-input rounded-md shadow-sm mt-1 block w-full">
                    <p class="text-gray-500 text-xs mt-1">Boş bırakırsanız başlıktan otomatik oluşturulacaktır.</p>
                    @error('slug')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category_id" id="category_id" class="form-select rounded-md shadow-sm mt-1 block w-full">
                        <option value="">Kategori Seçin</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $resource->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @if($category->children->count() > 0)
                                @foreach($category->children as $child)
                                    <option value="{{ $child->id }}" {{ old('category_id', $resource->category_id) == $child->id ? 'selected' : '' }}>
                                        -- {{ $child->name }}
                                    </option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kaynak Türü -->
                <div>
                    <label for="type_id" class="block text-sm font-medium text-gray-700 mb-1">Kaynak Türü</label>
                    <select name="type_id" id="type_id" class="form-select rounded-md shadow-sm mt-1 block w-full">
                        <option value="">Tür Seçin</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ old('type_id', $resource->type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('type_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Açıklama -->
                <div class="col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Kısa Açıklama</label>
                    <textarea name="description" id="description" rows="3" class="form-textarea rounded-md shadow-sm mt-1 block w-full">{{ old('description', $resource->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- İçerik -->
                <div class="col-span-2">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">İçerik</label>
                    <textarea name="content" id="content" rows="6" class="form-textarea rounded-md shadow-sm mt-1 block w-full">{{ old('content', $resource->content) }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mevcut Dosya -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mevcut Dosya</label>
                    @if($resource->file_path)
                        <div class="flex items-center space-x-2 mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <a href="{{ asset('storage/' . $resource->file_path) }}" class="text-blue-600 hover:text-blue-800" target="_blank">Dosyayı Görüntüle</a>
                            <label class="ml-4">
                                <input type="checkbox" name="remove_file" value="1" class="rounded border-gray-300 text-red-600">
                                <span class="text-sm text-red-600">Dosyayı Kaldır</span>
                            </label>
                        </div>
                    @else
                        <span class="text-gray-500 text-sm">Dosya yüklenmemiş</span>
                    @endif
                </div>

                <!-- Mevcut Görsel -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mevcut Görsel</label>
                    @if($resource->image_path)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $resource->image_path) }}" alt="{{ $resource->title }}" class="h-32 w-auto object-cover rounded-md">
                            <label class="flex items-center mt-2">
                                <input type="checkbox" name="remove_image" value="1" class="rounded border-gray-300 text-red-600">
                                <span class="text-sm text-red-600 ml-2">Görseli Kaldır</span>
                            </label>
                        </div>
                    @else
                        <span class="text-gray-500 text-sm">Görsel yüklenmemiş</span>
                    @endif
                </div>

                <!-- Dosya Yükleme -->
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Yeni Dosya Yükle</label>
                    <input type="file" name="file" id="file" class="mt-1 block w-full">
                    <p class="text-gray-500 text-xs mt-1">PDF, DOCX, XLSX, ZIP veya video dosyaları yükleyebilirsiniz.</p>
                    @error('file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Görsel -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Yeni Görsel Yükle</label>
                    <input type="file" name="image" id="image" class="mt-1 block w-full">
                    <p class="text-gray-500 text-xs mt-1">JPG, PNG veya WebP formatında (maks. 2MB).</p>
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Etiketler -->
                <div class="col-span-2">
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Etiketler</label>
                    <input type="text" name="tags" id="tags" value="{{ old('tags', $resource->tags->pluck('name')->implode(',')) }}" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="Etiketleri virgülle ayırın">
                    <p class="text-gray-500 text-xs mt-1">Etiketleri virgülle ayırarak girin. Örn: eğitim,ingilizce,gramer</p>
                    @error('tags')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Durum ve Özellikler -->
                <div>
                    <div class="flex items-center mb-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $resource->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Aktif</label>
                    </div>
                    
                    <div class="flex items-center mb-3">
                        <input type="checkbox" name="is_free" id="is_free" value="1" {{ old('is_free', $resource->is_free) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <label for="is_free" class="ml-2 block text-sm text-gray-700">Ücretsiz</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_popular" id="is_popular" value="1" {{ old('is_popular', $resource->is_popular) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <label for="is_popular" class="ml-2 block text-sm text-gray-700">Popüler</label>
                    </div>
                </div>

                <!-- İstatistikler (salt okunur) -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">İstatistikler</h3>
                    <div class="bg-gray-50 rounded-md p-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-500">İndirme Sayısı:</span>
                            <span class="text-sm font-medium">{{ $resource->download_count ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-500">Görüntülenme:</span>
                            <span class="text-sm font-medium">{{ $resource->view_count ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Oluşturulma:</span>
                            <span class="text-sm font-medium">{{ $resource->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                    Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Başlıktan slug oluşturma
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const originalSlug = "{{ $resource->slug }}";
    
    titleInput.addEventListener('blur', function() {
        if (slugInput.value === '' || slugInput.value === originalSlug) {
            slugInput.value = slugify(titleInput.value);
        }
    });
    
    function slugify(text) {
        return text
            .toString()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-')
            .replace(/[^\w-]+/g, '')
            .replace(/--+/g, '-');
    }
</script>
@endsection