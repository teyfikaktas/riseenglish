@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('word-sets.index') }}" 
                   class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-[#1a2e5a]">{{ $wordSet->name }}</h1>
                    @if($wordSet->description)
                        <p class="text-gray-600 mt-1">{{ $wordSet->description }}</p>
                    @endif
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="px-3 py-1 rounded-full text-sm font-medium text-white" style="background-color: {{ $wordSet->color }}">
                        {{ $words->count() }} kelime
                    </span>
                    <span class="text-sm text-gray-500">
                        {{ $wordSet->created_at->diffForHumans() }} oluşturuldu
                    </span>
                </div>
                <a href="{{ route('word-sets.edit', $wordSet) }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Düzenle
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Kelime Ekleme Formu -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-8">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-[#1a2e5a] flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Yeni Kelime Ekle
                </h2>
            </div>
            <form action="{{ route('word-sets.add-word', $wordSet) }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="english_word" class="block text-sm font-semibold text-gray-700 mb-2">
                            İngilizce Kelime <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="english_word" 
                               name="english_word" 
                               value="{{ old('english_word') }}"
                               placeholder="Örn: beautiful"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#e63946] focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label for="turkish_meaning" class="block text-sm font-semibold text-gray-700 mb-2">
                            Türkçe Anlamı <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="turkish_meaning" 
                               name="turkish_meaning" 
                               value="{{ old('turkish_meaning') }}"
                               placeholder="Örn: güzel"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#e63946] focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label for="word_type" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kelime Türü <span class="text-gray-400">(İsteğe bağlı)</span>
                        </label>
                        <select id="word_type" 
                                name="word_type" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#e63946] focus:border-transparent transition-all">
                            <option value="">Seçiniz</option>
                            <option value="noun" {{ old('word_type') === 'noun' ? 'selected' : '' }}>İsim (Noun)</option>
                            <option value="verb" {{ old('word_type') === 'verb' ? 'selected' : '' }}>Fiil (Verb)</option>
                            <option value="adjective" {{ old('word_type') === 'adjective' ? 'selected' : '' }}>Sıfat (Adjective)</option>
                            <option value="adverb" {{ old('word_type') === 'adverb' ? 'selected' : '' }}>Zarf (Adverb)</option>
                            <option value="preposition" {{ old('word_type') === 'preposition' ? 'selected' : '' }}>Edat (Preposition)</option>
                            <option value="pronoun" {{ old('word_type') === 'pronoun' ? 'selected' : '' }}>Zamir (Pronoun)</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" 
                            class="bg-[#e63946] hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Kelime Ekle
                    </button>
                </div>
            </form>
        </div>

        @if($words->count() > 0)
            <!-- Kelimeler Listesi -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-[#1a2e5a]">Kelimeler ({{ $words->count() }})</h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($words as $word)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4 mb-2">
                                        <h3 class="text-lg font-bold text-[#1a2e5a]">{{ $word->english_word }}</h3>
                                        <span class="text-gray-400">→</span>
                                        <span class="text-lg text-gray-700">{{ $word->turkish_meaning }}</span>
                                        @if($word->word_type)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-medium">
                                                {{ ucfirst($word->word_type) }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500">
                                        {{ $word->created_at->diffForHumans() }} eklendi
                                    </p>
                                </div>
                                <form action="{{ route('word-sets.delete-word', [$wordSet, $word]) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Bu kelimeyi silmek istediğinizden emin misiniz?')"
                                      class="ml-4">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Boş Durum -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="mb-6">
                        <svg class="w-20 h-20 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-3">İlk Kelimenizi Ekleyin!</h3>
                    <p class="text-gray-500 mb-6 leading-relaxed">
                        Bu set henüz boş. Yukarıdaki formu kullanarak ilk kelimenizi ekleyin ve öğrenmeye başlayın. 
                        Her eklediğiniz kelime ile kelime dağarcığınız genişleyecek!
                    </p>
                    <div class="text-sm text-gray-400">
                        💡 İpucu: Kelimelerinizi günlük hayatta karşılaştığınız durumlardan seçin
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection