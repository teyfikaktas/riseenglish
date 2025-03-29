@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.resource-categories.index') }}" class="mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Kategori Düzenle: {{ $category->name }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.resource-categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <!-- Kategori Adı -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Kategori Adı</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" class="form-input rounded-md shadow-sm mt-1 block w-full">
                    <p class="text-gray-500 text-xs mt-1">Boş bırakırsanız kategori adından otomatik oluşturulacaktır.</p>
                    @error('slug')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Üst Kategori -->
                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">Üst Kategori</label>
                    <select name="parent_id" id="parent_id" class="form-select rounded-md shadow-sm mt-1 block w-full">
                        <option value="">Ana Kategori</option>
                        @foreach($parentCategories as $parentCategory)
                            <option value="{{ $parentCategory->id }}" {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                                {{ $parentCategory->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-gray-500 text-xs mt-1">Eğer bu bir alt kategori ise üst kategoriyi seçin.</p>
                    @error('parent_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    Kategoriyi Güncelle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Kategori adından slug oluşturma
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const originalSlug = "{{ $category->slug }}";
    
    nameInput.addEventListener('blur', function() {
        if (slugInput.value === '' || slugInput.value === originalSlug) {
            slugInput.value = slugify(nameInput.value);
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