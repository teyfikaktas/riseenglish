@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-[#1a2e5a]">Öğrenme Paneli</h1>
            <div class="px-3 py-1 bg-[#e63946] text-white rounded-full text-xs font-bold">
                Öğrenci Girişi
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] rounded-lg p-4 text-white">
            <h2 class="text-lg font-semibold mb-2">İngilizce öğrenmek için interaktif araçlar</h2>
            <p class="text-sm opacity-90">Farklı oyun modları ve egzersizlerle eğlenerek İngilizce öğrenin!</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Test Kategorileri Kutusu -->
            <a href="{{ route('ogrenci.test-categories.index') }}" class="bg-white rounded-xl shadow-md hover:shadow-lg transition duration-200 overflow-hidden border border-gray-100">
                <div class="h-36 bg-[#f1faee] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#e63946]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-bold text-[#1a2e5a] mb-1">Test Kategorileri</h3>
                    <p class="text-sm text-gray-600">İngilizce testleri çözerek bilginizi geliştirin.</p>
                </div>
            </a>

            <!-- Test Geçmişim Kutusu -->
            <a href="{{ route('ogrenci.tests.history') }}" class="bg-white rounded-xl shadow-md hover:shadow-lg transition duration-200 overflow-hidden border border-gray-100">
                <div class="h-36 bg-[#e8f4f8] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#2a9d8f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-bold text-[#1a2e5a] mb-1">Test Geçmişim</h3>
                    <p class="text-sm text-gray-600">Çözdüğünüz testlerin sonuçlarını ve analizlerini görüntüleyin.</p>
                </div>
            </a>
            
            <!-- Yakında Gelecek Kartı 2 -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 opacity-70 overflow-hidden">
                <div class="h-36 bg-gray-100 flex items-center justify-center relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="bg-gray-800 bg-opacity-70 text-white px-3 py-1 rounded-full text-xs font-bold">
                            Yakında
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-bold text-gray-500 mb-1">İlerleme Takibi</h3>
                    <p class="text-sm text-gray-500">Öğrenme yolculuğunuzu takip edin ve başarılarınızı görün.</p>
                </div>
            </div>

            <!-- Kelime Oyunları (Yakında) -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 opacity-70 overflow-hidden">
                <div class="h-36 bg-gray-100 flex items-center justify-center relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="bg-gray-800 bg-opacity-70 text-white px-3 py-1 rounded-full text-xs font-bold">
                            Yakında
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-bold text-gray-500 mb-1">Kelime Oyunları</h3>
                    <p class="text-sm text-gray-500">Eğlenceli kelime oyunlarıyla İngilizcenizi geliştirin.</p>
                </div>
            </div>

            <!-- Sözlük (Yakında) -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 opacity-70 overflow-hidden">
                <div class="h-36 bg-gray-100 flex items-center justify-center relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="bg-gray-800 bg-opacity-70 text-white px-3 py-1 rounded-full text-xs font-bold">
                            Yakında
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-bold text-gray-500 mb-1">Kişisel Sözlük</h3>
                    <p class="text-sm text-gray-500">Öğrendiğiniz kelimeleri kaydedin ve tekrar edin.</p>
                </div>
            </div>

            <!-- Gramır Rehberi (Yakında) -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 opacity-70 overflow-hidden">
                <div class="h-36 bg-gray-100 flex items-center justify-center relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="bg-gray-800 bg-opacity-70 text-white px-3 py-1 rounded-full text-xs font-bold">
                            Yakında
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-bold text-gray-500 mb-1">Gramer Rehberi</h3>
                    <p class="text-sm text-gray-500">İngilizce gramer kurallarını öğrenin ve pratik yapın.</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 bg-[#f1faee] rounded-lg border border-[#e63946] border-opacity-30">
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#e63946] mr-3 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="font-bold text-[#1a2e5a] mb-1">Yeni Özellikler Geliyor!</h3>
                    <p class="text-sm text-gray-700">Yakında eklenecek yeni oyun modları ve öğrenme araçlarıyla İngilizce öğrenme deneyiminizi daha da keyifli hale getireceğiz. Takipte kalın!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection