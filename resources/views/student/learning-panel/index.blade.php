@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#1a2e5a]">Öğrenme Paneli</h1>
                <div class="px-3 py-1 bg-[#e63946] text-white rounded-full text-xs font-bold">
                    Öğrenci Girişi
                </div>
                        <a href="{{ route('exams.self-create') }}" 
           class="px-3 py-1 bg-[#1a2e5a] text-white rounded-full text-xs font-bold hover:bg-[#2a4a7a] transition-colors">
            📝 Sınav Oluştur
        </a>
            </div>

            <div class="bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] rounded-lg p-4 text-white">
                <h2 class="text-lg font-semibold mb-2">İngilizce öğrenmek için interaktif araçlar</h2>
                <p class="text-sm opacity-90">Farklı oyun modları ve egzersizlerle eğlenerek İngilizce öğrenin!</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Test Kategorileri Kutusu - Aktif (Mavi-Mor) -->
                <a href="{{ route('ogrenci.test-categories.index') }}"
                    class="group bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 overflow-hidden border-0 text-white">
                    <div class="h-36 bg-white/10 backdrop-blur-sm flex items-center justify-center relative">
                        <div class="absolute top-3 right-3">
                            <span class="bg-green-400 text-green-900 px-2 py-1 rounded-full text-xs font-bold">📚
                                Aktif</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-16 w-16 text-white drop-shadow-lg group-hover:scale-110 transition-transform"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-white mb-1">Test Kategorileri</h3>
                        <p class="text-sm text-white/90">İngilizce testleri çözerek bilginizi geliştirin.</p>
                    </div>
                </a>

                <!-- Test Geçmişim & Soru Analizi - Yakında (Yeşil-Turkuaz) -->
                <div
                    class="group bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden cursor-pointer">
                    <div class="h-36 bg-white/10 backdrop-blur-sm flex items-center justify-center relative">
                        <div class="absolute top-3 right-3">
                            <span class="bg-yellow-400 text-yellow-900 px-2 py-1 rounded-full text-xs font-bold">🚀
                                Yakında</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-16 w-16 text-white/80 group-hover:text-white group-hover:scale-110 transition-all"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-white mb-1">Test Geçmişim & Soru Analizi</h3>
                        <p class="text-sm text-white/90">Çözdüğünüz testlerin sonuçlarını, analizlerini ve soru bazlı
                            performansınızı görüntüleyin.</p>
                    </div>
                </div>

                <!-- Kelime Oyunları - Yakında (Turuncu-Kırmızı) -->
                <!-- Kelime Oyunları - Aktif (Turuncu-Kırmızı) -->
                <a href="{{ route('ogrenci.word-match-game') }}" class="block">
                    <div
                        class="group bg-gradient-to-br from-orange-500 to-red-600 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden cursor-pointer">
                        <div class="h-36 bg-white/10 backdrop-blur-sm flex items-center justify-center relative">
                            <div class="absolute top-3 right-3">
                                <span class="bg-green-400 text-green-900 px-2 py-1 rounded-full text-xs font-bold">🎮
                                    Aktif</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-16 w-16 text-white/80 group-hover:text-white group-hover:scale-110 transition-all"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-white mb-1">Eşleştir!</h3>
                            <p class="text-sm text-white/90">Eğlenceli kelime oyunlarıyla İngilizcenizi geliştirin.</p>
                        </div>
                    </div>
                </a>
                <!-- Kelimelerim - Auth Kontrolü ile -->
                <!-- Kelimelerim - Auth Kontrolü ile -->
                @auth
                    <a href="{{ route('word-sets.index') }}"
                        class="group bg-gradient-to-br from-slate-600 to-gray-800 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 overflow-hidden cursor-pointer">
                        <div class="h-36 bg-white/10 backdrop-blur-sm flex items-center justify-center relative">
                            <div class="absolute top-3 right-3">
                                <span class="bg-green-400 text-green-900 px-2 py-1 rounded-full text-xs font-bold">📚
                                    Aktif</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-16 w-16 text-white group-hover:scale-110 transition-all" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-white mb-1">Kelimelerim</h3>
                            <p class="text-sm text-white/90">Öğrendiğiniz kelimeleri kaydedin ve tekrar edin.</p>
                        </div>
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="group bg-gradient-to-br from-slate-600 to-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden cursor-pointer">
                        <div class="h-36 bg-white/10 backdrop-blur-sm flex items-center justify-center relative">
                            <div class="absolute top-3 right-3">
                                <span class="bg-orange-400 text-orange-900 px-2 py-1 rounded-full text-xs font-bold">🔒 Üye
                                    Ol</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-16 w-16 text-white/60 group-hover:text-white/80 group-hover:scale-110 transition-all"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-white mb-1">Kelimelerim</h3>
                            <p class="text-sm text-white/90">Bu özelliği kullanmak için üye olmanız gerekiyor.</p>
                        </div>
                    </a>
                @endauth
                <!-- Gramer Rehberi - Yakında (Pembe-Fuşya) -->
                <a href="{{ route('useful-resources.index') }}"
                    class="block group bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden cursor-pointer">
                    <div class="h-36 bg-white/10 backdrop-blur-sm flex items-center justify-center relative">
                        <div class="absolute top-3 right-3">
                            <span class="bg-purple-400 text-purple-900 px-2 py-1 rounded-full text-xs font-bold">✏️
                                Grammar</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-16 w-16 text-white/80 group-hover:text-white group-hover:scale-110 transition-all"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-white mb-1">Faydalı Kaynaklar</h3>
                        <p class="text-sm text-white/90">İngilizce Grammar kuralları, alıştırmalar ve kaynaklara ulaşın.
                        </p>
                    </div>
                </a>

                <!-- Bonus: Yeni Özellik Kartı (Sarı-Lime) -->
                <div
                    class="group bg-gradient-to-br from-amber-500 to-lime-600 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden cursor-pointer">
                    <div class="h-36 bg-white/10 backdrop-blur-sm flex items-center justify-center relative">
                        <div class="absolute top-3 right-3">
                            <span class="bg-green-400 text-green-900 px-2 py-1 rounded-full text-xs font-bold">✨
                                Yakında</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-16 w-16 text-white/80 group-hover:text-white group-hover:scale-110 transition-all"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-white mb-1">Hızlı Pratik</h3>
                        <p class="text-sm text-white/90">Günlük 5 dakikalık pratiklerle İngilizcenizi pekiştirin.</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-[#f1faee] rounded-lg border border-[#e63946] border-opacity-30">
                <div class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#e63946] mr-3 flex-shrink-0 mt-0.5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="font-bold text-[#1a2e5a] mb-1">Yeni Özellikler Geliyor!</h3>
                        <p class="text-sm text-gray-700">Yakında eklenecek yeni oyun modları ve öğrenme araçlarıyla
                            İngilizce öğrenme deneyiminizi daha da keyifli hale getireceğiz. Takipte kalın!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Auth token'ı localStorage'a kaydet (kullanıcı giriş yapmışsa)
        @auth
        const authToken = '{{ auth()->user()->createToken('game-token')->plainTextToken }}';
        localStorage.setItem('auth_token', authToken);
        console.log('✅ Auth token kaydedildi!');
        @else
            // Giriş yapılmamışsa token'ı temizle
            localStorage.removeItem('auth_token');
            console.log('⚠️ Kullanıcı giriş yapmamış, token temizlendi.');
        @endauth
    </script>
@endpush
