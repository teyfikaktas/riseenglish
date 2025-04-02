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
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded fixed top-4 right-4 shadow-lg z-50">
        {{ session('success') }}
    </div>
@endif
<div class="relative bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] py-20 overflow-hidden">
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

<!-- İçerik kısmı -->
<div class="container mx-auto px-6 relative z-10">
    <div class="flex flex-col md:flex-row items-center">
      <!-- Sol taraf (metin) -->
      <div class="w-full md:w-1/2 text-center md:text-left mb-12 md:mb-0">
        @if(auth()->check() && auth()->user()->hasRole('ogrenci'))
          <!-- GİRİŞ YAPMIŞ ÖĞRENCİ İÇİN KİŞİSELLEŞTİRİLMİŞ MESAJ -->
          <!-- Üst kısımda büyük ve çarpıcı 'Kurslarınız' yazısı -->
          <div class="mb-4 transform -translate-y-4">
            <span class="bg-[#e63946] text-white text-xl px-4 py-2 rounded-lg shadow-lg inline-block transform -rotate-2 hover:rotate-0 transition-transform duration-300 font-bold">
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
            Eğitim platformumuzda profesyonel eğitmenlerle yeni beceriler kazanın ve kariyerinizde bir adım öne çıkın.
          </p>
        @endif
        <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-4">
          @if(auth()->check() && auth()->user()->hasRole('ogrenci'))
            <!-- Giriş yapmış öğrenci için kurslarım butonu -->
            <a href="{{ url('/ogrenci/kurslarim') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-4 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
              <i class="fas fa-book-reader mr-2"></i>Kurslarıma Git
            </a>
            <a href="{{ url('/egitimler') }}" class="bg-white hover:bg-gray-100 text-[#1a2e5a] font-bold py-4 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
              <i class="fas fa-book-open mr-2"></i>Yeni Eğitimler
            </a>
          @else
            <!-- Giriş yapmamış kullanıcı için standart butonlar -->
            <a href="{{ url('/egitimler') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-4 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
              Eğitimleri Keşfet
            </a>
            <a href="{{ url('/kayit-ol') }}" class="bg-white hover:bg-gray-100 text-[#1a2e5a] font-bold py-4 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
              Hemen Başla
            </a>
          @endif
        </div>
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
<!-- İdiom Gösterim Alanı -->
<div class="p-6 bg-gray-50">
    @if(isset($dailyIdiom))
        <!-- İngilizce İdiom -->
        <div class="bg-white rounded-lg p-4 mb-4 shadow-md border-l-4 border-[#e63946]">
            <div class="text-xl font-bold text-[#1a2e5a] mb-1">"{{ $dailyIdiom->english_phrase }}"</div>
            <div class="text-sm text-gray-500 italic">{{ $dailyIdiom->turkish_translation }}</div>
        </div>
        
        <!-- Örnek Cümleler -->
        <div class="bg-white rounded-lg p-4 shadow-md border-l-4 border-[#1a2e5a]">
            <div class="text-lg font-bold text-[#1a2e5a]">Örnek Cümleler:</div>
            <div class="text-sm text-gray-600 mt-2">- {{ $dailyIdiom->example_sentence_1 }}</div>
            @if($dailyIdiom->example_sentence_2)
                <div class="text-sm text-gray-600">- {{ $dailyIdiom->example_sentence_2 }}</div>
            @endif
        </div>
        
        <!-- Karakter Görseli - Ortalanmış -->
        <div class="relative mt-6 flex justify-center">
            @if($dailyIdiom->image_path)
                <img src="{{ asset('storage/' . $dailyIdiom->image_path) }}" alt="İdiom Görseli" class="h-80 object-contain z-10">
            @else
                <img src="{{ asset('images/1.jpg') }}" alt="Varsayılan İdiom Görseli" class="h-80 object-contain z-10">
            @endif
            <div class="absolute top-0 right-10 animate-bounce z-20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#e63946]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
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
          @if(auth()->check() && auth()->user()->hasRole('ogrenci'))
            <!-- Aktif kurs sayısı kutusu - Giriş yapmış öğrenci için -->
            <div class="absolute -top-4 -left-4 bg-[#1a2e5a] text-white rounded-lg p-3 shadow-lg transform rotate-3 hover:rotate-0 transition-transform duration-300">
              <div class="flex items-center">
                <i class="fas fa-book-open mr-2"></i>
                <div>
                  <div class="text-lg font-bold">Aktif Kurslar</div>
                  <div class="text-2xl font-extrabold">{{ auth()->user()->enrolledCourses()->where('is_active', true)->count() }}</div>
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
            <!-- İndirim kutusu - Giriş yapmayan kullanıcı için -->
            <div class="absolute -top-4 -left-4 bg-[#e63946] text-white rounded-lg p-3 shadow-lg transform -rotate-2 hover:rotate-0 transition-transform duration-300">
              <div class="text-lg font-bold">
                %15 İNDİRİM
              </div>
              <div class="text-xs mt-1 bg-white text-[#e63946] px-2 py-1 rounded-full font-bold inline-block">
                SINIRLI SÜRE!
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container mx-auto px-4 py-16 bg-gray-50">
  <div class="text-center mb-12">
      <h2 class="text-3xl font-bold text-[#1a2e5a] mb-2">Öne Çıkan Eğitimler</h2>
      <div class="w-20 h-1 bg-[#e63946] mx-auto"></div>
      <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Profesyonel eğitmenlerimiz tarafından hazırlanan kaliteli ve güncel içeriklerle kariyer hedeflerinize bir adım daha yaklaşın.</p>
  </div>
  
  <!-- Slider Ana Container -->
  <div class="relative">
      <!-- Slider Controls - Mobilde Gizli (md boyutundan itibaren göster) -->
      <div class="hidden md:block">
          <button id="prevButton" class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-5 bg-white p-3 rounded-full shadow-lg z-10 text-[#1a2e5a]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
          </button>
          <button id="nextButton" class="absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-5 bg-white p-3 rounded-full shadow-lg z-10 text-[#1a2e5a]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                  <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-2 group h-full">
                      <div class="h-48 bg-gray-200 relative overflow-hidden">
                          @if($course->thumbnail)
                              <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                          @else
                              <div class="flex items-center justify-center h-full bg-gray-100">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                  </svg>
                              </div>
                          @endif
                          @if($course->discount_price)
                              <div class="absolute top-2 right-2 bg-[#e63946] text-white px-3 py-1 rounded-full font-bold text-sm">
                                  %{{ number_format((($course->price - $course->discount_price) / $course->price) * 100) }} İNDİRİM
                              </div>
                          @endif
                          
                          <!-- Başlangıç durumu etiketi -->
                          @php
                              $today = \Carbon\Carbon::today();
                              $startDate = \Carbon\Carbon::parse($course->start_date);
                              $endDate = \Carbon\Carbon::parse($course->end_date);
                              $daysLeft = $today->diffInDays($startDate, false);
                          @endphp

                          @if($startDate->isPast() && $endDate->isFuture())
                              <div class="absolute top-2 left-2 bg-[#44bd32] text-white text-xs font-bold px-2 py-1 rounded-full">
                                  DEVAM EDİYOR
                              </div>
                          @elseif($startDate->isPast() && $endDate->isPast())
                              <div class="absolute top-2 left-2 bg-[#718093] text-white text-xs font-bold px-2 py-1 rounded-full">
                                  TAMAMLANDI
                              </div>
                          @elseif($daysLeft <= 7 && $daysLeft > 0)
                              <div class="absolute top-2 left-2 bg-[#e1b12c] text-white text-xs font-bold px-2 py-1 rounded-full">
                                  {{ $daysLeft }} GÜN KALDI
                              </div>
                          @elseif($daysLeft == 0)
                              <div class="absolute top-2 left-2 bg-[#c23616] text-white text-xs font-bold px-2 py-1 rounded-full">
                                  BUGÜN BAŞLIYOR
                              </div>
                          @endif
                          
                          <!-- Kurs tipi ve seviye etiketi -->
                          <div class="absolute bottom-2 left-2 flex space-x-2">
                              @if($course->courseType)
                                  <span class="bg-[#1a2e5a] text-white text-xs font-bold px-2 py-1 rounded">{{ $course->courseType->name }}</span>
                              @endif
                              @if($course->courseLevel)
                                  <span class="bg-[#e63946] text-white text-xs font-bold px-2 py-1 rounded">{{ $course->courseLevel->name }}</span>
                              @endif
                          </div>
                      </div>
                      
                      <div class="p-6">
                          <h3 class="text-xl font-semibold mb-2 text-[#1a2e5a]">{{ $course->name }}</h3>
                          <p class="text-gray-600 mb-4 text-sm h-12 overflow-hidden">{{ Str::limit($course->description, 100) }}</p>
                          
                          <!-- Eğitim Tarihleri Bölümü -->
                          <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                              <div class="flex items-center mb-2">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                  </svg>
                                  <span class="font-medium text-[#1a2e5a]">Eğitim Tarihleri</span>
                              </div>
                              
                              @if($course->start_date && $course->end_date)
                                  <div class="grid grid-cols-2 gap-2 text-sm">
                                      <div>
                                          <span class="text-gray-500">Başlangıç:</span>
                                          <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') }}</span>
                                      </div>
                                      <div>
                                          <span class="text-gray-500">Bitiş:</span>
                                          <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($course->end_date)->format('d.m.Y') }}</span>
                                      </div>
                                  </div>
                                  
                                  @php
                                      $totalDuration = $startDate->diffInDays($endDate);
                                  @endphp
                                  
                                  <div class="mt-2">
                                      @if($startDate->isPast() && $endDate->isFuture())
                                          <!-- Kurs devam ediyor -->
                                          <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                              @php
                                                  $elapsed = $today->diffInDays($startDate);
                                                  $progress = ($elapsed / $totalDuration) * 100;
                                                  $progress = min(100, max(0, $progress));
                                              @endphp
                                              <div class="bg-[#44bd32] h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                          </div>
                                          <p class="text-xs text-gray-500 mt-1">
                                              <span class="font-medium">Eğitim devam ediyor</span>
                                          </p>
                                      @elseif($startDate->isFuture())
                                          <!-- Kurs başlamadı -->
                                          <p class="text-xs text-gray-500 mt-1">
                                              @if($daysLeft == 0)
                                                  <span class="font-medium text-[#e63946]">Bugün başlıyor!</span>
                                              @elseif($daysLeft == 1)
                                                  <span class="font-medium text-[#e63946]">Yarın başlıyor!</span>
                                              @else
                                                  <span class="font-medium text-[#1a2e5a]">{{ $daysLeft }} gün</span> sonra başlayacak
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
                              @if($course->teacher)
                                  <div class="flex items-center">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                      </svg>
                                      {{ $course->teacher->name }}
                                  </div>
                              @endif
                              
                              <!-- Toplam saat -->
                              @if($course->total_hours)
                                  <div class="flex items-center">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                      </svg>
                                      {{ $course->total_hours }} Saat
                                  </div>
                              @endif
                              
                              <!-- Kurs sıklığı -->
                              @if($course->courseFrequency)
                                  <div class="flex items-center">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                      </svg>
                                      {{ $course->courseFrequency->name }}
                                  </div>
                              @endif
                              
                              <!-- Sertifika bilgisi -->
                              @if($course->has_certificate)
                                  <div class="flex items-center">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                      </svg>
                                      Sertifikalı
                                  </div>
                              @endif
                              
                              <!-- Kontenjan bilgisi -->
                              @if($course->max_students)
                                  <div class="flex items-center">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                      </svg>
                                      {{ $course->max_students }} Kişi
                                  </div>
                              @endif
                          </div>
                          
                          <div class="flex justify-between items-center">
                              <div>
                                  @if($course->discount_price)
                                      <span class="text-gray-500 line-through text-sm">{{ number_format($course->price, 2) }} ₺</span>
                                      <span class="text-[#e63946] font-bold ml-2">{{ number_format($course->discount_price, 2) }} ₺</span>
                                  @else
                                      <span class="text-[#1a2e5a] font-bold">{{ number_format($course->price, 2) }} ₺</span>
                                  @endif
                              </div>
                              <a href="{{ url('/egitimler/' . $course->slug) }}" class="bg-[#e63946] hover:bg-[#d32836] text-white px-4 py-2 rounded-lg transition-colors duration-300 font-medium text-sm">Detayları Gör</a>
                          </div>
                      </div>
                  </div>
              </div>
              @empty
              <div class="w-full text-center py-12 bg-white rounded-lg shadow">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#1a2e5a] opacity-60 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                  <p class="text-lg text-[#1a2e5a] font-medium">Henüz öne çıkan eğitim bulunmamaktadır.</p>
                  <p class="text-gray-500 mt-2">Lütfen daha sonra tekrar kontrol edin.</p>
              </div>
              @endforelse
          </div>
      </div>
      
      <!-- Slider Pagination/Dots - Mobil için -->
      <div class="flex justify-center mt-6 md:hidden">
          <div id="sliderDots" class="flex space-x-2">
              <!-- Dots will be added with JS -->
          </div>
      </div>
  </div>
  
  <div class="mt-10 text-center">
      <a href="{{ url('/egitimler') }}" class="inline-block bg-[#1a2e5a] hover:bg-[#0f1d3a] text-white px-6 py-3 rounded-lg font-medium transition-colors duration-300">
          Tüm Eğitimleri Görüntüle
      </a>
  </div>
