<!-- resources/views/teacher/course_detail.blade.php -->
@extends('layouts.app')

@section('title', $course->name . ' - Kurs Detayı')

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
<div class="container mx-auto px-4 py-8">
    <!-- Üst Başlık ve Kurs Bilgileri -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="p-6 border-b">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $course->name }}</h1>
                    <div class="mt-2 flex items-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mr-2">
                            {{ $course->category->name ?? 'Kategori Belirtilmemiş' }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            {{ $course->level->name ?? 'Seviye Belirtilmemiş' }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('ogretmen.panel') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Panele Dön
                </a>
            </div>
            
            <!-- Kurs Bilgileri -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-500 text-sm mb-1">Başlangıç Tarihi</p>
                    <p class="font-semibold">{{ $course->start_date ? $course->start_date->format('d.m.Y') : 'Belirtilmemiş' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-500 text-sm mb-1">Öğrenci Kapasitesi</p>
                    <p class="font-semibold">{{ $course->students->count() }} / {{ $course->max_students }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-500 text-sm mb-1">Duyuru Sayısı</p>
                    <p class="font-semibold">{{ $announcements->count() }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-500 text-sm mb-1">Toplam Ödev</p>
                    <p class="font-semibold">{{ $homeworks->count() }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sekmeler -->
    <div x-data="{activeTab: 'announcements'}" class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'announcements'" :class="{'border-blue-500 text-blue-600': activeTab === 'announcements', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'announcements'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Duyurular
                </button>
                <button @click="activeTab = 'assignments'" :class="{'border-blue-500 text-blue-600': activeTab === 'assignments', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'assignments'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Ödevler
                </button>
                <button @click="activeTab = 'students'" :class="{'border-blue-500 text-blue-600': activeTab === 'students', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'students'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Öğrenciler
                </button>
            </nav>
        </div>
        
        <!-- Duyurular Sekmesi -->
        <div x-show="activeTab === 'announcements'" class="mt-6">
            <!-- Duyuru Oluşturma Formu -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Yeni Duyuru Oluştur</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('ogretmen.course.create-announcement', $course->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Başlık</label>
                            <input type="text" name="title" id="title" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">İçerik</label>
                            <textarea name="content" id="content" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Duyuru Oluştur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Duyuru Listesi -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Mevcut Duyurular</h3>
                </div>
                <div class="p-6">
                    @if ($announcements->isEmpty())
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                            <p class="mt-2 text-gray-500">Henüz duyuru oluşturulmadı.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($announcements as $announcement)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900">{{ $announcement->title }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">{{ $announcement->created_at->format('d.m.Y H:i') }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-2 text-gray-700">
                                    <p>{{ $announcement->content }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Ödevler Sekmesi -->
        <div x-show="activeTab === 'assignments'" class="mt-6">
            <!-- Ödev Oluşturma Formu -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Yeni Ödev Oluştur</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('ogretmen.course.create-homework', $course->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Başlık</label>
                            <input type="text" name="title" id="title" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                            <textarea name="description" id="description" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Teslim Tarihi</label>
                            <input type="date" name="due_date" id="due_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Ödev Oluştur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Ödev Listesi -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Mevcut Ödevler</h3>
                </div>
                <div class="p-6">
                    @if ($homeworks->isEmpty())
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-gray-500">Henüz ödev oluşturulmadı.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Başlık</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Oluşturulma Tarihi</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Tarihi</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Edilenler</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($homeworks as $homework)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $homework->title }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $homework->created_at->format('d.m.Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $homework->due_date->format('d.m.Y') }}
                                            @if ($homework->due_date->isPast())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                                    Süresi Doldu
                                                </span>
                                            @elseif ($homework->due_date->diffInDays(now()) <= 3)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                                                    {{ $homework->due_date->diffInDays(now()) }} Gün Kaldı
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $homework->submission_count }} / {{ $students->count() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Detay</a>
                                            <a href="#" class="text-green-600 hover:text-green-900 mr-3">Düzenle</a>
                                            <a href="#" class="text-red-600 hover:text-red-900">Sil</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Öğrenciler Sekmesi -->
        <div x-show="activeTab === 'students'" class="mt-6">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Kursa Kayıtlı Öğrenciler</h3>
                </div>
                <div class="p-6">
                    @if ($students->isEmpty())
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p class="mt-2 text-gray-500">Bu kursa henüz öğrenci kaydı yapılmadı.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Öğrenci</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-posta</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefon</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kayıt Tarihi</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($students as $student)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $student->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $student->phone ?? 'Belirtilmemiş' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $student->pivot ? $student->pivot->created_at->format('d.m.Y') : 'Belirtilmemiş' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Profil</a>
                                            <a href="#" class="text-green-600 hover:text-green-900 mr-3">Ödevler</a>
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Mesaj</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Alpine.js'i kullanabilmek için gerekli kodlar
    document.addEventListener('alpine:init', () => {
        // Gerekirse ek Alpine.js komponentleri buraya eklenebilir
    });
</script>
@endpush
@endsection