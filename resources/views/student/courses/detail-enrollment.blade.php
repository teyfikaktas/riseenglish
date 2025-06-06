<!-- resources/views/student/courses/detail.blade.php -->
@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="container mx-auto px-4">
        <!-- Başarı ve Hata mesajları -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 shadow-sm">
                {{ session('error') }}
            </div>
        @endif
        
        <!-- Üst Bölüm: Kurs Başlığı ve Temel Bilgiler -->
        <div class="bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="flex flex-col md:flex-row p-6">
                <!-- Sol taraf: Kurs resmi -->
                <div class="w-full md:w-1/4 mb-6 md:mb-0">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}" class="w-full h-60 object-cover rounded-lg shadow-md">
                    @else
                        <div class="w-full h-60 bg-gray-300 rounded-lg shadow-md flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    @endif
                </div>
                
            <!-- Sağ taraf: Kurs bilgileri -->
            <div class="w-full md:w-3/4 md:pl-8 text-white relative">
                <!-- Arkaplan efekti (mor renkler kaldırıldı) -->
                <div class="absolute -top-16 -right-16 w-64 h-64 bg-blue-500 rounded-full opacity-20 blur-xl"></div>
                <div class="absolute bottom-16 left-16 w-48 h-48 bg-blue-500 rounded-full opacity-20 blur-xl"></div>
                
                <!-- Kurs başlığı -->
                <h1 class="text-5xl font-black mb-6 text-white relative z-10">
                    {{ $course->name }}
                    <div class="h-1 w-24 bg-red-500 rounded-full mt-3"></div>
                </h1>
                
                <!-- Kurs açıklaması -->
                <div class="mb-8 p-6 bg-blue-900/30 rounded-xl backdrop-blur-md border-l-4 border-red-500 shadow-lg relative z-10">
                    <p class="text-blue-50 leading-relaxed">{{ $course->description }}</p>
                </div>
                
                <!-- Bilgi kartları -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 relative z-10">
                    <!-- Temel bilgiler -->
                    <div class="bg-blue-900/50 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-500 hover:-translate-y-2 group overflow-hidden relative">
                        <!-- Decorative elements (mor renk kaldırıldı) -->
                        <div class="absolute -top-12 -right-12 w-24 h-24 bg-red-500/20 rounded-full blur-xl group-hover:bg-red-500/30 transition-all duration-500"></div>
                        
                        <h3 class="text-xl font-bold mb-4 flex items-center text-white group-hover:text-red-400 transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-red-500 group-hover:text-red-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Kurs Bilgileri
                        </h3>
                        <div class="space-y-4">
                            @if($course->courseType)
                                <div class="flex items-center">
                                    <span class="w-28 text-blue-200 font-medium">Tür:</span>
                                    <span class="text-white bg-blue-600/40 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-medium shadow-inner border border-blue-400/20">{{ $course->courseType->name }}</span>
                                </div>
                            @endif
                            
                            @if($course->courseLevel)
                                <div class="flex items-center">
                                    <span class="w-28 text-blue-200 font-medium">Seviye:</span>
                                    <span class="text-white bg-blue-600/40 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-medium shadow-inner border border-blue-400/20">{{ $course->courseLevel->name }}</span>
                                </div>
                            @endif
                            
                            @if($course->courseFrequency)
                                <div class="flex items-center">
                                    <span class="w-28 text-blue-200 font-medium">Sıklık:</span>
                                    <span class="text-white bg-blue-600/40 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-medium shadow-inner border border-blue-400/20">{{ $course->courseFrequency->name }}</span>
                                </div>
                            @endif
                            
                            @if($course->total_hours)
                                <div class="flex items-center">
                                    <span class="w-28 text-blue-200 font-medium">Toplam Süre:</span>
                                    <span class="text-white bg-blue-600/40 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-medium shadow-inner border border-blue-400/20">{{ $course->total_hours }} Saat</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Tarihler -->
                    <div class="bg-blue-900/50 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-500 hover:-translate-y-2 group overflow-hidden relative">
                        <!-- Decorative elements (mor renk kaldırıldı) -->
                        <div class="absolute -top-12 -right-12 w-24 h-24 bg-red-500/20 rounded-full blur-xl group-hover:bg-red-500/30 transition-all duration-500"></div>
                        
                        <h3 class="text-xl font-bold mb-4 flex items-center text-white group-hover:text-red-400 transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-red-500 group-hover:text-red-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Tarih Bilgileri
                        </h3>
                        <div class="space-y-4">
                            @if($course->start_date)
                                <div class="flex items-center">
                                    <span class="w-28 text-blue-200 font-medium">Başlangıç:</span>
                                    <span class="text-white bg-blue-600/40 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-medium shadow-inner border border-blue-400/20">{{ \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') }}</span>
                                </div>
                            @endif
                            
                            @if($course->end_date)
                                <div class="flex items-center">
                                    <span class="w-28 text-blue-200 font-medium">Bitiş:</span>
                                    <span class="text-white bg-blue-600/40 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-medium shadow-inner border border-blue-400/20">{{ \Carbon\Carbon::parse($course->end_date)->format('d.m.Y') }}</span>
                                </div>
                            @endif
                            
                            @if($course->start_time)
                                <div class="flex items-center">
                                    <span class="w-28 text-blue-200 font-medium">Ders Saati:</span>
                                    <span class="text-white bg-blue-600/40 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-medium shadow-inner border border-blue-400/20">
                                        {{ \Carbon\Carbon::parse($course->start_time)->format('H:i') }}
                                        @if($course->end_time)
                                            - {{ \Carbon\Carbon::parse($course->end_time)->format('H:i') }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Öğretmen ve Toplam Öğrenci Bilgisi -->
                    <div class="bg-blue-900/50 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-500 hover:-translate-y-2 group overflow-hidden relative">
                        <!-- Decorative elements (mor renk kaldırıldı) -->
                        <div class="absolute -top-12 -right-12 w-24 h-24 bg-red-500/20 rounded-full blur-xl group-hover:bg-red-500/30 transition-all duration-500"></div>
                        
                        <h3 class="text-xl font-bold mb-4 flex items-center text-white group-hover:text-red-400 transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-red-500 group-hover:text-red-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Sınıf Bilgileri
                        </h3>
                        <div class="space-y-4">
                            @if($course->teacher)
                                <div class="flex items-center">
                                    <span class="w-28 text-blue-200 font-medium">Eğitmen:</span>
                                    <span class="text-white bg-blue-600/40 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-medium shadow-inner border border-blue-400/20">{{ $course->teacher->name }}</span>
                                </div>
                            @endif
                            
                            @if($course->max_students)
                                <div class="flex items-center">
                                    <span class="w-28 text-blue-200 font-medium">Kontenjan:</span>
                                    <div class="flex flex-col w-full">
                                        <span class="text-white bg-blue-600/40 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-medium shadow-inner border border-blue-400/20 mb-2">{{ $course->students->count() }} / {{ $course->max_students }} Kişi</span>
                                        <div class="w-full bg-blue-900/40 backdrop-blur-sm rounded-full h-2 overflow-hidden border border-blue-400/20">
                                            <div class="bg-red-500 h-2 rounded-full transform transition-all duration-500 ease-out" style="width: {{ ($course->students->count() / $course->max_students) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($course->has_certificate)
                                <div class="flex items-center">
                                    <span class="w-28 text-blue-200 font-medium">Sertifika:</span>
                                    <span class="text-white bg-green-600/40 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-medium shadow-inner border border-green-400/20 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Var
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Zoom bağlantısı -->
                @if($course->meeting_link)
                    <div class="mt-10 relative z-10">
                        <a href="{{ $course->meeting_link }}" target="_blank" class="group relative inline-flex items-center justify-center overflow-hidden rounded-xl bg-red-600 p-0.5 font-bold text-white shadow-lg">
                            <span class="relative rounded-xl bg-red-700 backdrop-blur-sm px-8 py-4 transition-all duration-300 ease-out group-hover:bg-red-800">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 group-hover:animate-pulse transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Derse Katıl (Zoom)
                                </span>
                            </span>
                        </a>
                    </div>
                @endif
            </div>
            </div>
        </div>
        
        <!-- Ana İçerik Bölümü - Tab Sistemi -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Tab Başlıkları -->
            <div class="flex border-b border-gray-200 overflow-x-auto">
                <button id="tab-announcements" class="tab-button whitespace-nowrap px-6 py-4 font-medium border-b-2 border-[#1a2e5a] text-[#1a2e5a]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    Duyurular
                </button>
                <button id="tab-homework" class="tab-button whitespace-nowrap px-6 py-4 font-medium text-gray-500 hover:text-[#1a2e5a]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Ödevler
                </button>

            </div>
            <!-- Tab İçerikleri -->
<div class="p-6">
    <!-- Duyurular Tab İçeriği -->
    <div id="content-announcements" class="tab-content">
        <h2 class="text-2xl font-bold text-[#1a2e5a] mb-6">Kurs Duyuruları</h2>
        
        @if(count($announcements) > 0)
        <div class="space-y-6">
            @foreach($announcements as $announcement)
                <div class="bg-gray-50 rounded-lg p-5 shadow-sm border-l-4 border-[#1a2e5a]">
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-semibold text-[#1a2e5a]">{{ $announcement->title }}</h3>
                        <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($announcement->created_at)->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="mt-3 text-gray-700">
                        {{ $announcement->content }}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 p-8 rounded-lg text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <p class="text-gray-600 font-medium">Henüz duyuru bulunmamaktadır.</p>
            <p class="text-sm text-gray-500 mt-2">Kursunuzla ilgili duyurular burada görünecektir.</p>
        </div>
    @endif
    </div>
    
    <!-- Ödevler Tab İçeriği -->
    <div id="content-homework" class="tab-content hidden">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-[#1a2e5a]">Kurs Ödevleri</h2>
            
        </div>
        
        @if(count($homeworks) > 0)
            <div class="space-y-6">
                @foreach($homeworks as $homework)
                    <div class="bg-gray-50 rounded-lg p-5 shadow-sm border-l-4 
                        @if($homework['status'] == 'Tamamlandı')
                            border-green-500
                        @elseif(\Carbon\Carbon::parse($homework['due_date'])->isPast())
                            border-red-500
                        @else
                            border-yellow-500
                        @endif
                    ">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-semibold text-[#1a2e5a]">{{ $homework['title'] }}</h3>
                            <span class="
                                @if($homework['status'] == 'Tamamlandı')
                                    bg-green-100 text-green-700
                                @elseif(\Carbon\Carbon::parse($homework['due_date'])->isPast())
                                    bg-red-100 text-red-700
                                @else
                                    bg-yellow-100 text-yellow-700
                                @endif
                                px-3 py-1 rounded-full text-xs font-medium">
                                @if($homework['status'] == 'Tamamlandı')
                                    Tamamlandı
                                @elseif(\Carbon\Carbon::parse($homework['due_date'])->isPast())
                                    Süresi Doldu
                                @else
                                    Bekleniyor
                                @endif
                            </span>
                        </div>
                        <div class="mt-3 text-gray-700">
                            {{ $homework['description'] }}
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                <span class="font-medium">Son Tarih:</span>
                                {{ \Carbon\Carbon::parse($homework['due_date'])->format('d.m.Y H:i') }}
                            </div>
                            
                            @if($homework['status'] != 'Tamamlandı')
                                <button class="submit-homework-btn bg-[#e63946] hover:bg-[#d32836] text-white px-4 py-2 rounded-md inline-flex items-center text-sm font-medium transition-colors duration-300" data-homework-id="{{ $homework['id'] }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    Ödevi Yükle
                                </button>
                            @else
                                <button class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md inline-flex items-center text-sm font-medium cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Tamamlandı
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 p-8 rounded-lg text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-gray-600">Henüz ödev bulunmamaktadır.</p>
            </div>
        @endif
    </div>
    
<!-- Geçmiş Ödevlerim Tab İçeriği -->
<div id="content-past-homework" class="tab-content hidden">
    <h2 class="text-2xl font-bold text-[#1a2e5a] mb-6">Geçmiş Ödevlerim</h2>
    
    @if(count($pastHomeworks) > 0)
    <div class="space-y-6">
        @foreach($pastHomeworks as $homework)
            <div class="bg-gray-50 rounded-lg p-5 shadow-sm border-l-4 
                @if($homework['status'] == 'Değerlendirildi')
                    border-green-500
                @else
                    border-yellow-500
                @endif
            ">
                <div class="flex justify-between items-start">
                    <h3 class="text-lg font-semibold text-[#1a2e5a]">{{ $homework['title'] }}</h3>
                    <span class="
                        @if($homework['status'] == 'Değerlendirildi')
                            bg-green-100 text-green-700
                        @else
                            bg-yellow-100 text-yellow-700
                        @endif
                        px-3 py-1 rounded-full text-xs font-medium">
                        {{ $homework['status'] }}
                    </span>
                </div>
                <div class="mt-3 text-gray-700">
                    <p>{{ $homework['description'] }}</p>
                </div>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1"><span class="font-medium">Yükleme Tarihi:</span> 
                            {{ \Carbon\Carbon::parse($homework['submission_date'])->format('d.m.Y H:i') }}
                        </p>
                        <p class="text-sm text-gray-500 mb-1"><span class="font-medium">Son Teslim Tarihi:</span> 
                            {{ \Carbon\Carbon::parse($homework['due_date'])->format('d.m.Y H:i') }}
                        </p>
                        <p class="text-sm text-gray-500"><span class="font-medium">Puan:</span> 
                            @if($homework['score'])
                                <span class="text-green-600 font-semibold">{{ $homework['score'] }}/{{ $homework['max_score'] }}</span>
                            @else
                                <span class="text-yellow-600 font-semibold">Değerlendiriliyor</span>
                            @endif
                        </p>
                    </div>
                    <div class="flex justify-end items-end space-x-2">
                        <button class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1.5 rounded-md inline-flex items-center text-sm font-medium transition-colors duration-300 view-homework-btn" data-homework-id="{{ $homework['id'] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Görüntüle
                        </button>
                        @if($homework['file_path'])
                        <a href="{{ asset('storage/' . $homework['file_path']) }}" target="_blank" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1.5 rounded-md inline-flex items-center text-sm font-medium transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            İndir
                        </a>
                        @endif
                    </div>
                </div>
                
                @if($homework['feedback'])
                <!-- Hoca Geri Bildirimi -->
                <div class="mt-5 border-t border-gray-200 pt-4">
                    <h4 class="font-medium text-blue-800 mb-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        Hoca Geri Bildirimi:
                    </h4>
                    <div class="bg-blue-50 p-4 rounded-lg text-gray-700">
                        <p>{{ $homework['feedback'] }}</p>
                    </div>
                    @if($homework['graded_at'])
                    <div class="mt-2 text-sm text-gray-500 text-right">
                        <span class="font-medium">Değerlendirme Tarihi:</span> 
                        {{ \Carbon\Carbon::parse($homework['graded_at'])->format('d.m.Y H:i') }}
                    </div>
                    @endif
                </div>
                @endif
            </div>
        @endforeach
    </div>
    @else
    <div class="bg-gray-50 p-8 rounded-lg text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <p class="text-gray-600 font-medium">Henüz tamamladığınız ödev bulunmamaktadır.</p>
        <p class="text-sm text-gray-500 mt-2">Tamamlanan ödevleriniz burada listelenecektir.</p>
    </div>
    @endif
</div>
    

    

</div>
<div id="modal-overlay" class="fixed inset-0 bg-gray-700 bg-opacity-30 backdrop-blur-sm z-50 hidden items-center justify-center transition-all duration-300">
    <div id="modal-container" class="w-full max-w-5xl transform scale-95 transition-all duration-300 ease-in-out flex flex-col">
        <div class="bg-white rounded-t-xl shadow-2xl border border-gray-200">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h3 id="modal-title" class="text-2xl font-bold text-[#1a2e5a]"></h3>
                <button id="modal-close" class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 p-2 rounded-full focus:outline-none transition-colors duration-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modal-content" class="p-8 max-h-[70vh] overflow-y-auto bg-white rounded-b-xl"></div>
        </div>
    </div>
</div>

<!-- Ödev Ekleme Modal'ı -->
<div id="homeworkModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-[#1a2e5a]">Yeni Ödev Ekle</h3>
                <button id="closeHomeworkModal" class="text-gray-400 hover:text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        

    </div>
</div>

<!-- Ödev Yükleme Modal'ı -->
<div id="submitHomeworkModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-xl">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-[#1a2e5a]">Ödev Yükle</h3>
                <button id="closeSubmitModal" class="text-gray-400 hover:text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        
        <form action="{{ route('ogrenci.odev-yukle', ['slug' => $course->slug, 'homeworkId' => 0]) }}" method="POST" enctype="multipart/form-data" id="submitHomeworkForm">            @csrf
            <input type="hidden" id="homeworkId" name="homework_id" value="">
            
            <div class="p-6">
                <div class="mb-4">
                    <label for="submit_comment" class="block text-sm font-medium text-gray-700 mb-1">Açıklama (İsteğe Bağlı)</label>
                    <textarea id="submit_comment" name="comment" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1a2e5a]"></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="submit_file" class="block text-sm font-medium text-gray-700 mb-1">Ödev Dosyası</label>
                    <input type="file" id="submit_file" name="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1a2e5a]" required>
                    <p class="mt-1 text-sm text-gray-500">PDF, Word, Excel veya görsel dosyaları yükleyebilirsiniz (Max: 10MB)</p>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 rounded-b-lg text-right">
                <button type="button" id="cancelSubmitBtn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md mr-2 hover:bg-gray-300 transition-colors duration-300">İptal</button>
                <button type="submit" class="px-4 py-2 bg-[#e63946] text-white rounded-md hover:bg-[#d32836] transition-colors duration-300">Ödevi Yükle</button>
            </div>
        </form>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab işlevselliği
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Tüm butonları deaktif yap
            tabButtons.forEach(btn => {
                btn.classList.remove('border-b-2', 'border-[#1a2e5a]', 'text-[#1a2e5a]');
                btn.classList.add('text-gray-500');
            });
            
            // Tıklanan butonu aktif yap
            this.classList.add('border-b-2', 'border-[#1a2e5a]', 'text-[#1a2e5a]');
            this.classList.remove('text-gray-500');
            
            // Tüm içerikleri gizle
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // İlgili içeriği göster
            const tabId = this.id.replace('tab-', 'content-');
            document.getElementById(tabId).classList.remove('hidden');
        });
    });
    
    // Ödev ekleme modal işlevselliği
    const addHomeworkBtn = document.getElementById('addHomeworkBtn');
    const homeworkModal = document.getElementById('homeworkModal');
    const closeHomeworkModal = document.getElementById('closeHomeworkModal');
    const cancelHomeworkBtn = document.getElementById('cancelHomeworkBtn');
    if (addHomeworkBtn && homeworkModal && closeHomeworkModal) {
        addHomeworkBtn.addEventListener('click', function() {
            homeworkModal.classList.remove('hidden');
        });
        
        closeHomeworkModal.addEventListener('click', function() {
            homeworkModal.classList.add('hidden');
        });
        
        cancelHomeworkBtn.addEventListener('click', function() {
            homeworkModal.classList.add('hidden');
        });
    }
    
    // Ödev yükleme modal işlevselliği
// Ödev yükleme modal işlevselliği
const submitButtons = document.querySelectorAll('.submit-homework-btn');
const submitHomeworkModal = document.getElementById('submitHomeworkModal');
const closeSubmitModal = document.getElementById('closeSubmitModal');
const cancelSubmitBtn = document.getElementById('cancelSubmitBtn');
const homeworkIdInput = document.getElementById('homeworkId');
const submitHomeworkForm = document.getElementById('submitHomeworkForm');

if (submitButtons.length > 0 && submitHomeworkModal) {
    submitButtons.forEach(button => {
        button.addEventListener('click', function() {
            const homeworkId = this.getAttribute('data-homework-id');
            homeworkIdInput.value = homeworkId;
            
            // Form action URL'ini güncelle
            const formAction = submitHomeworkForm.action;
            // homeworkId kısmını URL'de güncelle
            const newAction = formAction.replace(/\/odev-yukle\/[^\/]*$/, '/odev-yukle/' + homeworkId);
            submitHomeworkForm.action = newAction;
            
            submitHomeworkModal.classList.remove('hidden');
        });
    });
    
    closeSubmitModal.addEventListener('click', function() {
        submitHomeworkModal.classList.add('hidden');
    });
    
    cancelSubmitBtn.addEventListener('click', function() {
        submitHomeworkModal.classList.add('hidden');
    });
}
    
    // Hoca yanıtına yanıt verme modal işlevselliği
    const responseButtons = document.querySelectorAll('.reply-to-teacher');
    const teacherResponseModal = document.getElementById('teacherResponseModal');
    const closeResponseModal = document.getElementById('closeResponseModal');
    const cancelResponseBtn = document.getElementById('cancelResponseBtn');
    const feedbackIdInput = document.getElementById('feedbackId');
    
    if (responseButtons.length > 0 && teacherResponseModal) {
        responseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const feedbackId = this.getAttribute('data-feedback-id');
                feedbackIdInput.value = feedbackId;
                teacherResponseModal.classList.remove('hidden');
            });
        });
        
        closeResponseModal.addEventListener('click', function() {
            teacherResponseModal.classList.add('hidden');
        });
        
        cancelResponseBtn.addEventListener('click', function() {
            teacherResponseModal.classList.add('hidden');
        });
    }
    
    // Görüntüle butonları için işlevsellik
    const viewButtons = document.querySelectorAll('.view-homework-btn');
    
    if (viewButtons.length > 0) {
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const homeworkId = this.getAttribute('data-homework-id');
                // Buraya ödev görüntüleme işlevselliği eklenebilir
                // Örneğin: window.open(`/ogrenci/odev-goruntule/${homeworkId}`, '_blank');
                console.log(`Ödev görüntüleme: ${homeworkId}`);
            });
        });
    }
    
    // İndir butonları için işlevsellik
    const downloadButtons = document.querySelectorAll('.download-homework-btn');
    
    if (downloadButtons.length > 0) {
        downloadButtons.forEach(button => {
            button.addEventListener('click', function() {
                const homeworkId = this.getAttribute('data-homework-id');
                // Buraya ödev indirme işlevselliği eklenebilir
                // Örneğin: window.location.href = `/ogrenci/odev-indir/${homeworkId}`;
                console.log(`Ödev indirme: ${homeworkId}`);
            });
        });
    }
    
    // Sayfa dışı tıklamada modallerin kapanması
    window.addEventListener('click', function(event) {
        if (event.target === homeworkModal) {
            homeworkModal.classList.add('hidden');
        }
        
        if (event.target === submitHomeworkModal) {
            submitHomeworkModal.classList.add('hidden');
        }
        
        if (event.target === teacherResponseModal) {
            teacherResponseModal.classList.add('hidden');
        }
    });
    
    // Başarı mesajını otomatik gizle
    const successMessage = document.querySelector('.bg-green-100');
    if (successMessage) {
        setTimeout(() => {
            successMessage.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => {
                successMessage.remove();
            }, 500);
        }, 5000);
    }
    
    // Hata mesajını otomatik gizle
    const errorMessage = document.querySelector('.bg-red-100');
    if (errorMessage) {
        setTimeout(() => {
            errorMessage.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => {
                errorMessage.remove();
            }, 500);
        }, 5000);
    }
    
    // Form doğrulaması
    const homeworkForm = document.querySelector('form[action*="odev-ekle"]');
    const submitForm = document.querySelector('form[action*="odev-yukle"]');
    const responseForm = document.querySelector('form[action*="yanit-gonder"]');
    
    if (homeworkForm) {
        homeworkForm.addEventListener('submit', function(event) {
            const title = document.getElementById('homework_title').value.trim();
            const description = document.getElementById('homework_description').value.trim();
            
            if (title === '' || description === '') {
                event.preventDefault();
                alert('Lütfen tüm zorunlu alanları doldurunuz.');
            }
        });
    }
    
    if (submitForm) {
        submitForm.addEventListener('submit', function(event) {
            const file = document.getElementById('submit_file').value;
            
            if (file === '') {
                event.preventDefault();
                alert('Lütfen bir dosya seçin.');
            }
        });
    }
    
    if (responseForm) {
        responseForm.addEventListener('submit', function(event) {
            const response = document.getElementById('response_text').value.trim();
            
            if (response === '') {
                event.preventDefault();
                alert('Lütfen bir yanıt giriniz.');
            }
        });
    }
});
document.addEventListener('DOMContentLoaded', function() {
    // Sayfa yüklendiğinde modalı hazırla
    const modalOverlay = document.getElementById('modal-overlay');
    const modalContainer = document.getElementById('modal-container');
    const modalTitle = document.getElementById('modal-title');
    const modalContent = document.getElementById('modal-content');
    const modalClose = document.getElementById('modal-close');
    
    // Animasyonlu açılış kapanış için CSS sınıflarını ekle
    modalOverlay.classList.add('opacity-0');
    modalContainer.classList.add('opacity-0', 'scale-95');
    
    // Tüm duyurular, ödevler ve geri bildirimler için tıklanabilir sınıf ekle
    const announcements = document.querySelectorAll('#content-announcements .bg-gray-50');
    const homeworks = document.querySelectorAll('#content-homework .bg-gray-50');
    const pastHomeworks = document.querySelectorAll('#content-past-homework .bg-gray-50');
    const feedbacks = document.querySelectorAll('#content-teacher-feedback .bg-gray-50');
    
    const allItems = [...announcements, ...homeworks, ...pastHomeworks, ...feedbacks];
    
    allItems.forEach(item => {
        // Öğelere stil ekle
        item.classList.add('hover:shadow-lg', 'hover:border-l-8', 'transition-all', 'duration-200', 'cursor-pointer');
        
        item.addEventListener('click', function(e) {
            // Butonlara tıklandığında modalı açmayı engelle
            if (e.target.closest('button')) {
                return;
            }
            
            // Başlığı al
            const title = item.querySelector('h3').textContent;
            modalTitle.textContent = title;
            
            // İçeriğin bir kopyasını oluştur
            const contentClone = item.cloneNode(true);
            
            // Kart stillerini temizle
            contentClone.classList.remove('p-5', 'shadow-sm', 'rounded-lg', 'cursor-pointer', 'hover:shadow-lg', 'hover:border-l-8');
            contentClone.style.padding = '0';
            contentClone.style.border = 'none';
            
            // Tüm metin içeriğini büyüt ve okunabilir yap
            const titleElement = contentClone.querySelector('h3');
            if (titleElement) {
                titleElement.classList.remove('text-lg');
                titleElement.classList.add('text-2xl', 'mb-4');
            }
            
            // İçerik metinlerini geliştir
            const descriptions = contentClone.querySelectorAll('.mt-3.text-gray-700, .text-gray-700');
            descriptions.forEach(desc => {
                desc.classList.remove('mt-3');
                desc.classList.add('mt-6', 'space-y-4');
                
                // Paragrafları büyüt
                const paragraphs = desc.querySelectorAll('p');
                paragraphs.forEach(p => {
                    p.style.fontSize = '1.1rem';
                    p.style.lineHeight = '1.8';
                });
                
                // Liste öğelerini büyüt ve aralarına boşluk ekle
                const listItems = desc.querySelectorAll('li');
                listItems.forEach(li => {
                    li.style.fontSize = '1.1rem';
                    li.style.lineHeight = '1.8';
                    li.style.marginBottom = '0.75rem';
                });
            });
            
            // Tarihleri ve meta bilgileri geliştir
            const dateLabels = contentClone.querySelectorAll('.text-sm.text-gray-500');
            dateLabels.forEach(label => {
                label.classList.remove('text-sm');
                label.classList.add('text-base', 'py-2');
            });
            
            // Butonları büyüt ve güzelleştir
            const buttons = contentClone.querySelectorAll('button');
            buttons.forEach(button => {
                // Görüntüle butonu
                if (button.textContent.includes('Görüntüle')) {
                    button.classList.remove('text-sm', 'px-3', 'py-1.5');
                    button.classList.add('text-base', 'px-6', 'py-2.5', 'rounded-lg', 'shadow-md', 'hover:shadow-lg');
                } 
                // İndir butonu
                else if (button.textContent.includes('İndir')) {
                    button.classList.remove('text-sm', 'px-3', 'py-1.5');
                    button.classList.add('text-base', 'px-6', 'py-2.5', 'rounded-lg', 'shadow-md', 'hover:shadow-lg');
                }
                // Yanıtla butonu
                else if (button.textContent.includes('Yanıtla')) {
                    button.classList.remove('text-sm');
                    button.classList.add('text-base', 'px-4', 'py-2', 'bg-blue-100', 'hover:bg-blue-200', 'rounded-lg');
                }
            });
            
            // Modal içeriğini temizle ve içeriği ekle
            modalContent.innerHTML = '';
            
            // Eğer geri bildirim/ödev içeriği ise daha ayrıntılı bilgi göster
            if (contentClone.querySelector('.list-disc') || contentClone.textContent.includes('Perfect Tense')) {
                // Bir kapsayıcı div ekle
                const contentWrapper = document.createElement('div');
                contentWrapper.className = 'space-y-6';
                
                // İçeriği zenginleştir
                const enhancedContent = document.createElement('div');
                enhancedContent.className = 'p-6 bg-blue-50 bg-opacity-70 rounded-xl shadow-inner';
                enhancedContent.innerHTML = `
                    <h4 class="text-xl font-semibold text-blue-900 mb-4">Detaylı Bilgi</h4>
                    ${contentClone.innerHTML}
                `;
                
                // İpuçları veya ek bilgiler ekle
                const tipsSection = document.createElement('div');
                tipsSection.className = 'mt-8 p-6 bg-yellow-50 rounded-xl';
                tipsSection.innerHTML = `
                    <h4 class="flex items-center text-xl font-semibold text-yellow-800 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Hatırlatmalar ve İpuçları
                    </h4>
                    <ul class="list-disc pl-6 space-y-2 text-yellow-900">
                        <li>Benzer konular için ders notlarınızı gözden geçirebilirsiniz.</li>
                        <li>Sorularınız için öğretmeninize doğrudan mesaj gönderebilirsiniz.</li>
                        <li>Ödevinizi zamanında teslim etmeye özen gösteriniz.</li>
                    </ul>
                `;
                
                contentWrapper.appendChild(enhancedContent);
                contentWrapper.appendChild(tipsSection);
                modalContent.appendChild(contentWrapper);
            } else {
                // Standart içerik için
                modalContent.appendChild(contentClone);
            }
            
            // Modalı göster - animasyonlu
            modalOverlay.classList.remove('hidden');
            modalOverlay.classList.add('flex');
            
            // Animasyon için kısa bir gecikme
            setTimeout(() => {
                modalOverlay.classList.remove('opacity-0');
                modalOverlay.classList.add('opacity-100');
                modalContainer.classList.remove('opacity-0', 'scale-95');
                modalContainer.classList.add('opacity-100', 'scale-100');
            }, 10);
        });
    });
    
    // Modal kapatma işlevi
    function closeModal() {
        modalOverlay.classList.remove('opacity-100');
        modalContainer.classList.remove('opacity-100', 'scale-100');
        modalOverlay.classList.add('opacity-0');
        modalContainer.classList.add('opacity-0', 'scale-95');
        
        setTimeout(() => {
            modalOverlay.classList.add('hidden');
            modalOverlay.classList.remove('flex');
        }, 300); // Geçiş süresi
    }
    
    // Kapat butonuyla modalı kapat
    modalClose.addEventListener('click', closeModal);
    
    // Modal dışına tıklandığında kapat
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === modalOverlay) {
            closeModal();
        }
    });
    
    // ESC tuşuyla modalı kapat
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modalOverlay.classList.contains('hidden')) {
            closeModal();
        }
    });
});
</script>

</div>
</div>
@endsection