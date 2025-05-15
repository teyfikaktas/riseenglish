<!-- Modern, Small & Clean Navbar -->
<div class="sticky top-0 w-full bg-white shadow z-50">
  <div class="container mx-auto px-1 sm:px-2">

    <!-- Mobile Motto -->
    <div class="lg:hidden pt-1">
      <div class="text-center">
        <span class="text-[#1a2e5a] font-serif italic text-xs font-bold block leading-tight">
          Hakan Hoca Eğitim Hayatınızda Başarılar Diler.
        </span>
      </div>
    </div>

    <!-- Mobile Navigation -->
    <div class="flex lg:hidden items-center justify-between h-10 px-1">
      <!-- Menu Button -->
      <button class="mobile-menu-button p-1 rounded-md hover:bg-gray-100 transition" aria-label="Menüyü Aç">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <!-- Logo -->
      <a href="{{ url('/') }}" class="flex-shrink-0">
        <img src="{{ asset('images/logo.png') }}" alt="Rise English Logo" class="h-7 xs:h-9">
      </a>

      <!-- Login Button -->
      <div class="flex items-center">
        @guest
          <a href="{{ url('/oturum-ac') }}"
            class="bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-semibold py-0.5 px-2 text-xs rounded-md border border-[#e63946] shadow transition flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                clip-rule="evenodd" />
            </svg>
            <span>Giriş</span>
          </a>
        @else
          <div class="w-8"></div>
        @endguest
      </div>
    </div>

    <!-- Desktop Navigation -->
    <div class="hidden lg:flex items-center justify-between h-20 px-1">
      <!-- Logo & Motto -->
      <div class="flex-shrink-0 flex items-center">
        <a href="{{ url('/') }}" class="flex-shrink-0">
          <img src="{{ asset('images/logo.png') }}" alt="Rise English Logo" class="h-12">
        </a>
        <div class="ml-2 border-l border-[#e63946] pl-2">
          <span class="text-[#1a2e5a] font-serif italic text-xs font-bold block leading-tight whitespace-nowrap">
            Hakan Hoca Eğitim Hayatınızda Başarılar Diler.
          </span>
        </div>
      </div>

      <!-- Desktop Links -->
      <div class="flex items-center ml-auto mr-1 gap-1 text-xs">
        <!-- Ana Sayfa -->
        <a href="{{ url('/') }}"
          class="menu-link {{ request()->is('/') || request()->is('ana-sayfa') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          <span>Ana Sayfa</span>
        </a>

        <!-- Ücretsiz İçerikler -->
        <a href="{{ route('public.resources.index') }}"
          class="menu-link {{ request()->is('ucretsiz-kaynaklar') || request()->is('ucretsiz-kaynaklar/*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
          <span>Ücretsiz İçerikler</span>
        </a>

        <!-- Eğitimler -->
        <a href="{{ url('/egitimler') }}"
          class="menu-link {{ request()->is('egitimler') || request()->is('egitimler/*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path d="M12 14l9-5-9-5-9 5 9 5z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14z"/>
          </svg>
          <span>Eğitimler</span>
        </a>

        <!-- İletişim -->
        <a href="{{ url('/iletisim') }}"
          class="menu-link {{ request()->is('iletisim') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
          <span>İletişim</span>
        </a>

        <!-- Belgeler -->
        @if(auth()->check() && auth()->user()->hasRole('ogretmen'))
        <a href="{{ url('/ogretmen/belgeler') }}"
          class="menu-link {{ request()->is('ogretmen/belgeler') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <span>Belgeler</span>
        </a>
        @elseif(auth()->check() && auth()->user()->hasRole('ogrenci'))
        <a href="{{ url('/ogrenci/belgeler') }}"
          class="menu-link {{ request()->is('ogrenci/belgeler') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <span>Belgeler</span>
        </a>
        @endif

        <!-- Zinciri Kırma -->
        @if(!(auth()->check() && auth()->user()->hasRole('ogretmen')))
        <a href="{{ route('zinciri-kirma') }}"
          class="menu-link {{ request()->is('zinciri-kirma') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
          </svg>
          <span>Zinciri Kırma</span>
        </a>
        @endif
        @if(auth()->check() && auth()->user()->hasRole('ogretmen'))
        <a href="{{ route('ogretmen.chain-breaker-dashboard') }}"
        class="menu-link {{ request()->is('ogretmen/zinciri-kirma-takip') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
        </svg>
        <span>Zinciri Kırma Takip</span>
        </a>
        @endif
        <!-- Özel Ders / Derslerim -->
        @if(auth()->check() && auth()->user()->hasRole('ogretmen'))
        <a href="{{ route('ogretmen.private-lessons.index') }}"
          class="menu-link {{ request()->is('ogretmen/ozel-derslerim') || request()->is('ogretmen/ozel-derslerim/*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
          <span>Özel Ders</span>
        </a>
        @elseif(auth()->check() && auth()->user()->hasRole('ogrenci'))
        <a href="{{ route('ogrenci.private-lessons.index') }}"
          class="menu-link {{ request()->is('ogrenci/ozel-derslerim') || request()->is('ogrenci/ozel-derslerim/*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
          <span>Derslerim</span>
        </a>
        @endif
      </div>

      <!-- Login/Profile Button -->
      <div class="flex-shrink-0">
        @auth
        @if(auth()->user()->hasRole('ogrenci'))
          <button id="profile-btn"
            class="bg-white hover:bg-gray-50 text-gray-700 font-medium text-xs py-1 px-2 rounded-md border border-gray-200 shadow flex items-center focus:outline-none">
            <span class="mr-1">{{ auth()->user()->name }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
              viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div id="profile-dropdown"
            class="absolute right-0 mt-1 w-40 bg-white border rounded-md shadow-lg hidden z-50">
            <a href="{{ route('ogrenci.settings.index') }}"
              class="block px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 text-sm">
              <span class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500"
                  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Ayarlar</span>
              </span>
            </a>
            <div class="border-t border-gray-100"></div>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
              class="block px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 text-sm">
              <span class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500"
                  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Çıkış Yap</span>
              </span>
            </a>
          </div>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        @else
          <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();"
            class="bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-bold py-1 px-2 text-xs rounded-md border border-[#e63946] shadow transition flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V7.414a1 1 0 00-.293-.707L11.414 2H5a1 1 0 00-1 1v4.586l2.293-2.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0L3 13.414V3z"
                clip-rule="evenodd"/>
            </svg>
            <span>Oturumu Kapat</span>
          </a>
          <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        @endif
        @else
          <a href="{{ url('/oturum-ac') }}"
            class="bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-bold py-1 px-2 text-xs rounded-md border border-[#e63946] shadow transition flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                clip-rule="evenodd"/>
            </svg>
            <span>Oturum Aç</span>
          </a>
        @endauth
      </div>
    </div>
  </div>

  <!-- Mobile Menu Dropdown -->
<!-- Mobile Menu Dropdown -->
<div class="lg:hidden mobile-menu hidden bg-white border-t shadow-inner">
  <div class="py-1 divide-y divide-gray-100">
    <!-- Ana Sayfa -->
    <a href="{{ url('/') }}"
      class="menu-link block border-0 bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-1 px-2 mx-1.5 my-1 rounded-md flex items-center transition text-xs {{ request()->is('/') || request()->is('ana-sayfa') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
      </svg>
      <span>Ana Sayfa</span>
    </a>

    <!-- Ücretsiz İçerikler -->
    <a href="{{ route('public.resources.index') }}"
      class="menu-link block border-0 bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-1 px-2 mx-1.5 my-1 rounded-md flex items-center transition text-xs {{ request()->is('ucretsiz-kaynaklar') || request()->is('ucretsiz-kaynaklar/*') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
      </svg>
      <span>Ücretsiz İçerikler</span>
    </a>

    <!-- Eğitimler -->
    <a href="{{ url('/egitimler') }}"
      class="menu-link block border-0 bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-1 px-2 mx-1.5 my-1 rounded-md flex items-center transition text-xs {{ request()->is('egitimler') || request()->is('egitimler/*') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path d="M12 14l9-5-9-5-9 5 9 5z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14z"/>
      </svg>
      <span>Eğitimler</span>
    </a>

    <!-- İletişim -->
    <a href="{{ url('/iletisim') }}"
      class="menu-link block border-0 bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-1 px-2 mx-1.5 my-1 rounded-md flex items-center transition text-xs {{ request()->is('iletisim') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
      </svg>
      <span>İletişim</span>
    </a>
<!-- Zinciri Kırma Takip (Öğretmen için) -->
@if(auth()->check() && auth()->user()->hasRole('ogretmen'))
<a href="{{ route('ogretmen.chain-breaker-dashboard') }}"
  class="menu-link block border-0 bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-1 px-2 mx-1.5 my-1 rounded-md flex items-center transition text-xs {{ request()->is('ogretmen/zinciri-kirma-takip') ? 'active' : '' }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
       viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
  </svg>
  <span>Zinciri Kırma Takip</span>
</a>
@endif
    <!-- Belgeler (Öğretmen veya Öğrenci) -->
    @if(auth()->check() && auth()->user()->hasRole('ogretmen'))
    <a href="{{ url('/ogretmen/belgeler') }}"
      class="menu-link block border-0 bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-1 px-2 mx-1.5 my-1 rounded-md flex items-center transition text-xs {{ request()->is('ogretmen/belgeler') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
           viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
      </svg>
      <span>Belgeler</span>
    </a>
    @elseif(auth()->check() && auth()->user()->hasRole('ogrenci'))
    <a href="{{ url('/ogrenci/belgeler') }}"
      class="menu-link block border-0 bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-1 px-2 mx-1.5 my-1 rounded-md flex items-center transition text-xs {{ request()->is('ogrenci/belgeler') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
           viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
      </svg>
      <span>Belgeler</span>
    </a>
    @endif

    <!-- Zinciri Kırma (Öğretmen değilse) -->
    @if(!(auth()->check() && auth()->user()->hasRole('ogretmen')))
    <a href="{{ route('zinciri-kirma') }}"
      class="menu-link block border-0 bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-1 px-2 mx-1.5 my-1 rounded-md flex items-center transition text-xs {{ request()->is('zinciri-kirma') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
           viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
      </svg>
      <span>Zinciri Kırma</span>
    </a>
    @endif

    <!-- Özel Ders / Derslerim -->
    @if(auth()->check() && auth()->user()->hasRole('ogretmen'))
    <a href="{{ route('ogretmen.private-lessons.index') }}"
      class="menu-link block border-0 bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-1 px-2 mx-1.5 my-1 rounded-md flex items-center transition text-xs {{ request()->is('ogretmen/ozel-derslerim') || request()->is('ogretmen/ozel-derslerim/*') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
           viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
      </svg>
      <span>Özel Ders</span>
    </a>
    @elseif(auth()->check() && auth()->user()->hasRole('ogrenci'))
    <a href="{{ route('ogrenci.private-lessons.index') }}"
      class="menu-link block border-0 bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-1 px-2 mx-1.5 my-1 rounded-md flex items-center transition text-xs {{ request()->is('ogrenci/ozel-derslerim') || request()->is('ogrenci/ozel-derslerim/*') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
           viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
      </svg>
      <span>Derslerim</span>
    </a>
    @endif
  </div>
</div>


<!-- Zinciri Kırma Top Bar (Sadece Öğrenciler İçin) -->
@auth
  @if(auth()->user()->hasRole('ogrenci') && ($chainProgress = auth()->user()->chainProgress))
    @livewire('chain-breaker-top-bar')
  @endif
@endauth

<!-- Ekstra Modernleşme için Stil: -->
<style>
.menu-link {
  border: 1.5px solid transparent;
  background: #e63946;
  color: #fff;
  font-weight: 600;
  padding: 0.25rem 0.8rem;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  transition: all 0.18s cubic-bezier(.4,0,.2,1);
  box-shadow: 0 1px 4px rgba(230,57,70,.04);
  gap: 0.25rem;
  font-size: 0.85rem;
}
.menu-link:hover,
.menu-link.active {
  background: #d62836 !important;
  border-color: #fff !important;
  box-shadow: 0 2px 12px rgba(230,57,70,.15);
  color: #fff !important;
}
.menu-link.active {
  ring: 2px solid #e63946;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Mobile menu toggle
  const mobileMenuButton = document.querySelector('.mobile-menu-button');
  const mobileMenu = document.querySelector('.mobile-menu');
  if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener('click', function() {
      mobileMenu.classList.toggle('hidden');
    });
    mobileMenu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', function() {
        mobileMenu.classList.add('hidden');
      });
    });
  }

  // Profile dropdown
  const profileBtn = document.getElementById('profile-btn');
  const profileDropdown = document.getElementById('profile-dropdown');
  if(profileBtn && profileDropdown){
    profileBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      profileDropdown.classList.toggle('hidden');
    });
    document.addEventListener('click', function(e) {
      if (!profileDropdown.classList.contains('hidden') &&
          !profileDropdown.contains(e.target) && !profileBtn.contains(e.target)) {
        profileDropdown.classList.add('hidden');
      }
    });
    profileDropdown.addEventListener('click', function(e) {
      if (e.target.tagName === 'A') profileDropdown.classList.add('hidden');
    });
  }
});
</script>
