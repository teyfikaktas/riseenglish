<div class="sticky top-0 w-full bg-white shadow-md z-50">
    <style>
        /* Çok küçük fontlar için özel sınıf */
        .text-xxs {
            font-size: 0.65rem;
        }
        
        /* En küçük ekranlar için özel sınıf */
        @media (max-width: 370px) {
            .xs\:text-xs {
                font-size: 0.65rem;
            }
        }
    </style>
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-24">
            <!-- Logo ve Slogan -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ url('/') }}" class="flex-shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Rise English Logo" class="h-14 sm:h-16 md:h-18">
                </a>
                <div class="ml-2 sm:ml-4 border-l-2 border-[#e63946] pl-2 sm:pl-4">
                    <span class="text-[#1a2e5a] font-serif italic text-xs xs:text-sm sm:text-base md:text-lg lg:text-xl font-bold block leading-tight">
                        Hakan Hoca <br class="sm:hidden">Eğitim Hayatınızda<br class="sm:hidden"> Başarılar Diler.
                    </span>
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="hidden lg:flex items-center ml-auto mr-6 space-x-6 text-base">
                <a href="{{ url('/ana-sayfa') }}" class="text-gray-700 hover:text-red-600 font-medium transition duration-200 {{ request()->is('ana-sayfa') ? 'text-red-600' : '' }}">Ana Sayfa</a>
                <a href="{{ route('public.resources.index') }}" class="text-gray-700 hover:text-red-600 font-medium transition duration-200 {{ request()->is('ucretsiz-kaynaklar') ? 'text-red-600' : '' }}">Ücretsiz İçerikler</a>
                <a href="{{ url('/egitimler') }}" class="text-gray-700 hover:text-red-600 font-medium transition duration-200 {{ request()->is('egitimler') ? 'text-red-600' : '' }}">Eğitimler</a>
                <a href="{{ url('/iletisim') }}" class="text-gray-700 hover:text-red-600 font-medium transition duration-200 {{ request()->is('iletisim') ? 'text-red-600' : '' }}">İletişim</a>
                @if(auth()->check() && auth()->user()->hasRole('ogretmen'))
                <a href="{{ route('ogretmen.private-lessons.index') }}" 
                   class="text-gray-700 hover:text-red-600 font-medium transition duration-200 
                          {{ request()->is('ogretmen/ozel-derslerim') ? 'text-red-600' : '' }}">
                    Özel Ders
                </a>
            @endif
            @if(auth()->check() && auth()->user()->hasRole('ogrenci'))
            <a href="{{ route('ogrenci.private-lessons.index') }}" 
               class="text-gray-700 hover:text-red-600 font-medium transition duration-200 
                      {{ request()->is('ogrenci/ozel-derslerim') ? 'text-red-600' : '' }}">
                Özel Derslerim
            </a>
        @endif
            </div>
            
            <!-- Sağ Üst Alan: Giriş yapılmışsa profil veya oturum aç/kapat -->
            <div class="flex-shrink-0 relative">
                @auth
                    @if(auth()->user()->hasRole('ogrenci'))
                        <!-- Öğrenci: Profil simgesi ve açılır menü -->
                        <button id="profile-btn" class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded-lg focus:outline-none cursor-pointer">
                            <span class="text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <!-- Açılır Menü -->
                        <div id="profile-dropdown" class="absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg hidden">
                            <a href="{{ route('ogrenci.settings.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Ayarlar</a>
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Çıkış Yap</a>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <!-- Diğer roller: Sadece Oturumu Kapat butonu -->
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-bold py-2 sm:py-3 px-4 sm:px-8 text-sm sm:text-base rounded-lg border-2 border-[#e63946] shadow-lg hover:shadow-xl transition-all duration-300 flex items-center space-x-1 sm:space-x-2 transform hover:-translate-y-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V7.414a1 1 0 00-.293-.707L11.414 2H5a1 1 0 00-1 1v4.586l2.293-2.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0L3 13.414V3z" clip-rule="evenodd" />
                            </svg>
                            <span>Oturumu Kapat</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endif
                @else
                    <!-- Giriş yapılmamışsa Oturum Aç butonu -->
                    <a href="{{ url('/oturum-ac') }}" class="bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-bold py-2 sm:py-3 px-4 sm:px-8 text-sm sm:text-base rounded-lg border-2 border-[#e63946] shadow-lg hover:shadow-xl transition-all duration-300 flex items-center space-x-1 sm:space-x-2 transform hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        <span>Oturum Aç</span>
                    </a>
                @endauth
                
            </div>
            
            
            <!-- Mobile Menu Button (hidden on desktop) -->
            <div class="lg:hidden flex items-center">
                <button class="mobile-menu-button" aria-label="Menüyü Aç">
                    <i class="fas fa-bars text-blue-900 text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu (hidden by default) -->
    <div class="lg:hidden mobile-menu hidden px-4 py-2 bg-white border-t">
        <a href="{{ url('/ana-sayfa') }}" class="block py-2 text-gray-700 hover:text-red-600 font-medium {{ request()->is('ana-sayfa') ? 'text-red-600' : '' }}">Ana Sayfa</a>
        <a href="{{ route('public.resources.index') }}" class="block py-2 text-gray-700 hover:text-red-600 font-medium {{ request()->is('ucretsiz-icerikler') ? 'text-red-600' : '' }}">Ücretsiz İçerikler</a>        <a href="{{ url('/iletisim') }}" class="block py-2 text-gray-700 hover:text-red-600 font-medium {{ request()->is('iletisim') ? 'text-red-600' : '' }}">İletişim</a>
        @if(auth()->check() && auth()->user()->hasRole('ogretmen'))
        <a href="{{ route('ogretmen.private-lessons.index') }}" 
           class="block py-2 text-gray-700 hover:text-red-600 font-medium 
                  {{ request()->is('ogretmen/ozel-derslerim') ? 'text-red-600' : '' }}">
            Özel Ders
        </a>
    @endif
    @if(auth()->check() && auth()->user()->hasRole('ogrenci'))
    <a href="{{ route('ogrenci.private-lessons.index') }}" 
       class="block py-2 text-gray-700 hover:text-red-600 font-medium 
              {{ request()->is('ogrenci/ozel-derslerim') ? 'text-red-600' : '' }}">
        Özel Derslerim
    </a>
@endif
    </div>
</div>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const mobileMenu = document.querySelector('.mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
        
        // Profil açılır menü toggle
        const profileBtn = document.getElementById('profile-btn');
        const profileDropdown = document.getElementById('profile-dropdown');
        
        if(profileBtn && profileDropdown){
            profileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');
            });
            
            // Sayfa herhangi bir yerine tıklandığında menüyü kapat
            document.addEventListener('click', function() {
                profileDropdown.classList.add('hidden');
            });
        }
    });
</script>