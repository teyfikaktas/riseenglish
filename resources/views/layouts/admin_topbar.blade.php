<!-- resources/views/layouts/admin_topbar.blade.php -->
<div class="sticky top-0 w-full bg-[#1a2e5a] text-white shadow-md z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <div class="flex-shrink-0 relative">
                <div class="absolute inset-0 bg-white rounded-r-lg"></div>
                <a href="{{ url('/admin/dashboard') }}" class="flex items-center relative z-10 px-3 py-1.5">
                    <img src="{{ asset('images/logo.png') }}" alt="Rise English Logo" class="h-12">
                    <span class="hidden sm:inline text-sm font-bold ml-2 text-[#1a2e5a] whitespace-nowrap">Yönetici Paneli</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden xl:flex items-center flex-1 justify-center mx-6">
                <div class="flex items-center space-x-5 text-sm">
                    <a href="{{ url('/admin/dashboard') }}"
                       class="text-white hover:text-red-400 font-medium transition duration-200 whitespace-nowrap {{ request()->is('admin/dashboard') ? 'text-red-400' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ url('/admin/users') }}"
                       class="text-white hover:text-red-400 font-medium transition duration-200 whitespace-nowrap {{ request()->is('admin/users*') ? 'text-red-400' : '' }}">
                        Kullanıcılar
                    </a>
                    <a href="{{ url('/admin/groups') }}"
                       class="text-white hover:text-red-400 font-medium transition duration-200 whitespace-nowrap {{ request()->is('admin/groups*') ? 'text-red-400' : '' }}">
                        Gruplar
                    </a>
                    <a href="{{ url('/admin/courses') }}"
                       class="text-white hover:text-red-400 font-medium transition duration-200 whitespace-nowrap {{ request()->is('admin/courses*') ? 'text-red-400' : '' }}">
                        Kurslar
                    </a>
                    <a href="{{ url('/admin/sms') }}"
                       class="text-white hover:text-red-400 font-medium transition duration-200 whitespace-nowrap {{ request()->is('admin/sms*') ? 'text-red-400' : '' }}">
                        SMS
                    </a>

                    <!-- Test Yönetimi Dropdown -->
                    <div class="relative">
                        <button type="button"
                                class="text-white hover:text-red-400 font-medium transition duration-200 flex items-center whitespace-nowrap {{ request()->is('admin/test*') ? 'text-red-400' : '' }}"
                                onclick="toggleDropdown(event, 'tests-dropdown')">
                            Testler
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="tests-dropdown" class="absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50">
                            <div class="py-1">
                                <a href="{{ url('/admin/test-dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📂 Test Kategorileri</a>
                                <a href="{{ url('/admin/tests') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">📝 Testler</a>
                                <a href="{{ url('/admin/questions') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">❓ Sorular</a>
                            </div>
                        </div>
                    </div>

                    <!-- Kaynaklar Yönetimi Dropdown -->
                    <div class="relative">
                        <button type="button"
                                class="text-white hover:text-red-400 font-medium transition duration-200 flex items-center whitespace-nowrap {{ request()->is('admin/resources*') || request()->is('admin/resource-categories*') || request()->is('admin/resource-types*') || request()->is('admin/word-set-categories*') ? 'text-red-400' : '' }}"
                                onclick="toggleDropdown(event, 'resources-dropdown')">
                            Kaynaklar
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="resources-dropdown" class="absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50">
                            <div class="py-1">
                                <a href="{{ url('/admin/resources') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tüm Kaynaklar</a>
                                <a href="{{ url('/admin/resource-categories') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kaynak Kategorileri</a>
                                <a href="{{ url('/admin/resource-types') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kaynak Türleri</a>
                                <div class="border-t border-gray-200 my-1"></div>
                                <a href="{{ url('/admin/word-set-categories') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kelime Kategorileri</a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ url('/admin/contacts') }}"
                       class="text-white hover:text-red-400 font-medium transition duration-200 flex items-center whitespace-nowrap {{ request()->is('admin/contacts*') ? 'text-red-400' : '' }}">
                        Mesajlar
                        @php
                            $unreadCount = \App\Models\Contact::where('is_read', false)->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full ml-2">{{ $unreadCount }}</span>
                        @endif
                    </a>

                    <a href="{{ url('/') }}"
                       class="text-white hover:text-red-400 font-medium transition duration-200 whitespace-nowrap">
                        Ana Site
                    </a>
                </div>
            </nav>

            <!-- User Profile & Logout -->
            <div class="flex items-center space-x-3 flex-shrink-0">
                <!-- User Info -->
                <div class="hidden md:flex items-center">
                    <span class="text-white text-sm mr-2 max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                    <div class="h-8 w-8 rounded-full bg-red-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>

                <!-- Logout Button -->
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="hidden sm:flex bg-red-600 hover:bg-red-700 text-white font-bold py-1.5 px-3 rounded-lg shadow transition-all duration-300 items-center space-x-1.5 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V7.414a1 1 0 00-.293-.707L11.414 2H5a1 1 0 00-1 1v4.586l2.293-2.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0L3 13.414V3z" clip-rule="evenodd" />
                    </svg>
                    <span>Çıkış</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

                <!-- Mobile Menu Button -->
                <button type="button" class="xl:hidden admin-mobile-menu-button p-2" aria-label="Menüyü Aç">
                    <i class="fas fa-bars text-white text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="xl:hidden admin-mobile-menu hidden px-4 py-2 bg-[#283b6a] border-t border-blue-800 max-h-[80vh] overflow-y-auto">
        <a href="{{ url('/admin/dashboard') }}" class="block py-2 text-white hover:text-red-400 font-medium {{ request()->is('admin/dashboard') ? 'text-red-400' : '' }}">Dashboard</a>
        <a href="{{ url('/admin/users') }}" class="block py-2 text-white hover:text-red-400 font-medium {{ request()->is('admin/users*') ? 'text-red-400' : '' }}">Kullanıcılar</a>
        <a href="{{ url('/admin/groups') }}" class="block py-2 text-white hover:text-red-400 font-medium {{ request()->is('admin/groups*') ? 'text-red-400' : '' }}">Gruplar</a>
        <a href="{{ url('/admin/courses') }}" class="block py-2 text-white hover:text-red-400 font-medium {{ request()->is('admin/courses*') ? 'text-red-400' : '' }}">Kurslar</a>
        <a href="{{ url('/admin/sms') }}" class="block py-2 text-white hover:text-red-400 font-medium {{ request()->is('admin/sms*') ? 'text-red-400' : '' }}">SMS Yönetimi</a>

        <!-- Test Yönetimi Mobil -->
        <div class="py-2">
            <div class="flex items-center justify-between text-white hover:text-red-400 font-medium cursor-pointer" onclick="toggleMobileSubMenu('tests-submenu')">
                <span>Test Yönetimi</span>
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
            <div id="tests-submenu" class="hidden pl-4 pt-2">
                <a href="{{ url('/admin/test-dashboard') }}" class="block py-2 text-white hover:text-red-400">📂 Test Kategorileri</a>
                <a href="{{ url('/admin/tests') }}" class="block py-2 text-white hover:text-red-400">📝 Testler</a>
                <a href="{{ url('/admin/questions') }}" class="block py-2 text-white hover:text-red-400">❓ Sorular</a>
            </div>
        </div>

        <!-- Kaynaklar Mobil -->
        <div class="py-2">
            <div class="flex items-center justify-between text-white hover:text-red-400 font-medium cursor-pointer" onclick="toggleMobileSubMenu('resources-submenu')">
                <span>Kaynaklar Yönetimi</span>
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
            <div id="resources-submenu" class="hidden pl-4 pt-2">
                <a href="{{ url('/admin/resources') }}" class="block py-2 text-white hover:text-red-400">Tüm Kaynaklar</a>
                <a href="{{ url('/admin/resource-categories') }}" class="block py-2 text-white hover:text-red-400">Kaynak Kategorileri</a>
                <a href="{{ url('/admin/resource-types') }}" class="block py-2 text-white hover:text-red-400">Kaynak Türleri</a>
                <a href="{{ url('/admin/word-set-categories') }}" class="block py-2 text-white hover:text-red-400">Kelime Kategorileri</a>
            </div>
        </div>

        <a href="{{ url('/admin/contacts') }}" class="block py-2 text-white hover:text-red-400 font-medium {{ request()->is('admin/contacts*') ? 'text-red-400' : '' }}">
            İletişim Mesajları
            @if($unreadCount > 0)
                <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full ml-2">{{ $unreadCount }}</span>
            @endif
        </a>

        <a href="{{ url('/') }}" class="block py-2 text-white hover:text-red-400 font-medium">Ana Siteye Dön</a>

        <!-- Mobil Çıkış -->
        <div class="border-t border-blue-700 mt-2 pt-2 sm:hidden">
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="block py-2 text-red-400 hover:text-red-300 font-bold">
                <i class="fas fa-sign-out-alt mr-2"></i> Çıkış Yap
            </a>
        </div>
    </div>
</div>

<script>
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
            // Dropdown'ları kapat
            const dropdowns = document.querySelectorAll('[id$="-dropdown"]');
            dropdowns.forEach(function(dropdown) {
                const parentRelative = dropdown.closest('.relative');
                if (parentRelative && !parentRelative.contains(event.target) && !dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                }
            });

            // Mobil menüyü kapat
            if (adminMobileMenu && !adminMobileMenu.classList.contains('hidden') &&
                !adminMobileMenu.contains(event.target) &&
                adminMobileMenuButton && !adminMobileMenuButton.contains(event.target)) {
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

    function toggleDropdown(event, id) {
        event.stopPropagation();
        const dropdown = document.getElementById(id);
        if (dropdown) {
            // Diğer dropdown'ları kapat
            document.querySelectorAll('[id$="-dropdown"]').forEach(function(item) {
                if (item.id !== id && !item.classList.contains('hidden')) {
                    item.classList.add('hidden');
                }
            });
            dropdown.classList.toggle('hidden');
        }
    }
</script>