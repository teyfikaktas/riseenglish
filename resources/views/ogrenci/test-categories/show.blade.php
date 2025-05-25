@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#1a2e5a] via-[#2a4073] to-[#1a2e5a]">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col space-y-6">
            <!-- Başlık ve Geri Dön Butonu -->
            <div class="flex items-center">
                <a href="{{ route('ogrenci.test-categories.index') }}" class="flex items-center text-white hover:text-[#e63946] mr-4 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="text-sm font-medium">Kategorilere Dön</span>
                </a>
                <div class="flex items-center space-x-2">
                    <div class="bg-[#e63946] px-3 py-1 rounded-md">
                        <span class="text-white text-sm font-semibold">{{ $category->icon ?: '📝' }} {{ strtoupper($category->name) }}</span>
                    </div>
                </div>
            </div>

            <!-- Kategori Başlığı ve Açıklaması -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mr-4">
                        <span class="text-3xl">{{ $category->icon ?: '📝' }}</span>
                    </div>
                    <div class="text-left">
                        <h1 class="text-4xl md:text-5xl font-bold text-white">
                            {{ $category->name }}
                        </h1>
                        <p class="text-lg text-gray-300">
                            {{ $category->description }}
                        </p>
                    </div>
                </div>
                
                <!-- Kategori İstatistikleri -->
                <div class="bg-white rounded-xl p-4 inline-block border-2 border-[#1a2e5a]">
                    <div class="flex items-center space-x-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#1a2e5a]">{{ $category->tests->count() }}</div>
                            <div class="text-sm text-[#1a2e5a]">Test</div>
                        </div>
                        <div class="w-px h-8 bg-[#1a2e5a] opacity-30"></div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#1a2e5a]">{{ $category->tests->sum('questions_count') }}</div>
                            <div class="text-sm text-[#1a2e5a]">Soru</div>
                        </div>
                        <div class="w-px h-8 bg-[#1a2e5a] opacity-30"></div>
                        <div class="text-center">
                            <div class="text-sm font-bold">
                                <span class="px-3 py-1 rounded text-white font-medium
                                    @if($category->difficulty_level === 'Kolay')
                                        bg-green-500
                                    @elseif($category->difficulty_level === 'Orta')
                                        bg-yellow-500
                                    @elseif($category->difficulty_level === 'Zor')
                                        bg-red-500
                                    @elseif($category->difficulty_level === 'Kolay-Orta')
                                        bg-blue-500
                                    @elseif($category->difficulty_level === 'Orta-Zor')
                                        bg-orange-500
                                    @else
                                        bg-purple-500
                                    @endif
                                ">
                                    {{ $category->difficulty_level ?: 'Karma' }}
                                </span>
                            </div>
                            <div class="text-sm text-[#1a2e5a] mt-1">Zorluk</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Testler Listesi -->
            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-white mb-6">📝 Mevcut Testler</h2>
                
                @forelse($category->tests as $test)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 p-6 border-l-4 border-[#e63946] border-2 border-[#1a2e5a]">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <!-- Test Bilgileri -->
                            <div class="flex-1 mb-4 md:mb-0">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-[#1a2e5a] mb-2">{{ $test->title }}</h3>
                                        <p class="text-gray-600 text-sm mb-3">{{ $test->description }}</p>
                                        
                                        <!-- Test Özellikleri -->
                                        <div class="flex flex-wrap items-center gap-3 text-xs">
                                            <span class="bg-[#1a2e5a] text-white px-2 py-1 rounded flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                                </svg>
                                                {{ $test->questions_count }} Soru
                                            </span>
                                            
                                            @if($test->duration_minutes)
                                                <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $test->duration_minutes }} Dakika
                                                </span>
                                            @endif
                                            
                                            @if($test->difficulty_level)
                                                <span class="px-2 py-1 rounded
                                                    @if($test->difficulty_level === 'Kolay')
                                                        bg-green-100 text-green-600
                                                    @elseif($test->difficulty_level === 'Orta')
                                                        bg-yellow-100 text-yellow-600
                                                    @elseif($test->difficulty_level === 'Zor')
                                                        bg-red-100 text-red-600
                                                    @elseif($test->difficulty_level === 'Orta-Zor')
                                                        bg-orange-100 text-orange-600
                                                    @else
                                                        bg-purple-100 text-purple-600
                                                    @endif
                                                ">
                                                    {{ $test->difficulty_level }}
                                                </span>
                                            @endif
                                            
                                            @if($test->is_featured)
                                                <span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                    </svg>
                                                    Öne Çıkan
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Test Butonları -->
                            <div class="flex flex-col sm:flex-row gap-3 md:ml-6">
                                <a href="{{ route('ogrenci.tests.show', $test->slug) }}" 
                                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-center text-sm font-medium">
                                    📊 Detaylar
                                </a>
                                <a href="{{ route('ogrenci.tests.start', $test->slug) }}" 
                                   class="px-6 py-2 bg-[#e63946] hover:bg-[#d52936] text-white rounded-lg transition text-center font-medium">
                                    🚀 Teste Başla
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="text-white mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Bu Kategoride Test Bulunmuyor</h3>
                        <p class="text-gray-300 mb-4">{{ $category->name }} kategorisinde henüz test eklenmemiş.</p>
                        <a href="{{ route('ogrenci.test-categories.index') }}" 
                           class="inline-block px-6 py-2 bg-white text-[#1a2e5a] rounded-lg hover:bg-gray-100 transition font-medium">
                            Diğer Kategorileri Gör
                        </a>
                    </div>
                @endforelse
            </div>
            
            <!-- Bilgi Notu -->
            <div class="p-6 bg-white rounded-xl border-2 border-[#1a2e5a] shadow-md">
                <div class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#1a2e5a] mr-3 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="font-bold text-[#1a2e5a] mb-2">{{ $category->name }} Hakkında</h3>
                        <p class="text-sm text-gray-700">{{ $category->description }} Bu kategorideki testleri çözerek {{ strtolower($category->name) }} konusundaki bilginizi geliştirebilir ve sınav performansınızı artırabilirsiniz.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Test kartlarına hover efekti
    document.querySelectorAll('.bg-white.rounded-lg').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
</script>
@endsection