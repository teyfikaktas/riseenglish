{{-- resources/views/useful-resources/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-blue-50 to-white min-h-screen"
     style="background-image: url('{{ asset('images/free.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed; background-blend-mode: multiply;">
    
    <!-- Arka plan üzerinde overlay (okunabilirlik için) -->
    <div class="absolute inset-0 bg-white opacity-75"></div>
    
    <div class="container mx-auto px-4 py-12 relative z-10">
        <div class="text-center mb-12">
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-4xl mx-auto border border-gray-100">
                <h1 class="text-5xl font-bold text-[#2c3e7f] mb-4">Faydalı Kaynaklar</h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">İngilizce öğrenim sürecinizde size yardımcı olacak kapsamlı kaynaklarımızla dil becerilerinizi geliştirin.</p>
            </div>
        </div>
        
        {{-- Geliştirilmiş Filtreler --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-16 max-w-4xl mx-auto transition duration-300 hover:shadow-2xl border border-gray-100">
            <h3 class="text-2xl font-bold text-[#2c3e7f] mb-6 text-center">Kaynakları Filtrele</h3>
            
            {{-- Arama Kutusu --}}
            <div class="mb-6">
                <div class="relative">
                    <input type="text" id="search-resources" placeholder="Kaynak ara..." 
                           value="{{ request('search') }}"
                           class="w-full border-0 bg-gray-50 rounded-xl p-4 pl-12 shadow-sm focus:border-[#2c3e7f] focus:ring focus:ring-[#2c3e7f] focus:ring-opacity-50">
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
                        <select id="category-filter" class="w-full border-0 bg-gray-50 rounded-xl p-4 pr-10 shadow-sm focus:border-[#2c3e7f] focus:ring focus:ring-[#2c3e7f] focus:ring-opacity-50 appearance-none">
                            <option value="">Tüm Kategoriler</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->category }}" {{ request('category') == $category->category ? 'selected' : '' }}>
                                    {{ ucfirst($category->category) }} ({{ $category->count }})
                                </option>
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
                        <select id="sort-filter" class="w-full border-0 bg-gray-50 rounded-xl p-4 pr-10 shadow-sm focus:border-[#2c3e7f] focus:ring focus:ring-[#2c3e7f] focus:ring-opacity-50 appearance-none">
                            <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Varsayılan Sıralama</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>En Popüler</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>En Yeni</option>
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Alfabetik</option>
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
                        <select id="show-filter" class="w-full border-0 bg-gray-50 rounded-xl p-4 pr-10 shadow-sm focus:border-[#2c3e7f] focus:ring focus:ring-[#2c3e7f] focus:ring-opacity-50 appearance-none">
                            <option value="all">Tüm Kaynaklar</option>
                            <option value="popular">Sadece Popüler</option>
                            <option value="recent">Son Eklenenler</option>
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
                <a href="{{ route('useful-resources.index') }}" class="ml-4 px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl shadow-md hover:bg-gray-200 transition duration-300">
                    Sıfırla
                </a>
            </div>
        </div>

        {{-- Popüler Kaynaklar Slider --}}
        @if($popularResources->count() > 0)
        <div class="mb-16">
            <div class="flex items-center justify-center mb-6">
                <div class="bg-white rounded-2xl shadow-lg p-4 border border-gray-100">
                    <h2 class="text-3xl font-bold text-[#2c3e7f] flex items-center">
                        <div class="w-6 h-1 bg-[#e43546] rounded-full mr-3"></div>
                        Popüler Kaynaklar
                    </h2>
                </div>
                <div class="flex space-x-2 ml-4">
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
                    <div class="resource-card flex-shrink-0 w-80 bg-white rounded-2xl shadow-lg overflow-hidden transition duration-300 hover:shadow-xl border border-gray-100">
                        <div class="h-40 bg-gradient-to-r from-blue-200 to-indigo-100 relative overflow-hidden flex items-center justify-center">
                            <!-- Logo -->
                            <img src="{{ asset('images/imgt.jpg') }}" 
                                 alt="Logo" 
                                 class="center object-contain opacity-80"">
                            
                            <!-- Kategori Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="px-3 py-1 bg-[#2c3e7f] text-white text-xs font-bold rounded-full">{{ ucfirst($resource->category) }}</span>
                            </div>
                            
                            <!-- Popüler Badge -->
                            @if($resource->is_popular)
                            <div class="absolute top-3 left-3">
                                <span class="px-2 py-1 bg-[#e43546] text-white text-xs font-bold rounded-full">Popüler</span>
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
                                    {{ $resource->view_count }} görüntüleme
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                    </svg>
                                    {{ $resource->file_size_human }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ ucfirst(str_replace('-', ' ', $resource->category)) }}</span>
                                <a href="{{ route('useful-resources.show', $resource->slug) }}" class="px-4 py-2 bg-gradient-to-r from-[#2c3e7f] to-[#264285] text-white text-sm font-medium rounded-lg hover:from-[#264285] hover:to-[#1e3370] transition duration-300">
                                    İncele
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        
        {{-- Ana Kaynaklar Listesi --}}
        <div class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <div class="bg-white rounded-2xl shadow-lg p-4 border border-gray-100">
                    <h2 class="text-3xl font-bold text-[#2c3e7f] flex items-center">
                        <div class="w-6 h-1 bg-[#e43546] rounded-full mr-3"></div>
                        @if(request('category'))
                            {{ ucfirst(str_replace('-', ' ', request('category'))) }} Kaynakları
                        @elseif(request('search'))
                            "{{ request('search') }}" Arama Sonuçları
                        @else
                            Tüm Kaynaklar
                        @endif
                        <span class="ml-2 text-sm text-gray-500">({{ $resources->total() }} sonuç)</span>
                    </h2>
                </div>
            </div>
            
            @if($resources->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($resources as $resource)
                <div class="resource-card bg-white rounded-2xl shadow-lg overflow-hidden transition duration-300 hover:shadow-xl border border-gray-100 animate-fade-in">
                    <div class="h-40 bg-gradient-to-r from-blue-200 to-indigo-100 relative overflow-hidden flex items-center justify-center">
                        <!-- Logo -->
                        <img src="{{ asset('images/imgt.jpg') }}" 
                             alt="Logo" 
                             class=" center object-contain opacity-80">
                        
                        <!-- Kategori Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 bg-[#2c3e7f] text-white text-xs font-bold rounded-full">{{ ucfirst($resource->category) }}</span>
                        </div>
                        
                        <!-- Popüler Badge -->
                        @if($resource->is_popular)
                        <div class="absolute top-3 left-3">
                            <span class="px-2 py-1 bg-[#e43546] text-white text-xs font-bold rounded-full">Popüler</span>
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
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ ucfirst(str_replace('-', ' ', $resource->category)) }}</span>
                            <a href="{{ route('useful-resources.show', $resource->slug) }}" class="px-4 py-2 bg-gradient-to-r from-[#2c3e7f] to-[#264285] text-white text-sm font-medium rounded-lg hover:from-[#264285] hover:to-[#1e3370] transition duration-300">
                                İncele
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            {{-- Pagination --}}
            <div class="mt-8 flex justify-center">
                {{ $resources->links() }}
            </div>
            
            @else
            <div class="bg-white rounded-2xl shadow-lg p-10 text-center">
                <img src="{{ asset('images/imgt.jpg') }}" 
                     alt="Logo" 
                     class="center object-contain opacity-80"">
                <h3 class="text-2xl font-bold text-gray-700 mb-2">Kaynak Bulunamadı</h3>
                <p class="text-gray-500 mb-6">Arama kriterlerinizle eşleşen kaynak bulunamadı. Lütfen farklı anahtar kelimeler deneyin.</p>
                <a href="{{ route('useful-resources.index') }}" class="px-6 py-3 bg-[#2c3e7f] text-white font-medium rounded-xl shadow-md hover:bg-[#1e3370] transition duration-300">
                    Tüm Kaynakları Gör
                </a>
            </div>
            @endif
        </div>
        
        {{-- Newsletter --}}
        <div class="max-w-4xl mx-auto bg-gradient-to-r from-[#2c3e7f] to-[#1e3370] 
                    rounded-2xl shadow-xl p-10 mb-16 text-white overflow-hidden relative">
            <!-- Dekoratif arka plan elementleri -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-20 
                        rounded-full -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-white opacity-20 
                        rounded-full -ml-10 -mb-10"></div>
            
            <!-- İçerik alanı -->
            <div class="relative z-10">
                <!-- Başlık ve açıklama metni -->
                <div class="text-center mb-8">
                    <h3 class="text-3xl font-bold mb-3 text-white">Yeni Kaynaklar Eklendiğinde Haberdar Olun</h3>
                    <p class="text-white text-lg max-w-2xl mx-auto">
                        Email adresinizi bırakarak yeni eklenen kaynaklardan anında haberdar olabilirsiniz.
                    </p>
                </div>
                
                <!-- Form alanı -->
                <div class="flex flex-col md:flex-row gap-4 max-w-xl mx-auto">
                    <input type="email" 
                           placeholder="Email adresinizi giriniz" 
                           class="w-full md:flex-1 px-5 py-4 rounded-xl text-gray-800 
                                  border-2 border-gray-300 bg-white
                                  focus:outline-none focus:ring-2 focus:ring-[#e43546]">
                    <button class="bg-[#e43546] text-white font-bold px-8 py-4 rounded-xl 
                                   hover:bg-[#c52e3d] transition duration-300 shadow-lg
                                   border-2 border-[#ff4757]">
                        Abone Ol
                    </button>
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
    // Filter form functionality
    const searchInput = document.getElementById('search-resources');
    const categoryFilter = document.getElementById('category-filter');
    const sortFilter = document.getElementById('sort-filter');
    const showFilter = document.getElementById('show-filter');
    const filterButton = document.getElementById('filter-button');
    const searchButton = document.getElementById('search-button');
    
    // Slider functionality
    const scrollContainer = document.getElementById('popular-resources-slider');
    const leftBtn = document.getElementById('slideLeft');
    const rightBtn = document.getElementById('slideRight');
    
    if (scrollContainer && leftBtn && rightBtn) {
        leftBtn.addEventListener('click', function() {
            scrollContainer.scrollBy({ left: -350, behavior: 'smooth' });
        });
        
        rightBtn.addEventListener('click', function() {
            scrollContainer.scrollBy({ left: 350, behavior: 'smooth' });
        });
    }
    
    // Filter functionality
    function applyFilters() {
        const searchTerm = searchInput.value;
        const category = categoryFilter.value;
        const sort = sortFilter.value;
        const show = showFilter.value;
        
        const params = new URLSearchParams();
        if (searchTerm) params.append('search', searchTerm);
        if (category) params.append('category', category);
        if (sort && sort !== 'default') params.append('sort', sort);
        if (show && show !== 'all') params.append('show', show);
        
        const url = '{{ route("useful-resources.index") }}' + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    }
    
    if (filterButton) {
        filterButton.addEventListener('click', applyFilters);
    }
    
    if (searchButton) {
        searchButton.addEventListener('click', applyFilters);
    }
    
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
    }
});
</script>

@endsection