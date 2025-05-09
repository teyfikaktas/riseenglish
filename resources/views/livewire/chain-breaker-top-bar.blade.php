@auth
@if(auth()->user()->hasRole('ogrenci') && isset($daysCompleted))
<div class="bg-gradient-to-r from-[#1a2e5a] to-[#2c4375] shadow-md py-4">
    <div class="container mx-auto px-4">
        <!-- Desktop View -->
        <div class="hidden md:flex items-center justify-between">
            <!-- Sol Kısım - Madalya Gösterimi - Daha büyük madalya -->
            <div class="flex items-center">
                <div class="bg-gradient-to-r from-white to-gray-100 rounded-lg shadow-lg px-2 py-2 flex items-center border-2 border-yellow-300 w-72">
                    <div class="h-16 w-16 rounded-full flex items-center justify-center bg-white shadow-inner p-1">
                        <img src="{{ asset($levelImagePath) }}" alt="{{ $currentLevel }} Seviye" class="h-20 w-20 object-contain drop-shadow-lg">
                    </div>
                    <div class="ml-4">
                        <span class="text-gray-900 font-bold block text-base">{{ $currentLevel }} Seviye</span>
                        <span class="text-gray-700 text-sm">{{ $motivationalText }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Orta Kısım - Zinciri Kırma Sayfasına Git Butonu -->
            <div class="flex items-center">
                <a href="{{ route('zinciri-kirma') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold text-sm py-2 px-4 rounded-lg transition duration-300 transform hover:-translate-y-1 hover:shadow-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    <span>Zinciri Kırma Sayfasına Git</span>
                </a>
            </div>
            
            <!-- Sağ Kısım - Gün Bilgisi ve Günü Tamamla -->
            <div class="flex items-center space-x-3">
                <!-- Gün Bilgisi - Şık tasarım -->
                <div class="bg-white rounded-lg shadow-md px-3 py-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#e63946]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <div class="ml-2">
                        <span class="text-gray-900 font-bold">{{ $daysCompleted }}</span>
                        <span class="text-gray-600 font-medium"> gün</span>
                    </div>
                </div>
                
                <!-- Günü Tamamla Butonu - Şık Tasarım -->
                <button wire:click="completeDay" class="bg-gradient-to-r from-[#e63946] to-[#d62836] text-white font-bold text-sm py-2 px-4 rounded-lg transition duration-300 transform hover:-translate-y-1 hover:shadow-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Günü Tamamla</span>
                </button>
            </div>
        </div>

        <!-- Mobile View - Stack vertically on smaller screens - Daha büyük madalya -->
        <div class="md:hidden flex flex-col space-y-2">
            <!-- Üst Satır - Seviye ve Gün Bilgisi -->
            <div class="flex justify-between">
                <!-- Seviye Bilgisi - Daha büyük madalya -->
                <div class="bg-gradient-to-r from-white to-gray-100 rounded-lg shadow-md px-3 py-2 flex items-center flex-grow mr-2 border border-yellow-300">
                    <div class="h-12 w-12 rounded-full flex items-center justify-center bg-white shadow p-1">
                        <img src="{{ asset($levelImagePath) }}" alt="{{ $currentLevel }} Seviye" class="h-10 w-10 object-contain drop-shadow-md">
                    </div>
                    <div class="ml-3 flex-grow">
                        <span class="text-gray-900 font-bold block text-sm">{{ $currentLevel }} Seviye</span>
                        <span class="text-gray-700 text-xs sm:inline">{{ $motivationalText }}</span>
                    </div>
                </div>
                
                <!-- Gün Bilgisi -->
                <div class="bg-white rounded-lg shadow-md px-2 py-1 flex items-center min-w-[70px]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#e63946]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <div class="ml-1">
                        <span class="text-gray-900 font-bold text-xs">{{ $daysCompleted }}</span>
                        <span class="text-gray-600 font-medium text-xs"> gün</span>
                    </div>
                </div>
            </div>
            
            <!-- Alt Satır - Butonlar -->
            <div class="flex justify-between gap-2">
                <!-- Zinciri Kırma Sayfasına Git Butonu -->
                <a href="{{ route('zinciri-kirma') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold text-xs py-2 px-3 rounded-lg flex items-center flex-grow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    <span>Zinciri Kırma</span>
                </a>
                
                <!-- Günü Tamamla Butonu -->
                <button wire:click="completeDay" class="bg-gradient-to-r from-[#e63946] to-[#d62836] text-white font-bold text-xs py-2 px-3 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Günü Tamamla</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endauth

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