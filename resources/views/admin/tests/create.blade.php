@extends('layouts.app')

@section('title', 'Yeni Test Oluştur')

@section('content')
<div class="container mx-auto px-6 py-8">
    {{-- Başarı ve Hata Mesajları --}}
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

    <!-- Başlık -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">📝 Yeni Test Oluştur</h1>
            <p class="text-gray-600">Yeni bir test oluşturun ve kategorilere ekleyin</p>
        </div>
        
        <a href="{{ route('admin.tests.index') }}" 
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
            <h3 class="text-lg font-semibold text-gray-800">Test Bilgileri</h3>
        </div>
        
        <form action="{{ route('admin.tests.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Sol Kolon -->
                <div class="space-y-6">
                    <!-- Test Adı -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Test Adı <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               value="{{ old('title') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('title') border-red-300 @enderror"
                               placeholder="Örn: İngilizce Grammar Test - Seviye 1"
                               required>
                        @error('title')
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
                                  placeholder="Bu test ile ilgili açıklama yazın...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Test Kategorileri -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Test Kategorileri <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-md p-3">
                            @forelse($categories ?? [] as $category)
                                <label class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded">
                                    <input type="checkbox" 
                                           name="category_ids[]" 
                                           value="{{ $category->id }}"
                                           {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg">{{ $category->icon ?? '📚' }}</span>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                                            @if($category->difficulty_level)
                                                <span class="ml-2 text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">
                                                    {{ $category->difficulty_level }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">
                                    Henüz kategori bulunmuyor. 
                                    <a href="{{ route('admin.test-categories.create') }}" class="text-blue-600 hover:text-blue-800 underline">
                                        İlk kategoriyi oluşturun
                                    </a>
                                </p>
                            @endforelse
                        </div>
                        @error('category_ids')
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

                    <!-- Test Süresi -->
                    <div>
                        <label for="time_limit" class="block text-sm font-medium text-gray-700 mb-2">
                            Test Süresi (Dakika)
                        </label>
                        <input type="number" 
                               name="time_limit" 
                               id="time_limit" 
                               value="{{ old('time_limit', 30) }}"
                               min="1"
                               max="180"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('time_limit') border-red-300 @enderror"
                               placeholder="30">
                        <p class="mt-1 text-sm text-gray-500">Boş bırakırsanız süre sınırı olmaz</p>
                        @error('time_limit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Geçme Puanı -->
                    <div>
                        <label for="passing_score" class="block text-sm font-medium text-gray-700 mb-2">
                            Geçme Puanı (%)
                        </label>
                        <input type="number" 
                               name="passing_score" 
                               id="passing_score" 
                               value="{{ old('passing_score', 60) }}"
                               min="1"
                               max="100"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('passing_score') border-red-300 @enderror"
                               placeholder="60">
                        <p class="mt-1 text-sm text-gray-500">Testten geçmek için minimum puan yüzdesi</p>
                        @error('passing_score')
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

                    <!-- Aktif/Pasif -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <span class="ml-2 text-sm font-medium text-gray-700">Test aktif</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Aktif testler öğrenciler tarafından görülebilir</p>
                    </div>

                    {{-- Test Ayarları - Şimdilik comment out (tabloda field yok)
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-700">Test Ayarları</h4>
                        
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="shuffle_questions" 
                                   value="1"
                                   {{ old('shuffle_questions', false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <span class="ml-2 text-sm font-medium text-gray-700">Soruları karıştır</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="show_results" 
                                   value="1"
                                   {{ old('show_results', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <span class="ml-2 text-sm font-medium text-gray-700">Sonuçları göster</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="allow_retake" 
                                   value="1"
                                   {{ old('allow_retake', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <span class="ml-2 text-sm font-medium text-gray-700">Tekrar çözmeye izin ver</span>
                        </label>
                    </div>
                    --}}ünür</p>
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Test Ayarları -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-700">Test Ayarları</h4>
                        
                        <!-- Aktif/Pasif -->
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <span class="ml-2 text-sm font-medium text-gray-700">Test aktif</span>
                        </label>
                        
                        <!-- Sorular karıştırılsın -->
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="shuffle_questions" 
                                   value="1"
                                   {{ old('shuffle_questions', false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <span class="ml-2 text-sm font-medium text-gray-700">Soruları karıştır</span>
                        </label>
                        
                        <!-- Cevapları göster -->
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="show_results" 
                                   value="1"
                                   {{ old('show_results', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <span class="ml-2 text-sm font-medium text-gray-700">Sonuçları göster</span>
                        </label>
                        
                        <!-- Tekrar çözüme izin ver -->
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="allow_retake" 
                                   value="1"
                                   {{ old('allow_retake', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <span class="ml-2 text-sm font-medium text-gray-700">Tekrar çözmeye izin ver</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Önizleme -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-medium text-gray-700 mb-3">Test Önizlemesi:</h4>
                <div id="test-preview" class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <h5 id="preview-title" class="font-semibold text-gray-800">Test Adı</h5>
                        <div class="flex items-center space-x-2">
                            <span id="preview-difficulty" class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                Zorluk Seviyesi
                            </span>
                            <span id="preview-time" class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                ⏱ 30 dk
                            </span>
                        </div>
                    </div>
                    <p id="preview-description" class="text-sm text-gray-600 mb-3">Test açıklaması</p>
                    <div id="preview-categories" class="flex flex-wrap gap-2">
                        <!-- Seçilen kategoriler burada görünecek -->
                    </div>
                </div>
            </div>

            <!-- Butonlar -->
            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.tests.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-6 rounded-lg transition duration-200">
                    İptal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200">
                    Test Oluştur
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Önizleme güncelleme fonksiyonu
function updateTestPreview() {
    const title = document.getElementById('title').value || 'Test Adı';
    const description = document.getElementById('description').value || 'Test açıklaması';
    const difficulty = document.getElementById('difficulty_level').value || 'Zorluk Seviyesi';
    const timeLimit = document.getElementById('time_limit').value || '30';
    
    document.getElementById('preview-title').textContent = title;
    document.getElementById('preview-description').textContent = description;
    document.getElementById('preview-difficulty').textContent = difficulty;
    document.getElementById('preview-time').textContent = `⏱ ${timeLimit} dk`;
    
    // Seçilen kategorileri göster
    const selectedCategories = document.querySelectorAll('input[name="category_ids[]"]:checked');
    const categoriesContainer = document.getElementById('preview-categories');
    categoriesContainer.innerHTML = '';
    
    selectedCategories.forEach(checkbox => {
        const label = checkbox.closest('label');
        const categoryName = label.querySelector('.text-sm.font-medium').textContent;
        const categoryIcon = label.querySelector('.text-lg').textContent;
        
        const categoryTag = document.createElement('span');
        categoryTag.className = 'inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full';
        categoryTag.innerHTML = `<span class="mr-1">${categoryIcon}</span>${categoryName}`;
        
        categoriesContainer.appendChild(categoryTag);
    });
}

// Form alanları değiştiğinde önizlemeyi güncelle
document.addEventListener('DOMContentLoaded', function() {
    const previewInputs = ['title', 'description', 'difficulty_level', 'time_limit'];
    previewInputs.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', updateTestPreview);
            element.addEventListener('change', updateTestPreview);
        }
    });
    
    // Kategori checkbox'ları için event listener
    const categoryCheckboxes = document.querySelectorAll('input[name="category_ids[]"]');
    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateTestPreview);
    });
    
    // İlk yükleme
    updateTestPreview();
    
    // Başarı ve hata mesajlarını 5 saniye sonra otomatik gizle
    const alerts = document.querySelectorAll('[role="alert"]');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
});
</script>
@endpush
@endsection