<!-- resources/views/livewire/chain-breaker.blade.php -->
<div>
    <!-- Hero Section -->
    <div class="relative py-20 overflow-hidden">
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

        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-12">
                <span class="bg-[#e63946] text-white text-xl px-4 py-2 rounded-lg shadow-lg inline-block transform -rotate-2 hover:rotate-0 transition-transform duration-300 font-bold">
                    <i class="fas fa-link mr-2"></i>ZİNCİRİ KIRMA SİSTEMİ
                </span>
                <h1 class="text-4xl md:text-5xl font-bold text-white mt-6 mb-4">
                    Zinciri Kırma Sistemi ile <span class="text-[#e63946]">Disiplinli Çalış</span>, Seviye Atla!
                </h1>
                <p class="text-xl text-white/80 max-w-3xl mx-auto">
                    Eğitim süreci, yalnızca bilgi edinme değil; aynı zamanda <span class="font-semibold">alışkanlıklar geliştirme, sorumluluk alma ve hedefe odaklanma</span> yolculuğudur.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pb-20">
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <!-- Zincir Görselleştirme Alanı -->
            <div class="p-6 md:p-10 bg-gray-50">
                <div class="mb-8 text-center">
                    <h2 class="text-3xl font-bold text-[#1a2e5a]">Günlük İlerleme Zinciriniz</h2>
                    <p class="text-gray-600 mt-2">Her gün düzenli çalışarak zincirinizi güçlendirin ve seviye atlayın!</p>
                </div>

                @if(!$isUserAuthenticated)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
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

                <!-- Zinciriniz - ORİJİNAL TASARIM -->
                <div class="relative">
                    <!-- Seviye Göstergesi -->
                    <div class="absolute -left-4 top-0 bg-[#1a2e5a] text-white rounded-lg p-3 shadow-lg transform rotate-3 z-20">
                        <div class="text-center">
                            <div class="text-sm font-semibold">Mevcut Seviye</div>
                            <div id="currentLevel" class="text-2xl font-extrabold" style="color: {{ $levelColor }};">{{ $currentLevel }}</div>
                        </div>
                    </div>

                    <!-- Chain Container -->
                    <div id="chain-container" class="bg-white rounded-lg border border-gray-200 p-6 shadow-md relative overflow-hidden">
                        <div class="flex flex-wrap justify-center gap-2" id="chain-links-container">
                            <!-- Zincir halkaları -->
                            @for($i = 0; $i < $maxDays; $i++)
                                @if($i < $daysCompleted)
                                    <div class="chain-link w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background-color: {{ $levelColor }}">
                                        <i class="fas fa-check"></i>
                                    </div>
                                @else
                                    <div class="chain-link w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold bg-gray-200 text-gray-400">
                                        {{ $i + 1 }}
                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>

                    <!-- İlerleyiş Bilgisi -->
                    <div class="absolute -right-4 -bottom-4 bg-[#e63946] text-white rounded-lg p-3 shadow-lg z-20">
                        <div class="text-center">
                            <div class="text-sm font-semibold">Toplam Gün</div>
                            <div id="dayCount" class="text-2xl font-extrabold">{{ $daysCompleted }}/{{ $maxDays }}</div>
                        </div>
                    </div>
                </div>

                <!-- Streak Bilgisi -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-[#1a2e5a] bg-opacity-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Mevcut Seri</h3>
                                <p class="text-2xl font-bold text-[#1a2e5a]">{{ $currentStreak }} gün</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-[#e63946] bg-opacity-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#e63946]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">En Uzun Seri</h3>
                                <p class="text-2xl font-bold text-[#e63946]">{{ $longestStreak }} gün</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kontrol Butonları -->
                <div class="mt-10 flex flex-wrap justify-center gap-4">
                    <button wire:click="completeDay" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i>Günü Tamamla
                    </button>
                    <button wire:click="resetChain" class="bg-[#1a2e5a] hover:bg-[#132447] text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 flex items-center">
                        <i class="fas fa-undo mr-2"></i>Zinciri Sıfırla
                    </button>
                </div>
            </div>

            <!-- Seviye Atlama Modal -->
            @if($showLevelUpModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full">
                    <div class="text-center mb-4">
                        <div class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center" style="background-color: {{ $levelColor }}">
                            <i class="fas fa-trophy text-4xl text-white"></i>
                        </div>
                        <h2 class="text-3xl font-bold" style="color: {{ $levelColor }}">Tebrikler!</h2>
                        <p class="text-xl text-gray-700 mt-2">Yeni seviyeye ulaştınız!</p>
                    </div>
                    
                    <div class="flex items-center justify-center space-x-4 mb-6">
                        <div class="text-center">
                            <div class="text-gray-500 font-medium">Önceki Seviye</div>
                            <div class="text-xl font-bold">{{ $previousLevel }}</div>
                        </div>
                        
                        <div class="text-gray-400">
                            <i class="fas fa-arrow-right text-2xl"></i>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-gray-500 font-medium">Yeni Seviye</div>
                            <div class="text-2xl font-bold" style="color: {{ $levelColor }}">{{ $currentLevel }}</div>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 text-center mb-6">Bu tempoda devam ederek bir sonraki seviyeye ulaşmak için çalışın. Her gün bir adım daha!</p>
                    
                    <div class="text-center">
                        <button wire:click="closeLevelUpModal" class="bg-[#1a2e5a] hover:bg-[#132447] text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-300">
                            Harika!
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Seviye Açıklamaları -->
            <div class="p-6 md:p-10 border-t border-gray-200">
                <h2 class="text-3xl font-bold text-[#1a2e5a] mb-6">Seviye Atlama Sistemi Nasıl İşliyor?</h2>
                <p class="text-gray-700 mb-6">
                    Öğrenciler her ay zinciri kırmadan çalışmaya devam ettikçe, başarılarını somutlaştıran bir seviye kazanır. 
                    Bu seviyeler, öğrencinin istikrarlı emeğini ve çabasını yansıtır.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Seviye Kartları -->
                    <div class="relative bg-gradient-to-br from-amber-700 to-yellow-600 rounded-lg p-6 text-white shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-amber-700 rounded-full w-10 h-10 flex items-center justify-center font-bold shadow-md">1</div>
                        <h3 class="text-xl font-bold mb-2">Bronz</h3>
                        <p class="text-sm opacity-90">Başlangıç seviyesi – Disiplin yolculuğunun ilk adımı.</p>
                        <div class="mt-4 h-1 bg-white/30 rounded-full">
                            <div class="h-full bg-white rounded-full w-full"></div>
                        </div>
                    </div>
                    
                    <div class="relative bg-gradient-to-br from-gray-600 to-gray-500 rounded-lg p-6 text-white shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-gray-600 rounded-full w-10 h-10 flex items-center justify-center font-bold shadow-md">2</div>
                        <h3 class="text-xl font-bold mb-2">Demir</h3>
                        <p class="text-sm opacity-90">Direncin sembolü – Devamlılığın güç kazanıyor.</p>
                        <div class="mt-4 h-1 bg-white/30 rounded-full">
                            <div class="h-full bg-white rounded-full w-4/5"></div>
                        </div>
                    </div>
                    
                    <div class="relative bg-gradient-to-br from-gray-300 to-gray-400 rounded-lg p-6 text-gray-800 shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-gray-400 rounded-full w-10 h-10 flex items-center justify-center font-bold shadow-md">3</div>
                        <h3 class="text-xl font-bold mb-2">Gümüş</h3>
                        <p class="text-sm opacity-90">Kararlılığın meyvesi – İstikrar sağlanıyor.</p>
                        <div class="mt-4 h-1 bg-gray-800/30 rounded-full">
                            <div class="h-full bg-gray-800 rounded-full w-3/5"></div>
                        </div>
                    </div>
                    
                    <div class="relative bg-gradient-to-br from-yellow-500 to-yellow-300 rounded-lg p-6 text-yellow-900 shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-yellow-500 rounded-full w-10 h-10 flex items-center justify-center font-bold shadow-md">4</div>
                        <h3 class="text-xl font-bold mb-2">Altın</h3>
                        <p class="text-sm opacity-90">Parlama zamanı – Öğrenme süreci artık daha bilinçli.</p>
                        <div class="mt-4 h-1 bg-yellow-900/30 rounded-full">
                            <div class="h-full bg-yellow-900 rounded-full w-2/5"></div>
                        </div>
                    </div>
                    
                    <div class="relative bg-gradient-to-br from-gray-200 to-gray-100 rounded-lg p-6 text-gray-800 shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-gray-500 rounded-full w-10 h-10 flex items-center justify-center font-bold shadow-md">5</div>
                        <h3 class="text-xl font-bold mb-2">Platin</h3>
                        <p class="text-sm opacity-90">Yüksek başarı – Kendini aşma süreci hızlanıyor.</p>
                        <div class="mt-4 h-1 bg-gray-800/30 rounded-full">
                            <div class="h-full bg-gray-800 rounded-full w-1/5"></div>
                        </div>
                    </div>
                    
                    <div class="relative bg-gradient-to-br from-emerald-600 to-emerald-400 rounded-lg p-6 text-white shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-emerald-600 rounded-full w-10 h-10 flex items-center justify-center font-bold shadow-md">6</div>
                        <h3 class="text-xl font-bold mb-2">Zümrüt</h3>
                        <p class="text-sm opacity-90">Örnek birey – Disiplinin çevrene ilham veriyor.</p>
                        <div class="mt-4 h-1 bg-white/30 rounded-full">
                            <div class="h-full bg-white rounded-full w-2/12"></div>
                        </div>
                    </div>
                    
                    <div class="relative bg-gradient-to-br from-blue-600 to-cyan-400 rounded-lg p-6 text-white shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-blue-600 rounded-full w-10 h-10 flex items-center justify-center font-bold shadow-md">7</div>
                        <h3 class="text-xl font-bold mb-2">Elmas</h3>
                        <p class="text-sm opacity-90">Mükemmelliğe yakınlık – Artık büyük bir hedefin var.</p>
                        <div class="mt-4 h-1 bg-white/30 rounded-full">
                            <div class="h-full bg-white rounded-full w-1/12"></div>
                        </div>
                    </div>
                    
                    <div class="relative bg-gradient-to-br from-purple-700 to-pink-500 rounded-lg p-6 text-white shadow-lg transform transition-transform hover:scale-105">
                        <div class="absolute -top-3 -right-3 bg-white text-purple-700 rounded-full w-10 h-10 flex items-center justify-center font-bold shadow-md">8</div>
                        <h3 class="text-xl font-bold mb-2">MASTER</h3>
                        <p class="text-sm opacity-90">Ustalık seviyesi – Öğrenmenin zirvesindesin.</p>
                        <div class="mt-4 h-1 bg-white/30 rounded-full">
                            <div class="h-full bg-white rounded-full w-0"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Açıklama Kısmı -->
            <div class="p-6 md:p-10 bg-gray-50 border-t border-gray-200">
                <h2 class="text-3xl font-bold text-[#1a2e5a] mb-6">Zinciri Kırma Nedir?</h2>
                <div class="prose prose-lg max-w-none text-gray-700">
                    <p>"Zinciri Kırma", küçük ama istikrarlı adımlarla büyük hedeflere ulaşmayı esas alan bir yöntemdir. Bu zincir, her gün bir önceki günün üzerine eklenerek büyür.</p>
                    <p class="font-bold">Amaç? Hiçbir günü boş geçirmemek, zinciri asla kırmamak.</p>
                    
                    <h3 class="text-2xl font-bold text-[#1a2e5a] mt-8 mb-4">Sistemin Faydaları</h3>
                    <ul>
                        <li>Öğrencilerde <strong>sorumluluk bilinci</strong> oluşturur.</li>
                        <li><strong>Düzenli çalışma alışkanlığı</strong> kazandırır.</li>
                        <li>Görsel takip sayesinde <strong>motive edici bir süreç</strong> sunar.</li>
                        <li>Seviyeler sayesinde öğrenciler <strong>hedef odaklı</strong> çalışır.</li>
                        <li>Öğrenci, gelişimini <strong>somut ve adım adım</strong> izleyebilir.</li>
                    </ul>
                    
                    <h3 class="text-2xl font-bold text-[#1a2e5a] mt-8 mb-4">Sonuç Olarak</h3>
                    <p>"Zinciri Kırma – Seviye Atlama" sistemi, öğrencilerin akademik gelişimlerini desteklerken aynı zamanda yaşam boyu sürecek bir disiplin anlayışı kazandırmayı amaçlamaktadır. Her ✔️ işareti, öğrencinin kendine olan bağlılığını ve hedeflerine olan inancını temsil eder.</p>
                    
                    <div class="bg-[#1a2e5a] text-white p-6 rounded-lg mt-8">
                        <p class="text-xl font-bold">Bugün bir adım at. Zinciri başlat. Seviyeni yükselt. Ve unutma: Zinciri Kırma, Geleceğini İnşa Et.</p>
                    </div>
                    
                    <div class="text-center mt-10 text-[#e63946] font-bold text-xl">
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
            
            // Toast oluştur
            const toast = document.createElement('div');
            toast.className = `${bgColor} ${textColor} p-4 rounded-lg shadow-lg flex items-center fixed right-4 top-4 z-50 transform transition-transform duration-300 translate-x-full`;
            toast.innerHTML = `<div class="flex items-center">${icon}<div>${message}</div></div>`;
            document.body.appendChild(toast);
            
            // Toast göster
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            // Toast gizle ve kaldır
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 4000);
        }
    });
</script>