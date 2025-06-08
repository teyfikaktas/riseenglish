<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <title>{{ config('app.name', 'RiseEnglish') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@700;800;900&family=Playfair+Display:ital@0;1&family=Poppins:wght@600;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')

    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* Animasyonlar */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.3); }
            50% { box-shadow: 0 0 40px rgba(59, 130, 246, 0.6); }
        }
        
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        .game-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
        }
        
        .level-badge {
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
        }
    </style>
</head>

<body class="font-sans game-bg min-h-screen">
    <!-- Header -->
    <header class="bg-gradient-to-r from-purple-900/90 to-blue-900/90 backdrop-blur-lg border-b-4 border-yellow-400 shadow-2xl">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg hover:rotate-12 transition-transform">
                        <i class="fas fa-graduation-cap text-white text-lg md:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-3xl font-bold text-white font-['Poppins']">
                            Rise<span class="text-yellow-400">English</span>
                        </h1>
                        <p class="text-blue-200 text-sm hidden md:block">🎮 Öğren • Oyna • Kazan</p>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-white p-2 rounded-lg hover:bg-white/10">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <!-- Desktop Navigation -->
                @auth
                <div class="hidden md:flex items-center space-x-6">
                    <!-- Stats -->
                    <div class="flex items-center space-x-4">
                        <!-- Level -->
                        <div class="level-badge px-4 py-2 rounded-full flex items-center space-x-2">
                            <i class="fas fa-crown text-purple-800"></i>
                            <span class="text-purple-800 font-bold">Level 5</span>
                        </div>
                        
                        <!-- XP Bar -->
                        <div class="bg-black/30 rounded-full p-1 w-32">
                            <div class="bg-gradient-to-r from-green-400 to-blue-500 h-3 rounded-full" style="width: 65%"></div>
                            <p class="text-white text-xs text-center mt-1">650/1000 XP</p>
                        </div>
                        
                        <!-- Streak -->
                        <div class="bg-orange-500/20 px-3 py-2 rounded-lg border border-orange-400 flex items-center space-x-1">
                            <i class="fas fa-fire text-orange-400"></i>
                            <span class="text-orange-200 font-bold">7</span>
                        </div>
                        
                        <!-- Coins -->
                        <div class="bg-yellow-500/20 px-3 py-2 rounded-lg border border-yellow-400 flex items-center space-x-1">
                            <i class="fas fa-coins text-yellow-400"></i>
                            <span class="text-yellow-200 font-bold">1,250</span>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="relative group">
                        <button class="flex items-center space-x-3 bg-white/10 hover:bg-white/20 rounded-xl px-4 py-2 transition-all">
                            <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <div class="text-left">
                                <p class="text-white font-medium">{{ auth()->user()->name }}</p>
                                <p class="text-blue-200 text-sm">English Warrior</p>
                            </div>
                            <i class="fas fa-chevron-down text-white/60"></i>
                        </button>
                        
                        <!-- Dropdown -->
                        <div class="absolute right-0 top-full mt-2 w-64 bg-white rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                            <div class="p-4">
                                <div class="flex items-center space-x-3 pb-4 border-b">
                                    <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ auth()->user()->name }}</p>
                                        <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                                
                                <div class="py-4 space-y-2">
                                    <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100">
                                        <i class="fas fa-user text-blue-500"></i>
                                        <span class="text-gray-700">Profil</span>
                                    </a>
                                    <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100">
                                        <i class="fas fa-trophy text-yellow-500"></i>
                                        <span class="text-gray-700">Başarılar</span>
                                    </a>
                                    <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100">
                                        <i class="fas fa-cog text-gray-500"></i>
                                        <span class="text-gray-700">Ayarlar</span>
                                    </a>
                                </div>
                                
                                <div class="pt-4 border-t">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-red-50 w-full text-left">
                                            <i class="fas fa-sign-out-alt text-red-500"></i>
                                            <span class="text-red-600">Çıkış Yap</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- Guest Navigation -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold transition-all hover:scale-105 shadow-lg">
                        🚀 Giriş Yap
                    </a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-6 py-3 rounded-xl font-bold transition-all hover:scale-105 shadow-lg">
                        ⭐ Kayıt Ol
                    </a>
                </div>
                @endauth
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden mt-4 hidden">
                @auth
                <div class="bg-white/10 rounded-xl p-4 space-y-4">
                    <!-- User Info -->
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-white font-medium">{{ auth()->user()->name }}</p>
                            <p class="text-blue-200 text-sm">English Warrior</p>
                        </div>
                    </div>
                    
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-black/20 rounded-lg p-3 text-center">
                            <div class="flex items-center justify-center space-x-1 mb-1">
                                <i class="fas fa-crown text-yellow-400"></i>
                                <span class="text-white font-bold">Level 5</span>
                            </div>
                            <div class="bg-black/30 rounded-full p-1">
                                <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full" style="width: 65%"></div>
                            </div>
                            <p class="text-white text-xs mt-1">650/1000 XP</p>
                        </div>
                        
                        <div class="bg-black/20 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-fire text-orange-400"></i>
                                    <span class="text-orange-200 font-bold">7</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-coins text-yellow-400"></i>
                                    <span class="text-yellow-200 font-bold">1,250</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Menu Links -->
                    <div class="space-y-2">
                        <a href="#" class="flex items-center space-x-3 text-white hover:bg-white/10 px-3 py-2 rounded-lg">
                            <i class="fas fa-user text-blue-400"></i>
                            <span>Profil</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 text-white hover:bg-white/10 px-3 py-2 rounded-lg">
                            <i class="fas fa-trophy text-yellow-400"></i>
                            <span>Başarılar</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 text-white hover:bg-white/10 px-3 py-2 rounded-lg">
                            <i class="fas fa-cog text-gray-400"></i>
                            <span>Ayarlar</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center space-x-3 text-white hover:bg-red-500/20 px-3 py-2 rounded-lg w-full text-left">
                                <i class="fas fa-sign-out-alt text-red-400"></i>
                                <span>Çıkış Yap</span>
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="space-y-3">
                    <a href="{{ route('login') }}" class="block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-center">
                        🚀 Giriş Yap
                    </a>
                    <a href="{{ route('register') }}" class="block bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-3 rounded-xl font-bold text-center">
                        ⭐ Kayıt Ol
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="relative">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-900 to-purple-900 text-white py-8 md:py-12 mt-12 relative overflow-hidden">
        <!-- Background Icons -->
        <div class="absolute inset-0 opacity-10">
            <div class="float-animation absolute top-10 left-10 text-4xl md:text-6xl">🎮</div>
            <div class="float-animation absolute top-20 right-20 text-3xl md:text-4xl" style="animation-delay: 1s;">⭐</div>
            <div class="float-animation absolute bottom-10 left-1/4 text-4xl md:text-5xl" style="animation-delay: 2s;">🏆</div>
            <div class="float-animation absolute bottom-20 right-1/3 text-2xl md:text-3xl" style="animation-delay: 1.5s;">🎯</div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">Rise<span class="text-yellow-400">English</span></h3>
                            <p class="text-gray-300">Oyunlaştırılmış İngilizce Öğrenme</p>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-4">
                        İngilizce öğrenmeyi eğlenceli ve motivasyonel bir deneyime dönüştürüyoruz. 
                        Oyunlar, rozetler ve seviye sistemi ile öğrenme yolculuğunuzda ilerleyin!
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700">
                            <i class="fab fa-facebook-f text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center hover:bg-blue-500">
                            <i class="fab fa-twitter text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center hover:bg-pink-700">
                            <i class="fab fa-instagram text-white"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-bold mb-4 text-yellow-400">🎯 Hızlı Linkler</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white flex items-center space-x-2"><i class="fas fa-play text-green-400"></i><span>Test Çöz</span></a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white flex items-center space-x-2"><i class="fas fa-gamepad text-purple-400"></i><span>Oyunlar</span></a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white flex items-center space-x-2"><i class="fas fa-trophy text-yellow-400"></i><span>Başarılar</span></a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white flex items-center space-x-2"><i class="fas fa-chart-line text-blue-400"></i><span>İlerlemem</span></a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-bold mb-4 text-yellow-400">📞 İletişim</h4>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-blue-400"></i>
                            <span class="text-gray-300">info@riseenglish.com</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-green-400"></i>
                            <span class="text-gray-300">+90 545 762 44 98</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-map-marker-alt text-red-400"></i>
                            <span class="text-gray-300">Türkiye</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} Rise English. Tüm hakları saklıdır. 🚀 Oyunlaştırılmış öğrenme deneyimi.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Widget -->
    <div id="chat-widget" class="fixed right-4 bottom-4 z-50">
        @guest
        <button id="chat-toggle" class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full shadow-2xl hover:shadow-green-500/50 transition-all pulse-glow flex items-center justify-center group">
            <i class="fab fa-whatsapp text-white text-3xl group-hover:scale-110 transition-transform"></i>
            <div class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                <span class="text-white text-xs font-bold">💬</span>
            </div>
        </button>
        @endguest
      
        <!-- Chat Box -->
        <div id="chat-box" class="hidden bg-white rounded-2xl shadow-2xl w-80 mb-4 overflow-hidden max-w-[calc(100vw-2rem)]">
          <!-- Teacher Tabs -->
          <div class="flex">
            <button id="tab-hakan" class="flex-1 px-3 py-2 bg-green-500 text-white text-sm">
              <div class="font-bold">İngilizce Kursları</div>
              <div class="text-xs">Hakan Hoca</div>
            </button>
            <button id="tab-rumeysa" class="flex-1 px-3 py-2 bg-gray-200 text-gray-700 text-sm">
              <div class="font-bold">Eğitim Danışmanı</div>
              <div class="text-xs">Rümeysa Hoca</div>
            </button>
          </div>
      
          <!-- Header -->
          <div id="chat-header" class="bg-gradient-to-r from-green-500 to-green-600 px-4 py-3 flex items-center justify-between">
            <div class="flex items-center">
              <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center mr-3">
                <i id="teacher-icon" class="fas fa-user-tie text-green-600"></i>
              </div>
              <div class="text-white">
                <p id="teacher-name" class="font-bold">Hakan Hoca</p>
                <p id="teacher-role" class="text-xs">İngilizce Kursları</p>
              </div>
            </div>
            <button id="close-chat" class="text-white hover:text-gray-200">
              <i class="fas fa-times"></i>
            </button>
          </div>
      
          <!-- Messages -->
          <div class="p-4 bg-gray-50 max-h-60 overflow-y-auto">
            <div class="flex justify-center mb-4">
              <span class="text-xs text-gray-500 bg-gray-200 px-3 py-1 rounded-full">Bugün</span>
            </div>
            
            <div class="flex mb-4">
              <div class="bg-white rounded-lg p-3 shadow max-w-xs">
                <p class="text-gray-700">🎮 Merhaba! Size nasıl yardımcı olabilirim?</p>
                <p class="text-xs text-gray-500 text-right mt-1">{{ date('H:i') }}</p>
              </div>
            </div>
            
            <button class="w-full bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl py-2 px-3 font-medium hover:from-blue-600 hover:to-purple-600 transition-all flex items-center justify-center space-x-2">
              <i class="fas fa-rocket"></i>
              <span>Hemen Başla!</span>
            </button>
          </div>
      
          <!-- Input -->
          <div class="p-3 bg-gray-50 border-t">
            <form id="message-form" class="flex items-center space-x-2">
              <input type="text" id="message-input" placeholder="Mesajınızı yazın..." class="flex-1 border rounded-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
              <button type="submit" class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white hover:bg-green-600">
                <i class="fas fa-paper-plane"></i>
              </button>
            </form>
          </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuIcon = mobileMenuBtn.querySelector('i');
            
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                if(mobileMenu.classList.contains('hidden')) {
                    menuIcon.className = 'fas fa-bars text-xl';
                } else {
                    menuIcon.className = 'fas fa-times text-xl';
                }
            });
            
            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!mobileMenuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.add('hidden');
                    menuIcon.className = 'fas fa-bars text-xl';
                }
            });
            
            // Chat Widget
            const chatToggle = document.getElementById('chat-toggle');
            const chatBox = document.getElementById('chat-box');
            const closeChat = document.getElementById('close-chat');
            const messageForm = document.getElementById('message-form');
            const messageInput = document.getElementById('message-input');
            
            const tabHakan = document.getElementById('tab-hakan');
            const tabRumeysa = document.getElementById('tab-rumeysa');
            const teacherName = document.getElementById('teacher-name');
            const teacherRole = document.getElementById('teacher-role');
            const teacherIcon = document.getElementById('teacher-icon');

            let currentTeacher = 'hakan';

            function activateTab(teacher) {
                if(teacher === 'hakan'){
                    currentTeacher = 'hakan';
                    teacherName.textContent = 'Hakan Hoca';
                    teacherRole.textContent = 'İngilizce Kursları';
                    teacherIcon.className = 'fas fa-user-tie text-green-600';
                    tabHakan.classList.replace('bg-gray-200', 'bg-green-500');
                    tabHakan.classList.replace('text-gray-700', 'text-white');
                    tabRumeysa.classList.replace('bg-green-500', 'bg-gray-200');
                    tabRumeysa.classList.replace('text-white', 'text-gray-700');
                } else {
                    currentTeacher = 'rumeysa';
                    teacherName.textContent = 'Rümeysa Hoca';
                    teacherRole.textContent = 'Eğitim Danışmanı & Öğrenci Koçu';
                    teacherIcon.className = 'fas fa-user-graduate text-green-600';
                    tabRumeysa.classList.replace('bg-gray-200', 'bg-green-500');
                    tabRumeysa.classList.replace('text-gray-700', 'text-white');
                    tabHakan.classList.replace('bg-green-500', 'bg-gray-200');
                    tabHakan.classList.replace('text-white', 'text-gray-700');
                }
            }
            
            tabHakan.addEventListener('click', () => activateTab('hakan'));
            tabRumeysa.addEventListener('click', () => activateTab('rumeysa'));
            
            chatToggle.addEventListener('click', function() {
                chatBox.classList.toggle('hidden');
            });
            
            closeChat.addEventListener('click', function() {
                chatBox.classList.add('hidden');
            });
            
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                let messageText = '';
                if(currentTeacher === 'hakan'){
                    messageText = "İngilizce Kursları hakkında bilgi almak istiyorum!";
                } else {
                    messageText = "Rehberlik hakkında bilgi almak istiyorum!";
                }
                
                const phoneNumber = '905457624498';
                const encodedMessage = encodeURIComponent(messageText);
                const whatsappURL = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;
                window.open(whatsappURL, '_blank');
                
                messageInput.value = '';
            });
            
            // Achievement notification system
            function showAchievement(title, description, icon) {
                const achievement = document.createElement('div');
                achievement.className = 'fixed top-4 right-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white p-4 rounded-xl shadow-2xl z-50 transform translate-x-full transition-transform duration-500';
                achievement.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="${icon} text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold">${title}</h4>
                            <p class="text-sm opacity-90">${description}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-white/60 hover:text-white">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                
                document.body.appendChild(achievement);
                
                setTimeout(() => {
                    achievement.style.transform = 'translateX(0)';
                }, 100);
                
                setTimeout(() => {
                    achievement.style.transform = 'translateX(100%)';
                    setTimeout(() => achievement.remove(), 500);
                }, 5000);
            }
            
            // Example achievement trigger
            setTimeout(() => {
                showAchievement('🎉 Hoş Geldin!', 'Rise English\'e katıldığın için tebrikler!', 'fas fa-star');
            }, 2000);
        });
    </script>

    @livewireScripts
    @stack('scripts')

</body>
</html>