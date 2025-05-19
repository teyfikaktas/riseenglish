<!-- resources/views/welcome.blade.php -->
@extends('layouts.app')

@section('content')
    {{-- <form action="{{ route('send-otp') }}" method="POST">
    @csrf
   <input type="hidden" name="no" value="5541383539">
   <input type="hidden" name="message" value="Alooooo">
   <button type="submit" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-4 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
       <i class="fas fa-sms mr-2"></i>SMS Gönder
   </button>
</form> --}}
    <!-- Başarı mesajı için ekleme yapıyoruz -->

    @if (session('success'))
        <div id="successMessage"
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded fixed top-4 right-4 shadow-lg z-50 transform transition-transform duration-300 ease-in-out">
            <div class="flex items-center">
                <div class="py-1">
                    <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>{{ session('success') }}</div>
                <button onclick="closeSuccessMessage()" class="ml-4 text-green-700 hover:text-green-900 focus:outline-none">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Progress bar for auto-dismiss -->
            <div id="successMessageProgress" class="h-1 bg-green-500 mt-2 w-full transform origin-left"></div>
        </div>
    @endif
    <div class="relative bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] py-20 overflow-hidden">
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
        <!-- Öğrenci Videoları Slider Bölümü - Komple Düzeltilmiş Versiyon -->

        <!-- İçerik kısmı -->
        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center md:space-x-12"> <!-- space-x eklendi -->
                <!-- Sol taraf (metin ve video) -->
                <div class="w-full md:w-1/2 text-center md:text-left mb-12 md:mb-0">
                    <!-- Metin Bölümü - Üstte -->
                    @if (auth()->check() && auth()->user()->hasRole('ogrenci'))
                        <div class="mb-6"> <!-- mb-4 -> mb-6 artırıldı -->
                            <span
                                class="bg-[#e63946] text-white text-xl px-4 py-2 rounded-lg shadow-lg inline-block transform -rotate-2 hover:rotate-0 transition-transform duration-300 font-bold">
                                <i class="fas fa-graduation-cap mr-2"></i>KURSLARINIZ
                            </span>
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                            Öğrenmeye <span class="text-[#e63946]">Devam</span> Edin!
                        </h1>
                        <p class="text-xl text-white mb-8 max-w-lg mx-auto md:mx-0">
                            Eğitim yolculuğunuzda size yardımcı olmak için buradayız. Kurslarınıza hemen erişin.
                        </p>
                    @else
                        <!-- GİRİŞ YAPMAYAN KULLANICI İÇİN STANDART MESAJ -->
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                            Rise English ile <span class="text-[#e63946]">Öğrenmeye</span> Başlayın
                        </h1>
                        <p class="text-xl text-white mb-8 max-w-lg mx-auto md:mx-0">
                            Eğitim platformumuzda profesyonel eğitmenlerle yeni beceriler kazanın ve kariyerinizde bir adım
                            öne çıkın.
                        </p>
                    @endif

                    <!-- Video Bölümü - Ortada -->
                    <div class="relative rounded-xl overflow-hidden shadow-xl mb-8 mt-8"> <!-- mt-8 eklendi -->
                        <div class="relative pb-[56.25%]"> <!-- 16:9 aspect ratio -->
                            <div class="video-thumbnail absolute inset-0" data-video-id="VRqM2zyqJeI">
                                <!-- Video Thumbnail -->
                                <img src="https://i.ytimg.com/vi/VRqM2zyqJeI/hqdefault.jpg" alt="Rise English Tanıtım"
                                    class="w-full h-full object-cover">

                                <!-- Play Butonu -->
                                <div
                                    class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-20 h-20 rounded-full bg-[#e63946] flex items-center justify-center z-10 shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>

                                <!-- Koyu Overlay - Kontrastı Artırmak İçin -->
                                <div class="absolute inset-0  bg-opacity-30"></div>
                            </div>
                            <div class="video-iframe-container absolute inset-0 hidden"></div>
                        </div>

                        <!-- Video Üzerindeki Etiket/Badge -->
                        <div class="absolute top-4 right-4">
                            <div class="bg-[#e63946] text-white text-sm font-bold py-1 px-3 rounded-full shadow-lg">
                                <i class="fas fa-play-circle mr-1"></i> Tanıtım Videosu
                            </div>
                        </div>
                    </div>

                    <!-- Buton Bölümü - En Altta -->
                    <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-4 mt-8"> <!-- mt-8 eklendi -->
                        @if (auth()->check() && auth()->user()->hasRole('ogrenci'))
                            <!-- Giriş yapmış öğrenci için kurslarım butonu -->
                            <a href="{{ url('/ogrenci/kurslarim') }}"
                                class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-4 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                                <i class="fas fa-book-reader mr-2"></i>Kurslarıma Git
                            </a>
                            <a href="{{ url('/egitimler') }}"
                                class="bg-white hover:bg-gray-100 text-[#1a2e5a] font-bold py-4 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                                <i class="fas fa-book-open mr-2"></i>Yeni Eğitimler
                            </a>
                        @else
                            <!-- Giriş yapmamış kullanıcı için standart butonlar -->
                            <a href="{{ url('/egitimler') }}"
                                class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-4 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                                Eğitimleri Keşfet
                            </a>
                            <a href="{{ url('/kayit-ol') }}"
                                class="bg-white hover:bg-gray-100 text-[#1a2e5a] font-bold py-4 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                                Hemen Başla
                            </a>
                        @endif
                    </div>

                    <!-- Giriş yapmamış kullanıcılar için indirim banner'ı -->
                    @if (!auth()->check() || !auth()->user()->hasRole('ogrenci'))
                        <div
                            class="mt-6 bg-gradient-to-r from-[#e63946] to-[#d62836] rounded-lg p-3 shadow-lg transform -rotate-1 hover:rotate-0 transition-transform duration-300 mx-auto sm:mx-0 max-w-xs">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-white font-bold text-lg">%15 İNDİRİM</div>
                                    <div class="text-xs text-white opacity-90">Tüm eğitimlerde geçerli</div>
                                </div>
                                <div class="bg-white text-[#e63946] text-xs font-bold py-1 px-3 rounded-full shadow">
                                    RiseEnglish
                                </div>
                            </div>
                            <div class="w-full h-1 bg-white bg-opacity-30 mt-2 rounded-full overflow-hidden">
                                <div class="w-1/2 h-full bg-white rounded-full animate-pulse"></div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sağ taraf (görsel) - İdiom bölümü (HER KULLANICI İÇİN) -->
                <div class="w-full md:w-1/2">
                    <div class="relative">
                        <!-- Tüm kullanıcılar için İdiom görsel -->
                        <div class="w-full bg-white rounded-lg shadow-xl overflow-hidden border border-gray-200">
                            <!-- Üst başlık - Genel site tasarımına uygun -->
                            <div class="p-4 bg-[#1a2e5a] text-center relative">
                                <h2 class="text-2xl font-bold text-white">IDIOM OF THE DAY</h2>

                                <div class="absolute -right-2 top-2 transform rotate-12">
                                    <div class="bg-[#e63946] text-white text-xs font-bold py-1 px-3 rounded-full shadow-lg">
                                        RiseEnglish
                                    </div>
                                </div>
                            </div>

                            <!-- İdiom Gösterim Alanı -->
                            <div class="p-6 bg-gray-50">
                                @if (isset($dailyIdiom))
                                    <!-- İngilizce İdiom -->
                                    <div class="bg-white rounded-lg p-4 mb-4 shadow-md border-l-4 border-[#e63946]">
                                        <div class="text-xl font-bold text-[#1a2e5a] mb-1">
                                            "{{ $dailyIdiom->english_phrase }}"</div>
                                        <div class="text-md text-gray-500 italic">{{ $dailyIdiom->turkish_translation }}
                                        </div>
                                    </div>

                                    <!-- Örnek Cümleler -->
                                    <div class="bg-white rounded-lg p-4 shadow-md border-l-4 border-[#1a2e5a]">
                                        <div class="text-lg font-bold text-[#1a2e5a]">Örnek Cümleler:</div>
                                        <div class="text-md text-gray-600 mt-2">- {{ $dailyIdiom->example_sentence_1 }}
                                        </div>
                                        @if ($dailyIdiom->example_sentence_2)
                                            <div class="text-md text-gray-600">- {{ $dailyIdiom->example_sentence_2 }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Karakter Görseli - Ortalanmış -->
                                    <div class="relative mt-6 flex justify-center">
                                        @if ($dailyIdiom->image_path)
                                            <img src="{{ asset('storage/' . $dailyIdiom->image_path) }}"
                                                alt="İdiom Görseli" class="h-80 object-contain z-10">
                                        @else
                                            <img src="{{ asset('images/1.jpg') }}" alt="Varsayılan İdiom Görseli"
                                                class="h-80 object-contain z-10">
                                        @endif
                                        <div class="absolute top-0 right-10 animate-bounce z-20">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#e63946]"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                            </svg>
                                        </div>
                                    </div>
                                @else
                                    <!-- Veri yoksa gösterilecek alan -->
                                    <div class="bg-white rounded-lg p-4 shadow-md text-center">
                                        <div class="text-lg text-gray-500 italic">Bugün için deyim bulunamadı.</div>
                                    </div>
                                @endif
                            </div>

                            <!-- Alt Banner -->
                            <div class="py-3 px-4 bg-gray-100 text-center relative border-t border-gray-200">
                                <span class="inline-block text-[#1a2e5a] font-medium">
                                    Günlük İngilizce Deyimi
                                </span>
                            </div>
                        </div>

                        <!-- Kullanıcı türüne göre farklı bilgi kutuları -->
                        @if (auth()->check() && auth()->user()->hasRole('ogrenci'))
                            <!-- Aktif kurs sayısı kutusu - Giriş yapmış öğrenci için -->
                            <div
                                class="absolute -top-4 -left-4 bg-[#1a2e5a] text-white rounded-lg p-3 shadow-lg transform rotate-3 hover:rotate-0 transition-transform duration-300">
                                <div class="flex items-center">
                                    <i class="fas fa-book-open mr-2"></i>
                                    <div>
                                        <div class="text-lg font-bold">Aktif Kurslar</div>
                                        <div class="text-2xl font-extrabold">
                                            {{ auth()->user()->enrolledCourses()->where('is_active', true)->count() }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kişiselleştirilmiş animasyonlu vurgu kutusu - Giriş yapmış öğrenci için -->
                            <div class="absolute -bottom-4 -right-4 bg-white rounded-lg p-4 shadow-lg">
                                <div class="flex items-center">
                                    <div class="bg-[#e63946] rounded-full h-4 w-4 mr-2 animate-pulse"></div>
                                    <span class="font-bold text-[#1a2e5a]">Eğitiminize Devam Edin!</span>
                                </div>
                            </div>
                        @else
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="relative bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] py-16 overflow-hidden">
            <!-- Dekoratif arka plan desenleri -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <defs>
                        <pattern id="video-grid" width="10" height="10" patternUnits="userSpaceOnUse">
                            <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
                        </pattern>
                    </defs>
                    <rect width="100" height="100" fill="url(#video-grid)" />
                </svg>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                <!-- Başlık Bölümü - Orijinal hali korundu -->
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-white mb-2">Öğrencilerimiz Ne Dedi?</h2>
                    <div class="w-20 h-1 bg-[#e63946] mx-auto"></div>
                    <p class="mt-4 text-blue-100 max-w-2xl mx-auto">Başarı hikayelerini öğrencilerimizden dinleyin.</p>
                </div>

                <!-- Düzeltilmiş Video Slider HTML -->
                <div class="student-videos-slider relative">
                    <!-- Slider Navigation Controls -->
                    <div class="slider-controls">
                        <button id="prevVideo"
                            class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-5 bg-white p-3 rounded-full shadow-lg z-10 text-[#1a2e5a]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button id="nextVideo"
                            class="absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-5 bg-white p-3 rounded-full shadow-lg z-10 text-[#1a2e5a]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                    <!-- Video Slides Wrapper -->
                    <div class="video-slides-container overflow-hidden">
                        <div id="videoSlidesWrapper" class="flex transition-transform duration-500 ease-in-out">

                            <!-- Video 1 - Düzeltilmiş Thumbnail Yapısı -->
                            <div class="video-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                    <div class="relative pb-[56.25%]"> <!-- 16:9 aspect ratio -->
                                        <div class="video-thumbnail absolute inset-0" data-video-id="Kw0ezq06ruU">
                                            <!-- Sadece thumbnail resmi -->
                                            <img src="https://i.ytimg.com/vi/Kw0ezq06ruU/hqdefault.jpg"
                                                alt="Video thumbnail" class="w-full h-full object-cover">

                                            <!-- Play butonu üstte -->
                                            <div
                                                class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-[#e63946] flex items-center justify-center z-10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="video-iframe-container absolute inset-0 hidden"></div>
                                    </div>
                                    <div class="p-4 text-center bg-[#1a2e5a] text-white">
                                        <h3 class="font-semibold">EREĞLİ YÖK DİL BİRİNCİMİZ</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Video 2 - Düzeltilmiş Thumbnail Yapısı -->
                            <div class="video-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                    <div class="relative pb-[56.25%]"> <!-- 16:9 aspect ratio -->
                                        <div class="video-thumbnail absolute inset-0" data-video-id="WMfARGd1fkQ">
                                            <!-- Sadece thumbnail resmi -->
                                            <img src="https://i.ytimg.com/vi/WMfARGd1fkQ/hqdefault.jpg"
                                                alt="Video thumbnail" class="w-full h-full object-cover">

                                            <!-- Play butonu üstte -->
                                            <div
                                                class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-[#e63946] flex items-center justify-center z-10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="video-iframe-container absolute inset-0 hidden"></div>
                                    </div>
                                    <div class="p-4 text-center bg-[#1a2e5a] text-white">
                                        <h3 class="font-semibold">Öğrencilerimiz</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Video 3 - Düzeltilmiş Thumbnail Yapısı -->
                            <div class="video-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                    <div class="relative pb-[56.25%]"> <!-- 16:9 aspect ratio -->
                                        <div class="video-thumbnail absolute inset-0" data-video-id="cVPIqxeLPWI">
                                            <!-- Sadece thumbnail resmi -->
                                            <img src="https://i.ytimg.com/vi/cVPIqxeLPWI/hqdefault.jpg"
                                                alt="Video thumbnail" class="w-full h-full object-cover">

                                            <!-- Play butonu üstte -->
                                            <div
                                                class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-[#e63946] flex items-center justify-center z-10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="video-iframe-container absolute inset-0 hidden"></div>
                                    </div>
                                    <div class="p-4 text-center bg-[#1a2e5a] text-white">
                                        <h3 class="font-semibold">Öğrencilerimiz</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Video 4 - Düzeltilmiş Thumbnail Yapısı -->
                            <div class="video-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                    <div class="relative pb-[56.25%]"> <!-- 16:9 aspect ratio -->
                                        <div class="video-thumbnail absolute inset-0" data-video-id="js-iBirRIJU">
                                            <!-- Sadece thumbnail resmi -->
                                            <img src="https://i.ytimg.com/vi/js-iBirRIJU/hqdefault.jpg"
                                                alt="Video thumbnail" class="w-full h-full object-cover">

                                            <!-- Play butonu üstte -->
                                            <div
                                                class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-[#e63946] flex items-center justify-center z-10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="video-iframe-container absolute inset-0 hidden"></div>
                                    </div>
                                    <div class="p-4 text-center bg-[#1a2e5a] text-white">
                                        <h3 class="font-semibold">Öğrencilerimiz</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Video 5 - Düzeltilmiş Thumbnail Yapısı -->
                            <div class="video-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                    <div class="relative pb-[56.25%]"> <!-- 16:9 aspect ratio -->
                                        <div class="video-thumbnail absolute inset-0" data-video-id="GBxGfpVM5E8">
                                            <!-- Sadece thumbnail resmi -->
                                            <img src="https://i.ytimg.com/vi/GBxGfpVM5E8/hqdefault.jpg"
                                                alt="Video thumbnail" class="w-full h-full object-cover">

                                            <!-- Play butonu üstte -->
                                            <div
                                                class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-[#e63946] flex items-center justify-center z-10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="video-iframe-container absolute inset-0 hidden"></div>
                                    </div>
                                    <div class="p-4 text-center bg-[#1a2e5a] text-white">
                                        <h3 class="font-semibold">Öğrencilerimiz</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Video 6 - Düzeltilmiş Thumbnail Yapısı -->
                            <div class="video-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                    <div class="relative pb-[56.25%]"> <!-- 16:9 aspect ratio -->
                                        <div class="video-thumbnail absolute inset-0" data-video-id="cVPIqxeLPWI">
                                            <!-- Sadece thumbnail resmi -->
                                            <img src="https://i.ytimg.com/vi/cVPIqxeLPWI/hqdefault.jpg"
                                                alt="Video thumbnail" class="w-full h-full object-cover">

                                            <!-- Play butonu üstte -->
                                            <div
                                                class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-[#e63946] flex items-center justify-center z-10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="video-iframe-container absolute inset-0 hidden"></div>
                                    </div>
                                    <div class="p-4 text-center bg-[#1a2e5a] text-white">
                                        <h3 class="font-semibold">Öğrencilerimiz</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dots Navigation (Mobil için) -->
                    <div class="flex justify-center mt-6">
                        <div id="videoSliderDots" class="flex space-x-2">
                            <!-- Dots will be added with JS -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="bg-white py-16">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <!-- Teacher image section (takes up the entire left side) -->
                <div class="w-full md:w-2/5 flex justify-center mb-8 md:mb-0">
                    <img src="{{ asset('images/teacherwelcome.jpg') }}" alt="English Teacher"
                        class="rounded-lg shadow-lg w-full h-auto object-cover">
                </div>

                <!-- Founder's message area -->
                <div class="w-full md:w-3/5">
                    <h2 class="text-3xl font-bold text-[#1a2e5a] mb-6">Welcome to Rise English!</h2>
                    <div class="mb-8 text-gray-700">
                        <p class="mb-4">As the founder of Rise English, I am proud to present a platform designed not
                            just to teach English, but to inspire confidence, growth, and real communication skills. Our
                            mission is simple: to help every learner rise to their full potential through quality,
                            personalized, and motivating English education.</p>
                        <p class="mb-4">At Rise English, we believe language learning should be engaging, practical, and
                            goal-oriented. Whether you're preparing for an exam, improving your speaking, or starting from
                            scratch — we are here to guide you every step of the way.</p>
                        <p class="mb-4">This journey started with a passion for education and a belief that with the
                            right support, anyone can master English. I'm excited to see how far we can go — together.</p>
                        <p class="mb-2 font-semibold">Let's rise, learn, and grow</p>
                        <p class="font-bold text-[#e63946]">Hakan Ekinci</p>
                    </div>

                    <div class="pt-6 border-t border-gray-200">
                        <h2 class="text-3xl font-bold text-[#1a2e5a] mb-6">Rise English'e Hoş Geldiniz!</h2>
                        <div class="text-gray-700">
                            <p class="mb-4">Rise English'in kurucusu olarak sizlere sadece bir dil kursu değil, aynı
                                zamanda özgüven kazandıran, gelişimi destekleyen ve gerçek iletişim becerileri kazandıran
                                bir öğrenme ortamı sunmaktan gurur duyuyorum. Amacımız basit: Her öğrencinin kendi
                                potansiyelini keşfetmesine yardımcı olmak ve onu en iyi şekilde ortaya çıkarmak.</p>
                            <p class="mb-4">Rise English'te dil öğrenmenin ilham verici, pratik ve hedef odaklı olması
                                gerektiğine inanıyoruz. İster sınava hazırlanıyor olun, ister konuşma becerilerinizi
                                geliştirmek ya da sıfırdan başlamak istiyor olun — bu yolculukta her adımda yanınızdayız.
                            </p>
                            <p class="mb-4">Bu platform, eğitime duyduğum tutku ve doğru destekle herkesin İngilizceyi
                                öğrenebileceğine olan inancımla doğdu. Şimdi birlikte ne kadar yol kat edebileceğimizi
                                görmek için sabırsızlanıyorum.</p>
                            <p class="font-bold text-[#e63946]">Hakan Ekinci</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto px-4 py-16 bg-gray-50">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-[#1a2e5a] mb-2">Öne Çıkan Eğitimler</h2>
            <div class="w-20 h-1 bg-[#e63946] mx-auto"></div>
            <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Profesyonel eğitmenlerimiz tarafından hazırlanan kaliteli ve
                güncel içeriklerle kariyer hedeflerinize bir adım daha yaklaşın.</p>
        </div>

        <!-- Slider Ana Container -->
        <div class="relative">
            <!-- Slider Controls - Mobilde Gizli (md boyutundan itibaren göster) -->
            <div class="hidden md:block">
                <button id="prevButton"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-5 bg-white p-3 rounded-full shadow-lg z-10 text-[#1a2e5a]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button id="nextButton"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-5 bg-white p-3 rounded-full shadow-lg z-10 text-[#1a2e5a]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Slider/Carousel -->
            <div class="slider-container overflow-hidden">
                <div id="slidesWrapper" class="flex transition-transform duration-500 ease-in-out">
                    @forelse($featuredCourses as $course)
                        <!-- Eğitim Kartı - Slider Item -->
                        <div class="slider-item flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                            <div
                                class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-2 group h-full">
                                <div class="h-48 bg-gray-200 relative overflow-hidden">
                                    @if ($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                            alt="{{ $course->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="flex items-center justify-center h-full bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                    @endif
                                    @if ($course->discount_price)
                                        <div
                                            class="absolute top-2 right-2 bg-[#e63946] text-white px-3 py-1 rounded-full font-bold text-sm">
                                            %{{ number_format((($course->price - $course->discount_price) / $course->price) * 100) }}
                                            İNDİRİM
                                        </div>
                                    @endif

                                    <!-- Başlangıç durumu etiketi -->
                                    @php
                                        $today = \Carbon\Carbon::today();
                                        $startDate = \Carbon\Carbon::parse($course->start_date);
                                        $endDate = \Carbon\Carbon::parse($course->end_date);
                                        $daysLeft = $today->diffInDays($startDate, false);
                                    @endphp

                                    @if ($startDate->isPast() && $endDate->isFuture())
                                        <div
                                            class="absolute top-2 left-2 bg-[#44bd32] text-white text-xs font-bold px-2 py-1 rounded-full">
                                            DEVAM EDİYOR
                                        </div>
                                    @elseif($startDate->isPast() && $endDate->isPast())
                                        <div
                                            class="absolute top-2 left-2 bg-[#718093] text-white text-xs font-bold px-2 py-1 rounded-full">
                                            TAMAMLANDI
                                        </div>
                                    @elseif($daysLeft <= 7 && $daysLeft > 0)
                                        <div
                                            class="absolute top-2 left-2 bg-[#e1b12c] text-white text-xs font-bold px-2 py-1 rounded-full">
                                            {{ $daysLeft }} GÜN KALDI
                                        </div>
                                    @elseif($daysLeft == 0)
                                        <div
                                            class="absolute top-2 left-2 bg-[#c23616] text-white text-xs font-bold px-2 py-1 rounded-full">
                                            BUGÜN BAŞLIYOR
                                        </div>
                                    @endif

                                    <!-- Kurs tipi ve seviye etiketi -->
                                    <div class="absolute bottom-2 left-2 flex space-x-2">
                                        @if ($course->courseType)
                                            <span
                                                class="bg-[#1a2e5a] text-white text-xs font-bold px-2 py-1 rounded">{{ $course->courseType->name }}</span>
                                        @endif
                                        @if ($course->courseLevel)
                                            <span
                                                class="bg-[#e63946] text-white text-xs font-bold px-2 py-1 rounded">{{ $course->courseLevel->name }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="p-6">
                                    <h3 class="text-xl font-semibold mb-2 text-[#1a2e5a]">{{ $course->name }}</h3>
                                    <p class="text-gray-600 mb-4 text-sm h-12 overflow-hidden">
                                        {{ Str::limit($course->description, 100) }}</p>

                                    <!-- Eğitim Tarihleri Bölümü -->
                                    <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex items-center mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="font-medium text-[#1a2e5a]">Eğitim Tarihleri</span>
                                        </div>

                                        @if ($course->start_date && $course->end_date)
                                            <div class="grid grid-cols-2 gap-2 text-sm">
                                                <div>
                                                    <span class="text-gray-500">Başlangıç:</span>
                                                    <span
                                                        class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500">Bitiş:</span>
                                                    <span
                                                        class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($course->end_date)->format('d.m.Y') }}</span>
                                                </div>
                                            </div>

                                            @php
                                                $totalDuration = $startDate->diffInDays($endDate);
                                            @endphp

                                            <div class="mt-2">
                                                @if ($startDate->isPast() && $endDate->isFuture())
                                                    <!-- Kurs devam ediyor -->
                                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                        @php
                                                            $elapsed = $today->diffInDays($startDate);
                                                            $progress = ($elapsed / $totalDuration) * 100;
                                                            $progress = min(100, max(0, $progress));
                                                        @endphp
                                                        <div class="bg-[#44bd32] h-2 rounded-full"
                                                            style="width: {{ $progress }}%"></div>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        <span class="font-medium">Eğitim devam ediyor</span>
                                                    </p>
                                                @elseif($startDate->isFuture())
                                                    <!-- Kurs başlamadı -->
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        @if ($daysLeft == 0)
                                                            <span class="font-medium text-[#e63946]">Bugün başlıyor!</span>
                                                        @elseif($daysLeft == 1)
                                                            <span class="font-medium text-[#e63946]">Yarın başlıyor!</span>
                                                        @else
                                                            <span class="font-medium text-[#1a2e5a]">{{ $daysLeft }}
                                                                gün</span> sonra başlayacak
                                                        @endif
                                                    </p>
                                                @else
                                                    <!-- Kurs tamamlandı -->
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        <span class="font-medium">Eğitim tamamlandı</span>
                                                    </p>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-xs text-gray-500">Tarih bilgisi bulunmamaktadır.</p>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap items-center text-sm text-gray-500 mb-4 gap-3">
                                        <!-- Öğretmen bilgisi -->
                                        @if ($course->teacher)
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                {{ $course->teacher->name }}
                                            </div>
                                        @endif

                                        <!-- Toplam saat -->
                                        @if ($course->total_hours)
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $course->total_hours }} Saat
                                            </div>
                                        @endif

                                        <!-- Kurs sıklığı -->
                                        @if ($course->courseFrequency)
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $course->courseFrequency->name }}
                                            </div>
                                        @endif

                                        <!-- Sertifika bilgisi -->
                                        @if ($course->has_certificate)
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                                Sertifikalı
                                            </div>
                                        @endif

                                        <!-- Kontenjan bilgisi -->
                                        @if ($course->max_students)
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                {{ $course->max_students }} Kişi
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex justify-between items-center">
                                        {{-- <div>
                                  @if ($course->discount_price)
                                      <span class="text-gray-500 line-through text-sm">{{ number_format($course->price, 2) }} ₺</span>
                                      <span class="text-[#e63946] font-bold ml-2">{{ number_format($course->discount_price, 2) }} ₺</span>
                                  @else
                                      <span class="text-[#1a2e5a] font-bold">{{ number_format($course->price, 2) }} ₺</span>
                                  @endif
                              </div> --}}
                                        <a href="{{ url('/egitimler/' . $course->slug) }}"
                                            class="bg-[#e63946] hover:bg-[#d32836] text-white px-4 py-2 rounded-lg transition-colors duration-300 font-medium text-sm">Detayları
                                            Gör</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="w-full text-center py-12 bg-white rounded-lg shadow">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-16 w-16 text-[#1a2e5a] opacity-60 mx-auto mb-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="text-lg text-[#1a2e5a] font-medium">Henüz öne çıkan eğitim bulunmamaktadır.</p>
                            <p class="text-gray-500 mt-2">Lütfen daha sonra tekrar kontrol edin.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Slider Pagination/Dots - Mobil için -->
            <div class="flex justify-center mt-6 md:hidden">
                <!-- Mobil Ok Tuşları - Dots üzerinde -->
                <div class="flex justify-between items-center w-full max-w-xs mb-3">
                    <button id="mobilePrevButton"
                        class="bg-white p-3 rounded-full shadow-lg text-[#1a2e5a] focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div id="sliderDots" class="flex space-x-2">
                        <!-- Dots will be added with JS -->
                    </div>

                    <button id="mobileNextButton"
                        class="bg-white p-3 rounded-full shadow-lg text-[#1a2e5a] focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-10 text-center">
            <a href="{{ url('/egitimler') }}"
                class="inline-block bg-[#1a2e5a] hover:bg-[#0f1d3a] text-white px-6 py-3 rounded-lg font-medium transition-colors duration-300">
                Tüm Eğitimleri Görüntüle
            </a>
        </div>
    </div>

    <!-- Eğer kullanıcı öğrenci rolünde giriş yapmışsa kurslarım bölümünü göster -->
    @if (auth()->check() && auth()->user()->hasRole('ogrenci'))
        <div class="container mx-auto px-4 py-16 bg-gray-100">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-[#1a2e5a] mb-2">Kurslarınız</h2>
                <div class="w-20 h-1 bg-[#e63946] mx-auto"></div>
                <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Eğitimlerinize hızlıca erişin ve öğrenme yolculuğunuza
                    devam edin.</p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-lg">
                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-[#1a2e5a]">
                        <i class="fas fa-graduation-cap mr-2"></i>Devam Eden Kurslarınız
                    </h3>
                    <a href="{{ url('/ogrenci/kurslarim') }}"
                        class="text-[#e63946] hover:text-[#d32836] font-medium flex items-center">
                        Tümünü Görüntüle
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <!-- Basit Kurslarım Listesi -->
                <div class="space-y-4">
                    @php
                        // Sadece aktif ve onaylanmış kayıtları getir
                        $enrolledCourses = auth()
                            ->user()
                            ->enrolledCourses()
                            ->wherePivot('approval_status', 'approved') // Onaylanmış kayıtlar
                            ->where(function ($query) {
                                $query
                                    ->where('end_date', '>=', now()) // Bitiş tarihi bugünden sonra olanlar
                                    ->orWhereNull('end_date'); // Veya bitiş tarihi belirtilmemiş olanlar
                            })
                            ->where('is_active', true) // Aktif kurslar
                            ->take(3)
                            ->get();
                    @endphp

                    @forelse($enrolledCourses as $course)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-semibold text-[#1a2e5a]">{{ $course->name }}</h4>
                                    <div class="text-sm text-gray-500 mt-1">
                                        @if ($course->start_time && $course->end_time)
                                            {{ \Carbon\Carbon::parse($course->start_time)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($course->end_time)->format('H:i') }}
                                        @endif
                                        @if ($course->courseFrequency)
                                            {{ $course->courseFrequency->name }}
                                        @endif
                                    </div>
                                </div>
                                <div class="flex space-x-3">
                                    <a href="{{ route('ogrenci.kurs-detay', $course->slug) }}"
                                        class="text-[#1a2e5a] hover:text-[#e63946] font-medium text-sm">Detaylar</a>
                                    @if ($course->meeting_link)
                                        <a href="{{ $course->meeting_link }}" target="_blank"
                                            class="bg-[#e63946] hover:bg-[#d32836] text-white px-3 py-1 rounded-lg text-sm font-medium">Derse
                                            Katıl</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-gray-50 p-6 rounded-lg text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <p class="text-gray-600">Henüz bir kursa kayıt olmamışsınız.</p>
                            <a href="{{ url('/egitimler') }}"
                                class="mt-3 inline-block text-[#e63946] font-medium hover:underline">Kursları keşfedin</a>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ url('/ogrenci/kurslarim') }}"
                        class="bg-[#1a2e5a] hover:bg-[#132447] text-white px-6 py-2 rounded-lg inline-flex items-center font-medium transition-colors duration-300">
                        <i class="fas fa-book-open mr-2"></i>Tüm Kurslarımı Görüntüle
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 py-16">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12">Ücretsiz İçerikler</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Özellik 1 -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div
                            class="w-12 h-12 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-graduation-cap text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Uzman Eğitmenler</h3>
                        <p class="text-gray-600">Alanında uzman, deneyimli eğitmenlerden öğrenin.</p>
                    </div>

                    <!-- Özellik 2 -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div
                            class="w-12 h-12 bg-green-100 text-green-800 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Esnek Öğrenme</h3>
                        <p class="text-gray-600">İstediğiniz zaman, istediğiniz yerden eğitimlerimize erişin.</p>
                    </div>

                    <!-- Özellik 3 -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div
                            class="w-12 h-12 bg-purple-100 text-purple-800 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-certificate text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Sertifika</h3>
                        <p class="text-gray-600">Eğitimlerinizi tamamlayarak sertifika kazanın.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Animasyonlu Sabit Kayıt Paneli -->
    <div id="floatingSignupPanel"
        class="fixed left-4 bottom-4 md:left-8 md:bottom-8 z-50 w-72 md:w-80 bg-[#1a2e5a] rounded-lg overflow-visible shadow-2xl transform transition-all duration-500 hover:scale-105 group">
        <!-- Animasyon efekti için ekstra div -->
        <div class="absolute inset-0 opacity-0 group-hover:opacity-10 transition-opacity duration-500">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid-anim" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid-anim)" />
            </svg>
        </div>

        @if (!auth()->check() || !auth()->user()->hasRole('ogrenci'))
            <!-- İndirim etiketi - Öğrenci DEĞİLSE göster -->
            <div
                class="absolute -top-4 -left-4 z-10 bg-[#e63946] text-white px-3 py-1 rounded-lg transform -rotate-12 shadow-md font-bold text-sm">
                %15 İNDİRİM
            </div>
        @endif

        <!-- İçerik -->
        <div class="p-6 text-white relative">
            <div class="absolute top-2 right-2">
                <button id="closeFloatingPanel" class="text-white opacity-70 hover:opacity-100 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            @if (auth()->check() && auth()->user()->hasRole('ogrenci'))
                <!-- Giriş yapmış öğrenci için basit panel -->
                <h3 class="text-xl font-bold mb-2 flex items-center">
                    <span class="inline-block w-2 h-2 bg-[#e63946] rounded-full mr-2 animate-pulse"></span>
                    Kurslarınız
                </h3>

                <p class="text-blue-100 mb-4 text-sm">Aktif kurslarınızı görüntüleyebilir veya yeni kurslara göz
                    atabilirsiniz.</p>

                <div class="flex flex-col space-y-2">
                    <a href="{{ url('/ogrenci/kurslarim') }}"
                        class="bg-[#e63946] hover:bg-[#d32836] text-white px-4 py-2 rounded-lg transition-colors duration-300 font-medium text-sm text-center">
                        <i class="fas fa-graduation-cap mr-1"></i>Kurslarıma Git
                    </a>
                    <a href="{{ url('/egitimler') }}"
                        class="bg-white text-[#1a2e5a] hover:bg-gray-100 px-4 py-2 rounded-lg transition-colors duration-300 font-medium text-sm text-center">
                        <i class="fas fa-search mr-1"></i>Yeni Kurslar Keşfet
                    </a>
                </div>
            @else
                <!-- Giriş yapmamış kullanıcı için üyelik paneli -->
                <h3 class="text-xl font-bold mb-2 flex items-center">
                    <span class="inline-block w-2 h-2 bg-[#e63946] rounded-full mr-2 animate-pulse"></span>
                    Eğitimlere Katılın
                </h3>

                <p class="text-blue-100 mb-4 text-sm">Yüzlerce eğitime sınırsız erişim için bugün Rise English'a katılın.
                </p>

                <div class="flex flex-col space-y-2">
                    <a href="{{ url('/kayit-ol') }}"
                        class="bg-[#e63946] hover:bg-[#d32836] text-white px-4 py-2 rounded-lg transition-colors duration-300 font-medium text-sm text-center">
                        Hemen Üye Olun
                    </a>
                    <a href="{{ url('/egitimler') }}"
                        class="bg-white text-[#1a2e5a] hover:bg-gray-100 px-4 py-2 rounded-lg transition-colors duration-300 font-medium text-sm text-center">
                        Tüm Kursları Görüntüle
                    </a>
                </div>
            @endif
        </div>
    </div>

  <script>
    // Düzeltilmiş Video Slider JavaScript - Tam Versiyon
    document.addEventListener('DOMContentLoaded', function() {
        // Success message auto-hide functionality
        initSuccessMessage();

        // Floating panel functionality
        initFloatingPanel();

        // Main featured courses slider functionality
        initMainSlider();

        // Student Videos slider functionality - YENİLENMİŞ VERSİYON
        initVideoSlider();

        initMainPromoVideo();

        // ===== SUCCESS MESSAGE FUNCTIONS =====
        function initSuccessMessage() {
            const successMessage = document.getElementById('successMessage');
            const progressBar = document.getElementById('successMessageProgress');

            if (successMessage && progressBar) {
                // Start the progress bar animation
                progressBar.style.transition = 'width 5s linear';
                progressBar.style.width = '0';

                // Set a timeout to remove the message
                setTimeout(function() {
                    successMessage.classList.add('translate-x-full');
                    setTimeout(function() {
                        successMessage.remove();
                    }, 300);
                }, 5000);
            }

            // Function to close the success message manually (global function)
            window.closeSuccessMessage = function() {
                const successMessage = document.getElementById('successMessage');
                if (successMessage) {
                    successMessage.classList.add('translate-x-full');
                    setTimeout(function() {
                        successMessage.remove();
                    }, 300);
                }
            };
        }

        // ===== FLOATING PANEL FUNCTIONS =====
        function initFloatingPanel() {
            const closeFloatingPanelButton = document.getElementById('closeFloatingPanel');
            const floatingSignupPanel = document.getElementById('floatingSignupPanel');

            if (closeFloatingPanelButton && floatingSignupPanel) {
                closeFloatingPanelButton.addEventListener('click', function() {
                    floatingSignupPanel.classList.add('hidden');
                    floatingSignupPanel.style.display = 'none';

                    // Save user preference as cookie
                    document.cookie =
                        "hideFloatingPanel=true; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
                });

                // Check if user closed the panel before
                if (getCookie('hideFloatingPanel') === 'true') {
                    floatingSignupPanel.classList.add('hidden');
                }
            }

            // Helper function to get cookie value
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
            }
        }

        // ===== MAIN COURSES SLIDER FUNCTIONS =====
        function initMainSlider() {
            const slidesWrapper = document.getElementById('slidesWrapper');
            const sliderDots = document.getElementById('sliderDots');
            const nextButton = document.getElementById('nextButton');
            const prevButton = document.getElementById('prevButton');
            const mobileNextButton = document.getElementById('mobileNextButton');
            const mobilePrevButton = document.getElementById('mobilePrevButton');
            const sliderItems = document.querySelectorAll('.slider-item');

            if (!slidesWrapper || sliderItems.length === 0) return;

            let mainSliderIndex = 0;
            let mainSliderWidthPercent = 100;
            let mainVisibleSlides = 1;

            // Configure slider based on screen size
            function updateMainSlidesConfig() {
                if (window.innerWidth >= 1024) { // lg
                    mainVisibleSlides = 3;
                    mainSliderWidthPercent = 100 / 3;
                } else if (window.innerWidth >= 768) { // md
                    mainVisibleSlides = 2;
                    mainSliderWidthPercent = 50;
                } else { // sm and below
                    mainVisibleSlides = 1;
                    mainSliderWidthPercent = 100;
                }

                // Set slide widths
                sliderItems.forEach(item => {
                    item.style.width = `${mainSliderWidthPercent}%`;
                });

                // Update active slide
                updateMainSlide(mainSliderIndex);

                // Create dots
                createMainDots();
            }

            // Create dots for navigation
            function createMainDots() {
                if (!sliderDots) return;

                sliderDots.innerHTML = '';
                const totalDots = Math.ceil(sliderItems.length / mainVisibleSlides);

                for (let i = 0; i < totalDots; i++) {
                    const dot = document.createElement('div');
                    dot.classList.add('w-2', 'h-2', 'rounded-full', 'bg-gray-300', 'cursor-pointer',
                        'transition-colors');

                    if (i === Math.floor(mainSliderIndex / mainVisibleSlides)) {
                        dot.classList.remove('bg-gray-300');
                        dot.classList.add('bg-[#1a2e5a]');
                    }

                    dot.addEventListener('click', () => {
                        goToMainSlide(i * mainVisibleSlides);
                    });

                    sliderDots.appendChild(dot);
                }
            }

            // Update main slider position
            function updateMainSlide(index) {
                if (!slidesWrapper) return;

                mainSliderIndex = index;

                // Check maximum bounds
                const maxIndex = Math.max(0, sliderItems.length - mainVisibleSlides);
                if (mainSliderIndex > maxIndex) {
                    mainSliderIndex = maxIndex;
                }

                // Smooth transition with transform
                slidesWrapper.style.transition = 'transform 0.5s ease';
                slidesWrapper.style.transform = `translateX(-${mainSliderIndex * mainSliderWidthPercent}%)`;

                // Update dots
                updateMainActiveDot();
            }

            // Update active dot
            function updateMainActiveDot() {
                if (!sliderDots) return;

                const dots = sliderDots.querySelectorAll('div');
                const activeDotIndex = Math.floor(mainSliderIndex / mainVisibleSlides);

                dots.forEach((dot, index) => {
                    if (index === activeDotIndex) {
                        dot.classList.remove('bg-gray-300');
                        dot.classList.add('bg-[#1a2e5a]');
                    } else {
                        dot.classList.remove('bg-[#1a2e5a]');
                        dot.classList.add('bg-gray-300');
                    }
                });
            }

            // Go to specific slide
            function goToMainSlide(index) {
                updateMainSlide(index);
            }

            // Go to next slide
            function nextMainSlide() {
                if (mainSliderIndex < sliderItems.length - mainVisibleSlides) {
                    updateMainSlide(mainSliderIndex + mainVisibleSlides);
                } else {
                    // Loop to beginning
                    updateMainSlide(0);
                }
            }

            // Go to previous slide
            function prevMainSlide() {
                if (mainSliderIndex > 0) {
                    updateMainSlide(mainSliderIndex - mainVisibleSlides);
                } else {
                    // Loop to end
                    updateMainSlide(Math.max(0, sliderItems.length - mainVisibleSlides));
                }
            }

            // Single slide movement for mobile
            function nextMainSingleSlide() {
                if (mainSliderIndex < sliderItems.length - 1) {
                    updateMainSlide(mainSliderIndex + 1);
                } else {
                    updateMainSlide(0);
                }
            }

            function prevMainSingleSlide() {
                if (mainSliderIndex > 0) {
                    updateMainSlide(mainSliderIndex - 1);
                } else {
                    updateMainSlide(sliderItems.length - 1);
                }
            }

            // Button event handlers
            if (nextButton) nextButton.addEventListener('click', nextMainSlide);
            if (prevButton) prevButton.addEventListener('click', prevMainSlide);
            if (mobileNextButton) mobileNextButton.addEventListener('click', nextMainSingleSlide);
            if (mobilePrevButton) mobilePrevButton.addEventListener('click', prevMainSingleSlide);

            // Touch events for mobile swipe
            let mainTouchStartX = 0;
            let mainTouchEndX = 0;

            if (slidesWrapper) {
                slidesWrapper.addEventListener('touchstart', e => {
                    mainTouchStartX = e.changedTouches[0].screenX;
                });

                slidesWrapper.addEventListener('touchend', e => {
                    mainTouchEndX = e.changedTouches[0].screenX;
                    handleMainSwipe();
                });
            }

            function handleMainSwipe() {
                const swipeThreshold = 30;

                if (mainTouchEndX < mainTouchStartX - swipeThreshold) {
                    // Swipe left
                    nextMainSingleSlide();
                } else if (mainTouchEndX > mainTouchStartX + swipeThreshold) {
                    // Swipe right
                    prevMainSingleSlide();
                }
            }

            // Auto-slide functionality
            let mainAutoSlide;
            const sliderContainer = document.querySelector('.slider-container');

            if (sliderContainer && slidesWrapper) {
                mainAutoSlide = setInterval(nextMainSlide, 6000);

                // Pause auto-slide on user interaction
                sliderContainer.addEventListener('mouseenter', () => {
                    clearInterval(mainAutoSlide);
                });

                // Resume auto-slide when user leaves
                sliderContainer.addEventListener('mouseleave', () => {
                    clearInterval(mainAutoSlide);
                    mainAutoSlide = setInterval(nextMainSlide, 6000);
                });
            }

            // Initialize with screen size
            updateMainSlidesConfig();

            // Update on window resize
            window.addEventListener('resize', updateMainSlidesConfig);
        }

        function initMainPromoVideo() {
            console.log("Video thumbnail işlemi başlatılıyor...");
            
            // Tüm video thumbnail'lerini seçin - ana tanıtım ve slayt videoları dahil
            const videoThumbnails = document.querySelectorAll('.video-thumbnail');
            console.log(`Toplam ${videoThumbnails.length} video thumbnail bulundu`);
            
            videoThumbnails.forEach((thumbnail, index) => {
                const img = thumbnail.querySelector('img');
                if (img) {
                    const videoId = thumbnail.getAttribute('data-video-id');
                    console.log(`[${index}] Video işleniyor: ${videoId}, mevcut src: ${img.src}`);
                    
                    // Direkt varsayılan bir değer koyalım, sonra asenkron olarak yükleyelim
                    if (index === 0) {
                        // Ana video için özel yüksek kaliteli placeholder
                        thumbnail.classList.add('thumbnail-loading');
                        img.style.background = '#f1f1f1';
                    }
                    
                    // Tüm olası YouTube thumbnail formatlarını bir dizide tutalım
                    const thumbnailOptions = [
                        `https://i.ytimg.com/vi/${videoId}/maxresdefault.jpg`, // HD
                        `https://i.ytimg.com/vi/${videoId}/hqdefault.jpg`,     // High quality
                        `https://i.ytimg.com/vi/${videoId}/mqdefault.jpg`,     // Medium quality
                        `https://i.ytimg.com/vi/${videoId}/sddefault.jpg`,     // Standard quality
                        `https://i.ytimg.com/vi/${videoId}/0.jpg`,             // Alternatif format
                        `https://i.ytimg.com/vi/${videoId}/default.jpg`,       // Lowest quality
                        'https://via.placeholder.com/480x360?text=Video+Thumbnail' // Fallback
                    ];
                    
                    // Tüm formatlarda thumbnail'leri asenkron olarak kontrol edelim
                    // ve ilk çalışanı kullanalım
                    checkImageSources(thumbnailOptions, 0, (validSrc) => {
                        console.log(`[${index}] ${videoId} için çalışan kaynak bulundu: ${validSrc}`);
                        img.src = validSrc;
                        img.style.opacity = '1';
                        thumbnail.classList.remove('thumbnail-loading');
                    });
                    
                    // Görünürlük için CSS ekle
                    if (!document.getElementById('thumbnail-styles')) {
                        const style = document.createElement('style');
                        style.id = 'thumbnail-styles';
                        style.textContent = `
                            .thumbnail-loading { position: relative; }
                            .thumbnail-loading::after {
                                content: "Yükleniyor...";
                                position: absolute;
                                top: 50%;
                                left: 50%;
                                transform: translate(-50%, -50%);
                                color: #666;
                                font-size: 14px;
                                z-index: 1;
                            }
                        `;
                        document.head.appendChild(style);
                    }
                }
                
                // Video tıklama işlevselliği - Ana video için
                thumbnail.addEventListener('click', function() {
                    const videoId = this.getAttribute('data-video-id');
                    const iframeContainer = this.parentElement.querySelector('.video-iframe-container');
                    
                    if (iframeContainer) {
                        // Slider slayt geçişini durdur - videoSliderIsPlaying değişkenini true yap
                        if (window.videoSliderIsPlaying !== undefined) {
                            window.videoSliderIsPlaying = true;
                            // Varsa otomatik kaydırmayı durdur
                            if (window.videoSliderInterval) {
                                clearInterval(window.videoSliderInterval);
                            }
                        }
                        
                        // Video iframe'ini oluştur - video bittiğinde slayt geçişini tekrar başlatmak için event listener ekle
                        iframeContainer.innerHTML = `<iframe class="w-full h-full absolute inset-0" 
                            src="https://www.youtube.com/embed/${videoId}?autoplay=1&enablejsapi=1" 
                            title="YouTube video player" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen></iframe>`;
                        
                        this.style.display = 'none';
                        iframeContainer.classList.remove('hidden');
                    }
                });
            });
        }

        // Görüntünün yüklenebilir olup olmadığını kontrol eden yardımcı fonksiyon
        function checkImageSources(sources, index, callback) {
            if (index >= sources.length) {
                // Tüm kaynaklar denendi, varsayılan son kaynağı kullan
                callback(sources[sources.length - 1]);
                return;
            }
            
            const img = new Image();
            const timestamp = new Date().getTime();
            const source = sources[index].includes('?') ? 
                `${sources[index]}&_=${timestamp}` : 
                `${sources[index]}?_=${timestamp}`;
            
            img.onload = function() {
                // Bu kaynak çalıştı, geri çağır
                callback(source);
            };
            
            img.onerror = function() {
                console.log(`${source} yüklenemedi, sıradaki kaynak deneniyor`);
                // Bu kaynak çalışmadı, sıradakini dene
                checkImageSources(sources, index + 1, callback);
            };
            
            img.src = source;
        }

        // Alternatif thumbnail kaynaklarını deneyen yardımcı fonksiyon
        function tryAlternativeThumbnails(imgElement, videoId) {
            // Thumbnailleri kaliteden düşüğe doğru dene
            const thumbnailQualities = [
                'mqdefault.jpg',
                'sddefault.jpg',
                '0.jpg', // YouTube'un alternatif formatı
                'default.jpg' // En düşük kalite
            ];

            // Recursive olarak her bir thumbnail kalitesini dene
            function tryNextQuality(index) {
                if (index >= thumbnailQualities.length) {
                    // Tüm kaliteler başarısız olduysa, varsayılan bir görsel kullan
                    console.log(
                        `${videoId} için hiçbir thumbnail kalitesi bulunamadı, varsayılan görsel kullanılıyor`
                        );
                    imgElement.src = 'https://via.placeholder.com/480x360?text=Video+Thumbnail';
                    // Hata durumunda görünürlük ayarları
                    imgElement.style.objectFit = 'contain';
                    imgElement.style.background = '#f0f0f0';
                    return;
                }

                // Zamanlama sorunu olmaması için timestamp ekle
                const timestamp = new Date().getTime();
                imgElement.src =
                `https://i.ytimg.com/vi/${videoId}/${thumbnailQualities[index]}?_=${timestamp}`;

                // Bu thumbnail da başarısız olursa bir sonrakini dene
                imgElement.onerror = function() {
                    console.log(
                        `${videoId} için ${thumbnailQualities[index]} yüklenemedi, sonraki deneniyor`);
                    tryNextQuality(index + 1);
                };
            }

            // İlk kaliteden başla
            tryNextQuality(0);
        }

        // ===== STUDENT VIDEOS SLIDER FUNCTIONS - YENİLENMİŞ VERSİYON =====
        function initVideoSlider() {
            // DOM Elemanlarını Seç
            const videoSlidesWrapper = document.getElementById('videoSlidesWrapper');
            const videoSliderDots = document.getElementById('videoSliderDots');
            const nextVideoBtn = document.getElementById('nextVideo');
            const prevVideoBtn = document.getElementById('prevVideo');
            const videoSlides = document.querySelectorAll('.video-slide');

            // Eğer gerekli elemanlar yoksa işlemi sonlandır
            if (!videoSlidesWrapper || videoSlides.length === 0) return;

            // Değişkenler
            let currentIndex = 0;
            let slidesPerView = 1;
            const totalSlides = videoSlides.length;
            let slideWidth = 100; // Yüzde cinsinden
            let autoSlideInterval;
            
            // Video oynatma durumunu global olarak izle
            window.videoSliderIsPlaying = false;
            window.videoSliderInterval = null;

            // Ekran boyutuna göre görünür slayt sayısını ayarla
            function updateSlidesConfig() {
                if (window.innerWidth >= 1024) { // lg
                    slidesPerView = 3;
                    slideWidth = 100 / 3;
                } else if (window.innerWidth >= 768) { // md
                    slidesPerView = 2;
                    slideWidth = 50;
                } else { // sm ve altı
                    slidesPerView = 1;
                    slideWidth = 100;
                }

                // Slayt genişliklerini ayarla
                videoSlides.forEach(slide => {
                    slide.style.width = `${slideWidth}%`;
                });

                // Slaytları güncelle
                goToSlide(currentIndex);

                // Dot'ları oluştur
                createDots();
            }

            // Dot navigasyonu oluştur
            function createDots() {
                if (!videoSliderDots) return;

                videoSliderDots.innerHTML = '';
                const dotsCount = Math.ceil(totalSlides / slidesPerView);

                for (let i = 0; i < dotsCount; i++) {
                    const dot = document.createElement('div');
                    dot.classList.add('w-2', 'h-2', 'rounded-full', 'bg-white', 'bg-opacity-30',
                        'cursor-pointer', 'transition-all', 'duration-300');

                    if (i === Math.floor(currentIndex / slidesPerView)) {
                        dot.classList.remove('bg-opacity-30');
                        dot.classList.add('bg-opacity-100');
                    }

                    dot.addEventListener('click', () => {
                        goToSlide(i * slidesPerView);
                    });

                    videoSliderDots.appendChild(dot);
                }
            }

            // Belirli bir slayta git
            function goToSlide(index) {
                // Video oynatılıyorsa slayt geçişini durdur
                if (window.videoSliderIsPlaying) return;
                
                // Otomatik geçişi durdur
                clearInterval(autoSlideInterval);

                // Index'in sınırlar içinde olduğunu kontrol et
                currentIndex = index;
                if (currentIndex < 0) {
                    currentIndex = totalSlides - slidesPerView;
                } else if (currentIndex > totalSlides - slidesPerView) {
                    currentIndex = 0;
                }

                // CSS transform ile slaytları kaydır
                videoSlidesWrapper.style.transition = 'transform 0.5s ease';
                videoSlidesWrapper.style.transform = `translateX(-${currentIndex * slideWidth}%)`;

                // Aktif dot'u güncelle
                updateActiveDot();

                // Eğer video oynatılmıyorsa otomatik geçişi yeniden başlat
                if (!window.videoSliderIsPlaying) {
                    startAutoSlide();
                }
            }

            // Aktif dot'u güncelle
            function updateActiveDot() {
                if (!videoSliderDots) return;

                const dots = videoSliderDots.querySelectorAll('div');
                const activeDotIndex = Math.floor(currentIndex / slidesPerView);

                dots.forEach((dot, index) => {
                    if (index === activeDotIndex) {
                        dot.classList.remove('bg-opacity-30');
                        dot.classList.add('bg-opacity-100');
                    } else {
                        dot.classList.remove('bg-opacity-100');
                        dot.classList.add('bg-opacity-30');
                    }
                });
            }

            // Sonraki slayta geç
            function nextSlide() {
                // Video oynatılıyorsa slayt geçişini durdur
                if (window.videoSliderIsPlaying) return;
                goToSlide(currentIndex + slidesPerView);
            }

            // Önceki slayta geç
            function prevSlide() {
                // Video oynatılıyorsa slayt geçişini durdur
                if (window.videoSliderIsPlaying) return;
                goToSlide(currentIndex - slidesPerView);
            }

            // Otomatik geçişi başlat
            function startAutoSlide() {
                clearInterval(autoSlideInterval);
                
                // Eğer video oynatılmıyorsa otomatik geçişi başlat
                if (!window.videoSliderIsPlaying) {
                    autoSlideInterval = setInterval(() => {
                        // Her kontrol et - eğer video oynatılıyorsa otomatik slayt geçişini durduracak
                        if (!window.videoSliderIsPlaying) {
                            nextSlide();
                        }
                    }, 5000);
                    
                    // Otomatik geçiş aralığını kaydet (video bitiminde tekrar başlatmak için)
                    window.videoSliderInterval = autoSlideInterval;
                }
            }

            // Otomatik geçişi durdur
            function stopAutoSlide() {
                clearInterval(autoSlideInterval);
                window.videoSliderInterval = null;
            }

            // Buton event listener'ları
            if (nextVideoBtn) {
                nextVideoBtn.addEventListener('click', () => {
                    nextSlide();
                });
            }

            if (prevVideoBtn) {
                prevVideoBtn.addEventListener('click', () => {
                    prevSlide();
                });
            }

            // Video thumbnails tıklama olayları
            const videoThumbnails = document.querySelectorAll('.video-thumbnail');

            videoThumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    const videoId = this.getAttribute('data-video-id');
                    const iframeContainer = this.parentElement.querySelector('.video-iframe-container');

                    console.log("Video ID:", videoId); // Debugging

                    // Video başlatıldığında otomatik geçişi durdur
                    window.videoSliderIsPlaying = true;
                    stopAutoSlide();

                    // Video iframe'ini oluştur
                    const iframeId = `video-iframe-${videoId}`;
                    iframeContainer.innerHTML = `
                        <iframe id="${iframeId}" class="w-full h-full absolute inset-0" 
                            src="https://www.youtube.com/embed/${videoId}?autoplay=1&enablejsapi=1" 
                            title="YouTube video player" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen></iframe>`;

                    // Thumbnail'i gizle, iframe'i göster
                    this.style.display = 'none';
                    iframeContainer.classList.remove('hidden');
                    
                    // YouTube iFrame API ile video bitiş olayını dinle
                    window.addEventListener('message', function(event) {
                        // YouTube'dan gelen mesaj mı kontrol et
                        if (event.origin.startsWith('https://www.youtube.com') && 
                            typeof event.data === 'string') {
                            
                            try {
                                const data = JSON.parse(event.data);
                                // Video bitiş durumunu kontrol et (0 = bitti)
                                if (data.event === 'onStateChange' && data.info === 0) {
                                    // Video bittiğinde otomatik kaydırmayı tekrar başlat
                                    window.videoSliderIsPlaying = false;
                                    startAutoSlide();
                                }
                            } catch (e) {
                                // JSON değilse veya başka bir hata - yoksay
                            }
                        }
                    });

                    // Ayrıca, sayfadan ayrılma durumunda da otomatik kaydırmayı tekrar başlat
                    document.addEventListener('visibilitychange', function() {
                        if (document.visibilityState === 'hidden') {
                            // Sayfa arkaplanda ise ve video oynatılıyorsa, otomatik kaydırmayı tekrar başlat
                            // Bu, kullanıcı videoyu izlemekten vazgeçtiğinde yardımcı olur
                            setTimeout(() => {
                                window.videoSliderIsPlaying = false;
                                startAutoSlide();
                            }, 30000); // 30 saniye sonra tekrar başlat
                        }
                    });
                });
            });

            // Swipe işlevselliği
            let touchStartX = 0;
            let touchEndX = 0;

            if (videoSlidesWrapper) {
                videoSlidesWrapper.addEventListener('touchstart', e => {
                    touchStartX = e.changedTouches[0].screenX;
                });

                videoSlidesWrapper.addEventListener('touchend', e => {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                });
            }

            function handleSwipe() {
                const swipeThreshold = 30;

                // Video oynatılıyorsa swipe işlemini durdur
                if (window.videoSliderIsPlaying) return;

                if (touchEndX < touchStartX - swipeThreshold) {
                    // Sola kaydırma
                    nextSlide();
                } else if (touchEndX > touchStartX + swipeThreshold) {
                    // Sağa kaydırma
                    prevSlide();
                }
            }

            // Thumbnail yükleme hatalarını işle
            const thumbnailImages = document.querySelectorAll('.video-thumbnail img');
            thumbnailImages.forEach(img => {
                img.addEventListener('error', function() {
                    console.error('Thumbnail yükleme hatası:', this.src);

                    // Video ID'sini al
                    const videoId = this.parentElement.getAttribute('data-video-id');

                    // Alternatif thumbnail dene
                    this.src = `https://i.ytimg.com/vi/${videoId}/default.jpg`;

                    // İkinci deneme de başarısız olursa
                    this.addEventListener('error', function() {
                        // Placeholder resim göster
                        this.src = 'https://via.placeholder.com/480x360?text=Video+Thumbnail';
                    });
                });
            });

            // İlk yükleme için konfigürasyonu ayarla
            updateSlidesConfig();

            // Otomatik geçişi başlat
            startAutoSlide();

            // Ekran boyutu değiştiğinde güncelle
            window.addEventListener('resize', updateSlidesConfig);

            // Eğer sayfa yüklendiğinde video oynatılmıyorsa, videoSliderIsPlaying değişkenini kontrol et
            setInterval(() => {
                // Tüm iframe konteynerlerini kontrol et
                const videoContainers = document.querySelectorAll('.video-iframe-container');
                let anyVideoVisible = false;

                videoContainers.forEach(container => {
                    // Eğer herhangi bir iframe container görünürse (display != 'none' ve hidden değilse)
                    if (container.style.display !== 'none' && !container.classList.contains('hidden') && 
                        container.querySelector('iframe')) {
                        anyVideoVisible = true;
                    }
                });

                // Görünür video yoksa otomatik kaydırmayı tekrar başlat
                if (!anyVideoVisible && window.videoSliderIsPlaying) {
                    window.videoSliderIsPlaying = false;
                    startAutoSlide();
                }
            }, 10000); // Her 10 saniyede bir kontrol et
        }
    });
</script>
@endsection
