<div>
    <!-- Hero Section -->
    <div class="relative py-16 sm:py-20 overflow-hidden">
        <!-- Dekoratif arka plan desenleri -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)" />
            </svg>
        </div>

        <div class="container mx-auto px-4 sm:px-6 relative z-10">
            <!-- İkon Cinsiyet Seçimi - Sayfa başında göster -->
            @if ($isUserAuthenticated && $showGenderSelector)
                <div class="mb-6 bg-white/20 backdrop-blur-sm p-4 rounded-xl shadow-lg border border-white/30">
                    <div class="text-center mb-3">
                        <h3 class="text-lg font-bold text-white">İkon Tercihinizi Seçin</h3>
                        <p class="text-sm text-white/80">Seviye ikonlarının hangi versiyonunu görmek istersiniz?</p>
                    </div>
                    
                    <div class="flex justify-center gap-4">
                        <!-- Erkek İkon Seçeneği -->
                        <button wire:click="setGender('erkek')" class="flex flex-col items-center p-3 bg-white/90 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg hover:scale-105">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                                <i class="fas fa-male text-blue-600 text-xl"></i>
                            </div>
                            <span class="font-medium text-gray-800">Erkek</span>
                        </button>

                        <!-- Kadın İkon Seçeneği -->
                        <button wire:click="setGender('kadin')" class="flex flex-col items-center p-3 bg-white/90 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg hover:scale-105">
                            <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mb-2">
                                <i class="fas fa-female text-pink-600 text-xl"></i>
                            </div>
                            <span class="font-medium text-gray-800">Kadın</span>
                        </button>
                    </div>
                </div>
            @endif

            <div class="text-center mb-8 sm:mb-12">
                <span
                    class="bg-[#e63946] text-white text-lg sm:text-xl px-3 sm:px-4 py-1 sm:py-2 rounded-lg shadow-lg inline-block transform -rotate-2 hover:rotate-0 transition-transform duration-300 font-bold">
                    <i class="fas fa-link mr-2"></i>ZİNCİRİ KIRMA SİSTEMİ
                </span>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mt-4 sm:mt-6 mb-3 sm:mb-4">
                    Zinciri Kırma Sistemi ile <span class="text-[#e63946]">Disiplinli Çalış</span>, Seviye Atla!
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Eğitim süreci, yalnızca bilgi edinme değil; aynı zamanda <span class="font-semibold">alışkanlıklar
                        geliştirme, sorumluluk alma ve hedefe odaklanma</span> yolculuğudur.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pb-12 sm:pb-20">
         <div class="p-4 sm:p-6 md:p-10">
<livewire:chain-leaderboard :key="'leaderboard-'.auth()->id()" lazy />

        </div> 
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <!-- Zincir Görselleştirme Alanı -->
            <div class="p-4 sm:p-6 md:p-10 bg-gray-50 relative">
                <div class="mb-6 sm:mb-8 text-center">
                    <h2 class="text-2xl sm:text-3xl font-bold text-[#1a2e5a]">Günlük İlerleme Zinciriniz</h2>
                    <p class="text-gray-600 mt-2">Her gün düzenli çalışarak zincirinizi güçlendirin ve seviye atlayın!
                    </p>
                </div>

                @if (!$isUserAuthenticated)
                    <div class="bg-gradient-to-r from-[#e63946] to-[#d62836] text-white p-6 rounded-lg mt-8 shadow-lg">
                        <div class="flex flex-col md:flex-row items-center justify-between">
                            <div class="mb-4 md:mb-0 text-center md:text-left">
                                <h3 class="text-xl font-extrabold mb-2">BU YOLCULUKTA SEN DE YERİNİ AL!</h3>
                                <p class="text-white/90">İlerleme kaydetmek, seviye atlamak ve kendi başarı hikayeni
                                    yazmak için hemen üye ol.</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('login') }}"
                                    class="px-6 py-3 bg-white text-[#e63946] font-bold rounded-lg hover:bg-gray-100 transition duration-300 text-center">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Giriş Yap
                                </a>
                                <a href="{{ route('register') }}"
                                    class="px-6 py-3 bg-[#1a2e5a] text-white font-bold rounded-lg hover:bg-[#15274d] transition duration-300 flex items-center justify-center animate-pulse">
                                    <i class="fas fa-user-plus mr-2"></i>HEMEN ÜYE OL
                                </a>
                            </div>
                        </div>
                    </div>
                @endif <!-- Zinciriniz - MOBİL UYUMLU YENİ TASARIM -->

