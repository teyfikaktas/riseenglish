<!-- resources/views/student/courses/index.blade.php -->
@extends('layouts.app')

@section('title', 'Kurslarım')

@section('content')

@if(session('success'))
<div id="success-alert" class="fixed top-4 right-4 z-50 max-w-sm bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md" role="alert">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button onclick="document.getElementById('success-alert').style.display = 'none'" class="inline-flex text-green-500 hover:text-green-700">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // 5 saniye sonra bildirimi otomatik olarak kapat
    setTimeout(function() {
        var alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 1s';
            setTimeout(function() {
                alert.style.display = 'none';
            }, 1000);
        }
    }, 5000);
</script>
@endif
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Başlık ve Hoşgeldin Mesajı -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-1">Kurslarım</h1>
                    <p class="text-gray-600">Hoşgeldiniz, <span class="font-medium text-blue-600">{{ Auth::user()->name }}</span></p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Yeni Kursları Keşfet
                    </a>
                </div>
            </div>
        </div>
        
        <!-- İstatistik Kartları -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Aktif Kurslar Kartı -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-md overflow-hidden border border-blue-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="rounded-full bg-blue-500/10 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Aktif Kurslarım</p>
                            <h2 class="text-3xl font-bold text-blue-700">{{ $enrolledCourses->where('end_date', '>=', now())->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="bg-blue-500/5 px-6 py-3 border-t border-blue-200">
                    <a href="#aktif-kurslar" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center group">
                        Aktif Kurslarımı Görüntüle
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Tamamlanan Kurslar Kartı -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-md overflow-hidden border border-green-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="rounded-full bg-green-500/10 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Tamamlanan Kurslarım</p>
                            <h2 class="text-3xl font-bold text-green-700">{{ $enrolledCourses->where('end_date', '<', now())->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="bg-green-500/5 px-6 py-3 border-t border-green-200">
                    <a href="#tamamlanan-kurslar" class="text-green-600 hover:text-green-800 font-medium text-sm flex items-center group">
                        Tamamlanan Kurslarımı Görüntüle
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Bekleyen Ödevler Kartı -->
            <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl shadow-md overflow-hidden border border-amber-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="rounded-full bg-amber-500/10 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Bekleyen Ödevlerim</p>
                            <h2 class="text-3xl font-bold text-amber-700">0</h2>
                        </div>
                    </div>
                </div>
                <div class="bg-amber-500/5 px-6 py-3 border-t border-amber-200">
                    <a href="#odevlerim" class="text-amber-600 hover:text-amber-800 font-medium text-sm flex items-center group">
                        Tüm Ödevlerimi Görüntüle
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Aktif Kurslarım Bölümü -->
        <div id="aktif-kurslar" class="bg-white rounded-xl shadow-md mb-8 overflow-hidden">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <div class="flex items-center">
                    <div class="rounded-full bg-blue-100 p-2 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Aktif Kurslarım</h3>
                </div>
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $enrolledCourses->where('end_date', '>=', now())->count() }} Kurs</span>
            </div>
            <div class="p-6">
                @if($enrolledCourses->where('end_date', '>=', now())->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($enrolledCourses->where('end_date', '>=', now()) as $course)
                            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="h-48 bg-gradient-to-r from-blue-400 to-blue-500 relative overflow-hidden">
                                    @if ($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}" class="h-full w-full object-cover transition-transform duration-500 hover:scale-110">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold text-4xl">
                                            {{ strtoupper(substr($course->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    
                                    <!-- Kurs durumu etiketi -->
                                    <div class="absolute top-3 right-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200 shadow-sm">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="p-5">
                                    <h4 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1 hover:line-clamp-none transition-all duration-300">{{ $course->name }}</h4>
                                    
                                    <div class="flex items-center text-sm text-gray-600 mb-3">
                                        @if($course->teacher)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span>{{ $course->teacher->name }}</span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @if($course->courseType)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                                {{ $course->courseType->name }}
                                            </span>
                                        @endif
                                        
                                        @if($course->courseLevel)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                                {{ $course->courseLevel->name }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm mb-1.5">
                                            <span class="font-medium text-gray-700">İlerleme</span>
                                            <span class="font-medium text-blue-600">0%</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between text-sm text-gray-600 mb-5">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') : 'Belirtilmemiş' }}
                                        </div>
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $course->total_hours ?? 0 }} Saat
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('ogrenci.kurs-detay', $course->slug) }}" class="block w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-center py-2.5 px-4 rounded-lg shadow-sm hover:shadow transition-all duration-300 font-medium">
                                        Kursa Git
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 px-4">
                        <div class="bg-blue-50 rounded-full p-4 w-20 h-20 flex items-center justify-center mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Aktif kursunuz bulunmamaktadır</h3>
                        <p class="text-gray-600 max-w-md mx-auto mb-6">Yeni kurslara kaydolmak için kurs listesini inceleyebilirsiniz. İlgi alanlarınıza göre filtreleme yaparak size uygun kursları bulabilirsiniz.</p>
                        <a href="{{ route('courses.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-300 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Kursları Keşfet
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Tamamlanan Kurslarım Bölümü -->
        <div id="tamamlanan-kurslar" class="bg-white rounded-xl shadow-md mb-8 overflow-hidden">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <div class="flex items-center">
                    <div class="rounded-full bg-green-100 p-2 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Tamamlanan Kurslarım</h3>
                </div>
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $enrolledCourses->where('end_date', '<', now())->count() }} Kurs</span>
            </div>
            <div class="p-6">
                @if($enrolledCourses->where('end_date', '<', now())->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($enrolledCourses->where('end_date', '<', now()) as $course)
                            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="h-48 bg-gradient-to-r from-gray-400 to-gray-500 relative overflow-hidden">
                                    @if ($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}" class="h-full w-full object-cover transition-transform duration-500 hover:scale-110">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center bg-gradient-to-r from-gray-500 to-gray-600 text-white font-bold text-4xl">
                                            {{ strtoupper(substr($course->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    
                                    <!-- Kurs durumu etiketi -->
                                    <div class="absolute top-3 right-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200 shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Tamamlandı
                                        </span>
                                    </div>
                                    
                                    @if($course->has_certificate)
                                        <div class="absolute top-3 left-3">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                </svg>
                                                Sertifikalı
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="p-5">
                                    <h4 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1 hover:line-clamp-none transition-all duration-300">{{ $course->name }}</h4>
                                    
                                    <div class="flex items-center text-sm text-gray-600 mb-3">
                                        @if($course->teacher)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span>{{ $course->teacher->name }}</span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @if($course->courseType)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                                {{ $course->courseType->name }}
                                            </span>
                                        @endif
                                        
                                        @if($course->courseLevel)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                                {{ $course->courseLevel->name }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm mb-1.5">
                                            <span class="font-medium text-gray-700">Tamamlandı</span>
                                            <span class="font-medium text-green-600">100%</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-green-600 h-2.5 rounded-full" style="width: 100%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between text-sm text-gray-600 mb-5">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $course->end_date ? \Carbon\Carbon::parse($course->end_date)->format('d.m.Y') : 'Belirtilmemiş' }}
                                        </div>
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $course->total_hours ?? 0 }} Saat
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-3">
                                        <a href="{{ route('ogrenci.kurs-detay', $course->slug) }}" class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-center py-2.5 px-4 rounded-lg shadow-sm hover:shadow transition-all duration-300 font-medium">
                                            Detaylar
                                        </a>
                                        
                                        @if($course->has_certificate)
                                            <a href="#" class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-center py-2.5 px-4 rounded-lg shadow-sm hover:shadow transition-all duration-300 font-medium flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                </svg>
                                                Sertifika
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 px-4">
                        <div class="bg-green-50 rounded-full p-4 w-20 h-20 flex items-center justify-center mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Tamamlanan kursunuz bulunmamaktadır</h3>
                        <p class="text-gray-600 max-w-md mx-auto">Aktif kurslarınızı tamamladığınızda burada listelenecektir. Kursları tamamlamak için düzenli çalışmaya devam edin.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Ödevlerim Bölümü -->
        <div id="odevlerim" class="bg-white rounded-xl shadow-md mb-8 overflow-hidden">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <div class="flex items-center">
                    <div class="rounded-full bg-amber-100 p-2 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Ödevlerim</h3>
                </div>
                <span class="bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-0.5 rounded-full">0 Ödev</span>
            </div>
            <div class="p-6">
                <div class="text-center py-12 px-4">
                    <div class="bg-amber-50 rounded-full p-4 w-20 h-20 flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Henüz ödeviniz bulunmamaktadır</h3>
                    <p class="text-gray-600 max-w-md mx-auto">Öğretmenleriniz ödev verdiğinde burada listelenecektir. Ödevlerinizi zamanında tamamlamak için bildirimleri kontrol etmeyi unutmayın.</p>
                </div>
            </div>
        </div>
        
        <!-- Sayfa Sonu -->
        <div class="flex justify-center pb-8">
            <a href="#" class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600 transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7" />
                </svg>
                Sayfa Başına Dön
            </a>
        </div>
    </div>
</div>
@endsection