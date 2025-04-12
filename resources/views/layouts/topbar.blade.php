<div class="sticky top-0 w-full bg-white shadow-md z-50">
    <div class="container mx-auto">

        <div class="lg:hidden px-2 sm:px-4 pt-2">
            <div class="text-center">
                 <span class="text-[#1a2e5a] font-serif italic text-xs font-bold block leading-tight">
                     Hakan Hoca Eğitim Hayatınızda Başarılar Diler.
                 </span>
            </div>
        </div>

        <div class="flex lg:hidden items-center justify-between h-16 px-2 sm:px-4">
            <div class="flex justify-start">
                <button class="mobile-menu-button p-2 -ml-2 rounded-md hover:bg-gray-100 focus:outline-none transition duration-200" aria-label="Menüyü Aç">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <div class="flex justify-center">
                <a href="{{ url('/') }}" class="flex-shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Rise English Logo" class="h-12 xs:h-14">
                </a>
            </div>

            <div class="flex justify-end items-center">
                @guest
                    <a href="{{ url('/oturum-ac') }}" class="bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-bold py-1.5 px-3 text-xs sm:text-sm rounded-lg border-2 border-[#e63946] shadow hover:shadow-md transition-all duration-300 flex items-center space-x-1 transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        <span>Giriş</span>
                    </a>
                @else
                     <div class="w-16 sm:w-20"></div>
                @endguest
            </div>
        </div>

        <div class="hidden lg:flex items-center justify-between h-24 px-2 sm:px-4">
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ url('/') }}" class="flex-shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Rise English Logo" class="h-18">
                </a>
                <div class="ml-4 border-l-2 border-[#e63946] pl-4">
                    <span class="text-[#1a2e5a] font-serif italic lg:text-base xl:text-lg font-bold block leading-tight whitespace-nowrap">
                        Hakan Hoca Eğitim Hayatınızda Başarılar Diler.
                    </span>
                </div>
            </div>

            <div class="flex items-center ml-auto mr-2 xl:mr-4 space-x-1 xl:space-x-1 text-sm xl:text-base">
                <a href="{{ url('/') }}" class="py-2 px-2 xl:px-3 rounded-lg hover:bg-red-50 border border-transparent hover:border-red-200 {{ request()->is('/') || request()->is('ana-sayfa') ? 'bg-red-50 border-red-200 text-red-600 font-semibold' : 'text-gray-700' }}">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        <span class="inline">Ana Sayfa</span>
                    </span>
                </a>
                <a href="{{ route('public.resources.index') }}" class="py-2 px-2 xl:px-3 rounded-lg hover:bg-red-50 border border-transparent hover:border-red-200 {{ request()->is('ucretsiz-kaynaklar') || request()->is('ucretsiz-kaynaklar/*') ? 'bg-red-50 border-red-200 text-red-600 font-semibold' : 'text-gray-700' }}">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        <span class="inline">Ücretsiz İçerikler</span>
                    </span>
                </a>
                 <a href="{{ url('/egitimler') }}" class="py-2 px-2 xl:px-3 rounded-lg hover:bg-red-50 border border-transparent hover:border-red-200 {{ request()->is('egitimler') || request()->is('egitimler/*') ? 'bg-red-50 border-red-200 text-red-600 font-semibold' : 'text-gray-700' }}">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                        <span class="inline">Eğitimler</span>
                    </span>
                </a>
                 <a href="{{ url('/iletisim') }}" class="py-2 px-2 xl:px-3 rounded-lg hover:bg-red-50 border border-transparent hover:border-red-200 {{ request()->is('iletisim') ? 'bg-red-50 border-red-200 text-red-600 font-semibold' : 'text-gray-700' }}">
                    <span class="flex items-center">
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        <span class="inline">İletişim</span>
                    </span>
                </a>
                @if(auth()->check() && auth()->user()->hasRole('ogretmen'))
                <a href="{{ route('ogretmen.private-lessons.index') }}"
                   class="py-2 px-2 xl:px-3 rounded-lg hover:bg-red-50 border border-transparent hover:border-red-200 {{ request()->is('ogretmen/ozel-derslerim') || request()->is('ogretmen/ozel-derslerim/*') ? 'bg-red-50 border-red-200 text-red-600 font-semibold' : 'text-gray-700' }}">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        <span class="inline">Özel Ders</span>
                    </span>
                </a>
                @endif
                @if(auth()->check() && auth()->user()->hasRole('ogrenci'))
                <a href="{{ route('ogrenci.private-lessons.index') }}"
                   class="py-2 px-2 xl:px-3 rounded-lg hover:bg-red-50 border border-transparent hover:border-red-200 {{ request()->is('ogrenci/ozel-derslerim') || request()->is('ogrenci/ozel-derslerim/*') ? 'bg-red-50 border-red-200 text-red-600 font-semibold' : 'text-gray-700' }}">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        <span class="inline">Derslerim</span>
                    </span>
                </a>
                @endif
            </div>

            <div class="flex-shrink-0 relative">
                 @auth
                    @if(auth()->user()->hasRole('ogrenci'))
                        <button id="profile-btn" class="flex items-center space-x-2 p-2 hover:bg-gray-100 hover:shadow rounded-lg focus:outline-none cursor-pointer transition-all duration-200">
                            <span class="text-gray-700 font-medium text-base">{{ auth()->user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div id="profile-dropdown" class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg hidden overflow-hidden z-50">
                            <a href="{{ route('ogrenci.settings.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200">
                               <span class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>Ayarlar</span>
                            </a>
                            <div class="border-t border-gray-100"></div>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200">
                                <span class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>Çıkış Yap</span>
                            </a>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    @else
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();"
                           class="bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-bold py-3 px-4 xl:px-6 text-base rounded-lg border-2 border-[#e63946] shadow-lg hover:shadow-xl transition-all duration-300 flex items-center space-x-2 transform hover:-translate-y-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V7.414a1 1 0 00-.293-.707L11.414 2H5a1 1 0 00-1 1v4.586l2.293-2.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0L3 13.414V3z" clip-rule="evenodd" /></svg>
                            <span>Oturumu Kapat</span>
                        </a>
                        <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    @endif
                @else
                    <a href="{{ url('/oturum-ac') }}" class="bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-bold py-3 px-4 xl:px-6 text-base rounded-lg border-2 border-[#e63946] shadow-lg hover:shadow-xl transition-all duration-300 flex items-center space-x-2 transform hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" /></svg>
                        <span>Oturum Aç</span>
                    </a>
                @endauth
            </div>
        </div>

    </div>

    <div class="lg:hidden mobile-menu hidden bg-white border-t shadow-inner">
        <div class="py-2 divide-y divide-gray-100">
            <a href="{{ url('/') }}" class="flex items-center px-4 py-3 hover:bg-red-50 {{ request()->is('/') || request()->is('ana-sayfa') ? 'bg-red-50 border-l-4 border-red-500 pl-3' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <span class="font-medium text-gray-700 {{ request()->is('/') || request()->is('ana-sayfa') ? 'text-red-600' : '' }}">Ana Sayfa</span>
            </a>
            <a href="{{ route('public.resources.index') }}" class="flex items-center px-4 py-3 hover:bg-red-50 {{ request()->is('ucretsiz-kaynaklar') || request()->is('ucretsiz-kaynaklar/*') ? 'bg-red-50 border-l-4 border-red-500 pl-3' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <span class="font-medium text-gray-700 {{ request()->is('ucretsiz-kaynaklar') || request()->is('ucretsiz-kaynaklar/*') ? 'text-red-600' : '' }}">Ücretsiz İçerikler</span>
            </a>
            <a href="{{ url('/egitimler') }}" class="flex items-center px-4 py-3 hover:bg-red-50 {{ request()->is('egitimler') || request()->is('egitimler/*') ? 'bg-red-50 border-l-4 border-red-500 pl-3' : '' }}">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                <span class="font-medium text-gray-700 {{ request()->is('egitimler') || request()->is('egitimler/*') ? 'text-red-600' : '' }}">Eğitimler</span>
            </a>
            <a href="{{ url('/iletisim') }}" class="flex items-center px-4 py-3 hover:bg-red-50 {{ request()->is('iletisim') ? 'bg-red-50 border-l-4 border-red-500 pl-3' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                <span class="font-medium text-gray-700 {{ request()->is('iletisim') ? 'text-red-600' : '' }}">İletişim</span>
            </a>

             @auth
                @if(auth()->user()->hasRole('ogretmen'))
                <a href="{{ route('ogretmen.private-lessons.index') }}" class="flex items-center px-4 py-3 hover:bg-red-50 {{ request()->is('ogretmen/ozel-derslerim') || request()->is('ogretmen/ozel-derslerim/*') ? 'bg-red-50 border-l-4 border-red-500 pl-3' : '' }}">
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    <span class="font-medium text-gray-700 {{ request()->is('ogretmen/ozel-derslerim') || request()->is('ogretmen/ozel-derslerim/*') ? 'text-red-600' : '' }}">Özel Ders</span>
                </a>
                @endif

                 @if(auth()->user()->hasRole('ogrenci'))
                 <a href="{{ route('ogrenci.private-lessons.index') }}" class="flex items-center px-4 py-3 hover:bg-red-50 {{ request()->is('ogrenci/ozel-derslerim') || request()->is('ogrenci/ozel-derslerim/*') ? 'bg-red-50 border-l-4 border-red-500 pl-3' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    <span class="font-medium text-gray-700 {{ request()->is('ogrenci/ozel-derslerim') || request()->is('ogrenci/ozel-derslerim/*') ? 'text-red-600' : '' }}">Özel Derslerim</span>
                </a>
                 @endif

                 @if(auth()->user()->hasRole('ogrenci'))
                 <a href="{{ route('ogrenci.settings.index') }}" class="flex items-center px-4 py-3 hover:bg-red-50 {{ request()->is('ogrenci/ayarlar') ? 'bg-red-50 border-l-4 border-red-500 pl-3' : '' }}">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                     <span class="font-medium text-gray-700 {{ request()->is('ogrenci/ayarlar') ? 'text-red-600' : '' }}">Ayarlar</span>
                 </a>
                 @endif

                 <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                    class="flex items-center px-4 py-3 text-red-600 hover:bg-red-50">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                     <span class="font-medium">Çıkış Yap</span>
                 </a>
                 <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
             @endauth

        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        const profileBtn = document.getElementById('profile-btn');
        const profileDropdown = document.getElementById('profile-dropdown');

        if(profileBtn && profileDropdown){
            profileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                 if (profileDropdown && !profileDropdown.classList.contains('hidden') && !profileDropdown.contains(e.target) && !profileBtn.contains(e.target)) {
                    profileDropdown.classList.add('hidden');
                }
            });

            profileDropdown.addEventListener('click', function(e) {
                 if (e.target.tagName === 'A') {
                    profileDropdown.classList.add('hidden');
                 }
            });
        }
    });
</script>