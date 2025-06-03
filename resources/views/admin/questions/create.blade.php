@extends('layouts.app')

@section('title', 'Yeni Soru Oluştur')

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
        </div>
    @endif

    <!-- Başlık -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">❓ Yeni Soru Oluştur</h1>
            <p class="text-gray-600">Test sistemi için yeni soru oluşturun</p>
        </div>
        
        <a href="{{ route('admin.questions.index') }}" 
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
            <h3 class="text-lg font-semibold text-gray-800">Soru Bilgileri</h3>
        </div>
        
        <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Sol Kolon -->
                <div class="space-y-6">
                    <!-- Soru Metni -->
                    <div>
                        <label for="question_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Soru Metni <span class="text-red-500">*</span>
                        </label>
                        <textarea name="question_text" 
                                  id="question_text" 
                                  rows="4"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('question_text') border-red-300 @enderror"
                                  placeholder="Sorunuzu buraya yazın..."
                                  required>{{ old('question_text') }}</textarea>
                        @error('question_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Soru Tipi -->
                    <div>
                        <label for="question_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Soru Tipi <span class="text-red-500">*</span>
                        </label>
                        <select name="question_type" 
                                id="question_type"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('question_type') border-red-300 @enderror"
                                required
                                onchange="toggleOptions()">
                            <option value="">Seçiniz</option>
                            <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>Çoktan Seçmeli</option>
                            <option value="true_false" {{ old('question_type') == 'true_false' ? 'selected' : '' }}>Doğru/Yanlış</option>
                            <option value="fill_blank" {{ old('question_type') == 'fill_blank' ? 'selected' : '' }}>Boşluk Doldurma</option>
                            <option value="matching" {{ old('question_type') == 'matching' ? 'selected' : '' }}>Eşleştirme</option>
                        </select>
                        @error('question_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Çoktan Seçmeli Seçenekler -->
                    <div id="options_section" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Seçenekler <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            @for($i = 0; $i < 4; $i++)
                                <div class="flex items-center space-x-3">
                                    <span class="font-medium text-gray-700 w-8">{{ chr(65 + $i) }})</span>
                                    <input type="text" 
                                           name="options[]" 
                                           value="{{ old('options.' . $i) }}"
                                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                                           placeholder="Seçenek {{ chr(65 + $i) }}">
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Doğru Cevap -->
                    <div>
                        <label for="correct_answer" class="block text-sm font-medium text-gray-700 mb-2">
                            Doğru Cevap <span class="text-red-500">*</span>
                        </label>
                        <div id="correct_answer_section">
                            <!-- Çoktan seçmeli için -->
                            <select name="correct_answer" 
                                    id="correct_answer_select"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('correct_answer') border-red-300 @enderror"
                                    style="display: none;">
                                <option value="">Seçiniz</option>
                                <option value="A" {{ old('correct_answer') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('correct_answer') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ old('correct_answer') == 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" {{ old('correct_answer') == 'D' ? 'selected' : '' }}>D</option>
                            </select>
                            
                            <!-- Doğru/Yanlış için -->
                            <select name="correct_answer" 
                                    id="correct_answer_tf"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                                    style="display: none;">
                                <option value="">Seçiniz</option>
                                <option value="true" {{ old('correct_answer') == 'true' ? 'selected' : '' }}>Doğru</option>
                                <option value="false" {{ old('correct_answer') == 'false' ? 'selected' : '' }}>Yanlış</option>
                            </select>
                            
                            <!-- Diğer tipler için -->
                            <input type="text" 
                                   name="correct_answer" 
                                   id="correct_answer_text"
                                   value="{{ old('correct_answer') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('correct_answer') border-red-300 @enderror"
                                   placeholder="Doğru cevabı yazın">
                        </div>
                        @error('correct_answer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Açıklama -->
                    <div>
                        <label for="explanation" class="block text-sm font-medium text-gray-700 mb-2">
                            Açıklama
                        </label>
                        <textarea name="explanation" 
                                  id="explanation" 
                                  rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('explanation') border-red-300 @enderror"
                                  placeholder="Sorunun açıklaması veya çözümü...">{{ old('explanation') }}</textarea>
                        @error('explanation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sağ Kolon -->
                <div class="space-y-6">
                    <!-- Test Kategorileri -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kategoriler <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-md p-3">
                            @forelse($categories ?? [] as $category)
                                <label class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded">
                                    <input type="checkbox" 
                                           name="categories[]" 
                                           value="{{ $category->id }}"
                                           {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
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
                        @error('categories')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

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

                    <!-- Puan -->
                    <div>
                        <label for="points" class="block text-sm font-medium text-gray-700 mb-2">
                            Puan
                        </label>
                        <input type="number" 
                               name="points" 
                               id="points" 
                               value="{{ old('points', 1) }}"
                               min="1"
                               max="10"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('points') border-red-300 @enderror">
                        @error('points')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Görsel -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Soru Görseli
                        </label>
                        <input type="file" 
                               name="image" 
                               id="image" 
                               accept="image/*"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('image') border-red-300 @enderror">
                        <p class="mt-1 text-sm text-gray-500">JPG, PNG, GIF - Max 2MB</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Testlere Ekle -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Testlere Ekle (Opsiyonel)
                        </label>
                        <div class="space-y-2 max-h-32 overflow-y-auto border border-gray-200 rounded-md p-3">
                            @forelse($tests ?? [] as $test)
                                <label class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded">
                                    <input type="checkbox" 
                                           name="tests[]" 
                                           value="{{ $test->id }}"
                                           {{ in_array($test->id, old('tests', [])) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">{{ $test->title }}</span>
                                        @if($test->difficulty_level)
                                            <span class="ml-2 text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">
                                                {{ $test->difficulty_level }}
                                            </span>
                                        @endif
                                    </div>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-2">Henüz test yok</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Aktif/Pasif -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <span class="ml-2 text-sm font-medium text-gray-700">Soru aktif</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Aktif sorular testlerde kullanılabilir</p>
                    </div>
                </div>
            </div>

            <!-- Butonlar -->
            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.questions.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-6 rounded-lg transition duration-200">
                    İptal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200">
                    Soru Oluştur
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleOptions() {
    const questionType = document.getElementById('question_type').value;
    const optionsSection = document.getElementById('options_section');
    const correctAnswerSelect = document.getElementById('correct_answer_select');
    const correctAnswerTF = document.getElementById('correct_answer_tf');
    const correctAnswerText = document.getElementById('correct_answer_text');
    
    // Tüm input'ları gizle
    optionsSection.style.display = 'none';
    correctAnswerSelect.style.display = 'none';
    correctAnswerTF.style.display = 'none';
    correctAnswerText.style.display = 'none';
    
    // Name attribute'larını temizle
    correctAnswerSelect.removeAttribute('name');
    correctAnswerTF.removeAttribute('name');
    correctAnswerText.removeAttribute('name');
    
    if (questionType === 'multiple_choice' || questionType === 'matching') {
        optionsSection.style.display = 'block';
        correctAnswerSelect.style.display = 'block';
        correctAnswerSelect.setAttribute('name', 'correct_answer');
    } else if (questionType === 'true_false') {
        correctAnswerTF.style.display = 'block';
        correctAnswerTF.setAttribute('name', 'correct_answer');
    } else if (questionType === 'fill_blank') {
        correctAnswerText.style.display = 'block';
        correctAnswerText.setAttribute('name', 'correct_answer');
    }
}

// Sayfa yüklendiğinde çalıştır
document.addEventListener('DOMContentLoaded', function() {
    toggleOptions();
    
    // Başarı mesajlarını otomatik gizle
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