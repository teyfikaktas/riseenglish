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
            
            <!-- Test Kategorileri Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($categories as $category)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 p-6 border-l-4 border-[#1a2e5a] border-2 border-[#1a2e5a]">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-[#1a2e5a] mb-1">{{ $category->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $category->description }}</p>
                            </div>
                            <div class="w-12 h-12 bg-[#1a2e5a] rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ $category->icon ?: '📝' }}</span>
                            </div>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-4">{{ $category->description }}</p>
                        
                        <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                            <span class="bg-gray-100 px-2 py-1 rounded">
                                {{ $category->questions_count }} Soru • {{ $category->tests_count }} Test
                            </span>
                            <span class="px-2 py-1 rounded 
                                @if($category->difficulty_level === 'Kolay')
                                    bg-green-100 text-green-600
                                @elseif($category->difficulty_level === 'Orta')
                                    bg-yellow-100 text-yellow-600
                                @elseif($category->difficulty_level === 'Zor')
                                    bg-red-100 text-red-600
                                @elseif($category->difficulty_level === 'Kolay-Orta')
                                    bg-blue-100 text-blue-600
                                @elseif($category->difficulty_level === 'Orta-Zor')
                                    bg-orange-100 text-orange-600
                                @else
                                    bg-purple-100 text-purple-600
                                @endif
                            ">
                                {{ $category->difficulty_level ?: 'Karma' }}
                            </span>
                        </div>
                        
                        <a href="{{ route('ogrenci.test-categories.show', $category->slug) }}" 
                           class="block w-full bg-[#1a2e5a] hover:bg-[#0f1b3d] text-white font-medium py-2 px-4 rounded-lg transition text-center">
                            Testleri Görüntüle
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
                            <div class="text-2xl font-bold text-green-600">{{ $categories->sum('tests_count') }}</div>
                            <div class="text-sm text-gray-600">Toplam Test</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $categories->sum('questions_count') }}</div>
                            <div class="text-sm text-gray-600">Toplam Soru</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">
                                @php
                                    $totalMinutes = $categories->sum(function($category) {
                                        return $category->tests->sum('duration_minutes');
                                    });
                                @endphp
                                {{ $totalMinutes ?: '0' }}
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
                        <h3 class="font-bold text-[#1a2e5a] mb-2">Test Kategorileri Hakkında</h3>
                        <p class="text-sm text-gray-700">Her kategori farklı bir İngilizce becerisini ölçmek için tasarlanmıştır. Düzenli olarak test çözerek İngilizce seviyenizi artırabilir ve sınav performansınızı geliştirebilirsiniz. Her test sonrasında detaylı analiz ve açıklamalar sunuyoruz.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Kategori kartlarına hover efekti
    document.querySelectorAll('.bg-white.rounded-lg').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Sayfa yüklendiğinde animasyon
    window.addEventListener('load', function() {
        const cards = document.querySelectorAll('.bg-white.rounded-lg');
        cards.forEach((card, index) => {
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
    });
</script>
@endsection