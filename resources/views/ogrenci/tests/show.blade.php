{{-- resources/views/ogrenci/tests/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#1a2e5a] via-[#2a4073] to-[#1a2e5a]">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col space-y-6">
            <!-- Başlık ve Geri Dön Butonu -->
            <div class="flex items-center">
                @if($test->categories->first())
                    <a href="{{ route('ogrenci.test-categories.show', $test->categories->first()->slug) }}" class="flex items-center text-white hover:text-[#e63946] mr-4 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span class="text-sm font-medium">{{ $test->categories->first()->name }} Testlerine Dön</span>
                    </a>
                @else
                    <a href="{{ route('ogrenci.test-categories.index') }}" class="flex items-center text-white hover:text-[#e63946] mr-4 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span class="text-sm font-medium">Kategorilere Dön</span>
                    </a>
                @endif
                
                <div class="flex items-center space-x-2">
                    <div class="bg-[#e63946] px-3 py-1 rounded-md">
                        <span class="text-white text-sm font-semibold">📝 TEST DETAYI</span>
                    </div>
                    @if($test->is_featured)
                        <div class="bg-yellow-500 px-2 py-1 rounded-md">
                            <span class="text-white text-xs font-semibold">⭐ ÖNE ÇIKAN</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Test Başlığı ve Açıklaması -->
            <div class="text-center mb-8">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    {{ $test->title }}
                </h1>
                <p class="text-lg text-gray-300 max-w-3xl mx-auto">
                    {{ $test->description }}
                </p>
                
                <!-- Test Kategorileri -->
                @if($test->categories->count() > 0)
                    <div class="flex flex-wrap justify-center gap-2 mt-4">
                        @foreach($test->categories as $category)
                            <span class="bg-white text-[#1a2e5a] px-3 py-1 rounded-full text-sm border border-[#1a2e5a] font-medium">
                                {{ $category->icon ?: '📂' }} {{ $category->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Test İstatistikleri -->
            <div class="bg-white rounded-xl p-6 border-2 border-[#1a2e5a] shadow-lg">
                <h3 class="text-lg font-bold text-[#1a2e5a] mb-4 text-center">📊 Test Bilgileri</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-[#1a2e5a]">{{ $test->questions->count() }}</div>
                        <div class="text-sm text-gray-600">Toplam Soru</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">
                            {{ $test->duration_minutes ?: 'Sınırsız' }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ $test->duration_minutes ? 'Dakika' : 'Süre' }}
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">
                            <span class="px-3 py-1 rounded
                                @if($test->difficulty_level === 'Kolay')
                                    bg-green-100 text-green-600
                                @elseif($test->difficulty_level === 'Orta')
                                    bg-yellow-100 text-yellow-600
                                @elseif($test->difficulty_level === 'Zor')
                                    bg-red-100 text-red-600
                                @elseif($test->difficulty_level === 'Orta-Zor')
                                    bg-orange-100 text-orange-600
                                @else
                                    bg-blue-100 text-blue-600
                                @endif
                            ">
                                {{ $test->difficulty_level ?: 'Karma' }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-600 mt-2">Zorluk Seviyesi</div>
                    </div>
                    <div class="text-center">
                        @php
                            $userResults = $test->userResults()->where('user_id', auth()->id())->completed()->count();
                        @endphp
                        <div class="text-3xl font-bold text-purple-600">{{ $userResults }}</div>
                        <div class="text-sm text-gray-600">Çözüm Sayınız</div>
                    </div>
                </div>
            </div>

            <!-- Test Açıklaması ve Kurallar -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Test Hakkında -->
                <div class="bg-white rounded-xl p-6 border-2 border-[#1a2e5a] shadow-md">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#1a2e5a] mr-3 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="font-bold text-[#1a2e5a] mb-2">{{ $test->title }} Hakkında</h3>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                {{ $test->description }} Bu test ile bilginizi ölçebilir ve eksik konularınızı belirleyebilirsiniz.
                            </p>
                            
                            @if($test->instructions)
                                <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <h4 class="font-semibold text-blue-800 mb-1">📋 Test Talimatları</h4>
                                    <p class="text-sm text-blue-700">{{ $test->instructions }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Test Kuralları -->
                <div class="bg-white rounded-xl p-6 border-2 border-[#e63946] shadow-md">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#e63946] mr-3 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="font-bold text-[#e63946] mb-2">⚠️ Test Kuralları</h3>
                            <ul class="text-sm text-gray-700 space-y-2">
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2">✓</span>
                                    Her soru için sadece bir doğru cevap vardır
                                </li>
                                @if($test->duration_minutes)
                                    <li class="flex items-start">
                                        <span class="text-green-500 mr-2">✓</span>
                                        Test süresi {{ $test->duration_minutes }} dakikadır
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-red-500 mr-2">⚠</span>
                                        Süre dolduğunda testi bitire basarak tamamlanır.
                                    </li>
                                @endif
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2">✓</span>
                                    Test bittiğinde detaylı sonuç alacaksınız
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2">✓</span>
                                    Aynı testi istediğiniz kadar tekrar çözebilirsiniz
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Son Sonuçlarım -->
            @php
                $recentResults = $test->userResults()
                    ->where('user_id', auth()->id())
                    ->completed()
                    ->latest()
                    ->take(3)
                    ->get();
            @endphp

            @if($recentResults->count() > 0)
                <div class="bg-white rounded-xl p-6 border-2 border-[#1a2e5a] shadow-md">
                    <h3 class="font-bold text-[#1a2e5a] mb-4">📈 Son Sonuçlarım</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($recentResults as $result)
                            <div class="bg-gray-50 rounded-lg p-4 border">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">{{ $result->created_at->format('d.m.Y H:i') }}</span>
                                    <span class="text-lg font-bold 
                                        @if($result->percentage >= 80) text-green-600
                                        @elseif($result->percentage >= 60) text-yellow-600
                                        @else text-red-600
                                        @endif
                                    ">
                                        %{{ number_format($result->percentage, 1) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-700">
                                    {{ $result->correct_answers }}/{{ $result->total_questions }} Doğru
                                </div>
                                <div class="text-xs text-gray-500">
                                    Süre: {{ gmdate('H:i:s', $result->duration_seconds) }}
                                </div>
                                <a href="{{ route('ogrenci.tests.result', $result->id) }}" 
                                   class="mt-2 inline-block text-xs bg-[#1a2e5a] text-white px-2 py-1 rounded hover:bg-[#0f1b3d] transition">
                                    Detayları Gör
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Teste Başla Butonu -->
            <div class="text-center">
                <a href="{{ route('ogrenci.tests.start', $test->slug) }}" 
                   class="inline-flex items-center px-8 py-4 bg-[#e63946] hover:bg-[#d52936] text-white font-bold rounded-xl transition text-lg shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    🚀 Teste Başla
                </a>
                
                @if($recentResults->count() > 0)
                    <div class="mt-4">
                        <a href="{{ route('ogrenci.tests.history') }}" 
                           class="text-[#1a2e5a] hover:text-[#e63946] transition text-sm bg-white px-4 py-2 rounded-lg">
                            📊 Tüm Test Geçmişimi Gör
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Hover efektleri
    document.querySelectorAll('.bg-white.rounded-xl').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
</script>
@endsection