<div class="relative">
    <!-- Seviye Göstergesi - İnce ve uzun format -->
    <div class="absolute -left-2 top-0 md:-top-8 z-20 flex flex-col items-center">
        <div class="text-center group">
            <!-- Seviye ikon alanı - Daha ince ve uzun tasarım -->
            <div class="relative transform transition-all duration-500 hover:scale-110 hover:rotate-3">
                <!-- Parlama efekti - Daha ince ve uzun -->
                <div class="absolute -inset-1 bg-gradient-to-br from-yellow-400 via-pink-500 to-purple-500 rounded-2xl opacity-30 blur-lg animate-pulse group-hover:opacity-60"></div>
                
                <!-- Seviye ikonu - İnce uzun format -->
                <div class="relative z-10 overflow-hidden rounded-2xl shadow-lg border-2
                    @if($currentLevel == 'Bronz') border-amber-600 
                    @elseif($currentLevel == 'Demir') border-gray-600
                    @elseif($currentLevel == 'Gümüş') border-gray-300
                    @elseif($currentLevel == 'Altın') border-yellow-400
                    @elseif($currentLevel == 'Platin') border-gray-200
                    @elseif($currentLevel == 'Zümrüt') border-emerald-500
                    @elseif($currentLevel == 'Elmas') border-blue-500
                    @elseif($currentLevel == 'MASTER') border-purple-600
                    @else border-amber-600
                    @endif">
                    @switch($currentLevel)
                        @case('Bronz')
                            <img src="{{ asset('images/icons/' . ($iconGender == 'kadin' ? 'bronzkadin.jpg' : 'bronzerkek.jpg')) }}" 
                                alt="Bronz Seviye" 
                                class="w-14 h-28 sm:w-16 sm:h-32 md:w-18 md:h-36 object-cover transform transition-transform group-hover:scale-105">
                            @break
                        @case('Demir')
                            <img src="{{ asset('images/icons/' . ($iconGender == 'kadin' ? 'demirkadin.jpg' : 'demirerkek.jpg')) }}" 
                                alt="Demir Seviye" 
                                class="w-14 h-28 sm:w-16 sm:h-32 md:w-18 md:h-36 object-cover transform transition-transform group-hover:scale-105">
                            @break
                        @case('Gümüş')
                            <img src="{{ asset('images/icons/' . ($iconGender == 'kadin' ? 'gumuskadin.jpg' : 'gumuserkek.jpg')) }}" 
                                alt="Gümüş Seviye" 
                                class="w-14 h-28 sm:w-16 sm:h-32 md:w-18 md:h-36 object-cover transform transition-transform group-hover:scale-105">
                            @break
                        @case('Altın')
                            <img src="{{ asset('images/icons/' . ($iconGender == 'kadin' ? 'altinkadin.jpg' : 'altinerkek.jpg')) }}" 
                                alt="Altın Seviye" 
                                class="w-14 h-28 sm:w-16 sm:h-32 md:w-18 md:h-36 object-cover transform transition-transform group-hover:scale-105">
                            @break
                        @case('Platin')
                            <img src="{{ asset('images/icons/' . ($iconGender == 'kadin' ? 'platinkadin.jpg' : 'platinerkek.jpg')) }}" 
                                alt="Platin Seviye" 
                                class="w-14 h-28 sm:w-16 sm:h-32 md:w-18 md:h-36 object-cover transform transition-transform group-hover:scale-105">
                            @break
                        @case('Zümrüt')
                            <img src="{{ asset('images/icons/' . ($iconGender == 'kadin' ? 'yakutkadin.jpg' : 'yakuterkek.jpg')) }}" 
                                alt="Zümrüt Seviye" 
                                class="w-14 h-28 sm:w-16 sm:h-32 md:w-18 md:h-36 object-cover transform transition-transform group-hover:scale-105">
                            @break
                        @case('Elmas')
                            <img src="{{ asset('images/icons/' . ($iconGender == 'kadin' ? 'elmaskadin.jpg' : 'elmaserkek.jpg')) }}" 
                                alt="Elmas Seviye" 
                                class="w-14 h-28 sm:w-16 sm:h-32 md:w-18 md:h-36 object-cover transform transition-transform group-hover:scale-105">
                            @break
                        @case('MASTER')
                            <img src="{{ asset('images/icons/' . ($iconGender == 'kadin' ? 'masterkadin.jpg' : 'mastererkek.jpg')) }}" 
                                alt="Master Seviye" 
                                class="w-14 h-28 sm:w-16 sm:h-32 md:w-18 md:h-36 object-cover transform transition-transform group-hover:scale-105">
                            @break
                        @default
                            <img src="{{ asset('images/icons/' . ($iconGender == 'kadin' ? 'bronzkadin.jpg' : 'bronzerkek.jpg')) }}" 
                                alt="Bronz Seviye" 
                                class="w-14 h-28 sm:w-16 sm:h-32 md:w-18 md:h-36 object-cover transform transition-transform group-hover:scale-105">
                    @endswitch
                </div>
                
                <!-- Efektler -->
                <!-- Üst parıltı efekti -->
                <div class="absolute -top-1 right-3 w-3 h-3 bg-white rounded-full opacity-80 animate-ping"></div>
                <!-- Alt parıltı efekti -->
                <div class="absolute -bottom-1 left-3 w-2 h-2 bg-white rounded-full opacity-60 animate-pulse"></div>
                <!-- Yan kenar parlaması -->
                <div class="absolute top-1/4 -right-1 w-1 h-12 bg-gradient-to-b from-transparent via-white to-transparent opacity-40 animate-pulse"></div>
            </div>
            
            <!-- Seviye etiketi - İyileştirilmiş görünüm -->
            <div class="mt-2 px-3 py-1 bg-gradient-to-r from-[#1a2e5a] to-[#2a4e8a] text-white rounded-lg shadow-lg transform -rotate-2 hover:rotate-0 transition-all duration-300 font-bold text-xs sm:text-sm relative min-w-20">
                <span class="relative z-10">{{ $currentLevel }}</span>
                <!-- Seviye etiketi için ışıltı efekti -->
                <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-20 blur-sm transform translate-y-1/2 animate-shine"></span>
            </div>
        </div>
    </div>

    <!-- Diğer içerikler - Chain Container -->
    <div id="chain-container" class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 shadow-md relative overflow-hidden ml-16 sm:ml-18 md:ml-20 {{ !$isUserAuthenticated ? 'opacity-50 filter blur-sm' : '' }}">
        <div class="flex flex-wrap justify-center gap-2" id="chain-links-container">
            <!-- Zincir halkaları -->
            @for($i = 0; $i < $maxDays; $i++)
                @if($i < $daysCompleted)
                    <div class="chain-link w-6 sm:w-8 h-6 sm:h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200" style="background-color: {{ $levelColor }}">
                        <i class="fas fa-check"></i>
                    </div>
                @else
                    <div class="chain-link w-6 sm:w-8 h-6 sm:h-8 rounded-full flex items-center justify-center text-xs font-bold bg-gray-200 text-gray-400 hover:bg-gray-300 hover:text-gray-500 transition-colors duration-200">
                        {{ $i + 1 }}
                    </div>
                @endif
            @endfor
        </div>
    </div>

    <!-- Giriş yapmamış kullanıcılar için overlay -->
    @if(!$isUserAuthenticated)
    <div class="absolute inset-0 flex items-center justify-center z-30 pointer-events-none">
        <a href="{{ route('register') }}" class="bg-gradient-to-r from-[#e63946] to-[#d62836] text-white font-bold py-3 px-6 rounded-lg shadow-lg transform hover:scale-105 hover:shadow-xl transition duration-300 animate-bounce pointer-events-auto relative overflow-hidden group">
            <span class="relative z-10">ÜYE OL & ZİNCİRE BAŞLA</span>
            <!-- Buton için ışıltı efekti -->
            <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-30 transform -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
        </a>
    </div>
    @endif

    <!-- İlerleyiş Bilgisi - daha göz alıcı tasarım -->
    <div class="absolute -right-2 sm:-right-4 -bottom-2 sm:-bottom-4 bg-gradient-to-br from-[#e63946] to-[#b8212d] text-white rounded-lg p-2 sm:p-3 shadow-lg z-20 transform hover:scale-105 transition-transform duration-300">
        <div class="text-center">
            <div class="text-xs sm:text-sm font-semibold">Toplam Gün</div>
            <div id="dayCount" class="text-xl sm:text-2xl font-extrabold">{{ $daysCompleted }}/{{ $maxDays }}</div>
        </div>
        <!-- Kenar parıltısı -->
        <div class="absolute -inset-1 bg-gradient-to-r from-pink-500 to-red-500 rounded-lg opacity-30 blur-md"></div>
    </div>
