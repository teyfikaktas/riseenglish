<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-gradient-to-r from-[#1a2e5a] to-[#283b6a]">
    <!-- Sol taraf - Login formu -->
    <div class="w-full md:w-1/2 flex items-center justify-center">
        <div class="max-w-md w-full p-8 bg-white rounded-lg shadow-lg">
            <!-- Logo -->
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="Rise English Logo" class="h-36 mx-auto">
            </div>
            
            <h2 class="text-3xl font-bold text-center text-[#1a2e5a] mb-6">Giriş Yap</h2>
            
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">E-posta Adresi</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                        class="appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Şifre</label>
                    <input id="password" type="password" name="password" required 
                        class="appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <input class="mr-2 leading-tight" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="text-sm text-gray-700" for="remember">
                            Beni Hatırla
                        </label>
                    </div>
                    
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-[#e63946] hover:text-[#d62836]">
                        Şifremi Unuttum
                    </a>
                    @endif
                </div>
                
                <div class="mb-6">
                    <button type="submit" class="w-full bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
                        Giriş Yap
                    </button>
                </div>
                
                <div class="text-center">
                    <p class="text-gray-600">Hesabınız yok mu? 
                        <a href="{{ url('/kayit-ol') }}" class="text-[#1a2e5a] font-bold hover:underline">
                            Kayıt Ol
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Sağ taraf - Görsel ve bilgi -->
    <div class="hidden md:flex md:w-1/2 bg-white relative">
        <!-- Arka plan deseni -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="#1a2e5a" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)" />
            </svg>
        </div>
        
        <!-- Görsel -->
        <div class="relative w-full h-full flex items-center justify-center p-8">
            <div class="relative">
                <img src="{{ asset('images/teachers.jpg') }}" alt="İki Eğitmen" class="rounded-lg shadow-xl w-full max-w-lg">
                
                <div class="absolute -bottom-4 -right-4 bg-white rounded-lg p-4 shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-[#1a2e5a] rounded-full h-3 w-3 mr-2 animate-pulse"></div>
                        <span class="font-bold text-[#1a2e5a]">50+ Uzman Eğitmen</span>
                    </div>
                </div>
                
                <div class="absolute -top-4 -left-4 bg-[#e63946] text-white rounded-lg p-4 shadow-xl transform rotate-3 hover:rotate-0 transition-transform duration-300">
                    <div class="text-xl font-extrabold">İngilizce Öğretiyoruz!</div>
                    <div class="text-sm font-semibold">Profesyonel Eğitmenler</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection