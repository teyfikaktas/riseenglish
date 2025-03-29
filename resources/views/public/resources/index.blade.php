@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-blue-50 to-white min-h-screen">
    <div class="container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-[#2c3e7f] mb-4">Ücretsiz Kaynaklar</h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Ücretsiz sunduğumuz kaynaklarımızla dil sınavına hazırlanan herkesin yanındayız.</p>
        </div>
                {{-- Geliştirilmiş Filtreler --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 mb-16 max-w-4xl mx-auto transition duration-300 hover:shadow-2xl border border-gray-100">
                    <h3 class="text-2xl font-bold text-[#2c3e7f] mb-6 text-center">Kaynakları Filtrele</h3>
                    
                    {{-- Arama Kutusu --}}
                    <div class="mb-6">
                        <div class="relative">
                            <input type="text" id="search-resources" placeholder="Kaynak ara..." class="w-full border-0 bg-gray-50 rounded-xl p-4 pl-12 shadow-sm focus:border-[#2c3e7f] focus:ring focus:ring-[#2c3e7f] focus:ring-opacity-50">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <button id="search-button" class="absolute inset-y-0 right-0 px-4 py-2 bg-[#2c3e7f] text-white font-medium rounded-r-xl hover:bg-[#1e3370] transition duration-300">
                                Ara
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <div class="relative">
                                <select id="main-category-filter" class="w-full border-0 bg-gray-50 rounded-xl p-4 pr-10 shadow-sm focus:border-[#2c3e7f] focus:ring focus:ring-[#2c3e7f] focus:ring-opacity-50 appearance-none">
                                    <option value="">Ana Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#2c3e7f]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="relative">
                                <select id="sub-category-filter" class="w-full border-0 bg-gray-50 rounded-xl p-4 pr-10 shadow-sm focus:border-[#2c3e7f] focus:ring focus:ring-[#2c3e7f] focus:ring-opacity-50 appearance-none">
                                    <option value="">Alt Kategori</option>
                                    <!-- Alt kategoriler JavaScript ile doldurulacak -->
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#2c3e7f]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="relative">
                                <select id="resource-type" class="w-full border-0 bg-gray-50 rounded-xl p-4 pr-10 shadow-sm focus:border-[#2c3e7f] focus:ring focus:ring-[#2c3e7f] focus:ring-opacity-50 appearance-none">
                                    <option value="">Kaynak Türü</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->slug }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#2c3e7f]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-center">
                        <button id="filter-button" class="px-8 py-3 bg-gradient-to-r from-[#2c3e7f] to-[#264285] text-white font-medium rounded-xl shadow-md hover:from-[#264285] hover:to-[#1e3370] transition duration-300 transform hover:scale-105 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filtrele
                        </button>
                        <button id="reset-filters" class="ml-4 px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl shadow-md hover:bg-gray-200 transition duration-300">
                            Sıfırla
                        </button>
                    </div>
                </div>
        {{-- Popüler Kaynaklar Slider --}}
        <div id="popular-resources-section" class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-[#2c3e7f] flex items-center">
                    <div class="w-6 h-1 bg-[#e43546] rounded-full mr-3"></div>
                    Popüler Kaynaklar
                </h2>
                <div class="flex space-x-2">
                    <button id="slideLeft" class="p-2 rounded-full bg-white shadow-md hover:bg-gray-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#2c3e7f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button id="slideRight" class="p-2 rounded-full bg-white shadow-md hover:bg-gray-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#2c3e7f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="relative overflow-hidden">
                <div id="popular-resources-slider" class="flex space-x-6 pb-4 overflow-x-auto hide-scrollbar scroll-smooth">
                    @foreach($popularResources as $resource)
                    <div class="resource-card flex-shrink-0 w-80 bg-white rounded-2xl shadow-lg overflow-hidden transition duration-300 hover:shadow-xl border border-gray-100"
                         data-category="{{ $resource->category_id }}" 
                         data-parent-category="{{ $resource->category->parent_id ?? '' }}" 
                         data-type="{{ $resource->type->slug ?? '' }}"
                         data-title="{{ $resource->title }}"
                         data-description="{{ $resource->description }}">
                        <div class="h-40 bg-gradient-to-r from-blue-200 to-indigo-100 relative overflow-hidden">
                            @if($resource->image_path)
                                <img src="{{asset('storage/' . $resource->image_path) }}" alt="{{ $resource->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-3 right-3">
                                <span class="px-3 py-1 bg-[#2c3e7f] text-white text-xs font-bold rounded-full">{{ $resource->type->name ?? 'Kaynak' }}</span>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-lg text-[#2c3e7f] mb-2 line-clamp-2">{{ $resource->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $resource->description }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $resource->category->name }}</span>
                                </div>
                                <a href="{{ route('public.resources.show', $resource->slug) }}" class="px-4 py-2 bg-gradient-to-r from-[#2c3e7f] to-[#264285] text-white text-sm font-medium rounded-lg hover:from-[#264285] hover:to-[#1e3370] transition duration-300">
                                    İncele
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        


        {{-- Filtrelenmiş Sonuçlar (Başlangıçta gizli) --}}
        <div id="filtered-results" class="mb-16 hidden">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-[#2c3e7f] flex items-center">
                    <div class="w-6 h-1 bg-[#e43546] rounded-full mr-3"></div>
                    Filtrelenmiş Kaynaklar
                </h2>
            </div>
            
            {{-- Kaynaklar Grid --}}
            <div id="resources-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Filtrelenmiş kaynaklar JavaScript ile buraya eklenecek -->
            </div>
            
            {{-- Sonuç Bulunamadı Mesajı --}}
            <div id="no-results" class="hidden bg-white rounded-2xl shadow-lg p-10 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">Üzgünüz, Sonuç Bulunamadı</h3>
                <p class="text-gray-500 mb-6">Arama kriterlerinizle eşleşen kaynak bulunamadı. Lütfen farklı anahtar kelimeler veya filtreler deneyin.</p>
                <button id="clear-filters" class="px-6 py-3 bg-[#2c3e7f] text-white font-medium rounded-xl shadow-md hover:bg-[#1e3370] transition duration-300">
                    Filtreleri Temizle
                </button>
            </div>
        </div>
        
        {{-- Tüm Kategoriler ve Kaynaklar --}}
        @foreach($categories as $category)
        <div class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-[#2c3e7f] flex items-center">
                    <div class="w-6 h-1 bg-[#e43546] rounded-full mr-3"></div>
                    {{ $category->name }} Kaynakları
                </h2>
                <a href="#" class="text-[#2c3e7f] font-medium hover:underline">Tümünü Gör &rarr;</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @php
                    // Bu kategori ve alt kategorilerine ait kaynakları filtrele
                    $categoryResources = $resources->filter(function($resource) use ($category) {
                        return $resource->category_id == $category->id || 
                               $resource->category && $resource->category->parent_id == $category->id;
                    })->take(8);
                @endphp
                
                @forelse($categoryResources as $resource)
                <div class="resource-card bg-white rounded-2xl shadow-lg overflow-hidden transition duration-300 hover:shadow-xl border border-gray-100"
                     data-category="{{ $resource->category_id }}" 
                     data-parent-category="{{ $resource->category->parent_id ?? '' }}" 
                     data-type="{{ $resource->type->slug ?? '' }}"
                     data-title="{{ $resource->title }}"
                     data-description="{{ $resource->description }}">
                    <div class="h-40 bg-gradient-to-r from-blue-200 to-indigo-100 relative overflow-hidden">
                        @if($resource->image_path)
                            <img src="{{ asset('storage/' . $resource->image_path) }}" alt="{{ $resource->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 bg-[#2c3e7f] text-white text-xs font-bold rounded-full">{{ $resource->type->name ?? 'Kaynak' }}</span>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-lg text-[#2c3e7f] mb-2 line-clamp-2">{{ $resource->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $resource->description }}</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $resource->category->name }}</span>
                            </div>
                            <a href="{{ route('public.resources.show', $resource->slug) }}" class="px-4 py-2 bg-gradient-to-r from-[#2c3e7f] to-[#264285] text-white text-sm font-medium rounded-lg hover:from-[#264285] hover:to-[#1e3370] transition duration-300">
                                İncele
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500">{{ $category->name }} kategorisinde kaynak bulunamadı.</p>
                </div>
                @endforelse
            </div>
            
            {{-- Alt Kategorileri Göster --}}

        </div>
        @endforeach
        
        {{-- Newsletter --}}
        <div class="max-w-4xl mx-auto bg-gradient-to-r from-[#2c3e7f] to-[#1e3370] rounded-2xl shadow-xl p-10 mb-16 text-white overflow-hidden relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-white opacity-10 rounded-full -ml-10 -mb-10"></div>
            <div class="relative z-10">
                <div class="text-center mb-8">
                    <h3 class="text-3xl font-bold mb-3">Yeni Kaynaklar Eklendiğinde Haberdar Olun</h3>
                    <p class="text-blue-100 text-lg max-w-2xl mx-auto">Email adresinizi bırakarak yeni eklenen kaynaklardan anında haberdar olabilirsiniz.</p>
                </div>
                <div class="flex flex-col md:flex-row gap-4 max-w-xl mx-auto">
                    <input type="email" placeholder="Email adresinizi giriniz" class="w-full md:flex-1 px-5 py-4 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#e43546]">
                    <button class="bg-[#e43546] text-white font-bold px-8 py-4 rounded-xl hover:bg-[#c52e3d] transition duration-300 shadow-md">Abone Ol</button>
                </div>
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

.hide-scrollbar::-webkit-scrollbar {
    display: none;
}

.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.pulse-animation {
    animation: pulse 0.5s ease-in-out;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
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

.slide-up {
    animation: slideUp 0.5s ease-out forwards;
}

@keyframes slideUp {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
<script>

// Kategoriler verisini JavaScript'e aktarma
// Kategoriler verisini JavaScript'e aktarma
const categoriesData = @json($categories);

document.addEventListener('DOMContentLoaded', function() {
    // DOM Elementlerini Seçme
    const mainCategoryFilter = document.getElementById('main-category-filter');
    const subCategoryFilter = document.getElementById('sub-category-filter');
    const resourceTypeFilter = document.getElementById('resource-type');
    const filterButton = document.getElementById('filter-button');
    const resetButton = document.getElementById('reset-filters');
    const clearButton = document.getElementById('clear-filters');
    const searchButton = document.getElementById('search-button');
    const searchInput = document.getElementById('search-resources');
    const popularSection = document.getElementById('popular-resources-section');
    const filteredResultsSection = document.getElementById('filtered-results');
    const resourcesContainer = document.getElementById('resources-container');
    const noResultsMessage = document.getElementById('no-results');
    const scrollContainer = document.getElementById('popular-resources-slider');
    const leftBtn = document.getElementById('slideLeft');
    const rightBtn = document.getElementById('slideRight');
    const categoryLinks = document.querySelectorAll('.category-link');
    const filterContainer = document.querySelector('.bg-white.rounded-2xl.shadow-xl.p-6.mb-16');
    
    // Tüm kaynaklar ve kategori bölümleri - FİLTRELEME KUTUSUNU HARİÇ TUTMA
    const allResources = document.querySelectorAll('.resource-card');
    const resourceCards = document.querySelectorAll('.bg-white.rounded-2xl');
    
    // Kategori containerları - filtreleme kutusunu HARİÇ tutarak
    const categoryContainers = Array.from(document.querySelectorAll('.mb-16')).filter(container => {
        // Filtreleme kutusunu ve filtered-results bölümünü hariç tut
        return container !== filterContainer && container.id !== 'filtered-results';
    });
    
    // Kategori linklerine event listener ekle
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const categoryId = this.dataset.category;
            const subcategoryId = this.dataset.subcategory;
            
            // Filtreleri ayarla
            if (mainCategoryFilter) mainCategoryFilter.value = categoryId;
            updateSubCategories(categoryId);
            
            // Alt kategori seçiliyse onu da ayarla (timeout ile bekleyerek alt kategorilerin yüklenmesini sağla)
            if (subcategoryId && subCategoryFilter) {
                setTimeout(() => {
                    subCategoryFilter.value = subcategoryId;
                    applyFilters();
                }, 100);
            } else {
                applyFilters();
            }
        });
    });
    
    // Filtre seçeneklerinde değişiklik olduğunda animasyon
    const filterSelects = document.querySelectorAll('select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.classList.add('pulse-animation');
            setTimeout(() => {
                this.classList.remove('pulse-animation');
            }, 500);
        });
    });
    
    // Scroll butonları için event listener'lar
    if (scrollContainer && leftBtn && rightBtn) {
        leftBtn.addEventListener('click', function() {
            scrollContainer.scrollBy({ left: -350, behavior: 'smooth' });
        });
        
        rightBtn.addEventListener('click', function() {
            scrollContainer.scrollBy({ left: 350, behavior: 'smooth' });
        });
    }
    
    // Görünüm animasyonu için IntersectionObserver
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    resourceCards.forEach(card => {
        observer.observe(card);
    });
    
    // Ana kategori değiştiğinde alt kategorileri güncelle
    if (mainCategoryFilter && subCategoryFilter) {
        mainCategoryFilter.addEventListener('change', function() {
            updateSubCategories(this.value);
        });
    }
    
    // Alt kategorileri güncelleme fonksiyonu
    function updateSubCategories(mainCategoryId) {
        // Alt kategori seçimini temizle
        subCategoryFilter.innerHTML = '<option value="">Alt Kategori</option>';
        
        if (!mainCategoryId) return;
        
        // Seçilen ana kategorinin alt kategorilerini bul
        const selectedCategory = categoriesData.find(cat => cat.id == mainCategoryId);
        
        if (selectedCategory && selectedCategory.children) {
            selectedCategory.children.forEach(subCategory => {
                const option = document.createElement('option');
                option.value = subCategory.id;
                option.textContent = subCategory.name;
                subCategoryFilter.appendChild(option);
            });
        }
    }
    
    // Arama işlemi için
    if (searchButton) {
        searchButton.addEventListener('click', function() {
            performSearch();
        });
    }
    
    // Enter tuşu ile arama
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }
    
    // Filtreleme işlemi
    if (filterButton) {
        filterButton.addEventListener('click', function() {
            applyFilters();
        });
    }
    
    // Filtreleri sıfırlama
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            resetFilters();
        });
    }
    
    // Filtreleri temizleme (sonuç bulunamadı ekranından)
    if (clearButton) {
        clearButton.addEventListener('click', function() {
            resetFilters();
        });
    }
    
    function performSearch() {
        const searchTerm = searchInput ? searchInput.value.trim().toLowerCase() : '';
        if (searchTerm) {
            // Arama terimini uygula
            filterResources({ searchTerm });
        }
    }
    
    function applyFilters() {
        const mainCategoryId = mainCategoryFilter ? mainCategoryFilter.value : '';
        const subCategoryId = subCategoryFilter ? subCategoryFilter.value : '';
        const resourceType = resourceTypeFilter ? resourceTypeFilter.value : '';
        const searchTerm = searchInput ? searchInput.value.trim().toLowerCase() : '';
        
        // Filtreleri uygula
        filterResources({
            mainCategoryId,
            subCategoryId,
            resourceType,
            searchTerm
        });
    }
    
    function resetFilters() {
        // Input ve select değerlerini sıfırla
        if (searchInput) {
            searchInput.value = '';
        }
        
        if (mainCategoryFilter) {
            mainCategoryFilter.selectedIndex = 0;
        }
        
        if (subCategoryFilter) {
            subCategoryFilter.innerHTML = '<option value="">Alt Kategori</option>';
        }
        
        if (resourceTypeFilter) {
            resourceTypeFilter.selectedIndex = 0;
        }
        
        // Filtreleme sonuçlarını gizle
        if (filteredResultsSection) {
            filteredResultsSection.classList.add('hidden');
        }
        
        // Tüm kategori bölümlerini göster
        categoryContainers.forEach(section => {
            section.classList.remove('hidden');
        });
        
        // Popüler kaynaklar bölümünü göster
        if (popularSection) {
            popularSection.classList.remove('hidden');
        }
        
        // Sayfanın başına scroll
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    function filterResources(filters) {
        if (!allResources.length || !filters) return;
        
        // Filtrelenen kaynakları tutacak dizi
        let filteredResources = [];
        
        // Tüm kaynakları filtrelerle karşılaştır
        allResources.forEach(resource => {
            const category = resource.dataset.category;
            const parentCategory = resource.dataset.parentCategory;
            const type = resource.dataset.type;
            const title = resource.dataset.title ? resource.dataset.title.toLowerCase() : '';
            const description = resource.dataset.description ? resource.dataset.description.toLowerCase() : '';
            
            let matchesCategory = true;
            let matchesType = true;
            let matchesSearch = true;
            
            // Ana kategori ve alt kategori kontrolü
            if (filters.mainCategoryId && filters.mainCategoryId !== '') {
                if (filters.subCategoryId && filters.subCategoryId !== '') {
                    // Alt kategori seçilmişse, direkt o kategori id'si ile eşleşmeli
                    matchesCategory = (category === filters.subCategoryId);
                } else {
                    // Alt kategori seçilmemişse, ana kategori ile eşleşmeli
                    // veya ana kategorinin bir alt kategorisi olmalı
                    matchesCategory = (category === filters.mainCategoryId || parentCategory === filters.mainCategoryId);
                }
            }
            
            // Kaynak türü kontrolü
            if (filters.resourceType && filters.resourceType !== '') {
                matchesType = (type === filters.resourceType);
            }
            
            // Arama terimi kontrolü
            if (filters.searchTerm && filters.searchTerm !== '') {
                matchesSearch = (title.includes(filters.searchTerm) || description.includes(filters.searchTerm));
            }
            
            // Tüm filtrelere uyuyorsa listeye ekle
            if (matchesCategory && matchesType && matchesSearch) {
                filteredResources.push(resource);
            }
        });
        
        // DİĞER KATEGORİLERİ GİZLE - FAKAT FİLTRELEME KUTUSUNU KORUDUĞUMUZDAN EMİN OL
        categoryContainers.forEach(section => {
            section.classList.add('hidden');
        });
        
        // Popüler kaynaklar bölümünü gizle
        if (popularSection) {
            popularSection.classList.add('hidden');
        }
        
        // FİLTRELEME KUTUSUNUN GÖRÜNÜR KALDIĞINDAN EMİN OL
        if (filterContainer) {
            filterContainer.classList.remove('hidden');
        }
        
        // Sonuçları göster
        displayFilteredResults(filteredResources, filters);
    }
    
    function displayFilteredResults(resources, filters) {
        if (!resourcesContainer || !filteredResultsSection || !noResultsMessage) return;
        
        // Sonuç container'ını temizle
        resourcesContainer.innerHTML = '';
        
        // Başlığı güncelle
        const filteredTitle = document.querySelector('#filtered-results h2');
        if (filteredTitle) {
            let titleText = 'Arama Sonuçları';
            
            // Başlığı, filtreleme türüne göre ayarlayalım
            if (filters.searchTerm && filters.searchTerm !== '') {
                titleText = `"${filters.searchTerm}" için Arama Sonuçları`;
            } else if (filters.mainCategoryId && filters.mainCategoryId !== '') {
                // Kategori adını alalım
                const selectedCategory = mainCategoryFilter.options[mainCategoryFilter.selectedIndex].text;
                
                if (filters.subCategoryId && filters.subCategoryId !== '') {
                    // Alt kategori adını alalım
                    const selectedSubCategory = subCategoryFilter.options[subCategoryFilter.selectedIndex].text;
                    titleText = `${selectedSubCategory} Kaynakları`;
                } else {
                    titleText = `${selectedCategory} Kaynakları`;
                }
                
                // Eğer ayrıca kaynak türü de seçilmişse
                if (filters.resourceType && filters.resourceType !== '') {
                    const selectedType = resourceTypeFilter.options[resourceTypeFilter.selectedIndex].text;
                    titleText += ` - ${selectedType}`;
                }
            } else if (filters.resourceType && filters.resourceType !== '') {
                const selectedType = resourceTypeFilter.options[resourceTypeFilter.selectedIndex].text;
                titleText = `${selectedType} Kaynakları`;
            }
            
            filteredTitle.innerHTML = `
                <div class="w-6 h-1 bg-[#e43546] rounded-full mr-3"></div>
                ${titleText}
            `;
        }
        
        // Kaynak varsa göster, yoksa "bulunamadı" mesajı
        if (resources.length > 0) {
            // Filtrelenmiş sonuçlar bölümünü göster
            filteredResultsSection.classList.remove('hidden');
            noResultsMessage.classList.add('hidden');
            resourcesContainer.classList.remove('hidden');
            
            // Her kaynağı container'a ekle
            resources.forEach((resource, index) => {
                const clone = resource.cloneNode(true);
                clone.classList.add('slide-up');
                clone.style.animationDelay = `${index * 0.1}s`; // Her kart için kademeli animasyon
                resourcesContainer.appendChild(clone);
            });
            
            // Sonuçlara scroll (filtreleme kutusunun altına)
            setTimeout(() => {
                filteredResultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        } else {
            // Sonuç yoksa mesaj göster
            filteredResultsSection.classList.remove('hidden');
            resourcesContainer.classList.add('hidden');
            noResultsMessage.classList.remove('hidden');
            
            // Mesaja scroll
            setTimeout(() => {
                noResultsMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);
        }
    }
});
    </script>

    @endsection