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
                <!-- YDT Kategorisi -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 p-6 border-l-4 border-[#1a2e5a] border-2 border-[#1a2e5a]">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-[#1a2e5a] mb-1">YDT</h3>
                            <p class="text-sm text-gray-600">Yabancı Dil Testi</p>
                        </div>
                        <div class="w-12 h-12 bg-[#1a2e5a] rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">📝</span>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">YDS benzeri sorularla üniversite sınavlarına hazırlanın.</p>
                    <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                        <span class="bg-gray-100 px-2 py-1 rounded">120 Soru • 8 Test</span>
                        <span class="bg-orange-100 text-orange-600 px-2 py-1 rounded">Orta-Zor</span>
                    </div>
                    <button class="w-full bg-[#1a2e5a] hover:bg-[#0f1b3d] text-white font-medium py-2 px-4 rounded-lg transition">
                        Teste Başla
                    </button>
                </div>

                <!-- YÖKDİL Kategorisi -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 p-6 border-l-4 border-[#1a2e5a] border-2 border-[#1a2e5a]">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-[#1a2e5a] mb-1">YÖKDİL</h3>
                            <p class="text-sm text-gray-600">Yükseköğretim Kurumları Dil Sınavı</p>
                        </div>
                        <div class="w-12 h-12 bg-[#1a2e5a] rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">🎓</span>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Akademik İngilizce sorularıyla YÖKDİL'e hazırlanın.</p>
                    <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                        <span class="bg-gray-100 px-2 py-1 rounded">80 Soru • 6 Test</span>
                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded">Zor</span>
                    </div>
                    <button class="w-full bg-[#1a2e5a] hover:bg-[#0f1b3d] text-white font-medium py-2 px-4 rounded-lg transition">
                        Teste Başla
                    </button>
                </div>

                <!-- Zamanlar (Tenses) Kategorisi -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 p-6 border-l-4 border-[#1a2e5a] border-2 border-[#1a2e5a]">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-[#1a2e5a] mb-1">Zamanlar</h3>
                            <p class="text-sm text-gray-600">Tenses & Grammar</p>
                        </div>
                        <div class="w-12 h-12 bg-[#1a2e5a] rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">⏰</span>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Present, Past, Future ve diğer tüm zamanlar.</p>
                    <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                        <span class="bg-gray-100 px-2 py-1 rounded">50 Soru • 12 Test</span>
                        <span class="bg-green-100 text-green-600 px-2 py-1 rounded">Kolay-Orta</span>
                    </div>
                    <button class="w-full bg-[#1a2e5a] hover:bg-[#0f1b3d] text-white font-medium py-2 px-4 rounded-lg transition">
                        Teste Başla
                    </button>
                </div>

                <!-- Kelime Bilgisi Kategorisi -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 p-6 border-l-4 border-[#1a2e5a] border-2 border-[#1a2e5a]">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-[#1a2e5a] mb-1">Kelime Bilgisi</h3>
                            <p class="text-sm text-gray-600">Vocabulary Test</p>
                        </div>
                        <div class="w-12 h-12 bg-[#1a2e5a] rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">📚</span>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Kelime dağarcığınızı genişletin ve test edin.</p>
                    <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                        <span class="bg-gray-100 px-2 py-1 rounded">100 Soru • 15 Test</span>
                        <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded">Karma</span>
                    </div>
                    <button class="w-full bg-[#1a2e5a] hover:bg-[#0f1b3d] text-white font-medium py-2 px-4 rounded-lg transition">
                        Teste Başla
                    </button>
                </div>

                <!-- Reading Comprehension Kategorisi -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 p-6 border-l-4 border-[#1a2e5a] border-2 border-[#1a2e5a]">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-[#1a2e5a] mb-1">Okuduğunu Anlama</h3>
                            <p class="text-sm text-gray-600">Reading Comprehension</p>
                        </div>
                        <div class="w-12 h-12 bg-[#1a2e5a] rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">📖</span>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Metin anlama ve yorumlama becerilerinizi geliştirin.</p>
                    <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                        <span class="bg-gray-100 px-2 py-1 rounded">75 Soru • 10 Test</span>
                        <span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded">Orta</span>
                    </div>
                    <button class="w-full bg-[#1a2e5a] hover:bg-[#0f1b3d] text-white font-medium py-2 px-4 rounded-lg transition">
                        Teste Başla
                    </button>
                </div>

                <!-- KPDS/ÜDS Kategorisi -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 p-6 border-l-4 border-[#1a2e5a] border-2 border-[#1a2e5a]">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-[#1a2e5a] mb-1">KPDS/ÜDS</h3>
                            <p class="text-sm text-gray-600">Kamu Personeli & Üni. Dil Sınavı</p>
                        </div>
                        <div class="w-12 h-12 bg-[#1a2e5a] rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">🏛️</span>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">KPDS ve ÜDS sınavlarına yönelik özel sorular.</p>
                    <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                        <span class="bg-gray-100 px-2 py-1 rounded">75 Soru • 5 Test</span>
                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded">Zor</span>
                    </div>
                    <button class="w-full bg-[#1a2e5a] hover:bg-[#0f1b3d] text-white font-medium py-2 px-4 rounded-lg transition">
                        Teste Başla
                    </button>
                </div>
            </div>
            
            <!-- Bilgi Notu -->
            <div class="p-6 bg-white rounded-xl border-2 border-[#1a2e5a] shadow-md mt-8">
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