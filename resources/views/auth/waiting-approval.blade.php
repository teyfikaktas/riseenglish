@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden mx-auto">
        <div class="bg-[#1a2e5a] px-6 py-4">
            <h2 class="text-center text-2xl font-bold text-white">
                Hesap Onayı Bekleniyor
            </h2>
        </div>
        
        <div class="px-6 py-8">
            @if (session('warning'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                    {{ session('warning') }}
                </div>
            @endif
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="text-center mb-6">
                <div class="mb-4">
                    <svg class="mx-auto h-16 w-16 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Hesabınız İnceleniyor</h3>
                <p class="text-gray-700">
                    Öğretmeniniz hesabınızı onayladıktan sonra sistemi kullanmaya başlayabileceksiniz.
                </p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Hesap Bilgileriniz</h4>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span class="font-medium">Ad Soyad:</span>
                        <span>{{ Auth::user()->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">E-posta:</span>
                        <span>{{ Auth::user()->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Telefon:</span>
                        <span>{{ Auth::user()->phone }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Kayıt Tarihi:</span>
                        <span>{{ Auth::user()->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>
            </div>
            
            
            <div class="space-y-3">
                <button onclick="location.reload()" class="w-full flex justify-center items-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1a2e5a]">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Durumu Yenile
                </button>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#e63946] hover:bg-[#d62836] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#e63946]">
                        Çıkış Yap
                    </button>
                </form>
            </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <p class="text-xs text-center text-gray-500">
                Bir sorun mu var? <a href="{{ route('contact') }}" class="text-[#1a2e5a] hover:text-[#283b6a] font-medium">İletişime geçin</a>
            </p>
        </div>
    </div>
</div>

@endsection