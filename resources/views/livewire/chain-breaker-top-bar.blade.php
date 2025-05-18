<div>
    @auth
    @if(auth()->user()->hasRole('ogrenci') && isset($daysCompleted))
    <div class="bg-gradient-to-r from-[#1a2e5a] to-[#2c4375] shadow-md py-3">
        <div class="container mx-auto px-4">
            <!-- Desktop View -->
<div class="hidden md:flex items-center justify-between gap-6">
    <!-- Sol Kısım - Madalya ve Bilgiler -->
    <div class="bg-white rounded-lg shadow-md px-5 py-3 flex items-center justify-between flex-grow">
        <!-- Madalya -->
        <div class="flex items-center">
            <img src="{{ asset($levelImagePath) }}" 
                 alt="{{ $currentLevel }} Seviye" 
                 class="h-16 w-16 object-contain drop-shadow-lg">
            <div class="ml-5">
                <h3 class="text-xl font-bold text-gray-800">{{ $currentLevel }} Seviye</h3>
                <div class="flex items-center space-x-2">
                    <p class="text-sm text-gray-600 mt-0.5">{{ $motivationalText }}</p>
                    <span class="text-xs text-[#1a2e5a] font-serif italic font-bold whitespace-nowrap">• Hakan Hoca Eğitim Hayatınızda Başarılar Diler.</span>
                </div>
            </div>
        </div>
        
        <!-- Gün ve Seri Göstergesi -->
        <div class="flex items-center gap-4">
            <!-- Seri İkonu -->
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                </svg>
                <span class="text-lg font-semibold text-gray-700">SERİ</span>
            </div>
            
            <!-- Gün Kutusu ve İlerleme -->
            <div class="flex items-center gap-3">
                <!-- Mevcut Gün -->
                <div class="text-center bg-orange-50 px-5 py-2 rounded-lg border border-orange-200">
                    <div class="text-2xl font-bold text-orange-600">{{ $daysCompleted }}</div>
                    <div class="text-xs text-gray-600 font-medium">GÜN</div>
                </div>
                
                <!-- Ok İşareti -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
                
                <!-- Hedef Gün ve Madalya -->
                <div class="flex items-center gap-2">
                    @if($currentLevel !== 'MASTER' && $nextLevel)
                        <!-- Sonraki Seviye Madalyası -->
                        <div class="relative">
                            <div class="h-10 w-10 rounded-full flex items-center justify-center bg-gradient-to-br from-indigo-100 to-white shadow-inner">
                                <img src="{{ asset($nextLevelImagePath) }}" 
                                     alt="{{ $nextLevel }} Seviye" 
                                     class="h-8 w-8 object-contain opacity-70">
                            </div>
                        </div>
                    @endif
                    
                    <div class="text-center">
                        @if($currentLevel === 'MASTER')
                            <div class="text-sm text-gray-500">Tebrikler!</div>
                            <div class="text-xl font-bold text-indigo-600">🏆</div>
                            <div class="text-xs text-gray-600 font-medium">MASTER</div>
                        @else
                            <div class="text-sm text-gray-500">{{ $nextLevel }}</div>
                            <div class="text-xl font-bold text-indigo-600">{{ $daysUntilNextLevel }}</div>
                            <div class="text-xs text-gray-600 font-medium">KALDI</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sağ Kısım - Zinciri Kırma Sayfasına Git Butonu -->
    <a href="{{ route('zinciri-kirma') }}" 
       class="bg-gradient-to-r from-[#e63946] to-[#d62836] hover:from-[#d62836] hover:to-[#c52226] text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center gap-2 whitespace-nowrap">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
        </svg>
        <span>Zinciri Kırma Sayfasına Git</span>
    </a>
