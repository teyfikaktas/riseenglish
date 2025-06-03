@extends('layouts.app')

@section('title', 'Test Detayları - ' . $test->title)

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

    <!-- Başlık ve Aksiyon Butonları -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">📝 {{ $test->title }}</h1>
            <div class="flex items-center space-x-3">
                @if($test->is_active)
                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                        Aktif
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full">
                        <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                        Pasif
                    </span>
                @endif
                
                @if($test->difficulty_level)
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                        {{ $test->difficulty_level }}
                    </span>
                @endif
                
                <span class="text-sm text-gray-500">
                    Oluşturulma: {{ $test->created_at->format('d.m.Y H:i') }}
                </span>
            </div>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('admin.tests.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"></path>
                </svg>
                Geri Dön
            </a>
            <a href="{{ route('admin.tests.manage-questions', $test) }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Sorular ({{ $test->questions_count }})
            </a>
            <a href="{{ route('admin.tests.edit', $test) }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Düzenle
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Ana İçerik -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Test Bilgileri -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Test Bilgileri</h3>
                </div>
                <div class="p-6">
                    @if($test->description)
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-2">Açıklama</h4>
                            <p class="text-gray-600 leading-relaxed">{{ $test->description }}</p>
                        </div>
                    @endif

                    <!-- Test Parametreleri -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-3xl font-bold text-blue-600">{{ $test->questions_count }}</div>
                            <div class="text-sm text-gray-600 mt-1">Toplam Soru</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-3xl font-bold text-green-600">{{ $test->user_test_results_count }}</div>
                            <div class="text-sm text-gray-600 mt-1">Çözüm Sayısı</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-3xl font-bold text-purple-600">
                                @if($test->time_limit)
                                    {{ $test->time_limit }}dk
                                @else
                                    ∞
                                @endif
                            </div>
                            <div class="text-sm text-gray-600 mt-1">Süre Sınırı</div>
                        </div>
                        <div class="text-center p-4 bg-orange-50 rounded-lg">
                            <div class="text-3xl font-bold text-orange-600">%{{ $test->passing_score ?? 60 }}</div>
                            <div class="text-sm text-gray-600 mt-1">Geçme Puanı</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Kategorileri -->
            @if($test->categories->count() > 0)
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Test Kategorileri</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($test->categories as $category)
                                <div class="flex items-center p-4 bg-{{ $category->color ?? 'blue' }}-50 rounded-lg border border-{{ $category->color ?? 'blue' }}-200">
                                    <span class="text-2xl mr-3">{{ $category->icon ?? '📚' }}</span>
                                    <div>
                                        <h4 class="font-medium text-{{ $category->color ?? 'blue' }}-800">{{ $category->name }}</h4>
                                        @if($category->difficulty_level)
                                            <span class="text-xs px-2 py-1 bg-{{ $category->color ?? 'blue' }}-100 text-{{ $category->color ?? 'blue' }}-700 rounded-full">
                                                {{ $category->difficulty_level }}
                                            </span>
                                        @endif
                                        @if($category->description)
                                            <p class="text-sm text-{{ $category->color ?? 'blue' }}-600 mt-1">{{ Str::limit($category->description, 100) }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Test Soruları Preview -->
            @if($test->questions->count() > 0)
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Test Soruları</h3>
                        <a href="{{ route('admin.tests.manage-questions', $test) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Tümünü Görüntüle →
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($test->questions->take(5) as $index => $question)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-500">Soru {{ $index + 1 }}</span>
                                        <div class="flex space-x-2">
                                            @foreach($question->categories as $category) 
                                                <span class="text-xs px-2 py-1 bg-{{ $category->color ?? 'gray' }}-100 text-{{ $category->color ?? 'gray' }}-700 rounded-full">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="text-gray-800 mb-2">{{ Str::limit($question->question_text, 150) }}</p>
                                    <div class="text-sm text-gray-500">
                                        <span class="font-medium">{{ $question->points ?? 1 }} puan</span>
                                        @if($question->difficulty_level)
                                            • <span>{{ $question->difficulty_level }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($test->questions->count() > 5)
                                <div class="text-center py-4">
                                    <p class="text-gray-500">ve {{ $test->questions->count() - 5 }} soru daha...</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Test Soruları</h3>
                    </div>
                    <div class="p-6 text-center">
                        <div class="text-4xl mb-4">❓</div>
                        <h4 class="font-medium text-gray-800 mb-2">Henüz soru eklenmemiş</h4>
                        <p class="text-gray-600 mb-4">Bu teste soru ekleyerek başlayın</p>
                        <a href="{{ route('admin.tests.manage-questions', $test) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                            Soru Ekle
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Yan Panel -->
        <div class="space-y-6">
            <!-- Test Ayarları -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Test Ayarları</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Sorular karıştırılsın</span>
                        @if($test->shuffle_questions)
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                ✓ Evet
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">
                                ✗ Hayır
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Sonuçları göster</span>
                        @if($test->show_results)
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                ✓ Evet
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">
                                ✗ Hayır
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Tekrar çözmeye izin ver</span>
                        @if($test->allow_retake)
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                ✓ Evet
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">
                                ✗ Hayır
                            </span>
                        @endif
                    </div>
                    
                    <div class="pt-3 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Sıralama</span>
                            <span class="text-sm font-medium text-gray-800">{{ $test->sort_order }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test İstatistikleri -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">İstatistikler</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $test->user_test_results_count }}</div>
                        <div class="text-sm text-gray-600">Toplam Çözüm</div>
                    </div>
                    
                    @if($test->user_test_results_count > 0)
                        <!-- Bu kısım ileride test sonuçları analizi eklendiğinde doldurulacak -->
                        <div class="text-center text-sm text-gray-500">
                            Detaylı istatistikler yakında...
                        </div>
                    @else
                        <div class="text-center text-sm text-gray-500">
                            Henüz çözüm yok
                        </div>
                    @endif
                </div>
            </div>

            <!-- Hızlı Aksiyonlar -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Hızlı Aksiyonlar</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.tests.manage-questions', $test) }}" 
                       class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                        🔧 Soru Yönetimi
                    </a>
                    <a href="{{ route('admin.tests.edit', $test) }}" 
                       class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                        ✏️ Testi Düzenle
                    </a>
                    @if($test->is_active)
                        <a href="{{ route('ogrenci.tests.show', $test->slug) }}" 
                           target="_blank"
                           class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                            👁️ Öğrenci Görünümü
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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