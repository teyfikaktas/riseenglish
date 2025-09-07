{{-- resources/views/useful-resources/category.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-blue-50 to-white min-h-screen py-12">
    <div class="container mx-auto px-4">
        {{-- Breadcrumb --}}
        <nav class="mb-8">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="{{ route('useful-resources.index') }}" class="hover:text-[#2c3e7f] transition-colors">Faydalı Kaynaklar</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#2c3e7f] font-medium">{{ $categoryTitle }}</span>
            </div>
        </nav>

        {{-- Kategori Başlığı --}}
        <div class="text-center mb-12">
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-4xl mx-auto border border-gray-100">
                <h1 class="text-5xl font-bold text-[#2c3e7f] mb-4">{{ $categoryTitle }}</h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    {{ $categoryTitle }} kategorisindeki tüm kaynaklarımızı keşfedin ve İngilizce öğrenim sürecinizi hızlandırın.
                </p>
                <div class="mt-4 text-sm text-gray-500">
                    Toplam {{ $resources->total() }} kaynak bulundu
                </div>
            </div>
        </div>

        {{-- Sıralama ve Filtreler --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 max-w-4xl mx-auto border border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                    <label class="text-sm font-medium text-gray-700">Sıralama:</label>
                    <select id="sort-select" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#2c3e7f] focus:border-[#2c3e7f]">
                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Varsayılan</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>En Popüler</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>En Yeni</option>
                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Alfabetik</option>
                        <option value="most-downloaded" {{ request('sort') == 'most-downloaded' ? 'selected' : '' }}>En Çok İndirilen</option>
                    </select>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <input type="text" id="search-input" placeholder="Bu kategoride ara..." 
                               value="{{ request('search') }}"
                               class="border border-gray-300 rounded-lg px-4 py-2 pl-10 text-sm focus:ring-[#2c3e7f] focus:border-[#2c3e7f]">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button id="search-btn" class="px-4 py-2 bg-[#2c3e7f] text-white rounded-lg hover:bg-[#1e3370] transition duration-300">
                        Ara
                    </button>
                </div>
            </div>
        </div>

        {{-- Kaynaklar Grid --}}
        @if($resources->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($resources as $resource)
            <div class="resource-card bg-white rounded-2xl shadow-lg overflow-hidden transition duration-300 hover:shadow-xl border border-gray-100 animate-fade-in">
                <div class="h-40 bg-gradient-to-r from-blue-200 to-indigo-100 relative overflow-hidden">
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="px-3 py-1 bg-[#2c3e7f] text-white text-xs font-bold rounded-full">{{ ucfirst($resource->category) }}</span>
                    </div>
                    @if($resource->is_popular)
                    <div class="absolute top-3 left-3">
                        <span class="px-2 py-1 bg-[#e43546] text-white text-xs font-bold rounded-full flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            Popüler
                        </span>
                    </div>
                    @endif
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-lg text-[#2c3e7f] mb-2 line-clamp-2">{{ $resource->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $resource->description }}</p>
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            {{ $resource->view_count }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            {{ $resource->download_count }}
                        </span>
                        <span>{{ $resource->file_size_human }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ strtoupper($resource->file_type) }}</span>
                        <a href="{{ route('useful-resources.show', $resource->slug) }}" class="px-4 py-2 bg-gradient-to-r from-[#2c3e7f] to-[#264285] text-white text-sm font-medium rounded-lg hover:from-[#264285] hover:to-[#1e3370] transition duration-300">
                            İncele
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center">
            {{ $resources->links() }}
        </div>

        @else
        {{-- Sonuç Bulunamadı --}}
        <div class="bg-white rounded-2xl shadow-lg p-10 text-center max-w-2xl mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">Kaynak Bulunamadı</h3>
            <p class="text-gray-500 mb-6">{{ $categoryTitle }} kategorisinde henüz kaynak bulunmuyor.</p>
            <a href="{{ route('useful-resources.index') }}" class="px-6 py-3 bg-[#2c3e7f] text-white font-medium rounded-xl shadow-md hover:bg-[#1e3370] transition duration-300">
                Tüm Kaynakları Gör
            </a>
        </div>
        @endif

        {{-- Diğer Kategoriler --}}
        <div class="mt-16 bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <h3 class="text-2xl font-bold text-[#2c3e7f] mb-6 flex items-center">
                <div class="w-6 h-1 bg-[#e43546] rounded-full mr-3"></div>
                Diğer Kategoriler
            </h3>
            
            @php
                $otherCategories = [
                    'tenses' => 'Tenses (Zamanlar)',
                    'modals' => 'Modal Verbs (Modal Fiiller)',
                    'adjectives' => 'Adjectives (Sıfatlar)',
                    'pronouns' => 'Pronouns (Zamirler)',
                    'grammar' => 'Advanced Grammar (İleri Gramer)',
                    'conditionals' => 'Conditional Sentences (Koşul Cümleleri)',
                    'vocabulary' => 'Vocabulary (Kelime Bilgisi)',
                    'grammar-basics' => 'Grammar Basics (Temel Gramer)',
                    'advanced' => 'Advanced Topics (İleri Konular)',
                    'special' => 'Special Topics (Özel Konular)',
                    'word-formation' => 'Word Formation (Kelime Türetme)',
                    'patterns' => 'Language Patterns (Dil Kalıpları)'
                ];
                
                $currentCategory = $category;
                $filteredCategories = collect($otherCategories)->reject(function($title, $cat) use ($currentCategory) {
                    return $cat === $currentCategory;
                });
            @endphp
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($filteredCategories as $cat => $title)
                <a href="{{ route('useful-resources.category', $cat) }}" 
                   class="block p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-[#2c3e7f] hover:text-white transition duration-300 group">
                    <div class="font-semibold text-sm mb-1 group-hover:text-white">{{ $title }}</div>
                    @php
                        $count = \App\Models\UsefulResource::active()->byCategory($cat)->count();
                    @endphp
                    <div class="text-xs text-gray-500 group-hover:text-gray-200">{{ $count }} kaynak</div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-in-out forwards;
}

@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortSelect = document.getElementById('sort-select');
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    
    // Sıralama değiştiğinde
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            applyFilters();
        });
    }
    
    // Arama butonu
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            applyFilters();
        });
    }
    
    // Enter tuşu ile arama
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
    }
    
    function applyFilters() {
        const searchTerm = searchInput ? searchInput.value : '';
        const sort = sortSelect ? sortSelect.value : 'default';
        
        const params = new URLSearchParams();
        if (searchTerm) params.append('search', searchTerm);
        if (sort && sort !== 'default') params.append('sort', sort);
        
        const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    }
});
</script>

@endsection