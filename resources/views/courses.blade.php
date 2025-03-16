<!-- resources/views/courses.blade.php -->
@extends('layouts.app')

@section('content')
<!-- Başarı mesajı için ekleme yapıyoruz -->
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded fixed top-4 right-4 shadow-lg z-50">
        {{ session('success') }}
    </div>
@endif

<!-- Üst Banner -->
<div class="bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl font-bold text-white mb-6">Rise English Eğitim Programları</h1>
            <p class="text-xl text-white opacity-90 mb-8">Profesyonel eğitmenler eşliğinde kariyerinizi bir adım öne taşıyın</p>
            <div class="bg-white p-1 rounded-lg shadow-lg">
                <div class="flex flex-col md:flex-row">
                    <input type="text" id="courseSearch" placeholder="Eğitim ara..." class="flex-grow px-4 py-3 focus:outline-none rounded-lg">
                    <button id="searchButton" class="bg-[#e63946] hover:bg-[#d32836] text-white px-6 py-3 rounded-lg mt-2 md:mt-0 md:ml-2 transition-colors duration-300 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Ara
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ana İçerik -->
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row">
            <!-- Filtreler (Sol Kenar) -->
            <div class="w-full lg:w-1/4 mb-8 lg:mb-0 lg:pr-8">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-[#1a2e5a] mb-4 pb-2 border-b border-gray-200">Filtreleme Seçenekleri</h3>
                    
                    <!-- Filtre grupları -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-2">Kurs Kategorisi</h4>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-2">
                            @forelse($courseTypes as $type)
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="course_type[]" value="{{ $type->id }}" class="form-checkbox h-5 w-5 text-[#e63946] rounded transition duration-150 ease-in-out">
                                <span class="ml-2 text-gray-700 group-hover:text-[#e63946]">{{ $type->name }}</span>
                            </label>
                            @empty
                            <p class="text-gray-500 text-sm">Kategori bulunamadı</p>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-2">Seviye</h4>
                        <div class="space-y-2">
                            @forelse($courseLevels as $level)
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="course_level[]" value="{{ $level->id }}" class="form-checkbox h-5 w-5 text-[#e63946] rounded transition duration-150 ease-in-out">
                                <span class="ml-2 text-gray-700 group-hover:text-[#e63946]">{{ $level->name }}</span>
                            </label>
                            @empty
                            <p class="text-gray-500 text-sm">Seviye bulunamadı</p>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-2">Eğitim Durumu</h4>
                        <div class="space-y-2">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="course_status[]" value="upcoming" class="form-checkbox h-5 w-5 text-[#e63946] rounded transition duration-150 ease-in-out">
                                <span class="ml-2 text-gray-700 group-hover:text-[#e63946]">Yakında Başlayacak</span>
                            </label>
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="course_status[]" value="ongoing" class="form-checkbox h-5 w-5 text-[#e63946] rounded transition duration-150 ease-in-out">
                                <span class="ml-2 text-gray-700 group-hover:text-[#e63946]">Devam Eden</span>
                            </label>
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="course_status[]" value="completed" class="form-checkbox h-5 w-5 text-[#e63946] rounded transition duration-150 ease-in-out">
                                <span class="ml-2 text-gray-700 group-hover:text-[#e63946]">Tamamlanan</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-2">Özellikler</h4>
                        <div class="space-y-2">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="features[]" value="certificate" class="form-checkbox h-5 w-5 text-[#e63946] rounded transition duration-150 ease-in-out">
                                <span class="ml-2 text-gray-700 group-hover:text-[#e63946]">Sertifikalı</span>
                            </label>
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="features[]" value="discount" class="form-checkbox h-5 w-5 text-[#e63946] rounded transition duration-150 ease-in-out">
                                <span class="ml-2 text-gray-700 group-hover:text-[#e63946]">İndirimli</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-2">Fiyat Aralığı</h4>
                        <div class="px-2">
                            <div class="flex items-center justify-between mb-2">
                                <span id="minPriceValue" class="text-sm text-gray-600">0 ₺</span>
                                <span id="maxPriceValue" class="text-sm text-gray-600">5000 ₺</span>
                            </div>
                            <div class="relative">
                                <input type="range" id="priceRange" min="0" max="5000" step="100" value="5000" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex mt-8">
                        <button id="applyFilters" class="flex-1 bg-[#1a2e5a] hover:bg-[#152347] text-white py-2 px-4 rounded-lg mr-2 transition-colors duration-300">
                            Filtrele
                        </button>
                        <button id="clearFilters" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded-lg ml-2 transition-colors duration-300">
                            Temizle
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Kurslar (Sağ Taraf) -->
            <div class="w-full lg:w-3/4">
                <!-- Sıralama ve görünüm seçenekleri -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-6 flex flex-col sm:flex-row justify-between items-center">
                    <div class="mb-4 sm:mb-0 w-full sm:w-auto">
                        <span class="text-gray-600 mr-2">Toplam {{ $courses->total() }} kurs</span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 w-full sm:w-auto">
                        <div class="flex items-center mr-0 sm:mr-4 w-full sm:w-auto">
                            <label for="sortCourses" class="text-gray-600 mr-2 whitespace-nowrap">Sırala:</label>
                            <select id="sortCourses" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a2e5a] w-full">
                                <option value="newest">En Yeni</option>
                                <option value="popular">En Popüler</option>
                                <option value="price_low">Fiyat: Düşükten Yükseğe</option>
                                <option value="price_high">Fiyat: Yüksekten Düşüğe</option>
                                <option value="name_asc">İsim: A-Z</option>
                                <option value="name_desc">İsim: Z-A</option>
                            </select>
                        </div>
                        
                        <div class="flex space-x-2 w-full sm:w-auto justify-center">
                            <button id="gridView" class="bg-[#1a2e5a] text-white p-2 rounded-lg transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </button>
                            <button id="listView" class="bg-gray-200 text-gray-700 p-2 rounded-lg transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Kurs listesi - Grid View (Varsayılan) -->
                <div id="coursesGridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($courses as $course)
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
                    @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-lg shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#1a2e5a] opacity-60 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-lg text-[#1a2e5a] font-medium">Seçilen kriterlere uygun eğitim bulunamadı.</p>
                        <p class="text-gray-500 mt-2">Lütfen farklı filtre seçenekleri deneyiniz.</p>
                        <button id="resetAllFilters" class="mt-4 bg-[#e63946] hover:bg-[#d32836] text-white px-6 py-2 rounded-lg transition-colors duration-300 font-medium">
                            Tüm Filtreleri Sıfırla
                        </button>
                    </div>
                    @endforelse
                </div>
                
                <!-- Kurs listesi - List View (Gizli) -->
                <div id="coursesListView" class="hidden space-y-6">
                    @forelse($courses as $course)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg group">
                        <div class="flex flex-col md:flex-row">
                            <!-- Sol taraf (resim) -->
                            <div class="md:w-1/3 h-56 md:h-auto bg-gray-200 relative overflow-hidden">
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
                            </div>
                            
                            <!-- Sağ taraf (içerik) -->
                            <div class="md:w-2/3 p-6">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="flex space-x-2 mb-2">
                                            @if($course->courseType)
                                                <span class="bg-[#1a2e5a] text-white text-xs font-bold px-2 py-1 rounded">{{ $course->courseType->name }}</span>
                                            @endif
                                            @if($course->courseLevel)
                                                <span class="bg-[#e63946] text-white text-xs font-bold px-2 py-1 rounded">{{ $course->courseLevel->name }}</span>
                                            @endif
                                        </div>
                                        <h3 class="text-2xl font-semibold mb-2 text-[#1a2e5a]">{{ $course->name }}</h3>
                                    </div>
                                    <div class="text-right">
                                        @if($course->discount_price)
                                            <span class="text-gray-500 line-through text-sm">{{ number_format($course->price, 2) }} ₺</span>
                                            <div class="text-[#e63946] font-bold text-xl">{{ number_format($course->discount_price, 2) }} ₺</div>
                                        @else
                                            <div class="text-[#1a2e5a] font-bold text-xl">{{ number_format($course->price, 2) }} ₺</div>
                                        @endif
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 mb-4">{{ Str::limit($course->description, 200) }}</p>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <!-- Eğitmen -->
                                    @if($course->teacher)
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <div>
                                                <div class="text-xs text-gray-500">Eğitmen</div>
                                                <div class="font-medium">{{ $course->teacher->name }}</div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Toplam Saat -->
                                    @if($course->total_hours)
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div>
                                                <div class="text-xs text-gray-500">Süre</div>
                                                <div class="font-medium">{{ $course->total_hours }} Saat</div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Başlangıç Tarihi -->
                                    @if($course->start_date)
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <div>
                                                <div class="text-xs text-gray-500">Başlangıç</div>
                                                <div class="font-medium">{{ \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') }}</div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Kurs Sıklığı -->
                                    @if($course->courseFrequency)
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div>
                                                <div class="text-xs text-gray-500">Sıklık</div>
                                                <div class="font-medium">{{ $course->courseFrequency->name }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Ek özellikler (sertifika, kontenjan) -->
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @if($course->has_certificate)
                                        <span class="bg-gray-100 text-[#1a2e5a] text-xs font-medium px-2 py-1 rounded-full flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            Sertifikalı
                                        </span>
                                    @endif
                                    
                                    @if($course->max_students)
                                        <span class="bg-gray-100 text-[#1a2e5a] text-xs font-medium px-2 py-1 rounded-full flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            {{ $course->max_students }} Kişi Kontenjan
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="flex justify-end mt-4">
                                    <a href="{{ url('/egitimler/' . $course->slug) }}" class="bg-[#e63946] hover:bg-[#d32836] text-white px-6 py-2 rounded-lg transition-colors duration-300 font-medium">Detayları Gör</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 bg-white rounded-lg shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#1a2e5a] opacity-60 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-lg text-[#1a2e5a] font-medium">Seçilen kriterlere uygun eğitim bulunamadı.</p>
                        <p class="text-gray-500 mt-2">Lütfen farklı filtre seçenekleri deneyiniz.</p>
                        <button id="resetAllFilters" class="mt-4 bg-[#e63946] hover:bg-[#d32836] text-white px-6 py-2 rounded-lg transition-colors duration-300 font-medium">
                            Tüm Filtreleri Sıfırla
                        </button>
                    </div>
                    @endforelse
                </div>
                
                <!-- Sayfalama -->
                <div class="mt-8">
                    {{ $courses->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Öne Çıkan Eğitmenler Bölümü -->
<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-[#1a2e5a] mb-2">Öne Çıkan Eğitmenlerimiz</h2>
            <div class="w-20 h-1 bg-[#e63946] mx-auto"></div>
            <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Alanında uzman eğitmenlerimizle tanışın ve kaliteli eğitimin farkını yaşayın.</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredTeachers as $teacher)
            <div class="bg-gray-50 rounded-lg shadow-md overflow-hidden group hover:shadow-lg transition-shadow duration-300">
                <div class="h-56 bg-gray-200 relative overflow-hidden">
                    @if($teacher->profile_image)
                        <img src="{{ asset('storage/' . $teacher->profile_image) }}" alt="{{ $teacher->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="flex items-center justify-center h-full bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    @endif
                    
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                        <h3 class="text-white text-xl font-semibold">{{ $teacher->name }}</h3>
                        <p class="text-white text-opacity-90 text-sm">{{ $teacher->title }}</p>
                    </div>
                </div>
                
                <div class="p-4">
                    <p class="text-gray-600 mb-4 h-12 overflow-hidden">{{ Str::limit($teacher->bio, 80) }}</p>
                    <div class="flex justify-between items-center">
                        <div class="text-[#1a2e5a] font-medium">{{ $teacher->courses_count }} Eğitim</div>
                        <a href="#" class="text-[#e63946] font-medium hover:underline">Profili Gör</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Kayıt CTA Bölümü -->
<div class="bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto flex flex-col lg:flex-row items-center bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="lg:w-1/2 p-8 lg:p-12">
                <h2 class="text-3xl font-bold text-[#1a2e5a] mb-4">Eğitim Yolculuğunuza Bugün Başlayın</h2>
                <p class="text-gray-600 mb-6">Rise English ile profesyonel eğitmenlerimizin rehberliğinde dil becerilerinizi geliştirin ve kariyerinizde bir adım öne çıkın.</p>
                
                <ul class="space-y-3 mb-6">
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#e63946] mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Alanında uzman eğitmenlerle canlı dersler</span>
                    </li>
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#e63946] mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Tüm kurslarımızda sertifika imkanı</span>
                    </li>
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#e63946] mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Esnek ödeme seçenekleri ve taksit imkanları</span>
                    </li>
                </ul>
                
                <div class="space-x-4">
                    <a href="{{ url('/kayit-ol') }}" class="bg-[#e63946] hover:bg-[#d32836] text-white px-6 py-3 rounded-lg transition-colors duration-300 font-medium inline-block">
                        Hemen Üye Ol
                    </a>
                    <a href="{{ url('/iletisim') }}" class="text-[#1a2e5a] font-medium hover:text-[#e63946] transition-colors duration-300">
                        Bize Ulaşın
                    </a>
                </div>
            </div>
            
            <div class="lg:w-1/2 bg-[#1a2e5a] p-8 lg:p-12 text-white relative overflow-hidden">
                <!-- Dekoratif arka plan desenleri -->
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <defs>
                            <pattern id="grid-pattern" width="10" height="10" patternUnits="userSpaceOnUse">
                                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100" height="100" fill="url(#grid-pattern)" />
                    </svg>
                </div>
                
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold mb-4">İlk Kayıtta %20 İndirim</h3>
                    <p class="mb-6">Sınırlı bir süre için tüm yeni üyelere ilk eğitimlerinde %20 indirim sağlıyoruz.</p>
                    
                    <div class="bg-white/10 rounded-lg p-4 mb-6 backdrop-blur-sm">
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#e63946]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">Teklif Süresi Sınırlıdır!</span>
                        </div>
                        <div id="countdown" class="grid grid-cols-4 gap-2 text-center">
                            <div class="bg-white/20 rounded p-2">
                                <div id="days" class="text-2xl font-bold">00</div>
                                <div class="text-xs">Gün</div>
                            </div>
                            <div class="bg-white/20 rounded p-2">
                                <div id="hours" class="text-2xl font-bold">00</div>
                                <div class="text-xs">Saat</div>
                            </div>
                            <div class="bg-white/20 rounded p-2">
                                <div id="minutes" class="text-2xl font-bold">00</div>
                                <div class="text-xs">Dakika</div>
                            </div>
                            <div class="bg-white/20 rounded p-2">
                                <div id="seconds" class="text-2xl font-bold">00</div>
                                <div class="text-xs">Saniye</div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ url('/kayit-ol') }}" class="bg-white text-[#1a2e5a] hover:bg-gray-100 px-6 py-3 rounded-lg transition-colors duration-300 font-medium inline-block">
                        İndirimi Kullan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Görünüm değiştirme
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const coursesGridView = document.getElementById('coursesGridView');
    const coursesListView = document.getElementById('coursesListView');
    
    gridView.addEventListener('click', function() {
        gridView.classList.add('bg-[#1a2e5a]', 'text-white');
        gridView.classList.remove('bg-gray-200', 'text-gray-700');
        listView.classList.add('bg-gray-200', 'text-gray-700');
        listView.classList.remove('bg-[#1a2e5a]', 'text-white');
        
        coursesGridView.classList.remove('hidden');
        coursesListView.classList.add('hidden');
    });
    
    listView.addEventListener('click', function() {
        listView.classList.add('bg-[#1a2e5a]', 'text-white');
        listView.classList.remove('bg-gray-200', 'text-gray-700');
        gridView.classList.add('bg-gray-200', 'text-gray-700');
        gridView.classList.remove('bg-[#1a2e5a]', 'text-white');
        
        coursesListView.classList.remove('hidden');
        coursesGridView.classList.add('hidden');
    });
    
    // Fiyat range slider
    const priceRange = document.getElementById('priceRange');
    const minPriceValue = document.getElementById('minPriceValue');
    const maxPriceValue = document.getElementById('maxPriceValue');
    
    if (priceRange) {
        priceRange.addEventListener('input', function() {
            maxPriceValue.textContent = this.value + ' ₺';
        });
    }
    
    // Arama işlevi
    const searchButton = document.getElementById('searchButton');
    const courseSearch = document.getElementById('courseSearch');
    
    if (searchButton && courseSearch) {
        searchButton.addEventListener('click', function() {
            let currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('search', courseSearch.value);
            window.location.href = currentUrl.toString();
        });
        
        courseSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchButton.click();
            }
        });
    }
    
    // Filtre uygulama
    const applyFilters = document.getElementById('applyFilters');
    
    if (applyFilters) {
        applyFilters.addEventListener('click', function() {
            let currentUrl = new URL(window.location.href);
            
            // Kategoriler
            const courseTypes = document.querySelectorAll('input[name="course_type[]"]:checked');
            currentUrl.searchParams.delete('course_type');
            courseTypes.forEach(type => {
                currentUrl.searchParams.append('course_type', type.value);
            });
            
            // Seviyeler
            const courseLevels = document.querySelectorAll('input[name="course_level[]"]:checked');
            currentUrl.searchParams.delete('course_level');
            courseLevels.forEach(level => {
                currentUrl.searchParams.append('course_level', level.value);
            });
            
            // Durumlar
            const courseStatus = document.querySelectorAll('input[name="course_status[]"]:checked');
            currentUrl.searchParams.delete('course_status');
            courseStatus.forEach(status => {
                currentUrl.searchParams.append('course_status', status.value);
            });
            
            // Özellikler
            const features = document.querySelectorAll('input[name="features[]"]:checked');
            currentUrl.searchParams.delete('features');
            features.forEach(feature => {
                currentUrl.searchParams.append('features', feature.value);
            });
            
            // Fiyat aralığı
            const maxPrice = document.getElementById('priceRange').value;
            currentUrl.searchParams.set('max_price', maxPrice);
            
            // Sayfayı yenile
            window.location.href = currentUrl.toString();
        });
    }
    
    // Filtreleri temizleme
    const clearFilters = document.getElementById('clearFilters');
    const resetAllFilters = document.querySelectorAll('#resetAllFilters');
    
    if (clearFilters) {
        clearFilters.addEventListener('click', function() {
            let currentUrl = new URL(window.location.href);
            
            // Tüm arama parametrelerini temizle
            currentUrl.searchParams.delete('course_type');
            currentUrl.searchParams.delete('course_level');
            currentUrl.searchParams.delete('course_status');
            currentUrl.searchParams.delete('features');
            currentUrl.searchParams.delete('max_price');
            
            // Arama sorgusunu koru
            const searchQuery = currentUrl.searchParams.get('search');
            currentUrl.search = '';
            if (searchQuery) {
                currentUrl.searchParams.set('search', searchQuery);
            }
            
            // Sayfayı yenile
            window.location.href = currentUrl.toString();
        });
    }
    
    // Reset butonları için (birden fazla olabilir)
    if (resetAllFilters.length > 0) {
        resetAllFilters.forEach(button => {
            button.addEventListener('click', function() {
                let currentUrl = new URL(window.location.href);
                
                // Tüm arama parametrelerini temizle
                currentUrl.search = '';
                
                // Sayfayı yenile
                window.location.href = currentUrl.toString();
            });
        });
    }
    
    // Sıralama işlevi
    const sortCourses = document.getElementById('sortCourses');
    
    if (sortCourses) {
        // URL'den mevcut sıralama parametresini al
        const urlParams = new URLSearchParams(window.location.search);
        const sortParam = urlParams.get('sort');
        
        // Mevcut sıralama varsa, select elementini buna göre ayarla
        if (sortParam) {
            sortCourses.value = sortParam;
        }
        
        sortCourses.addEventListener('change', function() {
            let currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort', this.value);
            window.location.href = currentUrl.toString();
        });
    }
    
    // Geri sayım fonksiyonu
    function updateCountdown() {
        const endDate = new Date();
        endDate.setDate(endDate.getDate() + 7); // Örneğin, 7 gün sonra bitecek
        
        const now = new Date().getTime();
        const distance = endDate - now;
        
        // Hesaplamalar
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        // Sayaçları güncelle
        document.getElementById('days').innerHTML = days.toString().padStart(2, '0');
        document.getElementById('hours').innerHTML = hours.toString().padStart(2, '0');
        document.getElementById('minutes').innerHTML = minutes.toString().padStart(2, '0');
        document.getElementById('seconds').innerHTML = seconds.toString().padStart(2, '0');
    }
    
    // Sayaç elementlerini kontrol et
    if (document.getElementById('countdown')) {
        // Sayacı başlat
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
});
</script>
@endsection