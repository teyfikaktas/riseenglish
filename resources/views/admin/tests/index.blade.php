@extends('layouts.app')

@section('title', 'Testler')

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
            <h1 class="text-3xl font-bold text-gray-800 mb-2">📝 Testler</h1>
            <p class="text-gray-600">Test koleksiyonunuzu yönetin</p>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('admin.test-dashboard.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('admin.tests.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Yeni Test
            </a>
        </div>
    </div>

    <!-- Testler Listesi -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Test Listesi</h3>
        </div>
        
        <div class="p-6">
            @if($tests->count() > 0)
                <div class="space-y-6">
                    @foreach($tests as $test)
                        <!-- Test Kartı -->
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                            <!-- Test Başlığı ve Durum -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h4 class="text-lg font-semibold text-gray-800">{{ $test->title }}</h4>
                                        
                                        <!-- Durum Badge -->
                                        @if($test->is_active)
                                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                                <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">
                                                <span class="w-2 h-2 bg-red-400 rounded-full mr-1"></span>
                                                Pasif
                                            </span>
                                        @endif
                                        
                                        <!-- Zorluk Seviyesi -->
                                        @if($test->difficulty_level)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                {{ $test->difficulty_level }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($test->description)
                                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($test->description, 150) }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Test Bilgileri -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">{{ $test->questions_count }}</div>
                                    <div class="text-xs text-gray-600">Soru</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600">{{ $test->user_test_results_count }}</div>
                                    <div class="text-xs text-gray-600">Çözüm</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-purple-600">
                                        @if($test->duration_minutes)
                                            {{ $test->duration_minutes }}dk
                                        @else
                                            ∞
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-600">Süre</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-orange-600">%60</div>
                                    <div class="text-xs text-gray-600">Geçme</div>
                                </div>
                            </div>

                            <!-- Kategoriler -->
                            @if($test->categories->count() > 0)
                                <div class="mb-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($test->categories as $category)
                                            <span class="inline-flex items-center px-2 py-1 bg-{{ $category->color ?? 'blue' }}-100 text-{{ $category->color ?? 'blue' }}-800 text-xs rounded-full">
                                                <span class="mr-1">{{ $category->icon ?? '📚' }}</span>
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Test Ayarları -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <!-- Şimdilik mevcut field'lar yok, boş bırakıyoruz -->
                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">
                                    📝 Test Hazır
                                </span>
                            </div>

                            <!-- Aksiyon Butonları -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.tests.show', $test) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        👁 Detay
                                    </a>
                                    <a href="{{ route('admin.tests.manage-questions', $test) }}" 
                                       class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                        ❓ Sorular ({{ $test->questions_count }})
                                    </a>
                                    <a href="{{ route('admin.tests.edit', $test) }}" 
                                       class="text-green-600 hover:text-green-800 text-sm font-medium">
                                        ✏️ Düzenle
                                    </a>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <span class="text-xs text-gray-500">Sıra: {{ $test->sort_order }}</span>
                                    <form action="{{ route('admin.tests.destroy', $test) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Bu testi silmek istediğinizden emin misiniz?\n\nDikkat: Test çözülmüşse silinemez!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 text-sm font-medium"
                                                @if($test->user_test_results_count > 0) 
                                                    disabled 
                                                    title="Bu test öğrenciler tarafından çözülmüş, silinemez"
                                                @endif>
                                            🗑️ Sil
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Sayfalama -->
                @if($tests->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $tests->links() }}
                    </div>
                @endif
            @else
                <!-- Boş Durum -->
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📝</div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Henüz test yok</h3>
                    <p class="text-gray-600 mb-6">İlk testinizi oluşturun</p>
                    <a href="{{ route('admin.tests.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Yeni Test Oluştur
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
        }, 5000);
    });
});
</script>
@endpush
@endsection