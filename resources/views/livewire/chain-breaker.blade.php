<!-- resources/views/livewire/chain-breaker.blade.php -->
<div>
    <!-- Hero Section -->
    <div class="relative py-16 sm:py-20 overflow-hidden">
        <!-- Dekoratif arka plan desenleri -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)" />
            </svg>
        </div>

        <div class="container mx-auto px-4 sm:px-6 relative z-10">
            <div class="text-center mb-8 sm:mb-12">
                <span class="bg-[#e63946] text-white text-lg sm:text-xl px-3 sm:px-4 py-1 sm:py-2 rounded-lg shadow-lg inline-block transform -rotate-2 hover:rotate-0 transition-transform duration-300 font-bold">
                    <i class="fas fa-link mr-2"></i>ZİNCİRİ KIRMA SİSTEMİ
                </span>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mt-4 sm:mt-6 mb-3 sm:mb-4">
                    Zinciri Kırma Sistemi ile <span class="text-[#e63946]">Disiplinli Çalış</span>, Seviye Atla!
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Eğitim süreci, yalnızca bilgi edinme değil; aynı zamanda <span class="font-semibold">alışkanlıklar geliştirme, sorumluluk alma ve hedefe odaklanma</span> yolculuğudur.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pb-12 sm:pb-20">
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <!-- Zincir Görselleştirme Alanı -->
            <div class="p-4 sm:p-6 md:p-10 bg-gray-50">
                <div class="mb-6 sm:mb-8 text-center">
                    <h2 class="text-2xl sm:text-3xl font-bold text-[#1a2e5a]">Günlük İlerleme Zinciriniz</h2>
                    <p class="text-gray-600 mt-2">Her gün düzenli çalışarak zincirinizi güçlendirin ve seviye atlayın!</p>
                </div>

                @if(!$isUserAuthenticated)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 sm:mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Zinciri başlatmak ve ilerlemenizi kaydetmek için <a href="{{ route('login') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">giriş yapın</a> veya <a href="{{ route('register') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">hesap oluşturun</a>.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Zinciriniz - MOBİL UYUMLU YENİ TASARIM -->
                <div class="relative">
                    <!-- Seviye Göstergesi - Mobil uyumlu hale getirildi -->
                    <div class="absolute -left-2 top-0 md:-top-8 z-20 flex flex-col items-center">
                        <div class="text-center">
                            @switch($currentLevel)
                                @case('Bronz')
                                    <img src="{{ asset('images/bronz.png') }}" alt="Bronz Seviye" class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 object-contain drop-shadow-xl">
                                    @break
                                @case('Demir')
                                    <img src="{{ asset('images/demir.png') }}" alt="Demir Seviye" class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 object-contain drop-shadow-xl">
                                    @break
                                @case('Gümüş')
                                    <img src="{{ asset('images/gumus.png') }}" alt="Gümüş Seviye" class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 object-contain drop-shadow-xl">
                                    @break
                                @case('Altın')
                                    <img src="{{ asset('images/altin.png') }}" alt="Altın Seviye" class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 object-contain drop-shadow-xl">
                                    @break
                                @case('Platin')
                                    <img src="{{ asset('images/platin.png') }}" alt="Platin Seviye" class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 object-contain drop-shadow-xl">
                                    @break
                                @case('Zümrüt')
                                    <img src="{{ asset('images/zumrut.png') }}" alt="Zümrüt Seviye" class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 object-contain drop-shadow-xl">
                                    @break
                                @case('Elmas')
                                    <img src="{{ asset('images/elmas.png') }}" alt="Elmas Seviye" class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 object-contain drop-shadow-xl">
                                    @break
                                @case('MASTER')
                                    <img src="{{ asset('images/master.png') }}" alt="Master Seviye" class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 object-contain drop-shadow-xl">
                                    @break
                                @default
                                    <img src="{{ asset('images/bronz.png') }}" alt="Bronz Seviye" class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 object-contain drop-shadow-xl">
                            @endswitch
                            <div class="mt-1 text-xs sm:text-sm font-bold text-[#1a2e5a] bg-white px-2 py-1 rounded-md shadow-md">{{ $currentLevel }}</div>
                        </div>
                    </div>

                    <!-- Chain Container - Mobil için margin ekledik -->
                    <div id="chain-container" class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 shadow-md relative overflow-hidden ml-16 sm:ml-20 md:ml-24">
                        <div class="flex flex-wrap justify-center gap-2" id="chain-links-container">
                            <!-- Zincir halkaları -->
                            @for($i = 0; $i < $maxDays; $i++)
                                @if($i < $daysCompleted)
                                    <div class="chain-link w-6 sm:w-8 h-6 sm:h-8 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background-color: {{ $levelColor }}">
                                        <i class="fas fa-check"></i>
                                    </div>
                                @else
                                    <div class="chain-link w-6 sm:w-8 h-6 sm:h-8 rounded-full flex items-center justify-center text-xs font-bold bg-gray-200 text-gray-400">
                                        {{ $i + 1 }}
                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>

                    <!-- İlerleyiş Bilgisi -->
                    <div class="absolute -right-2 sm:-right-4 -bottom-2 sm:-bottom-4 bg-[#e63946] text-white rounded-lg p-2 sm:p-3 shadow-lg z-20">
                        <div class="text-center">
                            <div class="text-xs sm:text-sm font-semibold">Toplam Gün</div>
                            <div id="dayCount" class="text-xl sm:text-2xl font-extrabold">{{ $daysCompleted }}/{{ $maxDays }}</div>
                        </div>
                    </div>
                </div>

                <!-- Kontrol Butonları -->
                <div class="mt-8 sm:mt-10 flex flex-wrap justify-center gap-3 sm:gap-4">
                    <button wire:click="completeDay" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 flex items-center text-sm sm:text-base">
                        <i class="fas fa-plus-circle mr-2"></i>Günü Tamamla
                    </button>
                </div>
            </div>

            <!-- Seviye Atlama Modal -->
            @if($showLevelUpModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-xl shadow-2xl p-4 sm:p-8 max-w-md w-full">
                    <div class="text-center mb-4">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full mx-auto mb-4 flex items-center justify-center" style="background-color: {{ $levelColor }}">
                            <i class="fas fa-trophy text-3xl sm:text-4xl text-white"></i>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold" style="color: {{ $levelColor }}">Tebrikler!</h2>
                        <p class="text-lg sm:text-xl text-gray-700 mt-2">Yeni seviyeye ulaştınız!</p>
                    </div>
                    
                    <div class="flex items-center justify-center space-x-4 mb-6">
                        <div class="text-center">
                            <div class="text-gray-500 font-medium text-sm sm:text-base">Önceki Seviye</div>
                            <div class="text-lg sm:text-xl font-bold">{{ $previousLevel }}</div>
                        </div>
                        
                        <div class="text-gray-400">
                            <i class="fas fa-arrow-right text-xl sm:text-2xl"></i>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-gray-500 font-medium text-sm sm:text-base">Yeni Seviye</div>
                            <div class="text-xl sm:text-2xl font-bold" style="color: {{ $levelColor }}">{{ $currentLevel }}</div>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 text-center mb-6 text-sm sm:text-base">Bu tempoda devam ederek bir sonraki seviyeye ulaşmak için çalışın. Her gün bir adım daha!</p>
                    
                    <div class="text-center">
                        <button wire:click="closeLevelUpModal" class="bg-[#1a2e5a] hover:bg-[#132447] text-white font-bold py-2 sm:py-3 px-6 sm:px-8 rounded-lg shadow-lg transition duration-300 text-sm sm:text-base">
                            Harika!
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Seviye Açıklamaları -->
            <div class="p-4 sm:p-6 md:p-10 border-t border-gray-200">
                <h2 class="text-2xl sm:text-3xl font-bold text-[#1a2e5a] mb-4 sm:mb-6">Seviye Atlama Sistemi Nasıl İşliyor?</h2>
                <p class="text-gray-700 mb-4 sm:mb-6 text-sm sm:text-base">
                    Öğrenciler her ay zinciri kırmadan çalışmaya devam ettikçe, başarılarını somutlaştıran bir seviye kazanır. 
                    Bu seviyeler, öğrencinin istikrarlı emeğini ve çabasını yansıtır.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <!-- Seviye Kartları -->
                    <div class="relative bg-gradient-to-br from-amber-700 to-yellow-600 rounded-lg p-4 sm:p-6 text-white shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-amber-700 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">1</div>
                        <h3 class="text-lg sm:text-xl font-bold mb-2">Bronz</h3>
                        <p class="text-xs sm:text-sm opacity-90">Başlangıç seviyesi – Disiplin yolculuğunun ilk adımı.</p>
                        <div class="mt-4 h-1 bg-white/30 rounded-full">
                            <div class="h-full bg-white rounded-full w-full"></div>
                        </div>
                    </div>
                    
                    <div class="relative bg-gradient-to-br from-gray-600 to-gray-500 rounded-lg p-4 sm:p-6 text-white shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-gray-600 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">2</div>
                        <h3 class="text-lg sm:text-xl font-bold mb-2">Demir</h3>
                        <p class="text-xs sm:text-sm opacity-90">Direncin sembolü – Devamlılığın güç kazanıyor.</p>
                        <div class="mt-4 h-1 bg-white/30 rounded-full">
                            <div class="h-full bg-white rounded-full w-4/5"></div>
                        </div>
                    </div>
                    
                    <div class="relative bg-gradient-to-br from-gray-300 to-gray-400 rounded-lg p-4 sm:p-6 text-gray-800 shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-gray-400 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">3</div>
                        <h3 class="text-lg sm:text-xl font-bold mb-2">Gümüş</h3>
                        <p class="text-xs sm:text-sm opacity-90">Kararlılığın meyvesi – İstikrar sağlanıyor.</p>
                        <div class="mt-4 h-1 bg-gray-800/30 rounded-full">
                            <div class="h-full bg-gray-800 rounded-full w-3/5"></div>
                        </div>
                    </div>
                    
                    <div class="relative bg-gradient-to-br from-yellow-500 to-yellow-300 rounded-lg p-4 sm:p-6 text-yellow-900 shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-yellow-500 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">4</div>
                        <h3 class="text-lg sm:text-xl font-bold mb-2">Altın</h3>
                        <p class="text-xs sm:text-sm opacity-90">Parlama zamanı – Öğrenme süreci artık daha bilinçli.</p>
                        <div class="mt-4 h-1 bg-yellow-900/30 rounded-full">
                            <div class="h-full bg-yellow-900 rounded-full w-2/5"></div>
                        </div>
                    </div>
                    
                    <div class="relative bg-gradient-to-br from-gray-200 to-gray-100 rounded-lg p-4 sm:p-6 text-gray-800 shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-gray-500 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">5</div>
                        <h3 class="text-lg sm:text-xl font-bold mb-2">Platin</h3>
                        <p class="text-xs sm:text-sm opacity-90">Yüksek başarı – Kendini aşma süreci hızlanıyor.</p>
                        <div class="mt-4 h-1 bg-gray-800/30 rounded-full">
                            <div class="h-full bg-gray-800 rounded-full w-1/5"></div>
                        </div>
                    </div>
                    
                    <div class="relative bg-gradient-to-br from-emerald-600 to-emerald-400 rounded-lg p-4 sm:p-6 text-white shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-emerald-600 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">6</div>
                        <h3 class="text-lg sm:text-xl font-bold mb-2">Zümrüt</h3>
                        <p class="text-xs sm:text-sm opacity-90">Örnek birey – Disiplinin çevrene ilham veriyor.</p>
                        <div class="mt-4 h-1 bg-white/30 rounded-full">
                            <div class="h-full bg-white rounded-full w-2/12"></div>
                        </div>
                    </div>
                    
                    <div class="relative bg-gradient-to-br from-blue-600 to-cyan-400 rounded-lg p-4 sm:p-6 text-white shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-blue-600 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">7</div>
                        <h3 class="text-lg sm:text-xl font-bold mb-2">Elmas</h3>
                        <p class="text-xs sm:text-sm opacity-90">Mükemmelliğe yakınlık – Artık büyük bir hedefin var.</p>
                        <div class="mt-4 h-1 bg-white/30 rounded-full">
                            <div class="h-full bg-white rounded-full w-1/12"></div>
                        </div>
                    </div>
                    
                    <div class="relative bg-gradient-to-br from-purple-700 to-pink-500 rounded-lg p-4 sm:p-6 text-white shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-purple-700 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">8</div>
                        <h3 class="text-lg sm:text-xl font-bold mb-2">MASTER</h3>
                        <p class="text-xs sm:text-sm opacity-90">Ustalık seviyesi – Öğrenmenin zirvesindesin.</p>
                        <div class="mt-4 h-1 bg-white/30 rounded-full">
                            <div class="h-full bg-white rounded-full w-0"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Açıklama Kısmı -->
            <div class="p-4 sm:p-6 md:p-10 bg-gray-50 border-t border-gray-200">
                <h2 class="text-2xl sm:text-3xl font-bold text-[#1a2e5a] mb-4 sm:mb-6">Zinciri Kırma Nedir?</h2>
                <div class="prose prose-sm sm:prose-base lg:prose-lg max-w-none text-gray-700">
                    <p>"Zinciri Kırma", küçük ama istikrarlı adımlarla büyük hedeflere ulaşmayı esas alan bir yöntemdir. Bu zincir, her gün bir önceki günün üzerine eklenerek büyür.</p>
                    <p class="font-bold">Amaç? Hiçbir günü boş geçirmemek, zinciri asla kırmamak.</p>
                    
                    <h3 class="text-xl sm:text-2xl font-bold text-[#1a2e5a] mt-6 sm:mt-8 mb-3 sm:mb-4">Sistemin Faydaları</h3>
                    <ul class="text-sm sm:text-base">
                        <li>Öğrencilerde <strong>sorumluluk bilinci</strong> oluşturur.</li>
                        <li><strong>Düzenli çalışma alışkanlığı</strong> kazandırır.</li>
                        <li>Görsel takip sayesinde <strong>motive edici bir süreç</strong> sunar.</li>
                        <li>Seviyeler sayesinde öğrenciler <strong>hedef odaklı</strong> çalışır.</li>
                        <li>Öğrenci, gelişimini <strong>somut ve adım adım</strong> izleyebilir.</li>
                    </ul>
                    
                    <h3 class="text-xl sm:text-2xl font-bold text-[#1a2e5a] mt-6 sm:mt-8 mb-3 sm:mb-4">Sonuç Olarak</h3>
                    <p>"Zinciri Kırma – Seviye Atlama" sistemi, öğrencilerin akademik gelişimlerini desteklerken aynı zamanda yaşam boyu sürecek bir disiplin anlayışı kazandırmayı amaçlamaktadır. Her ✔️ işareti, öğrencinin kendine olan bağlılığını ve hedeflerine olan inancını temsil eder.</p>
                    
                    <div class="bg-[#1a2e5a] text-white p-4 sm:p-6 rounded-lg mt-6 sm:mt-8">
                        <p class="text-base sm:text-xl font-bold">Bugün bir adım at. Zinciri başlat. Seviyeni yükselt. Ve unutma: Zinciri Kırma, Geleceğini İnşa Et.</p>
                    </div>
                    
                    <div class="text-center mt-8 sm:mt-10 text-[#e63946] font-bold text-lg sm:text-xl">
                        RISE ENGLISH BAŞARILAR DİLER
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Konfeti Efekti İçin Script -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Başarı efekti
        window.addEventListener('show-success', event => {
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#e63946', '#1a2e5a', '#FFD700']
            });

            if (event.detail?.message) {
                showToast('success', event.detail.message);
            }
        });

        // Hata efekti
        window.addEventListener('show-error', event => {
            if (event.detail?.message) {
                showToast('error', event.detail.message);
            }
        });

        // Bilgilendirme efekti
        window.addEventListener('show-info', event => {
            if (event.detail?.message) {
                showToast('info', event.detail.message);
            }
        });

        // Seviye atlama animasyonu
        window.addEventListener('level-up-animation', event => {
            confetti({
                particleCount: 200,
                spread: 100,
                origin: { y: 0.4 },
                colors: ['#e63946', '#1a2e5a', '#FFD700']
            });
        });

        // Zincir kırma animasyonu
        window.addEventListener('chain-break-animation', event => {
            confetti({
                particleCount: 80,
                spread: 100,
                origin: { y: 0.4 },
                gravity: 1.5,
                colors: ['#e63946']
            });
        });

        // Zinciri sıfırlarken onay kutusu
        window.addEventListener('confirm-reset', event => {
            if (confirm('Zinciri sıfırlamak istediğinize emin misiniz? Bu işlem geri alınamaz!')) {
                Livewire.emit('confirmReset');
            }
        });

        // Toast mesajı gösterme fonksiyonu - Mobil uyumlu hale getirildi
        function showToast(type, message) {
            let bgColor = '';
            let textColor = 'text-white';
            let icon = '';
            
            switch(type) {
                case 'success':
                    bgColor = 'bg-green-500';
                    icon = '<svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                    break;
                case 'error':
                    bgColor = 'bg-red-500';
                    icon = '<svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                    break;
                case 'info':
                    bgColor = 'bg-blue-500';
                    icon = '<svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                    break;
            }
            
            // Toast oluştur - Mobile responsive tasarım
            const toast = document.createElement('div');
            
            // Mobil cihazlar için alt kısımda gösterme
            if (window.innerWidth < 640) {
                toast.className = `${bgColor} ${textColor} p-3 rounded-lg shadow-lg flex items-center fixed left-1/2 bottom-4 z-50 transform -translate-x-1/2 translate-y-full transition-transform duration-300 text-xs sm:text-sm max-w-xs`;
            } else {
                // Masaüstü için sağ üstte gösterme
                toast.className = `${bgColor} ${textColor} p-4 rounded-lg shadow-lg flex items-center fixed right-4 top-4 z-50 transform transition-transform duration-300 translate-x-full text-sm max-w-md`;
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