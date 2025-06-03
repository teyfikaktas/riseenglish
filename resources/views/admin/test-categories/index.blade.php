@extends('layouts.app')

@section('title', 'Test Kategorileri')

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
            <h1 class="text-3xl font-bold text-gray-800 mb-2">📂 Test Kategorileri</h1>
            <p class="text-gray-600">Test kategorilerini yönetin</p>
        </div>
        
        <a href="{{ route('admin.test-categories.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Yeni Kategori
        </a>
    </div>

    <!-- Kategoriler Listesi -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Kategori Listesi</h3>
        </div>
        
        <div class="p-6">
            @if($categories->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($categories as $category)
                        <!-- Kategori Kartı -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                            <!-- Kategori Başlığı -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <span class="text-2xl">{{ $category->icon ?? '📚' }}</span>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ $category->name }}</h4>
                                        @if($category->difficulty_level)
                                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">
                                                {{ $category->difficulty_level }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Durum Badge -->
                                <div class="flex items-center space-x-2">
                                    @if($category->is_active)
                                        <span class="w-3 h-3 bg-green-400 rounded-full"></span>
                                        <span class="text-xs text-green-600">Aktif</span>
                                    @else
                                        <span class="w-3 h-3 bg-red-400 rounded-full"></span>
                                        <span class="text-xs text-red-600">Pasif</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Açıklama -->
                            @if($category->description)
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                    {{ $category->description }}
                                </p>
                            @endif

                            <!-- İstatistikler -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <div class="flex space-x-4">
                                    <span>{{ $category->tests_count ?? 0 }} Test</span>
                                    <span>{{ $category->questions_count ?? 0 }} Soru</span>
                                </div>
                                <span class="text-xs">Sıra: {{ $category->sort_order }}</span>
                            </div>

                            <!-- Renk Önizlemesi -->
                            <div class="mb-4">
                                <div class="inline-flex items-center px-3 py-1 bg-{{ $category->color ?? 'blue' }}-100 text-{{ $category->color ?? 'blue' }}-800 rounded-lg text-sm">
                                    <span class="mr-2">{{ $category->icon ?? '📚' }}</span>
                                    {{ $category->name }}
                                </div>
                            </div>

                            <!-- Aksiyon Butonları -->
                            <div class="flex items-center justify-between space-x-2">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.test-categories.show', $category) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Detay
                                    </a>
                                    <a href="{{ route('admin.test-categories.edit', $category) }}" 
                                       class="text-green-600 hover:text-green-800 text-sm font-medium">
                                        Düzenle
                                    </a>
                                </div>
                                
                                <form action="{{ route('admin.test-categories.destroy', $category) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Bu kategoriyi silmek istediğinizden emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Sil
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Sayfalama -->
                @if($categories->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $categories->links() }}
                    </div>
                @endif
            @else
                <!-- Boş Durum -->
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📂</div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Henüz kategori yok</h3>
                    <p class="text-gray-600 mb-6">İlk test kategorinizi oluşturun</p>
                    <a href="{{ route('admin.test-categories.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Yeni Kategori Oluştur
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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