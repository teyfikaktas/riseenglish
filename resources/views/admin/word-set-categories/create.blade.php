@extends('layouts.app')

@section('title', 'Yeni Kategori')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">

    <div class="flex items-center mb-6">
        <a href="{{ route('admin.word-set-categories.index') }}" class="text-gray-500 hover:text-gray-700 mr-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Yeni Kategori</h1>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.word-set-categories.store') }}" method="POST">
            @csrf

            {{-- Üst Kategori --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Üst Kategori <span class="text-gray-400">(opsiyonel)</span></label>
                <select name="parent_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="">— Ana Kategori (üst yok) —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (old('parent_id', request('parent_id')) == $cat->id) ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @foreach($cat->children as $child)
                            <option value="{{ $child->id }}" {{ (old('parent_id', request('parent_id')) == $child->id) ? 'selected' : '' }}>
                                &nbsp;&nbsp;&nbsp;› {{ $child->name }}
                            </option>
                            @foreach($child->children as $grandchild)
                                <option value="{{ $grandchild->id }}" {{ (old('parent_id', request('parent_id')) == $grandchild->id) ? 'selected' : '' }}>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;› {{ $grandchild->name }}
                                </option>
                            @endforeach
                        @endforeach
                    @endforeach
                </select>
                @error('parent_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- İsim --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Adı <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                       placeholder="örn. Gramer, Günlük Kelimeler...">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Renk --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Renk</label>
                <div class="flex items-center space-x-3">
                    <input type="color" name="color" value="{{ old('color', '#3B82F6') }}" id="colorPicker"
                           class="h-10 w-16 rounded cursor-pointer border border-gray-300">
                    <input type="text" id="colorHex" value="{{ old('color', '#3B82F6') }}"
                           class="w-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 font-mono text-sm"
                           placeholder="#3B82F6">
                    {{-- Hazır renkler --}}
                    <div class="flex space-x-2">
                        @foreach(['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#14B8A6','#F97316','#6366F1','#84CC16'] as $c)
                            <button type="button" onclick="setColor('{{ $c }}')"
                                    class="w-6 h-6 rounded-full border-2 border-white shadow hover:scale-110 transition-transform"
                                    style="background-color: {{ $c }}" title="{{ $c }}"></button>
                        @endforeach
                    </div>
                </div>
                @error('color')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Sıra --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sıralama</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                       class="w-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                <p class="text-xs text-gray-400 mt-1">Küçük sayı = daha üstte görünür</p>
            </div>

            {{-- Butonlar --}}
            <div class="flex space-x-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition-all duration-300">
                    Kaydet
                </button>
                <a href="{{ route('admin.word-set-categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-lg transition-all duration-300">
                    İptal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const colorPicker = document.getElementById('colorPicker');
    const colorHex = document.getElementById('colorHex');

    colorPicker.addEventListener('input', function() {
        colorHex.value = this.value;
    });

    colorHex.addEventListener('input', function() {
        if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
            colorPicker.value = this.value;
        }
    });

    function setColor(hex) {
        colorPicker.value = hex;
        colorHex.value = hex;
        // hidden input'a da yaz (name="color" olan input color picker)
        colorPicker.dispatchEvent(new Event('input'));
    }
</script>
@endsection
