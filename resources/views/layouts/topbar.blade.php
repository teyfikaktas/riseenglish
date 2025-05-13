<!-- Improved Navbar with Reduced Spacing and Consistent Button Styling -->
<div class="sticky top-0 w-full bg-white shadow-md z-50">
    <div class="container mx-auto px-2 sm:px-4">

        <!-- Mobile Motto -->
        <div class="lg:hidden pt-2">
            <div class="text-center">
                <span class="text-[#1a2e5a] font-serif italic text-xs font-bold block leading-tight">
                    Hakan Hoca Eğitim Hayatınızda Başarılar Diler.
                </span>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="flex lg:hidden items-center justify-between h-14 px-2">
            <!-- Menu Button -->
            <div class="flex justify-start">
                <button class="mobile-menu-button p-1 rounded-md hover:bg-gray-100 focus:outline-none transition duration-200" aria-label="Menüyü Aç">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Logo -->
            <div class="flex justify-center">
                <a href="{{ url('/') }}" class="flex-shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Rise English Logo" class="h-10 xs:h-12">
                </a>
            </div>

            <!-- Login Button -->
            <div class="flex justify-end items-center">
                @guest
                    <a href="{{ url('/oturum-ac') }}" class="bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-bold py-1 px-2 text-xs rounded-lg border-2 border-[#e63946] shadow hover:shadow-md transition-all duration-300 flex items-center space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        <span>Giriş</span>
                    </a>
                @else
                    <div class="w-12"></div>
                @endguest
            </div>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center justify-between h-20 px-2">
            <!-- Logo and Motto -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ url('/') }}" class="flex-shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Rise English Logo" class="h-16">
                </a>
                <div class="ml-3 border-l-2 border-[#e63946] pl-3">
                    <span class="text-[#1a2e5a] font-serif italic lg:text-sm xl:text-base font-bold block leading-tight whitespace-nowrap">
                        Hakan Hoca Eğitim Hayatınızda Başarılar Diler.
                    </span>
                </div>
            </div>

            <!-- Navigation Links -->
<!-- Navigation Links - Desktop -->
<div class="flex items-center ml-auto mr-2 space-x-1 text-sm">
    <a href="{{ url('/') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-3 rounded-lg flex items-center {{ request()->is('/') || request()->is('ana-sayfa') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
        <span>Ana Sayfa</span>
    </a>
    <a href="{{ route('public.resources.index') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-3 rounded-lg flex items-center {{ request()->is('ucretsiz-kaynaklar') || request()->is('ucretsiz-kaynaklar/*') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        <span>Ücretsiz İçerikler</span>
    </a>
    <a href="{{ url('/egitimler') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-3 rounded-lg flex items-center {{ request()->is('egitimler') || request()->is('egitimler/*') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
        <span>Eğitimler</span>
    </a>
    <a href="{{ url('/iletisim') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-3 rounded-lg flex items-center {{ request()->is('iletisim') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
        <span>İletişim</span>
    </a>
    
    @if(auth()->check() && auth()->user()->hasRole('ogretmen'))
    <a href="{{ route('ogretmen.private-lessons.index') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-3 rounded-lg flex items-center {{ request()->is('ogretmen/ozel-derslerim') || request()->is('ogretmen/ozel-derslerim/*') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
        <span>Özel Ders</span>
    </a>
    @endif
    <!-- Navigation Links - Desktop -->
@if(auth()->check() && auth()->user()->hasRole('ogretmen'))
<a href="{{ route('ogretmen.chain-breaker-dashboard') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-3 rounded-lg flex items-center {{ request()->is('ogretmen/zinciri-kirma-takip') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
    </svg>
    <span>Zincir Takip</span>
</a>
@endif
    @if(auth()->check() && auth()->user()->hasRole('ogrenci'))
    {{-- <a href="{{ route('zinciri-kirma') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-3 rounded-lg flex items-center {{ request()->is('zinciri-kirma') || request()->is('zinciri-kirma/*') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
        </svg>
        <span>Zinciri Kırma</span>
    </a> --}}
    
    <a href="{{ route('ogrenci.private-lessons.index') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-3 rounded-lg flex items-center {{ request()->is('ogrenci/ozel-derslerim') || request()->is('ogrenci/ozel-derslerim/*') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        <span>Derslerim</span>
    </a>
    @endif