</div>
                <style>
                    @keyframes shine {
                        0% {
                            left: -100%;
                        }

                        100% {
                            left: 100%;
                        }
                    }

                    .animate-shine {
                        overflow: hidden;
                        position: relative;
                    }

                    .animate-shine::after {
                        content: '';
                        position: absolute;
                        top: 0;
                        right: 0;
                        bottom: 0;
                        left: 0;
                        background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.5), transparent);
                        animation: shine 3s infinite;
                        transform: skewX(-15deg);
                    }
                </style>

                <!-- Kontrol Butonları bölümüne ekleyin -->
                <div class="mt-8 sm:mt-10 flex flex-wrap justify-center gap-3 sm:gap-4">
                    @if ($isUserAuthenticated)
                        <button wire:click="toggleActivityForm"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 flex items-center text-sm sm:text-base">
                            <i class="fas fa-plus-circle mr-2"></i>Çalışma Ekle
                        </button>

                        <button wire:click="completeDay"
                            class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 flex items-center text-sm sm:text-base">
                            <i class="fas fa-check-circle mr-2"></i>Günü Tamamla
                        </button>

                        <button wire:click="toggleHistoryModal"
                            class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 flex items-center text-sm sm:text-base">
                            <i class="fas fa-history mr-2"></i>Geçmiş Çalışmalar
                        </button>
                    @else
                        <!-- Üye olmayan kullanıcılar için butonların devre dışı ve daha az belirgin versiyonları -->
                        <div class="relative group">
                            <button
                                class="bg-blue-300 text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded-lg shadow-lg flex items-center text-sm sm:text-base cursor-not-allowed opacity-70">
                                <i class="fas fa-plus-circle mr-2"></i>Çalışma Ekle
                            </button>
                            <div
                                class="absolute bottom-full left-1/2 transform -translate-x-1/2 -translate-y-2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none whitespace-nowrap">
                                Önce üye olmalısınız
                            </div>
                        </div>

                        <div class="relative group">
                            <button
                                class="bg-[#e6394680] text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded-lg shadow-lg flex items-center text-sm sm:text-base cursor-not-allowed opacity-70">
                                <i class="fas fa-check-circle mr-2"></i>Günü Tamamla
                            </button>
                            <div
                                class="absolute bottom-full left-1/2 transform -translate-x-1/2 -translate-y-2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none whitespace-nowrap">
                                Önce üye olmalısınız
                            </div>
                        </div>

                        <div class="relative group">
                            <button
                                class="bg-purple-400 text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded-lg shadow-lg flex items-center text-sm sm:text-base cursor-not-allowed opacity-70">
                                <i class="fas fa-history mr-2"></i>Geçmiş Çalışmalar
                            </button>
                            <div
                                class="absolute bottom-full left-1/2 transform -translate-x-1/2 -translate-y-2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none whitespace-nowrap">
                                Önce üye olmalısınız
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Giriş yapmamış kullanıcılar için ek call-to-action -->
                @if (!$isUserAuthenticated)
                    <div
                        class="mt-6 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-lg p-6 border border-blue-200 shadow-md">
                        <div class="flex flex-col sm:flex-row items-center justify-between">
                            <div class="mb-4 sm:mb-0">
                                <h3 class="text-xl font-bold text-[#1a2e5a] mb-2">Sisteme Erişim Kısıtlı</h3>
                                <p class="text-gray-700">Çalışma eklemek, dosya yüklemek ve ilerleme kaydetmek için üye
                                    olmalısınız.</p>
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('login') }}"
                                    class="bg-white border border-[#1a2e5a] text-[#1a2e5a] hover:bg-gray-50 font-bold py-2 px-4 rounded-lg transition duration-300">
                                    Giriş Yap
                                </a>
                                <a href="{{ route('register') }}"
                                    class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                                    Üye Ol
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Geçmiş Çalışmalar Modalı -->
                @if ($showHistoryModal)
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                        <div
                            class="bg-white rounded-xl shadow-2xl p-4 sm:p-8 max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-2xl sm:text-3xl font-bold text-[#1a2e5a]">Geçmiş Çalışmalarım</h2>
                                <button wire:click="closeHistoryModal" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times text-2xl"></i>
                                </button>
                            </div>

                            <!-- Ay Seçici -->
                            <div class="flex items-center justify-center gap-4 mb-6">
                                <button wire:click="changeMonth('prev')"
                                    class="p-2 rounded-full bg-gray-100 hover:bg-gray-200">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <div class="text-lg font-bold text-[#1a2e5a]">
                                    {{ \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->locale('tr')->monthName }}
                                    {{ $selectedYear }}
                                </div>
                                <button wire:click="changeMonth('next')"
                                    class="p-2 rounded-full bg-gray-100 hover:bg-gray-200">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>

                            <!-- Takvim Grid -->
                            <div class="grid grid-cols-7 gap-2 mb-6">
                                <!-- Gün başlıkları -->
                                @foreach (['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'] as $day)
                                    <div class="text-center font-bold text-gray-600 text-sm">{{ $day }}</div>
                                @endforeach

                                <!-- Takvim günleri -->
                                @php
                                    $firstDayOfMonth = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1);
                                    $lastDayOfMonth = $firstDayOfMonth->copy()->endOfMonth();
                                    $startDayOfWeek =
                                        $firstDayOfMonth->dayOfWeek === 0 ? 7 : $firstDayOfMonth->dayOfWeek;
                                    $daysInMonth = $firstDayOfMonth->daysInMonth;
                                @endphp

                                <!-- Boş günler (ay başlangıcından önce) -->
                                @for ($i = 1; $i < $startDayOfWeek; $i++)
                                    <div></div>
                                @endfor

                                <!-- Ayın günleri -->
                                @for ($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $currentDate = \Carbon\Carbon::createFromDate(
                                            $selectedYear,
                                            $selectedMonth,
                                            $day,
                                        );
                                        $dateString = $currentDate->format('Y-m-d');
                                        $isCompleted = in_array($dateString, $historicalDates);
                                        $isToday = $currentDate->isToday();
                                        $isFuture = $currentDate->isFuture();
                                    @endphp

                                    <div wire:click="selectDate('{{ $dateString }}')"
                                        class="aspect-square flex items-center justify-center rounded-lg cursor-pointer transition-all
                                        {{ $isCompleted ? 'bg-[#e63946] text-white hover:bg-[#d62836]' : '' }}
                                        {{ $isToday ? 'ring-2 ring-blue-500' : '' }}
                                        {{ $isFuture ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'hover:bg-gray-100' }}
                                        {{ !$isCompleted && !$isFuture ? 'bg-white text-gray-700' : '' }}
                                        {{ $selectedDate === $dateString ? 'ring-2 ring-purple-500' : '' }}">
                                        <span class="text-sm sm:text-base font-medium">{{ $day }}</span>
                                    </div>
                                @endfor
                            </div>

                            <!-- Seçili Günün Detayları -->
                            @if ($selectedDate)
                                <div class="border-t pt-6">
                                    <h3 class="text-xl font-bold text-[#1a2e5a] mb-4">
                                        {{ \Carbon\Carbon::parse($selectedDate)->locale('tr')->dayName }},
                                        {{ \Carbon\Carbon::parse($selectedDate)->format('d') }}
                                        {{ \Carbon\Carbon::parse($selectedDate)->locale('tr')->monthName }}
                                        {{ \Carbon\Carbon::parse($selectedDate)->year }}
                                    </h3>

                                    @if (count($selectedDateActivities) > 0)
                                        <div class="space-y-3">
                                            @foreach ($selectedDateActivities as $activity)
                                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0">
                                                            <i class="fas fa-book text-blue-500 text-xl"></i>
                                                        </div>
<div class="flex-1">
                                                           @if ($activity->content)
                                                               <p class="text-gray-700">{{ $activity->content }}</p>
                                                           @endif
                                                           @if ($activity->file_name)
                                                               <a href="{{ Storage::url($activity->file_path) }}"
                                                                   target="_blank"
                                                                   class="text-blue-600 hover:text-blue-800 text-sm mt-1 inline-flex items-center">
                                                                   <i class="fas fa-file mr-1"></i>
                                                                   {{ $activity->file_name }}
                                                               </a>
                                                           @endif
                                                           <p class="text-xs text-gray-500 mt-1">
                                                               {{ $activity->created_at->format('H:i') }}</p>
                                                       </div>
                                                   </div>
                                               </div>
                                           @endforeach
                                       </div>
                                   @else
                                       <p class="text-gray-500 text-center py-8">Bu tarihte çalışma kaydı bulunamadı.
                                       </p>
                                   @endif
                               </div>
                           @endif

                           <!-- İstatistikler -->
                           <div class="mt-6 bg-gray-50 rounded-lg p-4">
                               <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                                   <div>
                                       <div class="text-sm text-gray-600">Bu Ay Çalışılan Gün</div>
                                       <div class="text-2xl font-bold text-[#e63946]">{{ count($historicalDates) }}
                                       </div>
                                   </div>
                                   <div>
                                       <div class="text-sm text-gray-600">Toplam Çalışma</div>
                                       <div class="text-2xl font-bold text-[#1a2e5a]">{{ $daysCompleted }}</div>
                                   </div>
                                   <div>
                                       <div class="text-sm text-gray-600">En Uzun Seri</div>
                                       <div class="text-2xl font-bold text-green-600">{{ $longestStreak }} gün</div>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               @endif

               <!-- Çalışma Ekleme Formu -->
               @if ($showActivityForm && $isUserAuthenticated)
                   <div
                       class="mt-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 sm:p-8 border border-blue-200 shadow-lg">
                       <div class="flex items-center justify-between mb-6">
                           <div>
                               <h3 class="text-xl sm:text-2xl font-bold text-[#1a2e5a]">Bugünün Başarısını Kaydet! 🎯
                               </h3>
                               <p class="text-gray-600 text-sm mt-1">Her çalışma, seni bir adım daha yaklaştırıyor!
                               </p>
                           </div>
                           <div class="hidden sm:block">
                               <i class="fas fa-fire text-3xl text-orange-500 animate-pulse"></i>
                           </div>
                       </div>

            <form class="space-y-6" onsubmit="event.preventDefault();">
                           <div class="group">
                               <label for="content" class="block text-sm font-bold text-gray-700 mb-2">
                                   <i class="fas fa-pencil-alt mr-1 text-blue-500"></i>
                                   Bugün Ne Başardın?
                               </label>
                               <textarea wire:model="activityContent" id="content" rows="3"
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-all duration-200 hover:border-blue-400"
                                   placeholder="Örnek: 2 sayfa kelime çalıştım, Listening pratiği yaptım..."></textarea>
                               @error('activityContent')
                                   <span class="text-red-500 text-sm flex items-center mt-1"><i
                                           class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                               @enderror
                           </div>

                           <div class="group">
                               <label class="block text-sm font-bold text-gray-700 mb-3">
                                   <i class="fas fa-cloud-upload-alt mr-1 text-indigo-500"></i>
                                   Çalışmanı Paylaş (İsteğe Bağlı)
                               </label>

                               <!-- Drag & Drop Area -->
<div class="relative">
    <input type="file" wire:model="activityFiles" multiple id="file-upload"
        class="hidden"
        x-on:change="uploading = true">

    <label for="file-upload"
        x-data="{ uploading: false }"
        class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50/50 transition-all duration-300 group-hover:border-blue-400">

        <!-- Yükleniyor Göstergesi -->
        <div x-show="uploading" 
            class="absolute inset-0 bg-white bg-opacity-80 flex items-center justify-center rounded-xl z-10">
            <div class="flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mb-3"></div>
                <p class="text-blue-600 font-medium">Dosya Yükleniyor...</p>
            </div>
        </div>

        <div class="space-y-4">
            <!-- Upload Icon -->
            <div
                class="mx-auto w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-cloud-upload-alt text-2xl text-white"></i>
            </div>

            <!-- Text -->
            <div>
                <p class="text-lg font-bold text-gray-700">Dosyaları buraya sürükle</p>
                <p class="text-sm text-gray-500">veya buraya tıklayarak seç</p>
            </div>

            <!-- File Types -->
            <div class="flex justify-center gap-4 text-xs text-gray-400">
                <span><i class="fas fa-file-pdf text-red-500"></i> PDF</span>
                <span><i class="fas fa-file-image text-green-500"></i> Resim</span>
                <span><i class="fas fa-file-word text-blue-500"></i> Word</span>
            </div>
        </div>
    </label>
</div>
@error('activityFiles.*')
    <span class="text-red-500 text-sm flex items-center mt-2">
        <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
    </span>
@enderror
                           </div>
                           
                           <!-- Yüklenen Dosyalar -->
@if ($activityFiles)
    <div class="bg-white rounded-lg p-4 shadow-sm">
        <p class="text-sm font-bold text-gray-700 mb-3">
            <i class="fas fa-check-circle text-green-500 mr-1"></i>
            Yüklenen Dosyalar:
        </p>
        <div class="space-y-2">
            @foreach ($activityFiles as $file)
                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            @php
                                $extension = strtolower(
                                    $file->getClientOriginalExtension(),
                                );
                                $icon = 'fa-file';
                                $color = 'text-gray-500';

                                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $icon = 'fa-file-image';
                                    $color = 'text-green-500';
                                } elseif ($extension == 'pdf') {
                                    $icon = 'fa-file-pdf';
                                    $color = 'text-red-500';
                                } elseif (in_array($extension, ['doc', 'docx'])) {
                                    $icon = 'fa-file-word';
                                    $color = 'text-blue-500';
                                } elseif (in_array($extension, ['mp3', 'wav'])) {
                                    $icon = 'fa-file-audio';
                                    $color = 'text-purple-500';
                                }
                            @endphp
                            <i
                                class="fas {{ $icon }} {{ $color }} text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">
                                {{ $file->getClientOriginalName() }}</p>
                            <p class="text-xs text-gray-400">
                                {{ round($file->getSize() / 1024, 1) }} KB</p>
                        </div>
                    </div>
                    <div class="text-green-500">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-center text-blue-700">
                <i class="fas fa-info-circle text-blue-500 mr-2 text-lg"></i>
                <p class="text-sm font-medium">Dosyalarınız seçildi. Lütfen işlemi tamamlamak için aşağıdaki "Çalışmayı Kaydet" düğmesine basmayı unutmayın.</p>
            </div>
        </div>
    </div>
