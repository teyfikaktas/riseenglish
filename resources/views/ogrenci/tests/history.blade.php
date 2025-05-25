@extends('layouts.app')

@section('title', 'Test Geçmişim')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Başlık -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">📚 Test Geçmişim</h1>
        <p class="text-gray-600">Çözdüğünüz testlerin geçmişini ve sonuçlarınızı görüntüleyin</p>
    </div>

    <!-- İstatistikler -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="bg-blue-500 rounded-full p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-blue-600">Toplam Test</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $stats['total_tests'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="bg-green-500 rounded-full p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-green-600">Ortalama Puan</p>
                    <p class="text-2xl font-bold text-green-900">{{ number_format($stats['average_score'], 1) }}%</p>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="bg-purple-500 rounded-full p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-purple-600">En Yüksek Puan</p>
                    <p class="text-2xl font-bold text-purple-900">{{ number_format($stats['best_score'], 1) }}%</p>
                </div>
            </div>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="bg-orange-500 rounded-full p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-orange-600">Toplam Süre</p>
                    <p class="text-2xl font-bold text-orange-900">{{ $stats['total_time_minutes'] }} dk</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">🔍 Filtreleme ve Sıralama</h3>
        
        <form method="GET" action="{{ route('ogrenci.tests.history') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Kategori Filtresi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    <option value="">Tüm Kategoriler</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" {{ $categorySlug == $category->slug ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->tests_count }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Durum Filtresi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Tamamlanan</option>
                    <option value="started" {{ $status == 'started' ? 'selected' : '' }}>Başlatılan</option>
                    <option value="abandoned" {{ $status == 'abandoned' ? 'selected' : '' }}>Terk Edilen</option>
                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Tümü</option>
                </select>
            </div>

            <!-- Sıralama -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sıralama</label>
                <select name="sort" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    <option value="latest" {{ $sortBy == 'latest' ? 'selected' : '' }}>En Yeni</option>
                    <option value="oldest" {{ $sortBy == 'oldest' ? 'selected' : '' }}>En Eski</option>
                    <option value="score_high" {{ $sortBy == 'score_high' ? 'selected' : '' }}>En Yüksek Puan</option>
                    <option value="score_low" {{ $sortBy == 'score_low' ? 'selected' : '' }}>En Düşük Puan</option>
                </select>
            </div>

            <!-- Sayfa Başına -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sayfa Başına</label>
                <select name="per_page" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>

            <div class="md:col-span-2 lg:col-span-4 flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                    🔍 Filtrele
                </button>
                <a href="{{ route('ogrenci.tests.history') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                    🔄 Temizle
                </a>
            </div>
        </form>
    </div>

    <!-- Test Listesi -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                📋 Test Sonuçları 
                @if($results->total() > 0)
                    <span class="text-sm font-normal text-gray-600">({{ $results->total() }} sonuç)</span>
                @endif
            </h3>
        </div>

        @if($results->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($results as $result)
                    <div class="p-6 hover:bg-gray-50 transition duration-200">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                            <!-- Test Bilgileri -->
                            <div class="flex-1">
                                <div class="flex items-start space-x-4">
                                    <!-- Test Durumu İkonu -->
                                    <div class="flex-shrink-0 mt-1">
                                        @if($result->status === 'completed')
                                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        @elseif($result->status === 'started')
                                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                        @else
                                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <!-- Test Adı -->
                                        <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                            {{ $result->test->title }}
                                        </h4>

                                        <!-- Kategori -->
                                        <div class="flex items-center text-sm text-gray-600 mb-2">
                                            @if($result->test->categories->isNotEmpty())
                                                @foreach($result->test->categories as $category)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Kategori Yok
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Test Detayları -->
                                        <div class="flex flex-wrap items-center text-sm text-gray-600 space-x-4">
                                            <span>📅 {{ $result->created_at->format('d.m.Y H:i') }}</span>
                                            @if($result->status === 'completed')
                                                <span>⏱️ {{ $result->formatted_duration }}</span>
                                                <span>❓ {{ $result->total_questions }} soru</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sonuç Bilgileri -->
                            <div class="mt-4 lg:mt-0 lg:ml-6 flex items-center space-x-4">
                                @if($result->status === 'completed')
                                    <!-- Puan -->
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-{{ $result->success_color }}-600">
                                            {{ $result->percentage }}%
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $result->correct_answers }}/{{ $result->total_questions }}
                                        </div>
                                    </div>

                                    <!-- Başarı Seviyesi -->
                                    <div class="text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $result->success_color }}-100 text-{{ $result->success_color }}-800">
                                            {{ $result->success_level === 'excellent' ? 'Mükemmel' : 
                                               ($result->success_level === 'good' ? 'İyi' : 
                                               ($result->success_level === 'average' ? 'Orta' : 'Zayıf')) }}
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $result->grade_level }}
                                        </div>
                                    </div>

                                    <!-- Detaylı Sonuç Butonu -->
                                    <a href="{{ route('ogrenci.tests.result', $result->id) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 text-sm">
                                        📊 Detaylar
                                    </a>
                                @elseif($result->status === 'started')
                                    <span class="text-yellow-600 font-medium">⏳ Devam Et</span>
                                    <a href="{{ route('ogrenci.tests.show', $result->test->slug) }}" 
                                       class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 text-sm">
                                        ▶️ Devam Et
                                    </a>
                                @else
                                    <span class="text-red-600 font-medium">❌ {{ ucfirst($result->status) }}</span>
                                    <a href="{{ route('ogrenci.tests.show', $result->test->slug) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 text-sm">
                                        🔄 Tekrar Çöz
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sayfalama -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $results->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Boş Durum -->
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📝</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Henüz test çözmediniz</h3>
                <p class="text-gray-600 mb-6">İlk testinizi çözmeye başlayın!</p>
                <a href="{{ route('ogrenci.test-categories.index') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-md transition duration-200">
                    🚀 Teste Başla
                </a>
            </div>
        @endif
    </div>

    <!-- Alt Bilgi -->
    @if($results->count() > 0)
        <div class="mt-8 text-center">
            <div class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-200 rounded-lg">
                <span class="text-blue-600 font-medium">
                    🎯 Başarı Oranınız: {{ $stats['success_rate_percentage'] }}% 
                    ({{ $stats['success_rate'] }}/{{ $stats['completed_tests'] }} test)
                </span>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Form otomatik gönderme
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('select[name="category"], select[name="status"], select[name="sort"], select[name="per_page"]');
    
    selects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endpush