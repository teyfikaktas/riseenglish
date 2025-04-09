<div class="sticky top-0 w-full bg-white shadow-sm z-50 border-b border-gray-200">
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
            <div class="w-full md:w-auto md:flex md:items-center md:justify-end">
                <!-- Navigation - sadece lg (1024px ve üzeri) ekranlarda görünür -->
                <nav class="hidden lg:flex lg:flex-wrap lg:items-center lg:space-x-4 lg:mr-4">
                    <!-- Ana Sayfa -->
                    <div class="relative border-2 border-[#1a2e5a] rounded-lg p-0.5 bg-white shadow-sm hover:border-[#e63946] hover:shadow-md transition-all duration-300 hover:-translate-y-0.5 {{ request()->is('ana-sayfa') ? 'bg-[#1a2e5a] border-[#e63946]' : '' }}">
                        <a href="{{ url('/ana-sayfa') }}" class="block rounded px-3 py-1.5 font-semibold text-[#e63946] whitespace-nowrap {{ request()->is('ana-sayfa') ? 'text-white' : '' }}">
                            Ana Sayfa
                        </a>
                    </div>
                    
                    <!-- Ücretsiz İçerikler -->
                    <div class="relative border-2 border-[#1a2e5a] rounded-lg p-0.5 bg-white shadow-sm hover:border-[#e63946] hover:shadow-md transition-all duration-300 hover:-translate-y-0.5 {{ request()->routeIs('public.resources.index') ? 'bg-[#1a2e5a] border-[#e63946]' : '' }}">
                        <a href="{{ route('public.resources.index') }}" class="block rounded px-3 py-1.5 font-semibold text-[#e63946] whitespace-nowrap {{ request()->routeIs('public.resources.index') ? 'text-white' : '' }}">
                            Ücretsiz İçerikler
                        </a>
                    </div>
                    
                    <!-- Eğitimler -->
                    <div class="relative border-2 border-[#1a2e5a] rounded-lg p-0.5 bg-white shadow-sm hover:border-[#e63946] hover:shadow-md transition-all duration-300 hover:-translate-y-0.5 {{ request()->is('egitimler') ? 'bg-[#1a2e5a] border-[#e63946]' : '' }}">
                        <a href="{{ url('/egitimler') }}" class="block rounded px-3 py-1.5 font-semibold text-[#e63946] whitespace-nowrap {{ request()->is('egitimler') ? 'text-white' : '' }}">
                            Eğitimler
                        </a>
                    </div>
                    
                    <!-- İletişim -->
                    <div class="relative border-2 border-[#1a2e5a] rounded-lg p-0.5 bg-white shadow-sm hover:border-[#e63946] hover:shadow-md transition-all duration-300 hover:-translate-y-0.5 {{ request()->is('iletisim') ? 'bg-[#1a2e5a] border-[#e63946]' : '' }}">
                        <a href="{{ url('/iletisim') }}" class="block rounded px-3 py-1.5 font-semibold text-[#e63946] whitespace-nowrap {{ request()->is('iletisim') ? 'text-white' : '' }}">
                            İletişim
                        </a>
                    </div>
                    
                    <!-- Özel Ders Linkleri -->
                    @if(auth()->check() && auth()->user()->hasRole('ogretmen'))
                    <div class="relative border-2 border-[#1a2e5a] rounded-lg p-0.5 bg-white shadow-sm hover:border-[#e63946] hover:shadow-md transition-all duration-300 hover:-translate-y-0.5 {{ request()->routeIs('ogretmen.private-lessons.index') ? 'bg-[#1a2e5a] border-[#e63946]' : '' }}">
                        <a href="{{ route('ogretmen.private-lessons.index') }}" class="block rounded px-3 py-1.5 font-semibold text-[#e63946] whitespace-nowrap {{ request()->routeIs('ogretmen.private-lessons.index') ? 'text-white' : '' }}">
                            Özel Ders
                        </a>
                    </div>
                    @endif
                    
                    @if(auth()->check() && auth()->user()->hasRole('ogrenci'))
                    <div class="relative border-2 border-[#1a2e5a] rounded-lg p-0.5 bg-white shadow-sm hover:border-[#e63946] hover:shadow-md transition-all duration-300 hover:-translate-y-0.5 {{ request()->routeIs('ogrenci.private-lessons.index') ? 'bg-[#1a2e5a] border-[#e63946]' : '' }}">
                        <a href="{{ route('ogrenci.private-lessons.index') }}" class="block rounded px-3 py-1.5 font-semibold text-[#e63946] whitespace-nowrap {{ request()->routeIs('ogrenci.private-lessons.index') ? 'text-white' : '' }}">
                            Özel Derslerim
                        </a>
                    </div>
                    @endif
                </nav>

                <!-- Sağ Taraf Kimlik Doğrulama ve Mobil Menü -->
                <div class="flex items-center justify-between w-full md:w-auto">
                    <!-- Mobil Menü Butonu (lg ekrana kadar görünür) -->
                    <div class="lg:hidden flex items-center order-first">
                        <button class="mobile-menu-button p-2 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#e63946]" aria-label="Menüyü Aç">
                            <i class="fas fa-bars text-[#1a2e5a] text-xl"></i>
                        </button>
                    </div>

                    <!-- Oturum Aç/Kapat Düğmesi -->
                    <div class="relative ml-auto md:ml-4">
                        @auth
                            @if(auth()->user()->hasRole('ogrenci'))
                                <!-- Öğrenci: Profil -->
                                <button id="profile-btn" class="flex items-center space-x-2 border-2 border-[#1a2e5a] rounded-lg p-0.5 bg-white shadow-sm hover:border-[#e63946] hover:shadow-md focus:outline-none">
                                    <div class="flex items-center px-3 py-1.5">
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
                                <div class="border-2 border-[#1a2e5a] rounded-lg p-0.5 bg-white shadow-sm hover:border-[#e63946] hover:shadow-md">
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form-other').submit();"
                                       class="flex items-center px-3 py-1.5 text-[#e63946] font-semibold">
                                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                             <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                         </svg>
                                        <span>Çıkış</span>
                                    </a>
                                </div>
                                <form id="logout-form-other" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf </form>
                            @endif
                        @else
                            <!-- Giriş Butonu -->
                            <a href="{{ url('/oturum-ac') }}" class="inline-flex items-center bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                 </svg>
                                <span>Giriş</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobil/Tablet Menü - lg ekrana kadar görünür olacak şekilde ayarlandı -->
    <div class="lg:hidden mobile-menu hidden absolute top-full left-0 w-full bg-white border-t border-gray-200 shadow-lg p-4 space-y-4 z-50">
        <div class="w-full border-2 {{ request()->is('ana-sayfa') ? 'bg-[#1a2e5a] border-[#e63946]' : 'border-[#1a2e5a]' }} rounded-lg p-0.5 hover:border-[#e63946]">
            <a href="{{ url('/ana-sayfa') }}" class="block w-full px-3 py-2 font-semibold {{ request()->is('ana-sayfa') ? 'text-white' : 'text-[#e63946]' }}">
                Ana Sayfa
            </a>
        </div>
        
        <div class="w-full border-2 {{ request()->routeIs('public.resources.index') ? 'bg-[#1a2e5a] border-[#e63946]' : 'border-[#1a2e5a]' }} rounded-lg p-0.5 hover:border-[#e63946]">
            <a href="{{ route('public.resources.index') }}" class="block w-full px-3 py-2 font-semibold {{ request()->routeIs('public.resources.index') ? 'text-white' : 'text-[#e63946]' }}">
                Ücretsiz İçerikler
            </a>
        </div>
        
        <div class="w-full border-2 {{ request()->is('egitimler') ? 'bg-[#1a2e5a] border-[#e63946]' : 'border-[#1a2e5a]' }} rounded-lg p-0.5 hover:border-[#e63946]">
            <a href="{{ url('/egitimler') }}" class="block w-full px-3 py-2 font-semibold {{ request()->is('egitimler') ? 'text-white' : 'text-[#e63946]' }}">
                Eğitimler
            </a>
        </div>
        
        <div class="w-full border-2 {{ request()->is('iletisim') ? 'bg-[#1a2e5a] border-[#e63946]' : 'border-[#1a2e5a]' }} rounded-lg p-0.5 hover:border-[#e63946]">
            <a href="{{ url('/iletisim') }}" class="block w-full px-3 py-2 font-semibold {{ request()->is('iletisim') ? 'text-white' : 'text-[#e63946]' }}">
                İletişim
            </a>
        </div>
        
        {{-- Özel Ders Linkleri (Mobil/Tablet) --}}
        @if(auth()->check() && auth()->user()->hasRole('ogretmen'))
        <div class="w-full border-2 {{ request()->routeIs('ogretmen.private-lessons.index') ? 'bg-[#1a2e5a] border-[#e63946]' : 'border-[#1a2e5a]' }} rounded-lg p-0.5 hover:border-[#e63946]">
            <a href="{{ route('ogretmen.private-lessons.index') }}" class="block w-full px-3 py-2 font-semibold {{ request()->routeIs('ogretmen.private-lessons.index') ? 'text-white' : 'text-[#e63946]' }}">
                Özel Ders
            </a>
        </div>
        @endif
        
        @if(auth()->check() && auth()->user()->hasRole('ogrenci'))
        <div class="w-full border-2 {{ request()->routeIs('ogrenci.private-lessons.index') ? 'bg-[#1a2e5a] border-[#e63946]' : 'border-[#1a2e5a]' }} rounded-lg p-0.5 hover:border-[#e63946]">
            <a href="{{ route('ogrenci.private-lessons.index') }}" class="block w-full px-3 py-2 font-semibold {{ request()->routeIs('ogrenci.private-lessons.index') ? 'text-white' : 'text-[#e63946]' }}">
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