</div>
            <!-- Mobile View -->
            <div class="md:hidden space-y-3">
                <!-- Üst Kart - Madalya ve Seviye -->
                <div class="bg-white rounded-lg shadow-md p-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="{{ asset($levelImagePath) }}" 
                                 alt="{{ $currentLevel }} Seviye" 
                                 class="h-14 w-14 object-contain drop-shadow-lg">
                            <div class="ml-3">
                                <h3 class="text-base font-bold text-gray-800">{{ $currentLevel }} Seviye</h3>
                                <p class="text-xs text-gray-600">{{ $motivationalText }}</p>
                            </div>
                        </div>
                        
                        <!-- Mobil İlerleme Göstergesi -->
                        <div class="flex items-center gap-1.5">
                            <!-- Seri İkonu -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                            </svg>
                            
                            <!-- Mevcut Gün -->
                            <div class="bg-orange-50 px-2.5 py-1 rounded-lg border border-orange-200 text-center">
                                <div class="text-lg font-bold text-orange-600">{{ $daysCompleted }}</div>
                                <div class="text-[9px] text-gray-600 font-medium">GÜN</div>
                            </div>
                            
                            <!-- Ok -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                            
                            <!-- Hedef -->
                            <div class="flex items-center gap-1.5">
                                @if($currentLevel !== 'MASTER' && $nextLevel)
                                    <!-- Sonraki Madalya Mini -->
                                    <div class="h-7 w-7 rounded-full flex items-center justify-center bg-gradient-to-br from-indigo-100 to-white shadow">
                                        <img src="{{ asset($nextLevelImagePath) }}" 
                                             alt="{{ $nextLevel }} Seviye" 
                                             class="h-5 w-5 object-contain opacity-70">
                                    </div>
                                @endif
                                
                                <div class="bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-200 text-center">
                                    @if($currentLevel === 'MASTER')
                                        <div class="text-lg font-bold text-indigo-600">🏆</div>
                                        <div class="text-[9px] text-gray-600 font-medium">MASTER</div>
                                    @else
                                        <div class="text-lg font-bold text-indigo-600">{{ $daysUntilNextLevel }}</div>
                                        <div class="text-[9px] text-gray-600 font-medium">{{ $nextLevel }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Alt Kısım - Zinciri Kırma Butonu -->
                <a href="{{ route('zinciri-kirma') }}" 
                   class="w-full bg-gradient-to-r from-[#e63946] to-[#d62836] text-white font-bold py-2.5 px-4 rounded-lg flex items-center justify-center gap-2 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    <span>Zinciri Kırma Sayfasına Git</span>
                </a>
            </div>
        </div>
    </div>
    @endif
    @endauth

    <style>
        /* Madalya hover animasyonu */
        img[alt*="Seviye"]:hover {
            transform: scale(1.1) rotate(5deg);
            transition: transform 0.3s ease;
        }
        
        /* Buton hover gölge efekti */
        button:hover, a:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2), 0 4px 6px -2px rgba(0, 0, 0, 0.1);
        }
    </style>

    <script>
        document.addEventListener('livewire:initialized', function () {
            @this.on('show-success', (event) => {
                if (event.message) {
                    showToast('success', event.message);
                }
            });

            @this.on('show-error', (event) => {
                if (event.message) {
                    showToast('error', event.message);
                }
            });
            
            @this.on('level-up-animation', () => {
                confetti({
                    particleCount: 150,
                    spread: 80,
                    origin: { y: 0.3 },
                    colors: ['#e63946', '#1a2e5a', '#FFD700']
                });
            });
            
            @this.on('day-completed-animation', () => {
                confetti({
                    particleCount: 50,
                    spread: 60,
                    origin: { y: 0.3 },
                    colors: ['#e63946', '#1a2e5a', '#FFD700']
                });
            });
            
            // Toast mesajı gösterme fonksiyonu
            function showToast(type, message) {
                let bgColor = '';
                let textColor = 'text-white';
                let icon = '';
                
                switch(type) {
                    case 'success':
                        bgColor = 'bg-green-500';
                        icon = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                        break;
                    case 'error':
                        bgColor = 'bg-red-500';
                        icon = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                        break;
                    case 'info':
                        bgColor = 'bg-blue-500';
                        icon = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                        break;
                }
                
                // Toast oluştur - Mobile için responsive tasarım
                const toast = document.createElement('div');
                toast.className = `${bgColor} ${textColor} p-3 md:p-4 rounded-lg shadow-lg flex items-center fixed z-50 transform transition-transform duration-300 text-sm max-w-xs md:max-w-md`;
                
                // Mobile için aşağıdan yukarı hareket
                if (window.innerWidth < 640) {
                    toast.classList.add('bottom-4', 'left-1/2', '-translate-x-1/2', 'translate-y-full');
                } else {
                    toast.classList.add('right-4', 'top-4', 'translate-x-full');
                }
                
                toast.innerHTML = `<div class="flex items-center">${icon}<div>${message}</div></div>`;
                document.body.appendChild(toast);
                
                // Toast göster
                setTimeout(() => {
                    if (window.innerWidth < 640) {
                        toast.classList.remove('translate-y-full');
                    } else {
                        toast.classList.remove('translate-x-full');
                    }
                }, 100);
                
                // Toast gizle ve kaldır
                setTimeout(() => {
                    if (window.innerWidth < 640) {
                        toast.classList.add('translate-y-full');
                    } else {
                        toast.classList.add('translate-x-full');
                    }
                    setTimeout(() => {
                        document.body.removeChild(toast);
                    }, 300);
                }, 4000);
            }
        });
    </script>
</div>