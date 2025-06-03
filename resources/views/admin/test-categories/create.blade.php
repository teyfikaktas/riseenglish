@extends('layouts.app')

@section('title', 'Yeni Test Kategorisi')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Başlık -->
    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
@endif
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">📂 Yeni Test Kategorisi</h1>
            <p class="text-gray-600">Yeni bir test kategorisi oluşturun</p>
        </div>
        
        <a href="{{ route('admin.test-categories.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"></path>
            </svg>
            Geri Dön
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Kategori Bilgileri</h3>
        </div>
        
        <form action="{{ route('admin.test-categories.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Sol Kolon -->
                <div class="space-y-6">
                    <!-- Kategori Adı -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori Adı <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('name') border-red-300 @enderror"
                               placeholder="Örn: Grammar, Vocabulary, Listening"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Açıklama -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Açıklama
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('description') border-red-300 @enderror"
                                  placeholder="Kategorinin açıklamasını yazın...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- İkon -->
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                            İkon (Emoji)
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="text" 
                                   name="icon" 
                                   id="icon" 
                                   value="{{ old('icon') }}"
                                   class="w-20 text-center text-2xl rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('icon') border-red-300 @enderror"
                                   placeholder="📚">
                            <div class="text-sm text-gray-500">
                                <p>Kategoriyi temsil eden bir emoji seçin</p>
                                <div class="flex space-x-2 mt-2">
                                    <button type="button" onclick="setIcon('📚')" class="p-2 hover:bg-gray-100 rounded">📚</button>
                                    <button type="button" onclick="setIcon('📝')" class="p-2 hover:bg-gray-100 rounded">📝</button>
                                    <button type="button" onclick="setIcon('🎧')" class="p-2 hover:bg-gray-100 rounded">🎧</button>
                                    <button type="button" onclick="setIcon('💬')" class="p-2 hover:bg-gray-100 rounded">💬</button>
                                    <button type="button" onclick="setIcon('📖')" class="p-2 hover:bg-gray-100 rounded">📖</button>
                                    <button type="button" onclick="setIcon('🔤')" class="p-2 hover:bg-gray-100 rounded">🔤</button>
                                </div>
                            </div>
                        </div>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sağ Kolon -->
                <div class="space-y-6">
                    <!-- Zorluk Seviyesi -->
                    <div>
                        <label for="difficulty_level" class="block text-sm font-medium text-gray-700 mb-2">
                            Zorluk Seviyesi
                        </label>
                        <select name="difficulty_level" 
                                id="difficulty_level"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('difficulty_level') border-red-300 @enderror">
                            <option value="">Seçiniz</option>
                            <option value="Başlangıç" {{ old('difficulty_level') == 'Başlangıç' ? 'selected' : '' }}>Başlangıç</option>
                            <option value="Temel" {{ old('difficulty_level') == 'Temel' ? 'selected' : '' }}>Temel</option>
                            <option value="Orta" {{ old('difficulty_level') == 'Orta' ? 'selected' : '' }}>Orta</option>
                            <option value="İleri" {{ old('difficulty_level') == 'İleri' ? 'selected' : '' }}>İleri</option>
                            <option value="Uzman" {{ old('difficulty_level') == 'Uzman' ? 'selected' : '' }}>Uzman</option>
                        </select>
                        @error('difficulty_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Renk -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                            Tema Rengi
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="text" 
                                   name="color" 
                                   id="color" 
                                   value="{{ old('color', 'blue') }}"
                                   class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('color') border-red-300 @enderror">
                            <div class="flex space-x-2">
                                <button type="button" onclick="setColor('blue')" class="w-8 h-8 bg-blue-500 rounded-full border-2 border-gray-300 hover:border-gray-500"></button>
                                <button type="button" onclick="setColor('green')" class="w-8 h-8 bg-green-500 rounded-full border-2 border-gray-300 hover:border-gray-500"></button>
                                <button type="button" onclick="setColor('purple')" class="w-8 h-8 bg-purple-500 rounded-full border-2 border-gray-300 hover:border-gray-500"></button>
                                <button type="button" onclick="setColor('red')" class="w-8 h-8 bg-red-500 rounded-full border-2 border-gray-300 hover:border-gray-500"></button>
                                <button type="button" onclick="setColor('yellow')" class="w-8 h-8 bg-yellow-500 rounded-full border-2 border-gray-300 hover:border-gray-500"></button>
                                <button type="button" onclick="setColor('indigo')" class="w-8 h-8 bg-indigo-500 rounded-full border-2 border-gray-300 hover:border-gray-500"></button>
                            </div>
                        </div>
                        @error('color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sıralama -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Sıralama
                        </label>
                        <input type="number" 
                               name="sort_order" 
                               id="sort_order" 
                               value="{{ old('sort_order', 0) }}"
                               min="0"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('sort_order') border-red-300 @enderror"
                               placeholder="0">
                        <p class="mt-1 text-sm text-gray-500">Küçük sayılar önce görünür</p>
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Durum -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <span class="ml-2 text-sm font-medium text-gray-700">Kategori aktif</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Aktif kategoriler öğrenciler tarafından görülebilir</p>
                    </div>
                </div>
            </div>

            <!-- Önizleme -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-medium text-gray-700 mb-3">Önizleme:</h4>
                <div id="preview" class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg">
                    <span id="preview-icon" class="text-2xl mr-3">📚</span>
                    <div>
                        <div id="preview-name" class="font-medium">Kategori Adı</div>
                        <div id="preview-description" class="text-sm opacity-75">Kategori açıklaması</div>
                    </div>
                </div>
            </div>

            <!-- Butonlar -->
            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.test-categories.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-6 rounded-lg transition duration-200">
                    İptal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200">
                    Kategori Oluştur
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function setIcon(icon) {
    document.getElementById('icon').value = icon;
    updatePreview();
}

function setColor(color) {
    document.getElementById('color').value = color;
    updatePreview();
}

function updatePreview() {
    const name = document.getElementById('name').value || 'Kategori Adı';
    const description = document.getElementById('description').value || 'Kategori açıklaması';
    const icon = document.getElementById('icon').value || '📚';
    const color = document.getElementById('color').value || 'blue';
    
    document.getElementById('preview-name').textContent = name;
    document.getElementById('preview-description').textContent = description;
    document.getElementById('preview-icon').textContent = icon;
    
    // Renk sınıflarını güncelle
    const preview = document.getElementById('preview');
    preview.className = `inline-flex items-center px-4 py-2 bg-${color}-100 text-${color}-800 rounded-lg`;
}

// Form alanları değiştiğinde önizlemeyi güncelle + Mesaj otomatik gizleme
document.addEventListener('DOMContentLoaded', function() {
    // Önizleme güncellemesi için input event'leri
    const inputs = ['name', 'description', 'icon', 'color'];
    inputs.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', updatePreview);
        }
    });
    
    // İlk yükleme
    updatePreview();
    
    // Başarı ve hata mesajlarını 5 saniye sonra otomatik gizle
    const alerts = document.querySelectorAll('[role="alert"]');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000); // 5 saniye sonra kaybol
    });
});
</script>
@endpush
@endsection