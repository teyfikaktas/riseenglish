{{-- resources/views/useful-resources/show.blade.php --}}
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
                <a href="{{ route('useful-resources.category', $resource->category) }}" class="hover:text-[#2c3e7f] transition-colors">{{ ucfirst(str_replace('-', ' ', $resource->category)) }}</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#2c3e7f] font-medium">{{ $resource->title }}</span>
            </div>
        </nav>

        <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transition duration-300 hover:shadow-2xl">
            <div class="md:flex">
                <div class="md:flex-shrink-0 relative">
                    <div class="h-full w-full object-cover md:w-36 bg-gradient-to-r from-blue-200 to-indigo-100 flex items-center justify-center">
                        <!-- Ana Logo -->
                        <img src="{{ asset('images/imgt.jpg') }}" 
                             alt="Logo" 
                             class="w-full h-full object-contain opacity-80">
                    </div>
                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-[#2c3e7f]/30 to-transparent"></div>
                    @if($resource->is_popular)
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 bg-[#e43546] text-white text-xs font-bold rounded-full flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            Popüler
                        </span>
                    </div>
                    @endif
                </div>
                
                <div class="p-8 w-full">
                    <div class="flex flex-col md:flex-row md:items-start justify-between mb-6">
                        <div class="flex-1">
                            <div class="uppercase tracking-wide text-sm text-[#2c3e7f] font-bold flex items-center mb-2">
                                <span class="w-2 h-2 bg-[#e43546] rounded-full mr-2"></span>
                                {{ ucfirst(str_replace('-', ' ', $resource->category)) }}
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $resource->title }}</h1>
                            
                            {{-- İstatistikler --}}
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-[#2c3e7f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span>{{ $resource->view_count }} görüntüleme</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-[#2c3e7f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                    </svg>
                                    <span>{{ $resource->download_count }} indirme</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-[#2c3e7f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span>{{ $resource->file_size_human }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-[#2c3e7f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span>{{ strtoupper($resource->file_type) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-end mt-4 md:mt-0">
                            <span class="bg-green-100 text-green-800 text-sm font-medium px-4 py-2 rounded-full flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                                Ücretsiz
                            </span>
                        </div>
                    </div>
                    
                    {{-- Açıklama --}}
                    <div class="mb-8 p-6 bg-gray-50 rounded-xl border border-gray-100">
                        <h3 class="text-lg font-bold text-[#2c3e7f] flex items-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Açıklama
                        </h3>
                        <p class="text-gray-700 leading-relaxed">{{ $resource->description }}</p>
                    </div>
                    
                    {{-- Dosya Bilgileri --}}
                    <div class="mb-8 p-6 bg-blue-50 rounded-xl border border-blue-100">
                        <h3 class="text-lg font-bold text-[#2c3e7f] flex items-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Dosya Bilgileri
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <span class="text-sm text-gray-600">Dosya Adı:</span>
                                <p class="font-medium text-gray-900">{{ $resource->file_name }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Dosya Türü:</span>
                                <p class="font-medium text-gray-900">{{ strtoupper($resource->file_type) }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Dosya Boyutu:</span>
                                <p class="font-medium text-gray-900">{{ $resource->file_size_human }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Kategori:</span>
                                <p class="font-medium text-gray-900">{{ ucfirst(str_replace('-', ' ', $resource->category)) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Aksiyon Butonları --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('useful-resources.index') }}" class="flex items-center text-[#2c3e7f] hover:text-[#1e3370] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kaynaklara dön
                        </a>
                        
                        <div class="flex gap-3">
                            <button onclick="shareResource()" class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-xl shadow-md hover:bg-gray-200 transition duration-300 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                </svg>
                                Paylaş
                            </button>
                            
                            <a href="{{ route('useful-resources.download', $resource->slug) }}" 
                               class="px-6 py-3 bg-gradient-to-r from-[#2c3e7f] to-[#264285] text-white font-medium rounded-xl shadow-md hover:from-[#264285] hover:to-[#1e3370] transition duration-300 transform hover:scale-105 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Kaynağı İndir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- İlgili Kaynaklar --}}
            @if($relatedResources->count() > 0)
            <div class="border-t border-gray-200 p-8">
                <h3 class="text-xl font-bold text-[#2c3e7f] mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    İlgili Kaynaklar
                </h3>
                <div class="flex overflow-x-auto pb-4 hide-scrollbar gap-4">
                    @foreach($relatedResources as $relatedResource)
                        <div class="flex-shrink-0 w-64 bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition duration-300">
                            <div class="h-36 bg-gradient-to-r from-blue-200 to-indigo-100 flex items-center justify-center relative">
                                <!-- Logo -->
                                <img src="{{ asset('images/imgt.jpg') }}" 
                                     alt="Logo" 
                                     class="h-12 w-auto object-contain opacity-80">
                                <div class="absolute top-2 right-2">
                                    <span class="px-2 py-1 bg-[#2c3e7f] text-white text-xs font-bold rounded">{{ ucfirst($relatedResource->category) }}</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $relatedResource->title }}</h4>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $relatedResource->description }}</p>
                                <a href="{{ route('useful-resources.show', $relatedResource->slug) }}" 
                                   class="w-full block text-center py-2 px-3 bg-[#2c3e7f] text-white text-sm rounded-lg hover:bg-[#1e3370] transition">
                                    Görüntüle
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        {{-- Aynı kategorideki diğer kaynaklar --}}
        @php
            $categoryResources = \App\Models\UsefulResource::active()
                ->byCategory($resource->category)
                ->where('id', '!=', $resource->id)
                ->limit(8)
                ->get();
        @endphp
        
        @if($categoryResources->count() > 0)
        <div class="mt-12 max-w-5xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <h3 class="text-2xl font-bold text-[#2c3e7f] mb-6 flex items-center">
                    <div class="w-6 h-1 bg-[#e43546] rounded-full mr-3"></div>
                    {{ ucfirst(str_replace('-', ' ', $resource->category)) }} Kategorisindeki Diğer Kaynaklar
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($categoryResources as $categoryResource)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 hover:shadow-md transition duration-300">
                        <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $categoryResource->title }}</h4>
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                            <span>{{ $categoryResource->view_count }} görüntüleme</span>
                            <span>{{ $categoryResource->file_size_human }}</span>
                        </div>
                        <a href="{{ route('useful-resources.show', $categoryResource->slug) }}" 
                           class="text-[#2c3e7f] hover:text-[#1e3370] text-sm font-medium transition-colors">
                            Görüntüle →
                        </a>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('useful-resources.category', $resource->category) }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#2c3e7f] text-white font-medium rounded-lg hover:bg-[#1e3370] transition duration-300">
                        Tüm {{ ucfirst(str_replace('-', ' ', $resource->category)) }} Kaynaklarını Gör
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
// Paylaşım fonksiyonu
function shareResource() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $resource->title }}',
            text: '{{ $resource->description }}',
            url: window.location.href
        });
    } else {
        // Fallback: URL'yi kopyala
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Kaynak URL\'si kopyalandı!');
        });
    }
}

// Sayfa yüklendiğinde görüntüleme sayısını artır
document.addEventListener('DOMContentLoaded', function() {
    // AJAX ile view count artırma (opsiyonel)
    fetch('{{ route("useful-resources.show", $resource->slug) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ increment_view: true })
    }).catch(() => {
        // Hata durumunda sessizce devam et
    });
});
</script>

@endsection