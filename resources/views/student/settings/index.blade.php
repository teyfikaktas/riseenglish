@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Ayarlar</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Sidebar / Navigation -->
            <div class="bg-white rounded-lg shadow-md p-6 h-min">
                <h2 class="text-xl font-bold text-gray-700 mb-4">Ayarlar Menüsü</h2>
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="#profile-section" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 hover:text-red-600 rounded transition duration-200 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profil Bilgileri
                            </a>
                        </li>
                        <li>
                            <a href="#password-section" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 hover:text-red-600 rounded transition duration-200 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Şifre Değiştir
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="md:col-span-2 space-y-8">
                <!-- Profile Information Section -->
                <section id="profile-section" class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-700 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profil Bilgileri
                    </h2>
                    
                    <form action="{{ route('ogrenci.settings.update-profile') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Ad Soyad</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#1a2e5a] focus:border-[#1a2e5a]">
                                @error('name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-posta Adresi</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#1a2e5a] focus:border-[#1a2e5a]">
                                @error('email')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefon Numarası</label>
                                <input type="text" id="phone" value="{{ $user->phone }}" disabled
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                                <input type="hidden" name="phone" value="{{ $user->phone }}">
                                <p class="text-gray-500 text-sm mt-1">Telefon numarası değiştirilemez. Bu bilgi kayıt sırasında belirlenir. Değişiklik için lütfen destek personeli ile iletişime geçiniz.</p>
                            </div>
                            

                            
                            <div class="flex justify-end">
                                <button type="submit" class="bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-bold py-2 px-6 rounded-lg border-2 border-[#e63946] shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                    Profili Güncelle
                                </button>
                            </div>
                        </div>
                    </form>
                </section>
                
                <!-- Password Change Section -->
                <section id="password-section" class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-700 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Şifre Değiştir
                    </h2>
                    
                    <form action="{{ route('ogrenci.settings.update-password') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mevcut Şifre</label>
                                <input type="password" name="current_password" id="current_password" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#1a2e5a] focus:border-[#1a2e5a]">
                                @error('current_password')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Yeni Şifre</label>
                                <input type="password" name="password" id="password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#1a2e5a] focus:border-[#1a2e5a]">
                                <p class="text-gray-500 text-sm mt-1">En az 8 karakter uzunluğunda olmalıdır.</p>
                                @error('password')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Yeni Şifre (Tekrar)</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#1a2e5a] focus:border-[#1a2e5a]">
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-bold py-2 px-6 rounded-lg border-2 border-[#e63946] shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                    Şifreyi Güncelle
                                </button>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>

<script>
    // Smooth scroll to sections when clicking on navigation links
    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('a[href^="#"]');
        
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100, // Offset for navbar
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
@endsection