</div>

<!-- Eğer kullanıcı öğrenci rolünde giriş yapmışsa kurslarım bölümünü göster -->
@if(auth()->check() && auth()->user()->hasRole('ogrenci'))
<div class="container mx-auto px-4 py-16 bg-gray-100">
  <div class="text-center mb-12">
      <h2 class="text-3xl font-bold text-[#1a2e5a] mb-2">Kurslarınız</h2>
      <div class="w-20 h-1 bg-[#e63946] mx-auto"></div>
      <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Eğitimlerinize hızlıca erişin ve öğrenme yolculuğunuza devam edin.</p>
  </div>
  
  <div class="bg-white p-8 rounded-xl shadow-lg">
      <div class="mb-6 flex justify-between items-center">
          <h3 class="text-xl font-bold text-[#1a2e5a]">
              <i class="fas fa-graduation-cap mr-2"></i>Devam Eden Kurslarınız
          </h3>
          <a href="{{ url('/ogrenci/kurslarim') }}" class="text-[#e63946] hover:text-[#d32836] font-medium flex items-center">
              Tümünü Görüntüle
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
          </a>
      </div>
      
      <!-- Basit Kurslarım Listesi -->
      <div class="space-y-4">
          @php
          // Sadece aktif ve onaylanmış kayıtları getir
          $enrolledCourses = auth()->user()->enrolledCourses()
              ->wherePivot('approval_status', 'approved') // Onaylanmış kayıtlar
              ->where(function($query) {
                  $query->where('end_date', '>=', now()) // Bitiş tarihi bugünden sonra olanlar
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
                          @if($course->start_time && $course->end_time)
                              {{ \Carbon\Carbon::parse($course->start_time)->format('H:i') }} - 
                              {{ \Carbon\Carbon::parse($course->end_time)->format('H:i') }}
                          @endif
                          @if($course->courseFrequency)
                              {{ $course->courseFrequency->name }}
                          @endif
                      </div>
                  </div>
                  <div class="flex space-x-3">
                      <a href="{{ route('ogrenci.kurs-detay', $course->slug) }}" class="text-[#1a2e5a] hover:text-[#e63946] font-medium text-sm">Detaylar</a>
                      @if($course->meeting_link)
                      <a href="{{ $course->meeting_link }}" target="_blank" class="bg-[#e63946] hover:bg-[#d32836] text-white px-3 py-1 rounded-lg text-sm font-medium">Derse Katıl</a>
                      @endif
                  </div>
              </div>
          </div>
          @empty
          <div class="bg-gray-50 p-6 rounded-lg text-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
              <p class="text-gray-600">Henüz bir kursa kayıt olmamışsınız.</p>
              <a href="{{ url('/egitimler') }}" class="mt-3 inline-block text-[#e63946] font-medium hover:underline">Kursları keşfedin</a>
          </div>
          @endforelse
      </div>

      <div class="mt-8 pt-6 border-t border-gray-200">
          <a href="{{ url('/ogrenci/kurslarim') }}" class="bg-[#1a2e5a] hover:bg-[#132447] text-white px-6 py-2 rounded-lg inline-flex items-center font-medium transition-colors duration-300">
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
              <div class="w-12 h-12 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center mb-4">
                  <i class="fas fa-graduation-cap text-xl"></i>
              </div>
              <h3 class="text-xl font-semibold mb-2">Uzman Eğitmenler</h3>
              <p class="text-gray-600">Alanında uzman, deneyimli eğitmenlerden öğrenin.</p>
          </div>
          
          <!-- Özellik 2 -->
          <div class="bg-white p-6 rounded-lg shadow-md">
              <div class="w-12 h-12 bg-green-100 text-green-800 rounded-full flex items-center justify-center mb-4">
                  <i class="fas fa-clock text-xl"></i>
              </div>
              <h3 class="text-xl font-semibold mb-2">Esnek Öğrenme</h3>
              <p class="text-gray-600">İstediğiniz zaman, istediğiniz yerden eğitimlerimize erişin.</p>
          </div>
          
          <!-- Özellik 3 -->
          <div class="bg-white p-6 rounded-lg shadow-md">
              <div class="w-12 h-12 bg-purple-100 text-purple-800 rounded-full flex items-center justify-center mb-4">
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
<div id="floatingSignupPanel" class="fixed left-4 bottom-4 md:left-8 md:bottom-8 z-50 w-72 md:w-80 bg-[#1a2e5a] rounded-lg overflow-visible shadow-2xl transform transition-all duration-500 hover:scale-105 group">
  <!-- Animasyon efekti için ekstra div -->
  <div class="absolute inset-0 opacity-0 group-hover:opacity-10 transition-opacity duration-500">
      <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
          <defs>
              <pattern id="grid-anim" width="10" height="10" patternUnits="userSpaceOnUse">
                  <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
              </pattern>
          </defs>
          <rect width="100" height="100" fill="url(#grid-anim)" />
      </svg>
  </div>
  
  @if(!auth()->check() || !auth()->user()->hasRole('ogrenci'))
  <!-- İndirim etiketi - Öğrenci DEĞİLSE göster -->
  <div class="absolute -top-4 -left-4 z-10 bg-[#e63946] text-white px-3 py-1 rounded-lg transform -rotate-12 shadow-md font-bold text-sm">
      %15 İNDİRİM
  </div>
  @endif
  
  <!-- İçerik -->
  <div class="p-6 text-white relative">
    <div class="absolute top-2 right-2">
        <button id="closeFloatingPanel" class="text-white opacity-70 hover:opacity-100 transition-opacity">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    
    @if(auth()->check() && auth()->user()->hasRole('ogrenci'))
    <!-- Giriş yapmış öğrenci için basit panel -->
    <h3 class="text-xl font-bold mb-2 flex items-center">
        <span class="inline-block w-2 h-2 bg-[#e63946] rounded-full mr-2 animate-pulse"></span>
        Kurslarınız
    </h3>
    
    <p class="text-blue-100 mb-4 text-sm">Aktif kurslarınızı görüntüleyebilir veya yeni kurslara göz atabilirsiniz.</p>
    
    <div class="flex flex-col space-y-2">
        <a href="{{ url('/ogrenci/kurslarim') }}" class="bg-[#e63946] hover:bg-[#d32836] text-white px-4 py-2 rounded-lg transition-colors duration-300 font-medium text-sm text-center">
            <i class="fas fa-graduation-cap mr-1"></i>Kurslarıma Git
        </a>
        <a href="{{ url('/egitimler') }}" class="bg-white text-[#1a2e5a] hover:bg-gray-100 px-4 py-2 rounded-lg transition-colors duration-300 font-medium text-sm text-center">
            <i class="fas fa-search mr-1"></i>Yeni Kurslar Keşfet
        </a>
    </div>
    @else
    <!-- Giriş yapmamış kullanıcı için üyelik paneli -->
    <h3 class="text-xl font-bold mb-2 flex items-center">
        <span class="inline-block w-2 h-2 bg-[#e63946] rounded-full mr-2 animate-pulse"></span>
        Eğitimlere Katılın
    </h3>
    
    <p class="text-blue-100 mb-4 text-sm">Yüzlerce eğitime sınırsız erişim için bugün Rise English'a katılın.</p>
    
    <div class="flex flex-col space-y-2">
        <a href="{{ url('/kayit-ol') }}" class="bg-[#e63946] hover:bg-[#d32836] text-white px-4 py-2 rounded-lg transition-colors duration-300 font-medium text-sm text-center">
            Hemen Üye Olun
        </a>
        <a href="{{ url('/egitimler') }}" class="bg-white text-[#1a2e5a] hover:bg-gray-100 px-4 py-2 rounded-lg transition-colors duration-300 font-medium text-sm text-center">
            Tüm Kursları Görüntüle
        </a>
    </div>
    @endif
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const floatingPanel = document.getElementById('floatingSignupPanel');
    const closeButton = document.getElementById('closeFloatingPanel');
    
    // Panel ilk yüklendiğinde animasyon için
    setTimeout(() => {
        floatingPanel.classList.add('animate-bounce');
        setTimeout(() => {
            floatingPanel.classList.remove('animate-bounce');
        }, 1000);
    }, 3000);
    
    // Düzenli aralıklarla hafif bir sallanma animasyonu
    setInterval(() => {
        floatingPanel.classList.add('animate-pulse');
        setTimeout(() => {
            floatingPanel.classList.remove('animate-pulse');
        }, 1000);
    }, 10000);
    
    // Kapama butonu işlevselliği
    closeButton.addEventListener('click', function() {
        floatingPanel.classList.add('opacity-0', 'translate-y-10');
        setTimeout(() => {
            floatingPanel.classList.add('hidden');
        }, 500);
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const slidesWrapper = document.getElementById('slidesWrapper');
    const sliderDots = document.getElementById('sliderDots');
    const nextButton = document.getElementById('nextButton');
    const prevButton = document.getElementById('prevButton');
    const sliderItems = document.querySelectorAll('.slider-item');
    
    if (sliderItems.length === 0) return;
    
    let currentIndex = 0;
    let slideWidthPercent = 100;
    let visibleSlides = 1;
    
    // Ekran boyutuna göre görünecek slide sayısı
    function updateSlidesConfig() {
        if (window.innerWidth >= 1024) { // lg
            visibleSlides = 3;
            slideWidthPercent = 100 / 3;
        } else if (window.innerWidth >= 768) { // md
            visibleSlides = 2;
            slideWidthPercent = 50;
        } else { // sm ve altı
            visibleSlides = 1;
            slideWidthPercent = 100;
        }
        
        // Slide genişliklerini ayarla
        sliderItems.forEach(item => {
            item.style.width = `${slideWidthPercent}%`;
        });
        
        // Aktif slide'ı güncelle
        updateSlide(currentIndex);
        
        // Dot'ları oluştur
        createDots();
    }
    
    // Dot navigasyonunu oluştur (özellikle mobil için)
    function createDots() {
        sliderDots.innerHTML = '';
        const totalDots = Math.ceil(sliderItems.length / visibleSlides);
        
        for (let i = 0; i < totalDots; i++) {
            const dot = document.createElement('div');
            dot.classList.add('w-2', 'h-2', 'rounded-full', 'bg-gray-300', 'cursor-pointer', 'transition-colors');
            
            if (i === Math.floor(currentIndex / visibleSlides)) {
                dot.classList.remove('bg-gray-300');
                dot.classList.add('bg-[#1a2e5a]');
            }
            
            dot.addEventListener('click', () => {
                goToSlide(i * visibleSlides);
            });
            
            sliderDots.appendChild(dot);
        }
    }
    
    // Slide'ı güncelle
    function updateSlide(index) {
        currentIndex = index;
        
        // Slide'ın maksimum sınırını kontrol et
        const maxIndex = Math.max(0, sliderItems.length - visibleSlides);
        if (currentIndex > maxIndex) {
            currentIndex = maxIndex;
        }
        
        // Transform ile yatay kaydırma
        slidesWrapper.style.transform = `translateX(-${currentIndex * slideWidthPercent}%)`;
        
        // Aktif dot'u güncelle
        updateActiveDot();
    }
    
    // Aktif dot'u güncelle
    function updateActiveDot() {
        const dots = sliderDots.querySelectorAll('div');
        const activeDotIndex = Math.floor(currentIndex / visibleSlides);
        
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
    
    // Belirli slide'a git
    function goToSlide(index) {
        updateSlide(index);
    }
    
    // Sonraki slide'a git
    function nextSlide() {
        if (currentIndex < sliderItems.length - visibleSlides) {
            updateSlide(currentIndex + visibleSlides);
        } else {
            // Son slide'daysa başa dön (opsiyonel)
            updateSlide(0);
        }
    }
    
    // Önceki slide'a git
    function prevSlide() {
        if (currentIndex > 0) {
            updateSlide(currentIndex - visibleSlides);
        } else {
            // İlk slide'daysa sona git (opsiyonel)
            updateSlide(Math.max(0, sliderItems.length - visibleSlides));
        }
    }
    
    // Buton event listener'ları
    if (nextButton) nextButton.addEventListener('click', nextSlide);
    if (prevButton) prevButton.addEventListener('click', prevSlide);
    
    // Swipe desteği için dokunmatik ekran olayları (mobil için)
    let touchStartX = 0;
    let touchEndX = 0;
    
    slidesWrapper.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    slidesWrapper.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });
    
    function handleSwipe() {
        const swipeThreshold = 50; // px
        
        if (touchEndX < touchStartX - swipeThreshold) {
            // Sola kaydırma
            nextSlide();
        } else if (touchEndX > touchStartX + swipeThreshold) {
            // Sağa kaydırma
            prevSlide();
        }
    }
    
    // Ekran boyutu değiştiğinde ayarlamaları güncelle
    window.addEventListener('resize', updateSlidesConfig);
    
    // İlk yükleme
    updateSlidesConfig();
    
    // Otomatik geçiş için (opsiyonel)
    // setInterval(nextSlide, 5000);
});
</script>
@endsection