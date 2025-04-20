<!-- resources/views/auth/register.blade.php -->
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-gradient-to-r from-[#1a2e5a] to-[#283b6a]">
    <!-- Sol taraf - İndirim bilgisi -->
    <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-[#1a2e5a] to-[#283b6a] relative">
        <!-- Arka plan deseni -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)" />
            </svg>
        </div>
        
        <!-- İndirim bilgisi -->
        <div class="relative w-full h-full flex flex-col items-center justify-center p-8 text-center">
            <div class="bg-[#e63946] text-white rounded-xl p-8 shadow-2xl transform rotate-2 hover:rotate-0 transition-transform duration-300 max-w-md w-full mb-10 border-4 border-white">
                <div class="text-5xl font-extrabold mb-4">%15 İNDİRİM</div>
                <div class="text-2xl font-semibold mb-3">İlk Kayıtta</div>
                <div class="text-lg mt-2 bg-white text-[#e63946] px-4 py-2 rounded-lg font-bold inline-block animate-pulse">SINIRLI SÜRE!</div>
            </div>
            
            <div class="bg-white rounded-lg p-6 shadow-xl max-w-md w-full text-center mt-4">
                <div class="flex items-center justify-center mb-3">
                    <div class="bg-[#1a2e5a] rounded-full h-4 w-4 mr-2 animate-pulse"></div>
                    <span class="font-bold text-[#1a2e5a] text-2xl">5000+ Mezun Öğrenci</span>
                </div>
                <p class="text-gray-700">Profesyonel eğitmenlerimizle birlikte siz de İngilizce öğrenme yolculuğunuza başlayın.</p>
            </div>
            
            <div class="bg-white rounded-lg p-6 shadow-xl max-w-md w-full text-center mt-6">
                <div class="flex items-center justify-center mb-3">
                    <div class="bg-[#1a2e5a] rounded-full h-4 w-4 mr-2 animate-pulse"></div>
                    <span class="font-bold text-[#1a2e5a] text-2xl">4.9/5 Memnuniyet</span>
                </div>
                <p class="text-gray-700">Öğrencilerimizin yüksek memnuniyet oranıyla kalitemizi kanıtlıyoruz.</p>
            </div>
        </div>
    </div>

    <!-- Sağ taraf - Kayıt formu -->
    <div class="w-full md:w-1/2 flex items-center justify-center">
        <div class="max-w-md w-full p-8 bg-white rounded-lg shadow-lg">
            <!-- Logo -->
            <div class="text-center mb-6">
                <img id="registerLogo" src="{{ asset('images/logo.png') }}" alt="Rise English Logo" class="h-24 mx-auto transition-transform duration-500">
            </div>
            
            <h2 class="text-3xl font-bold text-center text-[#1a2e5a] mb-6">Hemen Üye Ol</h2>
            
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Register Form -->
            <form method="POST" action="{{ url('/kayit-ol') }}" id="registerForm">
                @csrf
                
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Ad Soyad</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required 
                        class="appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">E-posta Adresi</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                        class="appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Telefon Numarası</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">
                            +90
                        </span>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required placeholder="5xx xxx xx xx"
                            class="appearance-none border rounded-r-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone') border-red-500 @enderror">
                    </div>
                    @error('phone')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Kurs kayıtları ve bilgilendirmeler için telefon numaranızı doğrulamanız gerekecektir.</p>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Şifre</label>
                    <input id="password" type="password" name="password" required 
                        class="appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Şifre (Tekrar)</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required 
                        class="appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center">
                        <input id="terms" type="checkbox" name="terms" required class="mr-2">
                        <label for="terms" class="text-sm text-gray-700">
                            <a href="{{ url('/kullanici-sozlesmesi') }}" class="text-[#e63946] hover:underline" target="_blank">Kullanıcı sözleşmesini</a> okudum ve kabul ediyorum.
                        </label>
                    </div>
                    @error('terms')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <button id="registerButton" type="submit" class="w-full bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-4 sm:py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 text-lg sm:text-base touch-manipulation">
                        <span id="registerButtonText">Kayıt Ol</span>
                        <span id="registerButtonLoading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Yükleniyor...
                        </span>
                    </button>
                </div>
                
                <div class="text-center">
                    <p class="text-gray-600">Zaten hesabınız var mı? 
                        <a href="{{ url('/oturum-ac') }}" class="text-[#1a2e5a] font-bold hover:underline">
                            Oturum Aç
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const registerButton = document.getElementById('registerButton');
    const buttonText = document.getElementById('registerButtonText');
    const buttonLoading = document.getElementById('registerButtonLoading');
    const logo = document.getElementById('registerLogo');
    let isSubmitting = false;

    form.addEventListener('submit', function(e) {
        // Form doldurulması zorunlu alanların kontrolü
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        const terms = document.getElementById('terms').checked;
        
        // Basit bir form doğrulama
        if (!name || !email || !phone || !password || !passwordConfirmation || !terms) {
            return true; // Form doğrulaması tarayıcı tarafından yapılacak
        }
        
        // Şifre kontrolü
        if (password !== passwordConfirmation) {
            return true; // Form doğrulaması tarayıcı tarafından yapılacak
        }

        // Eğer form zaten gönderiliyorsa, tekrar gönderilmesini engelle
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }

        // Form gönderim durumunu true yap
        isSubmitting = true;
        
        // Butonu loading durumuna getir
        buttonText.classList.add('hidden');
        buttonLoading.classList.remove('hidden');
        registerButton.disabled = true;
        registerButton.classList.add('opacity-75');
        
        // Logo animasyonu başlat
        logo.classList.add('animate-spin');
        
        // Form normal şekilde gönderilsin
        return true;
    });

    // Mobil için daha iyi tıklama deneyimi
    registerButton.addEventListener('touchstart', function() {
        this.classList.add('scale-95');
    });

    registerButton.addEventListener('touchend', function() {
        this.classList.remove('scale-95');
    });
});
</script>
@endsection