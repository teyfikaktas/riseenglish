<div class="sticky top-0 w-full bg-white shadow-sm z-50 border-b border-gray-200">
    {{-- Stil ve Medya Sorguları --}}
    <style>
        .text-xxs { font-size: 0.65rem; }
        @media (max-width: 768px) { .login-signup-button { width: 100%; justify-content: center; margin-top: 0.5rem; } }
        @media (max-width: 370px) { .xs\:text-xs { font-size: 0.65rem; } }
        
        /* Menü kutusu stilleri */
        .menu-box {
            position: relative;
            border: 2px solid #1a2e5a;
            border-radius: 10px;
            padding: 2px;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            white-space: nowrap;
            margin: 0.25rem 0.35rem;
        }
        
        .menu-box:hover {
            border-color: #e63946;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(230, 57, 70, 0.15);
        }
        
        .menu-inner {
            border-radius: 7px;
            padding: 0.5rem 0.75rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .menu-box:hover .menu-inner {
            background-color: #f7f7f7;
        }
        
        /* Aktif menü kutusu stilleri */
        .menu-box.active {
            background-color: #1a2e5a;
            border-color: #e63946;
            border-width: 2px;
        }
        
        .menu-box.active .menu-inner {
            background-color: #1a2e5a;
        }
        
        .menu-box.active a {
            color: white !important;
        }
        
        .menu-box.active:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 30%;
            height: 3px;
            background-color: #e63946;
        }
        
        /* iPad Pro ve tablet ekranlar için düzenlemeler */
        @media (min-width: 769px) and (max-width: 1199px) {
            .menu-box {
                margin: 0.25rem;
            }
            .menu-inner {
                padding: 0.4rem 0.6rem;
                font-size: 0.85rem;
            }
            nav.lg\:flex {
                flex-wrap: wrap;
                justify-content: center;
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }
            .nav-container {
                flex-direction: row;
                align-items: center;
                justify-content: center;
            }
            .relative.ml-auto.lg\:ml-4 {
                margin: 0.5rem auto;
            }
        }
        
        /* iPad Pro 12.9" için özel düzenlemeler (1024x1366) */
        @media (min-width: 1024px) and (max-width: 1199px) {
            .container {
                max-width: 1020px;
                padding-left: 1rem;
                padding-right: 1rem;
            }
            nav.lg\:flex {
                display: flex !important;
                flex-wrap: wrap;
                justify-content: flex-end;
                padding-top: 0;
                padding-bottom: 0;
                margin-bottom: 0;
            }
            .nav-container {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                width: 100%;
            }
            .menu-box {
                margin: 0.15rem;
            }
            .menu-inner {
                padding: 0.35rem 0.5rem;
                font-size: 0.8rem;
            }
            .login-button {
                margin-left: 0.5rem;
            }
            .auth-buttons {
                margin-left: auto;
            }
        }
        
        /* Küçük mobil cihazlar için düzenlemeler */
        @media (max-width: 640px) {
            .menu-inner {
                padding: 0.4rem 0.5rem;
            }
        }
    </style>

    <div class="container mx-auto px-3">
        <div class="flex flex-wrap items-center justify-between py-3 md:py-2">
            <!-- Logo ve Slogan Bölümü -->
            <div class="w-full md:w-auto flex-shrink-0 flex flex-col sm:flex-row items-center mb-2 md:mb-0">
                <a href="{{ url('/') }}" class="flex-shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Rise English Logo" class="h-10 sm:h-12 md:h-14">
                </a>
                <div class="mt-1 sm:mt-0 ml-0 sm:ml-3 border-t-2 sm:border-t-0 sm:border-l-2 border-[#e63946] pt-1 sm:pt-0 pl-0 sm:pl-3 text-center sm:text-left">
                    <span class="text-[#1a2e5a] font-serif italic text-xs sm:text-sm font-bold block leading-tight">
                        Hakan Hoca Eğitim Hayatınızda Başarılar Diler.
                    </span>
                </div>
            </div>

            <!-- Sağ Taraftaki İçerik (Nav + Giriş Butonu) -->
            <div class="w-full md:w-auto flex flex-wrap items-center justify-between nav-container lg:flex-row lg:justify-end xl:justify-between">
                <!-- Navigation (Masaüstünde görünür) - YENİ MENÜ KUTUSU TASARIMI -->
                <nav class="hidden lg:flex items-center space-x-4 xl:space-x-6 text-sm mr-4 flex-wrap xl:flex-nowrap nav-items">
                    @php
                        $logoBlue = '#1a2e5a';
                        $logoRed = '#e63946';
                    @endphp

                    <div class="{{ request()->is('ana-sayfa') ? 'menu-box active' : 'menu-box' }}">
                        <a href="{{ url('/ana-sayfa') }}" class="menu-inner block font-semibold text-[#e63946]">
                            Ana Sayfa
                        </a>
                    </div>
                    
                    <div class="{{ request()->routeIs('public.resources.index') ? 'menu-box active' : 'menu-box' }}">
                        <a href="{{ route('public.resources.index') }}" class="menu-inner block font-semibold text-[#e63946]">
                            Ücretsiz İçerikler
                        </a>
                    </div>
                    
                    <div class="{{ request()->is('egitimler') ? 'menu-box active' : 'menu-box' }}">
                        <a href="{{ url('/egitimler') }}" class="menu-inner block font-semibold text-[#e63946]">
                            Eğitimler
                        </a>
                    </div>
                    
                    <div class="{{ request()->is('iletisim') ? 'menu-box active' : 'menu-box' }}">
                        <a href="{{ url('/iletisim') }}" class="menu-inner block font-semibold text-[#e63946]">
                            İletişim
                        </a>
                    </div>
                    
                    {{-- Özel Ders Linkleri --}}
                    @if(auth()->check() && auth()->user()->hasRole('ogretmen'))
                    <div class="{{ request()->routeIs('ogretmen.private-lessons.index') ? 'menu-box active' : 'menu-box' }}">
                        <a href="{{ route('ogretmen.private-lessons.index') }}" class="menu-inner block font-semibold text-[#e63946]">
                            Özel Ders
                        </a>
                    </div>
                    @endif
                    
                    @if(auth()->check() && auth()->user()->hasRole('ogrenci'))
                    <div class="{{ request()->routeIs('ogrenci.private-lessons.index') ? 'menu-box active' : 'menu-box' }}">
                        <a href="{{ route('ogrenci.private-lessons.index') }}" class="menu-inner block font-semibold text-[#e63946]">
                            Özel Derslerim
                        </a>
                    </div>
                    @endif
                </nav>

                <!-- Sağ Taraf: Kimlik Doğrulama Düğmeleri ve Mobil Menü -->
                <div class="flex items-center justify-between w-full lg:w-auto auth-buttons">
                    <!-- Mobil Menü Butonu -->
                    <div class="lg:hidden flex items-center order-first">
                        <button class="mobile-menu-button p-2 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#e63946]" aria-label="Menüyü Aç">
                            <i class="fas fa-bars text-[#1a2e5a] text-xl"></i>
                        </button>
                    </div>

                    <!-- Oturum Aç/Kapat Düğmesi - YENİLENMİŞ TASARIM -->
                    <div class="relative ml-auto lg:ml-4 login-btn-container">
                        @auth
                            @if(auth()->user()->hasRole('ogrenci'))
                                <!-- Öğrenci: Profil -->
                                <button id="profile-btn" class="menu-box flex items-center space-x-2 focus:outline-none cursor-pointer">
                                    <div class="menu-inner flex items-center">
                                        <span class="text-[#1a2e5a] font-semibold text-xs sm:text-sm">{{ auth()->user()->name }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#1a2e5a] ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </button>
                                <div id="profile-dropdown" class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-md shadow-xl hidden z-[60]">
                                    <a href="{{ route('ogrenci.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1a2e5a]">Ayarlar</a>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                       class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">Çıkış Yap</a>
                                </div>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf </form>
                            @else
                                <!-- Diğer roller: Çıkış -->
                                <div class="menu-box">
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form-other').submit();"
                                       class="menu-inner flex items-center text-[#e63946] font-semibold">
                                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                             <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                         </svg>
                                        <span>Çıkış</span>
                                    </a>
                                </div>
                                <form id="logout-form-other" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf </form>
                            @endif
                        @else
                            <!-- Giriş - LAÇİVERT TASARIMLI BUTON (BÜYÜTÜLMÜŞ) -->
                            <div class="login-button">
                                <a href="{{ url('/oturum-ac') }}" class="bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-bold py-2.5 px-5 text-sm rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center space-x-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                     </svg>
                                    <span>Giriş</span>
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobil Menü (Varsayılan olarak gizlidir) - YENİ MENÜ KUTUSU TASARIMI MOBİL İÇİN -->
    <div class="lg:hidden mobile-menu hidden absolute top-full left-0 w-full bg-white border-t border-gray-200 shadow-lg p-4 space-y-4">
        <div class="{{ request()->is('ana-sayfa') ? 'menu-box active' : 'menu-box' }} w-full">
            <a href="{{ url('/ana-sayfa') }}" class="menu-inner block font-semibold text-[#e63946] w-full">
                Ana Sayfa
            </a>
        </div>
        
        <div class="{{ request()->routeIs('public.resources.index') ? 'menu-box active' : 'menu-box' }} w-full">
            <a href="{{ route('public.resources.index') }}" class="menu-inner block font-semibold text-[#e63946] w-full">
                Ücretsiz İçerikler
            </a>
        </div>
        
        <div class="{{ request()->is('egitimler') ? 'menu-box active' : 'menu-box' }} w-full">
            <a href="{{ url('/egitimler') }}" class="menu-inner block font-semibold text-[#e63946] w-full">
                Eğitimler
            </a>
        </div>
        
        <div class="{{ request()->is('iletisim') ? 'menu-box active' : 'menu-box' }} w-full">
            <a href="{{ url('/iletisim') }}" class="menu-inner block font-semibold text-[#e63946] w-full">
                İletişim
            </a>
        </div>
        
        {{-- Özel Ders Linkleri (Mobil) --}}
        @if(auth()->check() && auth()->user()->hasRole('ogretmen'))
        <div class="{{ request()->routeIs('ogretmen.private-lessons.index') ? 'menu-box active' : 'menu-box' }} w-full">
            <a href="{{ route('ogretmen.private-lessons.index') }}" class="menu-inner block font-semibold text-[#e63946] w-full">
                Özel Ders
            </a>
        </div>
        @endif
        
        @if(auth()->check() && auth()->user()->hasRole('ogrenci'))
        <div class="{{ request()->routeIs('ogrenci.private-lessons.index') ? 'menu-box active' : 'menu-box' }} w-full">
            <a href="{{ route('ogrenci.private-lessons.index') }}" class="menu-inner block font-semibold text-[#e63946] w-full">
                Özel Derslerim
            </a>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const mobileMenu = document.querySelector('.mobile-menu');
        const profileBtn = document.getElementById('profile-btn');
        const profileDropdown = document.getElementById('profile-dropdown');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                mobileMenu.classList.toggle('hidden');
                if (!mobileMenu.classList.contains('hidden') && profileDropdown && !profileDropdown.classList.contains('hidden')) {
                    profileDropdown.classList.add('hidden');
                }
            });
        }

        if (profileBtn && profileDropdown) {
            profileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');
                if (!profileDropdown.classList.contains('hidden') && mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                }
            });
        }

        document.addEventListener('click', function(e) {
            if (mobileMenu && !mobileMenu.classList.contains('hidden') && !mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
            if (profileDropdown && !profileDropdown.classList.contains('hidden') && !profileDropdown.contains(e.target) && !profileBtn.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
        });
    });
</script>