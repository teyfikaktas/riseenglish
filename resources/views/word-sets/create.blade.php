@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('word-sets.index') }}" 
                   class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-[#1a2e5a]">Yeni Kelime Seti</h1>
            </div>
            <p class="text-gray-600">Yeni bir kelime seti oluşturun ve kelimelerinizi kategorilere ayırın</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <form action="{{ route('word-sets.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Set Adı -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Set Adı <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           placeholder="Örn: İş Hayatı, Günlük Kelimeler, Seyahat..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#e63946] focus:border-transparent transition-all @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Açıklama -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Açıklama <span class="text-gray-400">(İsteğe bağlı)</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              placeholder="Bu set hakkında kısa bir açıklama yazın..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#e63946] focus:border-transparent transition-all resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Renk Seçimi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Set Rengi <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-6 gap-3">
                        @php
                            $colors = [
                                '#3B82F6', '#10B981', '#F59E0B', '#EF4444', 
                                '#8B5CF6', '#06B6D4', '#84CC16', '#F97316',
                                '#EC4899', '#6366F1', '#14B8A6', '#F43F5E'
                            ];
                        @endphp
                        
                        @foreach($colors as $color)
                            <label class="cursor-pointer">
                                <input type="radio" 
                                       name="color" 
                                       value="{{ $color }}" 
                                       class="sr-only peer"
                                       {{ old('color', '#3B82F6') === $color ? 'checked' : '' }}>
                                <div class="w-12 h-12 rounded-lg border-4 border-transparent peer-checked:border-gray-800 transition-all hover:scale-110" 
                                     style="background-color: {{ $color }}"></div>
                            </label>
                        @endforeach
                    </div>
                    @error('color')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('word-sets.index') }}" 
                       class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold text-center transition-colors">
                        İptal
                    </a>
                    <button type="submit" 
                            class="flex-1 bg-[#e63946] hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        Set Oluştur
                    </button>
                </div>
            </form>
        </div>

        <!-- Bilgi Kutusu -->
        <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="font-semibold text-blue-800 mb-1">💡 İpucu</h3>
                    <p class="text-sm text-blue-700">
                        Kelime setlerinizi konularına göre ayırın. Örneğin "İş Hayatı", "Yemek", "Seyahat" gibi kategoriler 
                        oluşturarak kelimelerinizi daha düzenli öğrenebilirsiniz.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection