@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('word-sets.show', $wordSet) }}" 
                   class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-[#1a2e5a]">Set Düzenle</h1>
            </div>
            <p class="text-gray-600">{{ $wordSet->name }} setini düzenleyin</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <form action="{{ route('word-sets.update', $wordSet) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Set Adı -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Set Adı <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $wordSet->name) }}"
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
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#e63946] focus:border-transparent transition-all resize-none @error('description') border-red-500 @enderror">{{ old('description', $wordSet->description) }}</textarea>
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
                                       {{ old('color', $wordSet->color) === $color ? 'checked' : '' }}>
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
                    <a href="{{ route('word-sets.show', $wordSet) }}" 
                       class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold text-center transition-colors">
                        İptal
                    </a>
                    <button type="submit" 
                            class="flex-1 bg-[#e63946] hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>

        <!-- Silme Seçeneği -->
        <div class="mt-8 bg-red-50 rounded-xl border border-red-200 overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-bold text-red-800 mb-2">Tehlikeli Bölge</h3>
                <p class="text-red-700 text-sm mb-4">
                    Bu seti sildiğinizde, içindeki tüm kelimeler de kalıcı olarak silinecektir. Bu işlem geri alınamaz.
                </p>
                <form action="{{ route('word-sets.destroy', $wordSet) }}" 
                      method="POST" 
                      onsubmit="return confirm('Bu seti ve içindeki tüm kelimeleri silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Seti Sil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection