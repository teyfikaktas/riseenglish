@extends('layouts.app')

@section('title', 'Kurs Yönetimi')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Başlık ve Butonlar -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Kurs Yönetimi</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.courses.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Yeni Kurs Ekle
                </a>
                <div class="relative inline-block text-left">
                    <button type="button" id="settingsButton" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                        Ayarlar
                    </button>
                    <div id="settingsDropdown" class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100">
                        <div class="py-1">
                            <a href="{{ route('admin.course-types.index') }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                Kurs Tipleri
                            </a>
                            
                            <a href="{{ route('admin.course-levels.index') }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                </svg>
                                Kurs Seviyeleri
                            </a>
                            {{-- <a href="admin.course-levels.index" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                </svg>
                                Materyal Tipleri
                            </a> --}}
                        </div>
                        <div class="py-1">
                            {{-- <a href="#" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Değerlendirme Tipleri
                            </a> --}}
                            <a href="{{ route('admin.course-frequencies.index') }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                Kurs Sıklığı
                            </a>
                        </div>
                        <div class="py-1">
                            <a href="#" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                                Sistem Ayarları
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- İstatistik Kartları -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Toplam Kurs</div>
                        <div class="text-xl font-semibold text-gray-800">{{ $courses->total() }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Aktif Kurslar</div>
                        <div class="text-xl font-semibold text-gray-800">{{ $active_courses_count }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Toplam Öğrenci</div>
                        <div class="text-xl font-semibold text-gray-800">{{ $total_students }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Bu Ay Yeni Kurslar</div>
                        <div class="text-xl font-semibold text-gray-800">{{ $new_courses_this_month }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtreler -->
<!-- Filtreler -->
<div class="bg-white shadow-md rounded-lg p-4 mb-6">
    <div class="flex flex-wrap items-center">
        <div class="w-full md:w-1/5 px-2 mb-4 md:mb-0">
            <label for="categoryFilter" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select id="categoryFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                <option value="">Tümü</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-1/5 px-2 mb-4 md:mb-0">
            <label for="courseTypeFilter" class="block text-sm font-medium text-gray-700 mb-1">Kurs Tipi</label>
            <select id="courseTypeFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                <option value="">Tümü</option>
                @foreach($courseTypes as $courseType)
                    <option value="{{ $courseType->id }}">{{ $courseType->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-1/5 px-2 mb-4 md:mb-0">
            <label for="courseLevelFilter" class="block text-sm font-medium text-gray-700 mb-1">Kurs Seviyesi</label>
            <select id="courseLevelFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                <option value="">Tümü</option>
                @foreach($courseLevels as $courseLevel)
                    <option value="{{ $courseLevel->id }}">{{ $courseLevel->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-1/5 px-2 mb-4 md:mb-0">
            <label for="searchFilter" class="block text-sm font-medium text-gray-700 mb-1">Ara</label>
            <input type="text" id="searchFilter" placeholder="Kurs adı ara..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
        </div>
        <div class="w-full md:w-1/5 px-2 mb-4 md:mb-0 flex items-end">
            <button type="button" onclick="applyFilters()" class="w-full bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
                Filtrele
            </button>
        </div>
    </div>
</div>

        <!-- Kurs Listesi -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kurs Adı
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kurs Tipi
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Seviye
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Durum
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Öğrenci Sayısı
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tarih
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            İşlemler
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($courses as $course)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($course->thumbnail)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                    @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $course->name }}</div>
                                        <div class="text-sm text-gray-500">Kod: {{ $course->slug }}</div>
                                        @if($course->price)
                                            <div class="text-sm text-gray-500">Fiyat: {{ number_format($course->price, 2) }} ₺</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $course->courseType?->name ?? 'Belirtilmemiş' }}</div>
                                @if($course->teacher)
                                    <div class="text-xs text-gray-500">Öğretmen: {{ $course->teacher->name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($course->category)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            {{ $course->category->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-500">Kategorisiz</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($course->courseLevel)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if(str($course->courseLevel->name)->lower()->contains(['a1', 'başlangıç']))
                                            bg-green-100 text-green-800
                                        @elseif(str($course->courseLevel->name)->lower()->contains(['b', 'orta']))
                                            bg-yellow-100 text-yellow-800
                                        @elseif(str($course->courseLevel->name)->lower()->contains(['c', 'ileri']))
                                            bg-red-100 text-red-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ $course->courseLevel->name }}
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Belirtilmemiş
                                    </span>
                                @endif
                                
                                @if($course->total_hours)
                                    <div class="text-xs text-gray-500 mt-1">{{ $course->total_hours }} saat</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $course->is_active ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $course->is_active ? 'Aktif' : 'Pasif' }}
                                </span>
                                @if($course->has_certificate)
                                    <div class="text-xs text-gray-500 mt-1">
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-yellow-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Sertifikalı
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">{{ $course->students_count ?? $course->students->count() }}</div>
                                @if($course->max_students)
                                    <div class="text-xs text-gray-500">Max: {{ $course->max_students }}</div>
                                    
                                    @php
                                        $studentCount = $course->students_count ?? $course->students->count();
                                        $percentage = min(100, round(($studentCount / $course->max_students) * 100));
                                    @endphp
                                    
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>  
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        @if($course->start_date)
                                            <div class="text-xs text-gray-700">
                                                <span class="font-medium">Başlangıç:</span> {{ $course->start_date->format('d.m.Y') }}
                                            </div>
                                        @endif
                                        
                                        @if($course->end_date)
                                            <div class="text-xs text-gray-700 mt-1">
                                                <span class="font-medium">Bitiş:</span> {{ $course->end_date->format('d.m.Y') }}
                                            </div>
                                        @endif
                                        
                                        @if($course->courseFrequency)
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $course->courseFrequency->name }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex space-x-2 justify-end">
                                        <a href="{{ route('admin.courses.show', $course) }}" class="text-blue-600 hover:text-blue-900 transition-all duration-200" title="Görüntüle">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                                <!-- Kayıt Yönetimi butonu -->
                                <a href="{{ route('admin.courses.enrollments', $course) }}" class="text-green-600 hover:text-green-900 transition-all duration-200" title="Kayıt Yönetimi">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                    </svg>
                                </a>
        
                                        <a href="{{ route('admin.courses.edit', $course) }}" class="text-indigo-600 hover:text-indigo-900 transition-all duration-200" title="Düzenle">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>
                                        <button type="button" onclick="confirmDelete('{{ $course->id }}')" class="text-red-600 hover:text-red-900 transition-all duration-200" title="Sil">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <form id="delete-form-{{ $course->id }}" action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 whitespace-nowrap text-sm text-gray-500 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-gray-600 text-lg font-medium">Henüz kurs bulunmuyor.</span>
                                        <p class="text-gray-500 mt-1">Yeni bir kurs eklemek için "Yeni Kurs Ekle" butonunu kullanabilirsiniz.</p>
                                        <a href="{{ route('admin.courses.create') }}" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                            </svg>
                                            Yeni Kurs Ekle
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <!-- Sayfalama -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-700">
                            Toplam <span class="font-medium">{{ $courses->total() }}</span> kurs
                        </div>
                        <div>
                            {{ $courses->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Silme için onay dialogu -->
        <div id="deleteConfirmModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">Kursu Sil</h3>
                    <p class="mt-2 text-sm text-gray-500">Bu kursu silmek istediğinize emin misiniz? Bu işlem geri alınamaz.</p>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button type="button" onclick="closeDeleteModal()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            İptal
                        </button>
                        <button type="button" onclick="proceedDelete()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Sil
                        </button>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- JavaScript -->
        <script>
            // Global değişkenler
            let currentCourseId = null;
            
            // Ayarlar menüsü
            document.addEventListener('DOMContentLoaded', function() {
                const settingsButton = document.getElementById('settingsButton');
                const settingsDropdown = document.getElementById('settingsDropdown');
                
                // Ayarlar butonuna tıklayınca menüyü aç/kapat
                settingsButton.addEventListener('click', function() {
                    settingsDropdown.classList.toggle('hidden');
                });
                
                // Sayfa üzerinde herhangi bir yere tıklayınca menüyü kapat
                document.addEventListener('click', function(event) {
                    const isClickInside = settingsButton.contains(event.target);
                    
                    if (!isClickInside && !settingsDropdown.classList.contains('hidden')) {
                        settingsDropdown.classList.add('hidden');
                    }
                });
                
                // Kurs seviyelerini AJAX ile yükleme
                loadFilterOptions();
                
                // URL'deki mevcut filtre seçimlerini yükle
                loadCurrentFilterValues();
            });
            
            // Kurs seviye ve tip verilerini yükle
            function loadFilterOptions() {
                // Kurs seviyelerini AJAX ile yükleme
                fetch('/admin/course-levels/list')
                    .then(response => response.json())
                    .then(data => {
                        const courseLevelFilter = document.getElementById('courseLevelFilter');
                        data.forEach(level => {
                            const option = document.createElement('option');
                            option.value = level.id;
                            option.textContent = level.name;
                            courseLevelFilter.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Kurs seviyeleri yüklenemedi:', error));
                    
                // Kurs tiplerini AJAX ile yükleme
                fetch('/admin/course-types/list')
                    .then(response => response.json())
                    .then(data => {
                        const courseTypeFilter = document.getElementById('courseTypeFilter');
                        data.forEach(type => {
                            const option = document.createElement('option');
                            option.value = type.id;
                            option.textContent = type.name;
                            courseTypeFilter.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Kurs tipleri yüklenemedi:', error));
            }
            
            // Mevcut URL'deki filtre değerlerini form elemanlarına yükle
            function loadCurrentFilterValues() {
                const urlParams = new URLSearchParams(window.location.search);
                
                // Kurs tipi filtresini ayarla
                const typeId = urlParams.get('type_id');
                if (typeId) {
                    document.getElementById('courseTypeFilter').value = typeId;
                }
                
                // Kurs seviyesi filtresini ayarla
                const levelId = urlParams.get('level_id');
                if (levelId) {
                    document.getElementById('courseLevelFilter').value = levelId;
                }
                
                // Arama filtresini ayarla
                const search = urlParams.get('search');
                if (search) {
                    document.getElementById('searchFilter').value = search;
                }
            }
            
            // Filtreleri uygula
            function applyFilters() {
                const typeId = document.getElementById('courseTypeFilter').value;
                const levelId = document.getElementById('courseLevelFilter').value;
                const search = document.getElementById('searchFilter').value;
                
                const params = new URLSearchParams();
                
                if (typeId) params.append('type_id', typeId);
                if (levelId) params.append('level_id', levelId);
                if (search) params.append('search', search);
                
                window.location.href = `${window.location.pathname}?${params.toString()}`;
            }
            
            // Arama kutusunda Enter tuşuna basılınca filtreleri uygula
            document.getElementById('searchFilter').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyFilters();
                }
            });
            
            // Silme onay modalını göster
            function confirmDelete(courseId) {
                currentCourseId = courseId;
                document.getElementById('deleteConfirmModal').classList.remove('hidden');
            }
            
            // Silme onay modalını kapat
            function closeDeleteModal() {
                document.getElementById('deleteConfirmModal').classList.add('hidden');
                currentCourseId = null;
            }
            
            // Silme işlemine devam et
            function proceedDelete() {
                if (currentCourseId) {
                    document.getElementById(`delete-form-${currentCourseId}`).submit();
                }
                closeDeleteModal();
            }
        </script>
    @endsection