</div>

            <!-- Login/Profile Button -->
            <div class="flex-shrink-0">
                @auth
                   @if(auth()->user()->hasRole('ogrenci'))
                       <button id="profile-btn" class="bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm py-2 px-4 rounded-lg border border-gray-200 shadow-sm flex items-center focus:outline-none">
                           <span class="mr-2">{{ auth()->user()->name }}</span>
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                           </svg>
                       </button>
                       <div id="profile-dropdown" class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg hidden overflow-hidden z-50">
                           <a href="{{ route('ogrenci.settings.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                              <span class="flex items-center">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                  </svg>
                                  <span class="font-medium">Ayarlar</span>
                              </span>
                           </a>
                           <div class="border-t border-gray-100"></div>
                           <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                               <span class="flex items-center">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                   </svg>
                                   <span class="font-medium">Çıkış Yap</span>
                               </span>
                           </a>
                       </div>
                       <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                   @else
                       <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();" class="bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-bold py-2 px-3 text-sm rounded-lg border-2 border-[#e63946] shadow-md hover:shadow-lg transition-all duration-300 flex items-center space-x-1">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V7.414a1 1 0 00-.293-.707L11.414 2H5a1 1 0 00-1 1v4.586l2.293-2.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0L3 13.414V3z" clip-rule="evenodd" /></svg>
                           <span>Oturumu Kapat</span>
                       </a>
                       <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                   @endif
               @else
                   <a href="{{ url('/oturum-ac') }}" class="bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-bold py-2 px-3 text-sm rounded-lg border-2 border-[#e63946] shadow-md hover:shadow-lg transition-all duration-300 flex items-center space-x-1">
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" /></svg>
                       <span>Oturum Aç</span>
                   </a>
               @endauth
           </div>
        </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div class="lg:hidden mobile-menu hidden bg-white border-t shadow-inner">
        <div class="py-2 divide-y divide-gray-100">
            <a href="{{ url('/') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-4 mx-4 my-2 rounded-lg flex items-center {{ request()->is('/') || request()->is('ana-sayfa') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <span>Ana Sayfa</span>
            </a>
            <!-- More mobile menu items -->
            <a href="{{ route('public.resources.index') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-4 mx-4 my-2 rounded-lg flex items-center {{ request()->is('ucretsiz-kaynaklar') || request()->is('ucretsiz-kaynaklar/*') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <span>Ücretsiz İçerikler</span>
            </a>
            <a href="{{ url('/egitimler') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-4 mx-4 my-2 rounded-lg flex items-center {{ request()->is('egitimler') || request()->is('egitimler/*') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                <span>Eğitimler</span>
            </a>
            <a href="{{ url('/iletisim') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-4 mx-4 my-2 rounded-lg flex items-center {{ request()->is('iletisim') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                <span>İletişim</span>
            </a>
    
            <!-- Role-specific menu items -->
            @auth
                @if(auth()->user()->hasRole('ogretmen'))
                <a href="{{ route('ogretmen.private-lessons.index') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-4 mx-4 my-2 rounded-lg flex items-center {{ request()->is('ogretmen/ozel-derslerim') || request()->is('ogretmen/ozel-derslerim/*') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    <span>Özel Ders</span>
                </a>
                           <a href="{{ route('ogretmen.chain-breaker-dashboard') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-4 mx-4 my-2 rounded-lg flex items-center {{ request()->is('ogretmen/zinciri-kirma-takip') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Zincir Takip</span>
            </a>
                @endif
    
                @if(auth()->user()->hasRole('ogrenci'))
                <a href="{{ route('zinciri-kirma') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-4 mx-4 my-2 rounded-lg flex items-center {{ request()->is('zinciri-kirma') || request()->is('zinciri-kirma/*') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    <span>Zinciri Kırma</span>
                </a>
    
                <a href="{{ route('ogrenci.private-lessons.index') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-4 mx-4 my-2 rounded-lg flex items-center {{ request()->is('ogrenci/ozel-derslerim') || request()->is('ogrenci/ozel-derslerim/*') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    <span>Özel Derslerim</span>
                </a>
    
                <a href="{{ route('ogrenci.settings.index') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-4 mx-4 my-2 rounded-lg flex items-center {{ request()->is('ogrenci/ayarlar') ? 'border-2 border-white shadow-lg ring-2 ring-red-400 bg-[#d62836]' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span>Ayarlar</span>
                </a>
                @endif
    
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();" class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    <span class="font-medium">Çıkış Yap</span>
                </a>
                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            @endauth
        </div>
    </div>
</div>

<!-- Zinciri Kırma Top Bar - Öğrenci kullanıcıları için navbar'ın hemen altına ekleyin -->
@auth
@if(auth()->user()->hasRole('ogrenci') && ($chainProgress = auth()->user()->chainProgress))
@livewire('chain-breaker-top-bar')
@endif
@endauth

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle functionality
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.querySelector('.mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Close menu when clicking menu items
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
            });
        });
    }

    // Profile dropdown functionality
    const profileBtn = document.getElementById('profile-btn');
    const profileDropdown = document.getElementById('profile-dropdown');

    if(profileBtn && profileDropdown){
        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (profileDropdown && !profileDropdown.classList.contains('hidden') && 
                !profileDropdown.contains(e.target) && !profileBtn.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
        });

        // Close dropdown when clicking menu items
        profileDropdown.addEventListener('click', function(e) {
            if (e.target.tagName === 'A') {
                profileDropdown.classList.add('hidden');
            }
        });
    }
});
</script>