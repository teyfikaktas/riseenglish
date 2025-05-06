<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col md:flex-row bg-gradient-to-r from-[#1a2e5a] to-[#283b6a]">
    <!-- Sol taraf - Login formu -->
    <div class="w-full md:w-1/2 flex items-center justify-center py-8">
        <div class="max-w-md w-full p-8 bg-white rounded-lg shadow-lg">
            <!-- Logo -->
            <div class="text-center mb-8">
                <img id="logo" src="{{ asset('images/logo.png') }}" alt="Rise English Logo" class="h-32 sm:h-40 md:h-48 mx-auto transition-transform duration-500">
            </div>
            
            <h2 class="text-3xl font-bold text-center text-[#1a2e5a] mb-6">Giriş Yap</h2>
            
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">E-posta Adresi</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                        class="appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6 relative">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Şifre</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required 
                            class="appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                        <!-- CAPSLOCK Uyarısı - Yeni tasarım -->
                        <div id="capsLockWarning" class="hidden absolute transform -translate-y-full left-0 top-0 mb-1 px-3 py-2 bg-[#1a2e5a] text-white text-sm font-medium rounded-md shadow-lg opacity-90 transition-all duration-300 z-10">
                            <div class="flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span>CAPSLOCK açık</span>
                            </div>
                            <!-- Üçgen işaret (tooltip görünümü için) -->
                            <div class="absolute h-2 w-2 bg-[#1a2e5a] transform rotate-45 left-4 -bottom-1"></div>
                        </div>
                    </div>
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
                    
                    @if (Route::has('password.sms.request'))
                    <span class="mx-2">|</span>
                    <a href="{{ route('password.sms.request') }}" class="text-sm text-[#e63946] hover:text-[#d62836]">
                        SMS ile Şifremi Unuttum
                    </a>
                    @endif
                </div>
                
                <div class="mb-6">
                    <button id="loginButton" type="submit" class="w-full bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-4 sm:py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 text-lg sm:text-base touch-manipulation">
                        <span id="buttonText">Giriş Yap</span>
                        <span id="buttonLoading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Yükleniyor...
                        </span>
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
    
    <!-- Sağ taraf - Görsel ve bilgi (Sadece desktop) -->
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
                
                <!-- Etiketin pozisyonunu değiştiriyorum - resmin üstüne -->
                <div class="absolute -top-12 right-4 bg-[#e63946] text-white rounded-lg p-4 shadow-xl transform rotate-3 hover:rotate-0 transition-transform duration-300">
                    <div class="text-xl font-extrabold">İngilizce Öğretiyoruz!</div>
                    <div class="text-sm font-semibold">Profesyonel Eğitmenler</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobil için resim (Sadece mobil görünümde) -->
    <div class="block md:hidden w-full bg-white p-4">
        <div class="relative max-w-sm mx-auto my-6">
            <img src="{{ asset('images/teachers.jpg') }}" alt="İki Eğitmen" class="rounded-lg shadow-xl w-full">
            
            <div class="absolute bottom-2 right-2 bg-white rounded-lg p-3 shadow-lg text-sm">
                <div class="flex items-center">
                    <div class="bg-[#1a2e5a] rounded-full h-2 w-2 mr-2 animate-pulse"></div>
                    <span class="font-bold text-[#1a2e5a]">50+ Uzman Eğitmen</span>
                </div>
            </div>
            
            <div class="absolute -top-8 right-2 bg-[#e63946] text-white rounded-lg p-3 shadow-xl">
                <div class="text-sm font-extrabold">İngilizce Öğretiyoruz!</div>
                <div class="text-xs font-semibold">Profesyonel Eğitmenler</div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const buttonText = document.getElementById('buttonText');
    const buttonLoading = document.getElementById('buttonLoading');
    const logo = document.getElementById('logo');
    const passwordInput = document.getElementById('password');
    const capsLockWarning = document.getElementById('capsLockWarning');
    let isSubmitting = false;

    // CAPSLOCK kontrolü için geliştirilmiş event listener'lar
    function checkCapsLock(e) {
        if (e.getModifierState('CapsLock')) {
            capsLockWarning.classList.remove('hidden');
            // Animasyon efekti
            setTimeout(() => {
                capsLockWarning.classList.add('opacity-100');
            }, 10);
        } else {
            capsLockWarning.classList.add('hidden');
            capsLockWarning.classList.remove('opacity-100');
        }
    }

    // Her tuş vuruşunda CAPSLOCK durumunu kontrol et
    passwordInput.addEventListener('keyup', checkCapsLock);
    
    // İlk kez şifre alanına tıklandığında da kontrol et
    passwordInput.addEventListener('focus', checkCapsLock);
    
    // Şifre alanından çıkıldığında uyarıyı gizle
    passwordInput.addEventListener('blur', function() {
        capsLockWarning.classList.add('hidden');
        capsLockWarning.classList.remove('opacity-100');
    });
    
    // Pencere odağı değiştiğinde de kontrol et (başka uygulamadan geri dönüş)
    window.addEventListener('focus', function() {
        if (document.activeElement === passwordInput) {
            checkCapsLock(new KeyboardEvent('keyup'));
        }
    });

    form.addEventListener('submit', function(e) {
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
        loginButton.disabled = true;
        loginButton.classList.add('opacity-75');
        
        // Logo animasyonu başlat
        logo.classList.add('animate-spin');
        
        // Form normal şekilde gönderilsin
        return true;
    });

    // Mobil için daha iyi tıklama deneyimi
    loginButton.addEventListener('touchstart', function() {
        this.classList.add('scale-95');
    });

    loginButton.addEventListener('touchend', function() {
        this.classList.remove('scale-95');
    });
});
</script>
@endsection