{{-- resources/views/ogrenci/test-categories/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#1a2e5a] via-[#2a4073] to-[#1a2e5a]">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col space-y-6">
            <!-- Başlık ve Geri Dön Butonu -->
            <div class="flex items-center">
                <a href="{{ route('ogrenci.learning-panel.index') }}" class="flex items-center text-white hover:text-[#e63946] mr-4 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="text-sm font-medium">Geri Dön</span>
                </a>
                <div class="flex items-center space-x-2">
                    <div class="bg-[#e63946] px-3 py-1 rounded-md">
                        <span class="text-white text-sm font-semibold">📚 TEST KATEGORİLERİ</span>
                    </div>
                </div>
            </div>

            <!-- Ana Başlık -->
            <div class="text-center mb-8">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    Teste <span class="text-[#e63946]">Devam</span> Edin!
                </h1>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                    İngilizce öğrenme yolculuğunuzda size yardımcı olacak farklı test kategorilerinden birini seçin.
                    Kategorilerimize hemen erişin.
                </p>
            </div>
            
            <!-- Ana İçerik Alanı -->
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sol Panel - Filtreler ve Arama -->
                <div class="lg:w-1/4">
                    <div class="bg-white rounded-xl border-2 border-[#1a2e5a] shadow-md p-6 sticky top-4">
                        <h3 class="text-lg font-bold text-[#1a2e5a] mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z" />
                            </svg>
                            Filtrele & Ara
                        </h3>
                        
                        <!-- Arama Kutusu -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">🔍 Kategori Ara</label>
                            <div class="relative">
                                <input type="text" id="searchInput" placeholder="Kategori adı yazın..." 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3 top-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Kategori Filtresi -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">📚 Kategoriler</label>
                            <div class="space-y-1 max-h-32 overflow-y-auto">
                                <label class="flex items-center text-sm">
                                    <input type="checkbox" name="categories" class="category-filter text-[#1a2e5a] focus:ring-[#1a2e5a]" value="all" checked>
                                    <span class="ml-2 text-gray-600">Tümü</span>
                                </label>
                                @foreach($categories->pluck('name')->unique() as $categoryName)
                                    <label class="flex items-center text-sm">
                                        <input type="checkbox" name="categories" class="category-filter text-[#1a2e5a] focus:ring-[#1a2e5a]" value="{{ strtolower($categoryName) }}">
                                        <span class="ml-2 text-gray-700">{{ $categoryName }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Zorluk Seviyesi Filtresi -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">📊 Zorluk Seviyesi</label>
                            <div class="space-y-1">
                                <label class="flex items-center text-sm">
                                    <input type="radio" name="difficulty" class="difficulty-filter text-[#1a2e5a] focus:ring-[#1a2e5a]" value="all" checked>
                                    <span class="ml-2 text-gray-600">Tümü</span>
                                </label>
                                @foreach($categories->pluck('difficulty_level')->filter()->unique() as $difficulty)
                                    <label class="flex items-center text-sm">
                                        <input type="radio" name="difficulty" class="difficulty-filter
                                            @if($difficulty === 'Kolay') text-green-600 focus:ring-green-500
                                            @elseif($difficulty === 'Orta') text-yellow-600 focus:ring-yellow-500
                                            @elseif($difficulty === 'Zor') text-red-600 focus:ring-red-500
                                            @elseif($difficulty === 'Kolay-Orta') text-blue-600 focus:ring-blue-500
                                            @elseif($difficulty === 'Orta-Zor') text-orange-600 focus:ring-orange-500
                                            @else text-purple-600 focus:ring-purple-500
                                            @endif" value="{{ $difficulty }}">
                                        <span class="ml-2
                                            @if($difficulty === 'Kolay') text-green-600
                                            @elseif($difficulty === 'Orta') text-yellow-600
                                            @elseif($difficulty === 'Zor') text-red-600
                                            @elseif($difficulty === 'Kolay-Orta') text-blue-600
                                            @elseif($difficulty === 'Orta-Zor') text-orange-600
                                            @else text-purple-600
                                            @endif">
                                            @if($difficulty === 'Kolay') 🟢
                                            @elseif($difficulty === 'Orta') 🟡
                                            @elseif($difficulty === 'Zor') 🔴
                                            @elseif($difficulty === 'Kolay-Orta') 🔵
                                            @elseif($difficulty === 'Orta-Zor') 🟠
                                            @else 🟣
                                            @endif {{ $difficulty }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Soru Sayısı Filtresi -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">📝 Soru Sayısı</label>
                            <select id="questionCountFilter" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent text-sm">
                                <option value="all">Tüm Kategoriler</option>
                                <option value="1-10">1-10 Soru</option>
                                <option value="11-25">11-25 Soru</option>
                                <option value="26-50">26-50 Soru</option>
                                <option value="50+">50+ Soru</option>
                            </select>
                        </div>
                        
                        <!-- Sıralama -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">🔄 Sıralama</label>
                            <select id="sortFilter" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent text-sm">
                                <option value="name">📝 İsme Göre (A-Z)</option>
                                <option value="name-desc">📝 İsme Göre (Z-A)</option>
                                <option value="questions">📊 Soru Sayısı (↓ Fazla)</option>
                                <option value="questions-desc">📊 Soru Sayısı (↑ Az)</option>
                                <option value="tests">🎯 Test Sayısı (↓ Fazla)</option>
                                <option value="tests-desc">🎯 Test Sayısı (↑ Az)</option>
                            </select>
                        </div>
                        
                        <!-- Filtrele ve Temizle Butonları -->
                        <div class="space-y-2">
                            <button id="applyFilters" class="w-full bg-[#1a2e5a] hover:bg-[#0f1b3d] text-white font-medium py-2 px-4 rounded-lg transition text-sm">
                                🔍 FİLTRELE
                            </button>
                            <button id="clearFilters" class="w-full bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition text-sm">
                                🗑️ TEMİZLE
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Sağ Panel - Kategoriler -->
                <div class="lg:w-3/4">
                    <!-- Sonuç Bilgisi -->
                    <div class="mb-4 flex justify-between items-center">
                        <p class="text-white text-sm">
                            <span id="resultCount">{{ $categories->count() }}</span> kategori gösteriliyor
                        </p>
                        <div class="text-white text-xs">
                            💡 Sol panelden filtreleyebilirsiniz
                        </div>
                    </div>
                    
                    <!-- Test Kategorileri Grid -->
                    <div id="categoriesGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse($categories as $category)
                            <div class="category-card bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 p-6 border-l-4 border-[#1a2e5a] border-2 border-[#1a2e5a]"
                                 data-name="{{ strtolower($category->name ?? '') }}"
                                 data-description="{{ strtolower($category->description ?? '') }}"
                                 data-difficulty="{{ $category->difficulty_level ?? 'Karma' }}"
                                 data-questions="{{ $category->questions_count ?? 0 }}"
                                 data-tests="{{ $category->tests_count ?? 0 }}">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-[#1a2e5a] mb-1">{{ $category->name ?? 'Kategori' }}</h3>
                                        @if($category->description)
                                            <p class="text-xs text-gray-500">{{ Str::limit($category->description, 50) }}</p>
                                        @endif
                                    </div>
                                    <div class="w-12 h-12 bg-[#1a2e5a] rounded-lg flex items-center justify-center ml-3">
                                        <span class="text-white font-bold text-lg">{{ $category->icon ?? '📝' }}</span>
                                    </div>
                                </div>
                                
                                @if($category->description)
                                    <p class="text-gray-600 text-sm mb-4">{{ $category->description }}</p>
                                @endif
                                
                                <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                                    <span class="bg-gray-100 px-2 py-1 rounded">
                                        {{ $category->questions_count ?? 0 }} Soru • {{ $category->tests_count ?? 0 }} Test
                                    </span>
                                    <span class="px-2 py-1 rounded font-medium
                                        @if(($category->difficulty_level ?? '') === 'Kolay')
                                            bg-green-100 text-green-600
                                        @elseif(($category->difficulty_level ?? '') === 'Orta')
                                            bg-yellow-100 text-yellow-600
                                        @elseif(($category->difficulty_level ?? '') === 'Zor')
                                            bg-red-100 text-red-600
                                        @elseif(($category->difficulty_level ?? '') === 'Kolay-Orta')
                                            bg-blue-100 text-blue-600
                                        @elseif(($category->difficulty_level ?? '') === 'Orta-Zor')
                                            bg-orange-100 text-orange-600
                                        @else
                                            bg-purple-100 text-purple-600
                                        @endif
                                    ">
                                        {{ $category->difficulty_level ?? 'Karma' }}
                                    </span>
                                </div>
                                
                                <a href="{{ route('ogrenci.test-categories.show', $category->slug ?? '#') }}" 
                                   class="block w-full bg-[#1a2e5a] hover:bg-[#0f1b3d] text-white font-medium py-2 px-4 rounded-lg transition text-center text-sm">
                                    🎯 Testleri Görüntüle
                                </a>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <div class="text-white mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-white mb-2">Henüz Test Kategorisi Bulunmuyor</h3>
                                <p class="text-gray-300">Test kategorileri yakında eklenecek.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- Sonuç Bulunamadı Mesajı -->
                    <div id="noResults" class="hidden text-center py-12">
                        <div class="text-white mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">🔍 Aradığınız Kriterlerde Kategori Bulunamadı</h3>
                        <p class="text-gray-300">Lütfen arama kriterlerinizi değiştirip tekrar deneyin.</p>
                        <button onclick="document.getElementById('clearFilters').click()" class="mt-4 bg-[#e63946] hover:bg-[#c53030] text-white px-4 py-2 rounded-lg transition">
                            Filtreleri Temizle
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- İstatistikler -->
            @if($categories->count() > 0)
                <div class="bg-white rounded-xl border-2 border-[#1a2e5a] shadow-md mt-8 p-6">
                    <h3 class="text-lg font-bold text-[#1a2e5a] mb-4 text-center">📊 Genel İstatistikler</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#1a2e5a]">{{ $categories->count() }}</div>
                            <div class="text-sm text-gray-600">Toplam Kategori</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $categories->sum('tests_count') ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Toplam Test</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $categories->sum('questions_count') ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Toplam Soru</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">
                                @php
                                    $totalMinutes = 0;
                                    if($categories->count() > 0) {
                                        foreach($categories as $category) {
                                            if(isset($category->tests)) {
                                                $totalMinutes += $category->tests->sum('duration_minutes') ?? 0;
                                            }
                                        }
                                    }
                                @endphp
                                {{ $totalMinutes }}
                            </div>
                            <div class="text-sm text-gray-600">Dakika İçerik</div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Bilgi Notu -->
            <div class="p-6 bg-white rounded-xl border-2 border-[#1a2e5a] shadow-md">
                <div class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#1a2e5a] mr-3 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="font-bold text-[#1a2e5a] mb-2">💡 Test Kategorileri Hakkında</h3>
                        <p class="text-sm text-gray-700">Her kategori farklı bir İngilizce becerisini ölçmek için tasarlanmıştır. Düzenli olarak test çözerek İngilizce seviyenizi artırabilir ve sınav performansınızı geliştirebilirsiniz. Her test sonrasında detaylı analiz ve açıklamalar sunuyoruz.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Filtre sistemi yükleniyor...');
    
    // Element seçicileri
    const searchInput = document.getElementById('searchInput');
    const difficultyFilters = document.querySelectorAll('.difficulty-filter');
    const categoryFilters = document.querySelectorAll('.category-filter');
    const questionCountFilter = document.getElementById('questionCountFilter');
    const sortFilter = document.getElementById('sortFilter');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const categoryCards = document.querySelectorAll('.category-card');
    const resultCount = document.getElementById('resultCount');
    const noResults = document.getElementById('noResults');
    const categoriesGrid = document.getElementById('categoriesGrid');
    
    console.log('📊 Bulunan kategori sayısı:', categoryCards.length);
    console.log('🏷️ Kategori filtreleri:', categoryFilters.length);
    
    // Türkçe karakter normalize etme fonksiyonu
    function normalizeText(text) {
        if (!text) return '';
        return text.toLowerCase()
            .replace(/ç/g, 'c')
            .replace(/ğ/g, 'g')
            .replace(/ı/g, 'i')
            .replace(/ö/g, 'o')
            .replace(/ş/g, 's')
            .replace(/ü/g, 'u')
            .trim();
    }
    
    // Ana filtreleme fonksiyonu
    function applyFilters() {
        console.log('🔍 Filtreler uygulanıyor...');
        
        const searchTerm = normalizeText(searchInput.value);
        const selectedDifficulty = document.querySelector('.difficulty-filter:checked')?.value || 'all';
        const selectedCategories = Array.from(categoryFilters)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);
        const questionCount = questionCountFilter.value;
        const sortBy = sortFilter.value;
        
        console.log('Arama terimi:', searchTerm);
        console.log('Zorluk:', selectedDifficulty);
        console.log('Seçili kategoriler:', selectedCategories);
        console.log('Soru sayısı:', questionCount);
        console.log('Sıralama:', sortBy);
        
        let visibleCards = [];
        
        // Her kartı kontrol et
        categoryCards.forEach((card, index) => {
            const name = normalizeText(card.dataset.name || '');
            const description = normalizeText(card.dataset.description || '');
            const difficulty = card.dataset.difficulty || 'Karma';
            const questions = parseInt(card.dataset.questions) || 0;
            const tests = parseInt(card.dataset.tests) || 0;
            
            let showCard = true;
            
            // Arama filtresi
            if (searchTerm && !name.includes(searchTerm) && !description.includes(searchTerm)) {
                showCard = false;
                console.log(`❌ Kart ${index + 1} arama ile eşleşmiyor`);
            }
            
            // Kategori filtresi
            if (!selectedCategories.includes('all') && selectedCategories.length > 0) {
                const cardNameMatch = selectedCategories.some(cat => name.includes(cat.toLowerCase()));
                if (!cardNameMatch) {
                    showCard = false;
                    console.log(`❌ Kart ${index + 1} kategori ile eşleşmiyor`);
                }
            }
            
            // Zorluk seviyesi filtresi
            if (selectedDifficulty !== 'all' && difficulty !== selectedDifficulty) {
                showCard = false;
                console.log(`❌ Kart ${index + 1} zorluk ile eşleşmiyor`);
            }
            
            // Soru sayısı filtresi
            if (questionCount !== 'all') {
                switch (questionCount) {
                    case '1-10':
                        if (questions < 1 || questions > 10) showCard = false;
                        break;
                    case '11-25':
                        if (questions < 11 || questions > 25) showCard = false;
                        break;
                    case '26-50':
                        if (questions < 26 || questions > 50) showCard = false;
                        break;
                    case '50+':
                        if (questions <= 50) showCard = false;
                        break;
                }
                if (!showCard) console.log(`❌ Kart ${index + 1} soru sayısı ile eşleşmiyor`);
            }
            
            // Kartı göster/gizle
            if (showCard) {
                visibleCards.push({
                    element: card,
                    name: card.querySelector('h3')?.textContent || '',
                    questions: questions,
                    tests: tests
                });
                card.style.display = 'block';
                console.log(`✅ Kart ${index + 1} gösteriliyor`);
            } else {
                card.style.display = 'none';
            }
        });
        
        console.log('👀 Görünen kart sayısı:', visibleCards.length);
        
        // Sıralama
        if (visibleCards.length > 0) {
            visibleCards.sort((a, b) => {
                switch (sortBy) {
                    case 'name':
                        return a.name.localeCompare(b.name, 'tr');
                    case 'name-desc':
                        return b.name.localeCompare(a.name, 'tr');
                    case 'questions':
                        return b.questions - a.questions;
                    case 'questions-desc':
                        return a.questions - b.questions;
                    case 'tests':
                        return b.tests - a.tests;
                    case 'tests-desc':
                        return a.tests - b.tests;
                    default:
                        return 0;
                }
            });
            
            // Kartları yeniden sırala
            visibleCards.forEach(card => {
                categoriesGrid.appendChild(card.element);
            });
        }
        
        // Sonuç sayısını güncelle
        resultCount.textContent = visibleCards.length;
        
        // Sonuç bulunamadı mesajını göster/gizle
        if (visibleCards.length === 0) {
            noResults.classList.remove('hidden');
            categoriesGrid.classList.add('hidden');
            console.log('🚫 Hiç sonuç bulunamadı');
        } else {
            noResults.classList.add('hidden');
            categoriesGrid.classList.remove('hidden');
            console.log('✅ Sonuçlar gösteriliyor');
        }
    }
    
    // Event listeners
    applyFiltersBtn.addEventListener('click', applyFilters);
    
    searchInput.addEventListener('input', function() {
        console.log('⌨️ Arama yapılıyor:', this.value);
        applyFilters();
    });
    
    questionCountFilter.addEventListener('change', applyFilters);
    sortFilter.addEventListener('change', applyFilters);
    
    difficultyFilters.forEach(radio => {
        radio.addEventListener('change', applyFilters);
    });
    
    categoryFilters.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Eğer "Tümü" seçildiyse diğerlerini kapat
            if (this.value === 'all' && this.checked) {
                categoryFilters.forEach(cb => {
                    if (cb.value !== 'all') cb.checked = false;
                });
            } 
            // Eğer başka bir kategori seçildiyse "Tümü"yü kapat
            else if (this.value !== 'all' && this.checked) {
                const allCheckbox = document.querySelector('.category-filter[value="all"]');
                if (allCheckbox) allCheckbox.checked = false;
            }
            applyFilters();
        });
    });
    
    // Filtreleri temizle
    clearFiltersBtn.addEventListener('click', function() {
        console.log('🗑️ Filtreler temizleniyor...');
        
        searchInput.value = '';
        questionCountFilter.value = 'all';
        sortFilter.value = 'name';
        
        // İlk radio button'ı (Tümü) seç
        const allRadio = document.querySelector('.difficulty-filter[value="all"]');
        if (allRadio) allRadio.checked = true;
        
        // Kategori checkboxlarını temizle ve "Tümü"yü seç
        categoryFilters.forEach(checkbox => {
            checkbox.checked = checkbox.value === 'all';
        });
        
        applyFilters();
    });
    
    // Kategori kartlarına hover efekti
    categoryCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Sayfa yüklendiğinde animasyon
    categoryCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }, index * 100);
    });
    
    console.log('✅ Filtre sistemi hazır!');
});
</script>
@endsection