@endif
                           <!-- Motivasyon Mesajı -->
                           <div
                               class="bg-gradient-to-r from-orange-100 to-yellow-100 rounded-lg p-4 border border-orange-200">
                               <div class="flex items-center space-x-3">
                                   <i class="fas fa-rocket text-2xl text-orange-500"></i>
                                   <p class="text-sm font-medium text-orange-800">
                                       Harika gidiyorsun! Her gün kaydettiğin çalışmalar seni hedefe yaklaştırıyor. 🚀
                                   </p>
                               </div>
                           </div>

                           <!-- Butonlar -->
                           <div class="flex justify-end space-x-3 pt-4">
        <button type="button" wire:click="resetActivityForm"
            class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 flex items-center">
            <i class="fas fa-times mr-2"></i> İptal
        </button>
        <button type="button" wire:click="addActivity"
            class="px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 flex items-center transform hover:scale-105">
            <i class="fas fa-save mr-2"></i> Çalışmayı Kaydet
        </button>
                           </div>
                       </form>
                   </div>
               @endif

               <!-- Bugünün Çalışmaları -->
               @if (!empty($todayActivities) && $isUserAuthenticated)
                   <div class="mt-6 bg-white rounded-lg p-4 sm:p-6 border border-gray-200">
                       <h3 class="text-lg sm:text-xl font-bold text-[#1a2e5a] mb-4">Bugünün Çalışmaları</h3>

                       <div class="space-y-3">
                           @foreach ($todayActivities as $activity)
                               <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg" wire:key="activity-{{ $activity->id }}">
                                   <div class="flex-shrink-0">
                                       <i class="fas fa-book text-blue-500 text-xl"></i>
                                   </div>
                                   <div class="flex-1">
                                       @if ($activity->content)
                                           <p class="text-gray-700">{{ $activity->content }}</p>
                                       @endif
                                       @if ($activity->file_name)
                                           <a href="{{ Storage::url($activity->file_path) }}" target="_blank"
                                               class="text-blue-600 hover:text-blue-800 text-sm mt-1 inline-flex items-center">
                                               <i class="fas fa-file mr-1"></i> {{ $activity->file_name }}
                                           </a>
                                       @endif
                                       <p class="text-xs text-gray-500 mt-1">
                                           {{ $activity->created_at->format('H:i') }}</p>
                                   </div>
                                   <div class="flex-shrink-0">
                                       <button wire:click="confirmDeleteActivity({{ $activity->id }})"
                                           class="text-red-400 hover:text-red-600 transition-colors"
                                           title="Çalışmayı Sil">
                                           <i class="fas fa-trash-alt"></i>
                                       </button>
                                   </div>
                               </div>
                           @endforeach
                       </div>
                   </div>
               @endif
           </div>

           <!-- Seviye Atlama Modal -->
           @if ($showLevelUpModal)
               <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                   <div class="bg-white rounded-xl shadow-2xl p-4 sm:p-8 max-w-md w-full">
                       <div class="text-center mb-4">
                           <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full mx-auto mb-4 flex items-center justify-center"
                               style="background-color: {{ $levelColor }}">
                               <i class="fas fa-trophy text-3xl sm:text-4xl text-white"></i>
                           </div>
                           <h2 class="text-2xl sm:text-3xl font-bold" style="color: {{ $levelColor }}">Tebrikler!
                           </h2>
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
                               <div class="text-xl sm:text-2xl font-bold" style="color: {{ $levelColor }}">
                                   {{ $currentLevel }}</div>
                           </div>
                       </div>

                       <p class="text-gray-600 text-center mb-6 text-sm sm:text-base">Bu tempoda devam ederek bir
                           sonraki seviyeye ulaşmak için çalışın. Her gün bir adım daha!</p>

                       <div class="text-center">
                           <button wire:click="closeLevelUpModal"
                               class="bg-[#1a2e5a] hover:bg-[#132447] text-white font-bold py-2 sm:py-3 px-6 sm:px-8 rounded-lg shadow-lg transition duration-300 text-sm sm:text-base">
                               Harika!
                           </button>
                       </div>
                   </div>
               </div>
           @endif

           <!-- Seviye Açıklamaları -->
           <div class="p-4 sm:p-6 md:p-10 border-t border-gray-200">
               <h2 class="text-2xl sm:text-3xl font-bold text-[#1a2e5a] mb-4 sm:mb-6">Seviye Atlama Sistemi Nasıl
                   İşliyor?</h2>
               <p class="text-gray-700 mb-4 sm:mb-6 text-sm sm:text-base">
                   Öğrenciler her ay zinciri kırmadan çalışmaya devam ettikçe, başarılarını somutlaştıran bir seviye
                   kazanır.
                   Bu seviyeler, öğrencinin istikrarlı emeğini ve çabasını yansıtır.
               </p>

               <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                   <!-- Seviye Kartları -->
                   <div
                       class="relative bg-gradient-to-br from-amber-700 to-yellow-600 rounded-lg p-4 sm:p-6 text-white shadow-lg transform transition-transform hover:scale-105">
                       <div
                           class="absolute -top-3 -right-3 bg-white text-amber-700 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">
                           1</div>
                       <h3 class="text-lg sm:text-xl font-bold mb-2">Bronz</h3>
                       <p class="text-xs sm:text-sm opacity-90">Başlangıç seviyesi – Disiplin yolculuğunun ilk adımı.
                       </p>
                       <div class="mt-4 h-1 bg-white/30 rounded-full">
                           <div class="h-full bg-white rounded-full w-full"></div>
                       </div>
                   </div>

                   <div
                       class="relative bg-gradient-to-br from-gray-600 to-gray-500 rounded-lg p-4 sm:p-6 text-white shadow-lg transform transition-transform hover:scale-105">
                       <div
                           class="absolute -top-3 -right-3 bg-white text-gray-600 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">
                           2</div>
                       <h3 class="text-lg sm:text-xl font-bold mb-2">Demir</h3>
                       <p class="text-xs sm:text-sm opacity-90">Direncin sembolü – Devamlılığın güç kazanıyor.</p>
                       <div class="mt-4 h-1 bg-white/30 rounded-full">
                           <div class="h-full bg-white rounded-full w-4/5"></div>
                       </div>
                   </div>

                   <div
                       class="relative bg-gradient-to-br from-gray-300 to-gray-400 rounded-lg p-4 sm:p-6 text-gray-800 shadow-lg transform transition-transform hover:scale-105">
                       <div
                           class="absolute -top-3 -right-3 bg-white text-gray-400 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">
                           3</div>
                       <h3 class="text-lg sm:text-xl font-bold mb-2">Gümüş</h3>
                       <p class="text-xs sm:text-sm opacity-90">Kararlılığın meyvesi – İstikrar sağlanıyor.</p>
                       <div class="mt-4 h-1 bg-gray-800/30 rounded-full">
                           <div class="h-full bg-gray-800 rounded-full w-3/5"></div>
                       </div>
                   </div>

                   <div
                       class="relative bg-gradient-to-br from-yellow-500 to-yellow-300 rounded-lg p-4 sm:p-6 text-yellow-900 shadow-lg transform transition-transform hover:scale-105">
                       <div
                           class="absolute -top-3 -right-3 bg-white text-yellow-500 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">
                           4</div>
                       <h3 class="text-lg sm:text-xl font-bold mb-2">Altın</h3>
                       <p class="text-xs sm:text-sm opacity-90">Parlama zamanı – Öğrenme süreci artık daha bilinçli.
                       </p>
                       <div class="mt-4 h-1 bg-yellow-900/30 rounded-full">
                           <div class="h-full bg-yellow-900 rounded-full w-2/5"></div>
                       </div>
                   </div>

                   <div
                       class="relative bg-gradient-to-br from-gray-200 to-gray-100 rounded-lg p-4 sm:p-6 text-gray-800 shadow-lg transform transition-transform hover:scale-105">
                       <div
                           class="absolute -top-3 -right-3 bg-white text-gray-500 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">
                           5</div>
                       <h3 class="text-lg sm:text-xl font-bold mb-2">Platin</h3>
                       <p class="text-xs sm:text-sm opacity-90">Yüksek başarı – Kendini aşma süreci hızlanıyor.</p>
                       <div class="mt-4 h-1 bg-gray-800/30 rounded-full">
                           <div class="h-full bg-gray-800 rounded-full w-1/5"></div>
                       </div>
                   </div>

                   <div
                       class="relative bg-gradient-to-br from-emerald-600 to-emerald-400 rounded-lg p-4 sm:p-6 text-white shadow-lg transform transition-transform hover:scale-105">
                       <div
                           class="absolute -top-3 -right-3 bg-white text-emerald-600 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">
                           6</div>
                       <h3 class="text-lg sm:text-xl font-bold mb-2">Zümrüt</h3>
                       <p class="text-xs sm:text-sm opacity-90">Örnek birey – Disiplinin çevrene ilham veriyor.</p>
                       <div class="mt-4 h-1 bg-white/30 rounded-full">
                           <div class="h-full bg-white rounded-full w-2/12"></div>
                       </div>
                   </div>

                   <div
                       class="relative bg-gradient-to-br from-blue-600 to-cyan-400 rounded-lg p-4 sm:p-6 text-white shadow-lg transform transition-transform hover:scale-105">
                       <div
                           class="absolute -top-3 -right-3 bg-white text-blue-600 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">
                           7</div>
                       <h3 class="text-lg sm:text-xl font-bold mb-2">Elmas</h3>
                       <p class="text-xs sm:text-sm opacity-90">Mükemmelliğe yakınlık – Artık büyük bir hedefin var.
                       </p>
                       <div class="mt-4 h-1 bg-white/30 rounded-full">
                           <div class="h-full bg-white rounded-full w-1/12"></div>
                       </div>
                   </div>

                   <div
                       class="relative bg-gradient-to-br from-purple-700 to-pink-500 rounded-lg p-4 sm:p-6 text-white shadow-lg transform transition-transform hover:scale-105">
                       <div
                           class="absolute -top-3 -right-3 bg-white text-purple-700 rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold shadow-md text-sm sm:text-base">
                           8</div>
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
                   <p>"Zinciri Kırma", küçük ama istikrarlı adımlarla büyük hedeflere ulaşmayı esas alan bir yöntemdir.
                       Bu zincir, her gün bir önceki günün üzerine eklenerek büyür.</p>
                   <p class="font-bold">Amaç? Hiçbir günü boş geçirmemek, zinciri asla kırmamak.</p>

                   <h3 class="text-xl sm:text-2xl font-bold text-[#1a2e5a] mt-6 sm:mt-8 mb-3 sm:mb-4">Sistemin
                       Faydaları</h3>
                   <ul class="text-sm sm:text-base">
                       <li>Öğrencilerde <strong>sorumluluk bilinci</strong> oluşturur.</li>
                       <li><strong>Düzenli çalışma alışkanlığı</strong> kazandırır.</li>
                       <li>Görsel takip sayesinde <strong>motive edici bir süreç</strong> sunar.</li>
                       <li>Seviyeler sayesinde öğrenciler <strong>hedef odaklı</strong> çalışır.</li>
                       <li>Öğrenci, gelişimini <strong>somut ve adım adım</strong> izleyebilir.</li>
                   </ul>

                   <h3 class="text-xl sm:text-2xl font-bold text-[#1a2e5a] mt-6 sm:mt-8 mb-3 sm:mb-4">Sonuç Olarak
                   </h3>
                   <p>"Zinciri Kırma – Seviye Atlama" sistemi, öğrencilerin akademik gelişimlerini desteklerken aynı
                       zamanda yaşam boyu sürecek bir disiplin anlayışı kazandırmayı amaçlamaktadır. Her ✔️ işareti,
                       öğrencinin kendine olan bağlılığını ve hedeflerine olan inancını temsil eder.</p>

                   <div class="bg-[#1a2e5a] text-white p-4 sm:p-6 rounded-lg mt-6 sm:mt-8">
                       <p class="text-base sm:text-xl font-bold">Bugün bir adım at. Zinciri başlat. Seviyeni yükselt.
                           Ve unutma: Zinciri Kırma, Geleceğini İnşa Et.</p>
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
   document.addEventListener('DOMContentLoaded', function() {
       // Event Listener'lar
       window.addEventListener('show-success', event => {
           showConfetti();
           if (event.detail?.message) {
               showToast('success', event.detail.message);
           }
       });

       window.addEventListener('show-error', event => {
           if (event.detail?.message) {
               showToast('error', event.detail.message);
           }
       });

       window.addEventListener('show-info', event => {
           if (event.detail?.message) {
               showToast('info', event.detail.message);
           }
       });

       window.addEventListener('level-up-animation', event => {
           showLevelUpConfetti();
       });

       window.addEventListener('chain-break-animation', event => {
           showBreakConfetti();
       });
       window.addEventListener('day-completed-animation', event => {
           showDayCompletedConfetti();
       });

       window.addEventListener('confirm-reset', event => {
           if (confirm('Zinciri sıfırlamak istediğinize emin misiniz? Bu işlem geri alınamaz!')) {
               @this.dispatch('confirmReset'); // Livewire 3 için emit yerine dispatch kullanıyoruz
           }
       });

       // Yeni: Çalışma silme onayı
       window.addEventListener('confirm-delete-activity', event => {
           if (confirm('Bu çalışmayı silmek istediğinize emin misiniz?')) {
               @this.deleteActivity(event.detail.activityId);
           }
       });

       // Konfeti Fonksiyonları
       function showConfetti() {
           confetti({
               particleCount: 100,
               spread: 70,
               origin: {
                   y: 0.6
               },
               colors: ['#e63946', '#1a2e5a', '#FFD700']
           });
       }

       function showLevelUpConfetti() {
           confetti({
               particleCount: 200,
               spread: 100,
               origin: {
                   y: 0.4
               },
               colors: ['#e63946', '#1a2e5a', '#FFD700']
           });
       }

       function showBreakConfetti() {
           confetti({
               particleCount: 80,
               spread: 100,
               origin: {
                   y: 0.4
               },
               gravity: 1.5,
               colors: ['#e63946']
           });
       }

       function showDayCompletedConfetti() {
           confetti({
               particleCount: 50,
               spread: 50,
               origin: {
                   y: 0.6
               },
               colors: ['#e63946', '#1a2e5a']
           });
       }

       // Toast Mesajı Fonksiyonu
       function showToast(type, message) {
           const toastConfig = {
               success: {
                   bgColor: 'bg-green-500',
                   icon: '<i class="fas fa-check-circle mr-2"></i>'
               },
               error: {
                   bgColor: 'bg-red-500',
                   icon: '<i class="fas fa-exclamation-circle mr-2"></i>'
               },
               info: {
                   bgColor: 'bg-blue-500',
                   icon: '<i class="fas fa-info-circle mr-2"></i>'
               }
           };

           const config = toastConfig[type] || toastConfig.info;
           const toast = document.createElement('div');

           // Responsive pozisyonlama
           const isMobile = window.innerWidth < 640;
           const baseClasses =
               `${config.bgColor} text-white p-3 rounded-lg shadow-lg flex items-center fixed z-50 transition-transform duration-300`;
           const positionClasses = isMobile ?
               'left-1/2 bottom-4 transform -translate-x-1/2 translate-y-full text-xs max-w-xs' :
               'right-4 top-4 transform translate-x-full text-sm max-w-md';

           toast.className = `${baseClasses} ${positionClasses}`;
           toast.innerHTML = `<div class="flex items-center">${config.icon}${message}</div>`;

           document.body.appendChild(toast);

           // Animasyon
           setTimeout(() => {
               toast.classList.remove(isMobile ? 'translate-y-full' : 'translate-x-full');
           }, 100);

           // Kaldırma
           setTimeout(() => {
               toast.classList.add(isMobile ? 'translate-y-full' : 'translate-x-full');
               setTimeout(() => {
                   if (document.body.contains(toast)) {
                       document.body.removeChild(toast);
                   }
               }, 300);
           }, 4000);
       }
   });
</script>