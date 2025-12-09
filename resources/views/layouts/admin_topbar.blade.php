<!-- resources/views/layouts/admin_topbar.blade.php -->
<div class="sticky top-0 w-full bg-[#1a2e5a] text-white shadow-md z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-24">
            <!-- Logo - Beyaz Arka Plan Eklendi -->
            <div class="flex-shrink-0 relative">
                <div class="absolute inset-0 bg-white rounded-r-lg"></div>
                
                <a href="{{ url('/admin/dashboard') }}" class="flex flex-col md:flex-row items-center justify-center md:justify-start relative z-10 px-4 py-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Rise English Logo" class="h-12 md:h-16">
                    <span class="text-center md:text-left text-sm md:text-lg font-bold md:ml-3 text-[#1a2e5a] -mt-1 md:mt-0">Yönetici Paneli</span>
                </a>
            </div>

            <!-- Navigation - Admin Links -->
            <div class="hidden lg:flex items-center ml-auto mr-6 space-x-6 text-base">
                <a href="{{ url('/admin/dashboard') }}" class="text-white hover:text-red-400 font-medium transition duration-200 {{ request()->is('admin/dashboard') ? 'text-red-400' : '' }}">Dashboard</a>
                <a href="{{ url('/admin/users') }}" class="text-white hover:text-red-400 font-medium transition duration-200 {{ request()->is('admin/users*') ? 'text-red-400' : '' }}">Kullanıcılar</a>
                <a href="{{ url('/admin/groups') }}" class="text-white hover:text-red-400 font-medium transition duration-200 {{ request()->is('admin/groups*') ? 'text-red-400' : '' }}">Gruplar</a>
                <a href="{{ url('/admin/courses') }}" class="text-white hover:text-red-400 font-medium transition duration-200 {{ request()->is('admin/courses*') ? 'text-red-400' : '' }}">Kurslar</a>

                <a href="{{ url('/admin/sms') }}" class="text-white hover:text-red-400 font-medium transition duration-200 {{ request()->is('admin/sms*') ? 'text-red-400' : '' }}">SMS Yönetimi</a>
                
                <!-- Test Yönetimi Açılır Menü -->
                <div class="relative">
                    <a href="javascript:void(0)"
                       class="text-white hover:text-red-400 font-medium transition duration-200 {{ request()->is('admin/test*') ? 'text-red-400' : '' }} flex items-center"
                       onclick="toggleDropdown('tests-dropdown')">
                        Test Yönetimi
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <!-- Dropdown Menü -->
                    <div id="tests-dropdown" class="absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50">
                        <div class="py-1" role="menu" aria-orientation="vertical">
                            <a href="{{ url('/admin/test-dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                📂 Test Kategorileri
                            </a>
                            <a href="{{ url('/admin/tests') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                📝 Testler
                            </a>
                            <a href="{{ url('/admin/questions') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                ❓ Sorular
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kaynaklar Yönetimi Açılır Menü -->
                <div class="relative">
                    <a href="javascript:void(0)"
                       class="text-white hover:text-red-400 font-medium transition duration-200 {{ request()->is('admin/resources*') || request()->is('admin/resource-categories*') || request()->is('admin/resource-types*') || request()->is('admin/resource-tags*') ? 'text-red-400' : '' }} flex items-center"
                       onclick="toggleDropdown('resources-dropdown')">
                        Kaynaklar Yönetimi
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <!-- Dropdown Menü -->
                    <div id="resources-dropdown" class="absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50">
                        <div class="py-1" role="menu" aria-orientation="vertical">
                            <a href="{{ url('/admin/resources') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                Tüm Kaynaklar
                            </a>
                            <a href="{{ url('/admin/resource-categories') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                Kaynak Kategorileri
                            </a>
                            <a href="{{ url('/admin/resource-types') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                Kaynak Türleri
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ url('/admin/contacts') }}" class="text-white hover:text-red-400 font-medium transition duration-200 {{ request()->is('admin/contacts*') ? 'text-red-400' : '' }}">
                    İletişim Mesajları
                    @php
                        $unreadCount = \App\Models\Contact::where('is_read', false)->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full ml-2">{{ $unreadCount }}</span>
                    @endif
                </a>
                <a href="{{ url('/') }}" class="text-white hover:text-red-400 font-medium transition duration-200">Ana Siteye Dön</a>
            </div>

            <!-- User Profile & Logout -->
            <div class="flex items-center space-x-4">
                <!-- User Info -->
                <div class="hidden md:flex items-center">
                    <span class="text-white mr-2">{{ Auth::user()->name }}</span>
                    <div class="h-8 w-8 rounded-full bg-red-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>

                <!-- Logout Button -->
                <div class="flex-shrink-0">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg border-2 border-red-700 shadow-lg hover:shadow-xl transition-all duration-300 flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V7.414a1 1 0 00-.293-.707L11.414 2H5a1 1 0 00-1 1v4.586l2.293-2.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0L3 13.414V3z" clip-rule="evenodd" />
                        </svg>
                        <span>Çıkış</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="lg:hidden flex items-center ml-4">
                <button class="admin-mobile-menu-button" aria-label="Menüyü Aç">
                    <i class="fas fa-bars text-white text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="lg:hidden admin-mobile-menu hidden px-4 py-2 bg-[#283b6a] border-t border-blue-800">
        <a href="{{ url('/admin/dashboard') }}" class="block py-2 text-white hover:text-red-400 font-medium {{ request()->is('admin/dashboard') ? 'text-red-400' : '' }}">Dashboard</a>
        <a href="{{ url('/admin/users') }}" class="block py-2 text-white hover:text-red-400 font-medium {{ request()->is('admin/users*') ? 'text-red-400' : '' }}">Kullanıcılar</a>
        <a href="{{ url('/admin/groups') }}" class="block py-2 text-white hover:text-red-400 font-medium {{ request()->is('admin/groups*') ? 'text-red-400' : '' }}">Gruplar</a>
        <a href="{{ url('/admin/courses') }}" class="block py-2 text-white hover:text-red-400 font-medium {{ request()->is('admin/courses*') ? 'text-red-400' : '' }}">Kurslar</a>

        <!-- Özel Dersler Mobil Menü -->
        <div class="py-2">
            <div class="flex items-center justify-between text-white hover:text-red-400 font-medium" onclick="toggleMobileSubMenu('private-lessons-submenu')">
                <span>Özel Dersler</span>
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
            <div id="private-lessons-submenu" class="hidden pl-4 pt-2">
                <a href="#" class="block py-2 text-white hover:text-red-400 font-medium">Özel Ders Listesi</a>
                <a href="#" class="block py-2 text-white hover:text-red-400 font-medium">Yeni Özel Ders</a>
            </div>
        </div>

        <a href="{{ url('/admin/contacts') }}" class="block py-2 text-white hover:text-red-400 font-medium {{ request()->is('admin/contacts*') ? 'text-red-400' : '' }}">
            İletişim Mesajları
            @if($unreadCount > 0)
                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full ml-2">{{ $unreadCount }}</span>
            @endif
        </a>

        <!-- Kaynaklar Mobil Menü -->
        <div class="py-2">
            <div class="flex items-center justify-between text-white hover:text-red-400 font-medium" onclick="toggleMobileSubMenu('resources-submenu')">
                <span>Kaynaklar Yönetimi</span>
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
            <div id="resources-submenu" class="hidden pl-4 pt-2">
                <a href="{{ url('/admin/resources') }}" class="block py-2 text-white hover:text-red-400 font-medium">
                    Tüm Kaynaklar
                </a>
                <a href="{{ url('/admin/resource-categories') }}" class="block py-2 text-white hover:text-red-400 font-medium">
                    Kaynak Kategorileri
                </a>
                <a href="{{ url('/admin/resource-types') }}" class="block py-2 text-white hover:text-red-400 font-medium">
                    Kaynak Türleri
                </a>
            </div>
        </div>

        <a href="{{ url('/admin/sms') }}" class="block py-2 text-white hover:text-red-400 font-medium {{ request()->is('admin/sms*') ? 'text-red-400' : '' }}">SMS Yönetimi</a>
        <a href="{{ url('/') }}" class="block py-2 text-white hover:text-red-400 font-medium">Ana Siteye Dön</a>
    </div>
</div>

{{-- Script --}}
<script>
    // Mobile menu toggle for admin
    document.addEventListener('DOMContentLoaded', function() {
        const adminMobileMenuButton = document.querySelector('.admin-mobile-menu-button');
        const adminMobileMenu = document.querySelector('.admin-mobile-menu');

        if (adminMobileMenuButton && adminMobileMenu) {
            adminMobileMenuButton.addEventListener('click', function(event) {
                event.stopPropagation();
                adminMobileMenu.classList.toggle('hidden');
            });
        }

        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('.absolute > [id$="-dropdown"]');
            dropdowns.forEach(function(dropdown) {
                const parentRelative = dropdown.closest('.relative');
                if (parentRelative && !parentRelative.contains(event.target) && !dropdown.classList.contains('hidden')) {
                     dropdown.classList.add('hidden');
                }
            });

            if (adminMobileMenu && !adminMobileMenu.classList.contains('hidden') && !adminMobileMenu.contains(event.target) && adminMobileMenuButton && !adminMobileMenuButton.contains(event.target)) {
                 adminMobileMenu.classList.add('hidden');
            }
        });
    });

    function toggleMobileSubMenu(id) {
        const submenu = document.getElementById(id);
        if (submenu) {
            submenu.classList.toggle('hidden');
            const icon = submenu.previousElementSibling.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-chevron-down');
                icon.classList.toggle('fa-chevron-up');
            }
        }
    }

    function toggleDropdown(id) {
        event.stopPropagation();
        const dropdown = document.getElementById(id);
        if (dropdown) {
            const isHidden = dropdown.classList.contains('hidden');
            const allDropdowns = document.querySelectorAll('.absolute > [id$="-dropdown"]');
            allDropdowns.forEach(function(item) {
                if (item.id !== id && !item.classList.contains('hidden')) {
                    item.classList.add('hidden');
                }
            });
            dropdown.classList.toggle('hidden');
        }
    }
</script>