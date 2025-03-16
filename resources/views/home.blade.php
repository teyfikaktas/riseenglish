<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] py-12">
    <div class="container mx-auto px-6">
        <div class="bg-white rounded-lg p-8 shadow-lg">
            <!-- Başarı mesajı -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-[#1a2e5a]">Hoş Geldiniz</h1>
                <p class="text-gray-600 mt-2">Hesabınıza başarıyla giriş yaptınız.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kullanıcı bilgileri kartı -->
                <div class="bg-gray-50 rounded-lg p-6 shadow-md">
                    <h2 class="text-xl font-semibold text-[#1a2e5a] mb-4">Hesap Bilgileri</h2>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <span class="font-semibold w-24">Ad Soyad:</span>
                            <span>{{ Auth::user()->name }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-semibold w-24">E-posta:</span>
                            <span>{{ Auth::user()->email }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-semibold w-24">Üyelik:</span>
                            <span>{{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('d.m.Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Hızlı erişim kartı -->
                <div class="bg-gray-50 rounded-lg p-6 shadow-md">
                    <h2 class="text-xl font-semibold text-[#1a2e5a] mb-4">Hızlı Erişim</h2>
                    <div class="space-y-4">
                        <a href="#" class="flex items-center p-3 bg-white rounded-lg shadow-sm hover:bg-gray-100 transition-colors">
                            <div class="w-10 h-10 bg-[#1a2e5a] text-white rounded-full flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                </svg>
                            </div>
                            <span>Eğitimlerim</span>
                        </a>
                        
                        <a href="#" class="flex items-center p-3 bg-white rounded-lg shadow-sm hover:bg-gray-100 transition-colors">
                            <div class="w-10 h-10 bg-[#e63946] text-white rounded-full flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span>Profil Ayarları</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ url('/') }}" class="inline-block bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-bold py-3 px-8 rounded-lg transition duration-300">
                    Ana Sayfaya Dön
                </a>
            </div>
        </div>
    </div>
</div>
@endsection