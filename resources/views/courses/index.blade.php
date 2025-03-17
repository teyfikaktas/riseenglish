<!-- resources/views/student/courses/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="container mx-auto px-4">
        <!-- Başarı mesajı gösterimi -->
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
        
        <!-- Sayfa Başlığı -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-[#1a2e5a]">Kurslarım</h1>
            <a href="{{ route('courses.index') }}" class="bg-[#1a2e5a] hover:bg-[#132447] text-white px-4 py-2 rounded-lg inline-flex items-center font-medium transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Yeni Kursları Keşfet
            </a>
        </div>
        
        <!-- Ana İçerik -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-[#1a2e5a] flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Devam Eden Kurslarım
                </h2>
            </div>
            
            <div class="p-6">
                @if($enrolledCourses->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($enrolledCourses as $course)
                            @if(\Carbon\Carbon::parse($course->start_date)->isPast() && \Carbon\Carbon::parse($course->end_date)->isFuture())
                                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                                    <div class="h-48 bg-gray-200 relative overflow-hidden">
                                        @if($course->thumbnail)
                                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full bg-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <div class="absolute top-2 left-2 bg-[#44bd32] text-white text-xs font-bold px-2 py-1 rounded-full">
                                            DEVAM EDİYOR
                                        </div>
                                    </div>
                                    
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-[#1a2e5a] mb-2">{{ $course->name }}</h3>
                                        
                                        <div class="mb-3 text-sm">
                                            <div class="flex items-center mb-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>
                                                    @if($course->start_time && $course->end_time)
                                                        {{ \Carbon\Carbon::parse($course->start_time)->format('H:i') }} - 
                                                        {{ \Carbon\Carbon::parse($course->end_time)->format('H:i') }}
                                                    @else
                                                        Saat bilgisi yok
                                                    @endif
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span>
                                                    @if($course->courseFrequency)
                                                        {{ $course->courseFrequency->name }}
                                                    @else
                                                        Sıklık bilgisi yok
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-between items-center mt-4">
                                            <a href="{{ route('ogrenci.kurs-detay', $course->slug) }}" class="bg-[#1a2e5a] hover:bg-[#132447] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
                                                Detaya Git
                                            </a>
                                            
                                            @if($course->meeting_link)
                                                <a href="{{ $course->meeting_link }}" target="_blank" class="bg-[#e63946] hover:bg-[#d32836] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
                                                    <span class="hidden md:inline">Derse Katıl</span>
                                                    <span class="inline md:hidden">Katıl</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 p-8 rounded-lg text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-gray-600">Henüz devam eden bir kursunuz bulunmamaktadır.</p>
                        <a href="{{ route('courses.index') }}" class="mt-4 inline-block text-[#1a2e5a] font-medium hover:underline">Kursları keşfedin</a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Yaklaşan Kurslar -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-[#1a2e5a] flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Yaklaşan Kurslarım
                </h2>
            </div>
            
            <div class="p-6">
                @php
                    $upcomingCourses = $enrolledCourses->filter(function($course) {
                        return \Carbon\Carbon::parse($course->start_date)->isFuture();
                    });
                @endphp
                
                @if($upcomingCourses->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($upcomingCourses as $course)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                                <div class="h-48 bg-gray-200 relative overflow-hidden">
                                    @if($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    @php
                                        $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($course->start_date), false);
                                    @endphp
                                    
                                    @if($daysLeft <= 7 && $daysLeft > 0)
                                        <div class="absolute top-2 left-2 bg-[#e1b12c] text-white text-xs font-bold px-2 py-1 rounded-full">
                                            {{ $daysLeft }} GÜN KALDI
                                        </div>
                                    @elseif($daysLeft == 0)
                                        <div class="absolute top-2 left-2 bg-[#c23616] text-white text-xs font-bold px-2 py-1 rounded-full">
                                            BUGÜN BAŞLIYOR
                                        </div>
                                    @else
                                        <div class="absolute top-2 left-2 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                            YAKLAŞAN
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-[#1a2e5a] mb-2">{{ $course->name }}</h3>
                                    
                                    <div class="mb-3 text-sm">
                                        <div class="flex items-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>Başlangıç: {{ \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') }}</span>
                                        </div>
                                        
                                        <div class="flex items-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>
                                                @if($course->start_time && $course->end_time)
                                                    {{ \Carbon\Carbon::parse($course->start_time)->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($course->end_time)->format('H:i') }}
                                                @else
                                                    Saat bilgisi yok
                                                @endif
                                            </span>
                                        </div>
                                        
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span>
                                                @if($course->teacher)
                                                    {{ $course->teacher->name }}
                                                @else
                                                    Eğitmen atanmadı
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center mt-4">
                                        <a href="{{ route('ogrenci.kurs-detay', $course->slug) }}" class="bg-[#1a2e5a] hover:bg-[#132447] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
                                            Detaya Git
                                        </a>
                                        
                                        <span class="text-sm text-gray-500">
                                            @if($daysLeft == 0)
                                                Bugün başlıyor!
                                            @elseif($daysLeft == 1)
                                                Yarın başlıyor!
                                            @else
                                                {{ $daysLeft }} gün sonra
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 p-8 rounded-lg text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-gray-600">Henüz yaklaşan bir kursunuz bulunmamaktadır.</p>
                        <a href="{{ route('courses.index') }}" class="mt-4 inline-block text-[#1a2e5a] font-medium hover:underline">Kursları keşfedin</a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Tamamlanan Kurslar -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-[#1a2e5a] flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tamamlanan Kurslarım
                </h2>
            </div>
            
            <div class="p-6">
                @php
                    $completedCourses = $enrolledCourses->filter(function($course) {
                        return \Carbon\Carbon::parse($course->end_date)->isPast();
                    });
                @endphp
                
                @if($completedCourses->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($completedCourses as $course)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                                <div class="h-48 bg-gray-200 relative overflow-hidden">
                                    @if($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <div class="absolute top-2 left-2 bg-gray-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                        TAMAMLANDI
                                    </div>
                                    
                                    @if($course->has_certificate)
                                        <div class="absolute top-2 right-2 bg-[#e63946] text-white text-xs font-bold px-2 py-1 rounded-full">
                                            SERTİFİKALI
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-[#1a2e5a] mb-2">{{ $course->name }}</h3>
                                    
                                    <div class="mb-3 text-sm">
                                        <div class="flex items-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>Tamamlanma: {{ \Carbon\Carbon::parse($course->end_date)->format('d.m.Y') }}</span>
                                        </div>
                                        
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span>
                                                @if($course->teacher)
                                                    {{ $course->teacher->name }}
                                                @else
                                                    Eğitmen atanmadı
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center mt-4">
                                        <a href="{{ route('ogrenci.kurs-detay', $course->slug) }}" class="bg-[#1a2e5a] hover:bg-[#132447] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
                                            Detaya Git
                                        </a>
                                        
                                        @if($course->has_certificate)
                                            <a href="#" class="bg-[#e63946] hover:bg-[#d32836] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
                                                Sertifikayı Gör
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 p-8 rounded-lg text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-gray-600">Henüz tamamlanan bir kursunuz bulunmamaktadır.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
@endsection