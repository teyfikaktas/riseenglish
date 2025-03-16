<!-- resources/views/courses/detail.blade.php -->
@extends('layouts.app')

@section('content')
<!-- Başarı mesajı için ekleme yapıyoruz -->
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded fixed top-4 right-4 shadow-lg z-50">
        {{ session('success') }}
    </div>
@endif

<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Kurs Header Bölümü -->
            <div class="md:flex">
                <!-- Sol: Kurs Resmi -->
                <div class="md:w-1/3 relative h-64 md:h-auto">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    @endif

                    @if($course->discount_price)
                        <div class="absolute top-2 right-2 bg-[#e63946] text-white px-4 py-2 rounded-lg font-bold">
                            %{{ number_format((($course->price - $course->discount_price) / $course->price) * 100) }} İNDİRİM
                        </div>
                    @endif
                    
                    <!-- Etiketler ve durum -->
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                        <div class="flex flex-wrap gap-2">
                            @if($course->courseType)
                                <span class="bg-[#1a2e5a] text-white text-xs font-bold px-2 py-1 rounded">{{ $course->courseType->name }}</span>
                            @endif
                            @if($course->courseLevel)
                                <span class="bg-[#e63946] text-white text-xs font-bold px-2 py-1 rounded">{{ $course->courseLevel->name }}</span>
                            @endif
                            @if($course->category)
                                <span class="bg-[#44bd32] text-white text-xs font-bold px-2 py-1 rounded">{{ $course->category->name }}</span>
                            @endif
                            @if($course->has_certificate)
                                <span class="bg-[#0097e6] text-white text-xs font-bold px-2 py-1 rounded">Sertifikalı</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Sağ: Kurs Bilgileri -->
                <div class="md:w-2/3 p-6 md:p-8">
                    <div class="flex justify-between items-start">
                        <h1 class="text-3xl font-bold text-[#1a2e5a] mb-3">{{ $course->name }}</h1>
                        
                        <!-- Paylaşım butonları -->
                        <div class="flex space-x-2">
                            <button class="p-2 bg-blue-100 text-blue-600 rounded-full hover:bg-blue-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
                                </svg>
                            </button>
                            <button class="p-2 bg-blue-100 text-blue-400 rounded-full hover:bg-blue-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                </svg>
                            </button>
                            <button class="p-2 bg-blue-100 text-green-600 rounded-full hover:bg-blue-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Öğretmen bilgisi, değerlendirmeler vb. -->
                    <div class="flex items-center mb-4">
                        @if($course->teacher)
                            <div class="flex items-center mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-gray-700">Eğitmen: <span class="font-medium">{{ $course->teacher->name }}</span></span>
                            </div>
                        @endif

                        <!-- Değerlendirme yıldızları (örnek) -->
                        <div class="flex items-center">
                            <div class="flex items-center space-x-1 mr-1">
                                @for($i = 0; $i < 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $i < 4.5 ? 'text-yellow-400' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-gray-700 text-sm">(4.5/5)</span>
                        </div>
                    </div>
                    
                    <!-- Kısa açıklama -->
                    <p class="text-gray-600 mb-6">{{ $course->description }}</p>
                    
                    <!-- Kurs detayları -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <!-- Toplam saat -->
                        <div class="flex flex-col">
                            <span class="text-gray-500 text-sm">Toplam Süre</span>
                            <span class="font-medium text-[#1a2e5a]">{{ $course->total_hours ?? 'Belirtilmemiş' }} Saat</span>
                        </div>
                        
                        <!-- Kontenjan -->
                        <div class="flex flex-col">
                            <span class="text-gray-500 text-sm">Kontenjan</span>
                            <span class="font-medium text-[#1a2e5a]">{{ $course->max_students ?? 'Sınırsız' }} Kişi</span>
                        </div>
                        
                        <!-- Başlangıç tarihi -->
                        <div class="flex flex-col">
                            <span class="text-gray-500 text-sm">Başlangıç</span>
                            <span class="font-medium text-[#1a2e5a]">
                                @if($course->start_date)
                                    {{ \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') }}
                                @else
                                    Belirtilmemiş
                                @endif
                            </span>
                        </div>
                        
                        <!-- Sıklık -->
                        <div class="flex flex-col">
                            <span class="text-gray-500 text-sm">Eğitim Sıklığı</span>
                            <span class="font-medium text-[#1a2e5a]">
                                {{ $course->courseFrequency->name ?? 'Belirtilmemiş' }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Fiyat ve kayıt butonu -->
                    <div class="flex flex-col md:flex-row items-center justify-between mt-6 space-y-4 md:space-y-0">
                        <div>
                            @if($course->discount_price)
                                <span class="text-gray-500 line-through text-xl">{{ number_format($course->price, 2) }} ₺</span>
                                <span class="text-[#e63946] font-bold text-2xl ml-2">{{ number_format($course->discount_price, 2) }} ₺</span>
                            @else
                                <span class="text-[#1a2e5a] font-bold text-2xl">{{ number_format($course->price, 2) }} ₺</span>
                            @endif
                        </div>
                        <a href="{{ url('/kurs-kayit/' . $course->id) }}" class="bg-[#e63946] hover:bg-[#d32836] text-white px-8 py-3 rounded-lg transition-colors duration-300 font-bold text-lg shadow-md hover:shadow-lg w-full md:w-auto text-center">
                            Hemen Kaydol
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- İçerik ve detaylar (tabs) -->
            <div class="border-t border-gray-200 mt-6">
                <div x-data="{ activeTab: 'overview' }" class="px-6 md:px-8 py-6">
                    <!-- Tab butonları -->
                    <div class="flex overflow-x-auto space-x-4 border-b border-gray-200 mb-6 pb-2">
                        <button @click="activeTab = 'overview'" 
                            :class="{'border-b-2 border-[#1a2e5a] text-[#1a2e5a] font-bold': activeTab === 'overview', 'text-gray-500 hover:text-[#1a2e5a]': activeTab !== 'overview'}" 
                            class="py-2 px-1 transition-colors whitespace-nowrap">
                            Genel Bakış
                        </button>
                        <button @click="activeTab = 'objectives'" 
                            :class="{'border-b-2 border-[#1a2e5a] text-[#1a2e5a] font-bold': activeTab === 'objectives', 'text-gray-500 hover:text-[#1a2e5a]': activeTab !== 'objectives'}" 
                            class="py-2 px-1 transition-colors whitespace-nowrap">
                            Kazanımlar
                        </button>
                        <button @click="activeTab = 'location'" 
                            :class="{'border-b-2 border-[#1a2e5a] text-[#1a2e5a] font-bold': activeTab === 'location', 'text-gray-500 hover:text-[#1a2e5a]': activeTab !== 'location'}" 
                            class="py-2 px-1 transition-colors whitespace-nowrap">
                            Konum ve Erişim
                        </button>
                        <button @click="activeTab = 'instructor'" 
                            :class="{'border-b-2 border-[#1a2e5a] text-[#1a2e5a] font-bold': activeTab === 'instructor', 'text-gray-500 hover:text-[#1a2e5a]': activeTab !== 'instructor'}" 
                            class="py-2 px-1 transition-colors whitespace-nowrap">
                            Eğitmen Hakkında
                        </button>
                    </div>
                    
                    <!-- Tab içerikleri -->
                    <div>
                        <!-- Genel Bakış -->
                        <div x-show="activeTab === 'overview'" class="prose max-w-none">
                            <h3 class="text-xl font-semibold mb-4">Kurs Açıklaması</h3>
                            <div class="text-gray-700">
                                {!! nl2br(e($course->description)) !!}
                            </div>
                            
                            <div class="mt-8">
                                <h3 class="text-xl font-semibold mb-4">Eğitim Takvimi</h3>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @if($course->start_date && $course->end_date)
                                            <div>
                                                <div class="flex items-center mb-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="text-gray-700 font-medium">Eğitim Tarihleri</span>
                                                </div>
                                                <p class="text-gray-600 ml-7">
                                                    {{ \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') }} - 
                                                    {{ \Carbon\Carbon::parse($course->end_date)->format('d.m.Y') }}
                                                    <span class="text-sm text-gray-500 block">
                                                        ({{ \Carbon\Carbon::parse($course->start_date)->diffInDays(\Carbon\Carbon::parse($course->end_date)) + 1 }} gün)
                                                    </span>
                                                </p>
                                            </div>
                                        @endif
                                        
@if($course->start_time && $course->end_time)
    <div>
        <div class="flex items-center mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-gray-700 font-medium">Eğitim Saatleri</span>
        </div>
        <p class="text-gray-600 ml-7">
            {{ \Carbon\Carbon::parse($course->start_time)->format('H:i') }} - 
            {{ \Carbon\Carbon::parse($course->end_time)->format('H:i') }}
        </p>
    </div>
@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kazanımlar -->
                        <div x-show="activeTab === 'objectives'" class="prose max-w-none">
                            <h3 class="text-xl font-semibold mb-4">Kurs Kazanımları</h3>
                            @if($course->objectives)
                                <div class="text-gray-700">
                                    {!! nl2br(e($course->objectives)) !!}
                                </div>
                            @else
                                <div class="text-gray-500 italic">
                                    Kurs kazanımları henüz belirtilmemiştir.
                                </div>
                            @endif
                            
                            <!-- Yetenek ve sertifika bilgisi (örnek) -->
                            <div class="mt-8">
                                <h3 class="text-xl font-semibold mb-4">Kazanacağınız Yetenekler</h3>
                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">İngilizce Konuşma</span>
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">İngilizce Yazma</span>
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">İngilizce Dinleme</span>
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">İngilizce Okuma</span>
                                </div>
                            </div>
                            
                            @if($course->has_certificate)
                                <div class="mt-8 bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <span class="text-green-700 font-medium">Sertifika Bilgisi</span>
                                    </div>
                                    <p class="text-green-700 ml-8">
                                        Bu eğitimi başarıyla tamamladığınızda Rise English tarafından onaylı sertifika almaya hak kazanacaksınız.
                                    </p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Konum ve Erişim -->
                        <div x-show="activeTab === 'location'" class="prose max-w-none">
                            <h3 class="text-xl font-semibold mb-4">Eğitim Konumu ve Erişim Bilgileri</h3>
                            
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                @if($course->location)
                                    <div class="mb-6">
                                        <div class="flex items-center mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="text-gray-700 font-medium">Fiziksel Konum</span>
                                        </div>
                                        <p class="text-gray-600 ml-7">
                                            {{ $course->location }}
                                        </p>
                                        
                                        <!-- Harita (örnek) -->
                                        <div class="mt-4 bg-gray-200 w-full h-64 rounded-lg flex items-center justify-center">
                                            <span class="text-gray-500">Harita yükleniyor...</span>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($course->meeting_link)
                                    <div>
                                        <div class="flex items-center mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-gray-700 font-medium">Online Erişim</span>
                                        </div>
                                        <div class="ml-7">
                                            <p class="text-gray-600 mb-2">
                                                Eğitim online olarak gerçekleştirilecektir. Kayıt olduktan sonra toplantı bağlantısı ve şifreniz size e-posta ile gönderilecektir.
                                            </p>

                                            <div class="mt-4">
                                                <a href="{{ $course->meeting_link }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-[#1a2e5a] rounded-lg text-[#1a2e5a] bg-white hover:bg-[#1a2e5a] hover:text-white transition-colors duration-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                    Toplantı Bağlantısına Git
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Eğitmen Hakkında -->
                        <div x-show="activeTab === 'instructor'" class="prose max-w-none">
                            @if($course->teacher)
                                <h3 class="text-xl font-semibold mb-4">Eğitmen Bilgisi</h3>
                                <div class="flex flex-col md:flex-row md:items-start bg-gray-50 rounded-lg p-6 border border-gray-200">
                                    <!-- Eğitmen fotoğrafı -->
                                    <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                                        <div class="w-24 h-24 bg-[#1a2e5a] text-white rounded-full flex items-center justify-center text-2xl font-bold">
                                            {{ substr($course->teacher->name, 0, 1) }}
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h4 class="text-xl font-semibold text-[#1a2e5a]">{{ $course->teacher->name }}</h4>
                                        <p class="text-gray-600 mb-4">İngilizce Eğitmeni</p>
                                        
                                        <div class="text-gray-700 mb-4">
                                            <p>{{ $course->teacher->biography ?? 'Eğitmen hakkında bilgi bulunmamaktadır.' }}</p>
                                        </div>
                                        
                                        <!-- Sosyal medya bağlantıları -->
                                        <div class="flex space-x-3">
                                            <a href="#" class="text-blue-600 hover:text-blue-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
                                                </svg>
                                            </a>
                                            <a href="#" class="text-blue-400 hover:text-blue-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                                </svg>
                                            </a>
                                            <a href="#" class="text-blue-700 hover:text-blue-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-gray-500 italic">
                                    Bu kurs için eğitmen bilgisi bulunmamaktadır.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Benzer Kurslar Bölümü -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-[#1a2e5a] mb-6">Beğenebileceğiniz Diğer Kurslar</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($similarCourses as $similarCourse)
                <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-2 group">
                    <div class="h-48 bg-gray-200 relative overflow-hidden">
                        @if($similarCourse->thumbnail)
                            <img src="{{ asset('storage/' . $similarCourse->thumbnail) }}" alt="{{ $similarCourse->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="flex items-center justify-center h-full bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        @endif
                        @if($similarCourse->discount_price)
                            <div class="absolute top-2 right-2 bg-[#e63946] text-white px-3 py-1 rounded-full font-bold text-sm">
                                %{{ number_format((($similarCourse->price - $similarCourse->discount_price) / $similarCourse->price) * 100) }} İNDİRİM
                            </div>
                        @endif
                        
                        <!-- Kurs tipi ve seviye etiketi -->
                        <div class="absolute bottom-2 left-2 flex space-x-2">
                            @if($similarCourse->courseType)
                                <span class="bg-[#1a2e5a] text-white text-xs font-bold px-2 py-1 rounded">{{ $similarCourse->courseType->name }}</span>
                            @endif
                            @if($similarCourse->courseLevel)
                                <span class="bg-[#e63946] text-white text-xs font-bold px-2 py-1 rounded">{{ $similarCourse->courseLevel->name }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <h3 class="text-lg font-semibold mb-2 text-[#1a2e5a]">{{ $similarCourse->name }}</h3>
                        <p class="text-gray-600 mb-4 text-sm h-12 overflow-hidden">{{ Str::limit($similarCourse->description, 80) }}</p>
                        
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            @if($similarCourse->teacher)
                                <div class="flex items-center mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $similarCourse->teacher->name }}
                                </div>
                            @endif
                            
                            @if($similarCourse->total_hours)
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $similarCourse->total_hours }} Saat
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div>
                                @if($similarCourse->discount_price)
                                    <span class="text-gray-500 line-through text-sm">{{ number_format($similarCourse->price, 2) }} ₺</span>
                                    <span class="text-[#e63946] font-bold ml-2">{{ number_format($similarCourse->discount_price, 2) }} ₺</span>
                                @else
                                    <span class="text-[#1a2e5a] font-bold">{{ number_format($similarCourse->price, 2) }} ₺</span>
                                @endif
                            </div>
                            <a href="{{ url('/egitimler/' . $similarCourse->slug) }}" class="bg-[#e63946] hover:bg-[#d32836] text-white px-3 py-1 rounded-lg transition-colors duration-300 text-sm">Detayları Gör</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Testimonial/Yorumlar -->
        <div class="mt-16 bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-[#1a2e5a] mb-6">Öğrenci Yorumları</h2>
            
            <!-- Yorum Ekle Formu -->
            <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Yorum Yap</h3>
                
                @auth
                    <form action="{{ route('course.review', $course->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Puanınız</label>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" class="hidden peer">
                                    <label for="star{{ $i }}" class="cursor-pointer p-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="comment" class="block text-gray-700 mb-2">Yorumunuz</label>
                            <textarea id="comment" name="comment" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1a2e5a]" placeholder="Bu kurs hakkında düşüncelerinizi paylaşın..."></textarea>
                        </div>
                        
                        <button type="submit" class="bg-[#1a2e5a] text-white px-6 py-2 rounded-lg hover:bg-[#15243f] transition-colors">
                            Yorum Yap
                        </button>
                    </form>
                @else
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-blue-700">Yorum yapabilmek için <a href="{{ route('login') }}" class="font-bold underline">giriş yapmalısınız</a>.</p>
                    </div>
                @endauth
            </div>
            
          <!-- Yorumlar Listesi -->
<div class="space-y-6">
    <!-- Örnek yorumlar (gerçek veriden gelecek) -->
    <div class="border-b border-gray-200 pb-6">
        <div class="flex justify-between mb-2">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-[#1a2e5a] text-white rounded-full flex items-center justify-center text-lg font-bold mr-3">
                    A
                </div>
                <div>
                    <h4 class="font-semibold">Ahmet Yılmaz</h4>
                    <div class="flex items-center text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                </div>
            </div>
            <span class="text-gray-500 text-sm">23.02.2023</span>
        </div>
        <p class="text-gray-700">Harika bir eğitimdi. Öğretmen çok sabırlı ve konulara hakimdi. Kesinlikle tavsiye ederim!</p>
    </div>
    
    <div class="border-b border-gray-200 pb-6">
        <div class="flex justify-between mb-2">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-[#e63946] text-white rounded-full flex items-center justify-center text-lg font-bold mr-3">
                    M
                </div>
                <div>
                    <h4 class="font-semibold">Merve Demir</h4>
                    <div class="flex items-center text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                </div>
            </div>
            <span class="text-gray-500 text-sm">15.01.2023</span>
        </div>
        <p class="text-gray-700">İyi bir kurstu, ancak daha fazla pratik yapma imkanı olabilirdi. Yine de İngilizcemi geliştirmeme çok yardımcı oldu.</p>
    </div>
    
    <div class="border-b border-gray-200 pb-6">
        <div class="flex justify-between mb-2">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-[#0097e6] text-white rounded-full flex items-center justify-center text-lg font-bold mr-3">
                    B
                </div>
                <div>
                    <h4 class="font-semibold">Burak Kaya</h4>
                    <div class="flex items-center text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                </div>
            </div>
            <span class="text-gray-500 text-sm">5.12.2022</span>
        </div>
        <p class="text-gray-700">Mükemmel bir deneyimdi! Eğitmen çok profesyonel ve samimiydi. Dersler son derece akıcı ve öğretici geçti. Her seviyeye uygun içerikler sunuldu. İngilizce konuşma pratiği için çok faydalı oldu. Teşekkürler Rise English!</p>
    </div>
</div>

<!-- Daha Fazla Yorum Butonu -->
<div class="mt-6 text-center">
    <button class="text-[#1a2e5a] font-medium hover:underline">
        Daha Fazla Yorum Göster
    </button>
</div>
</div>
</div>
</div>
</div>

@endsection

@section('scripts')
<script>
    // Bu kısımda Alpine.js veya başka bir JavaScript kütüphanesi kullanılarak sekmeler ve diğer etkileşimli öğeler yönetilebilir
    document.addEventListener('DOMContentLoaded', function() {
        // Yorumlar bölümü için "Daha Fazla Yorum Göster" butonunun işlevselliği
        const moreCommentsButton = document.querySelector('.mt-6.text-center button');
        if (moreCommentsButton) {
            moreCommentsButton.addEventListener('click', function() {
                // AJAX ile daha fazla yorum yükleme işlemi burada yapılabilir
                alert('Daha fazla yorum yükleme işlevi henüz eklenmedi.');
            });
        }
        
        // Diğer JavaScript işlevleri buraya eklenebilir
    });
</script>
@endsection