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
        <!-- Kurs Toplantı Bilgileri -->
<div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Online Toplantı Bilgileri</h3>
        <button type="button" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700"
                onclick="toggleMeetingForm()">
            Düzenle
        </button>
    </div>
    <div class="p-6">
        <div class="mb-4">
            <p class="text-gray-500 text-sm mb-1">Mevcut Toplantı Linki:</p>
            @if($course->meeting_link)
                <a href="{{ $course->meeting_link }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                    {{ $course->meeting_link }}
                </a>
            @else
                <p class="text-gray-400 italic">Toplantı linki henüz belirlenmemiş</p>
            @endif
        </div>
        
        <div>
            <p class="text-gray-500 text-sm mb-1">Mevcut Toplantı Şifresi:</p>
            @if($course->meeting_password)
                <p class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $course->meeting_password }}</p>
            @else
                <p class="text-gray-400 italic">Toplantı şifresi henüz belirlenmemiş</p>
            @endif
        </div>
        
        <!-- Düzenleme Formu (varsayılan olarak gizli) -->
        <div id="meetingForm" class="hidden mt-6 border-t pt-4">
            <form action="{{ route('ogretmen.course.update-meeting-info', $course->id) }}" method="POST">
                                @csrf
                
                <div class="mb-4">
                    <label for="meeting_link" class="block text-sm font-medium text-gray-700 mb-1">Toplantı Linki</label>
                    <input type="url" id="meeting_link" name="meeting_link" 
                           value="{{ $course->meeting_link }}"
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <div class="mb-4">
                    <label for="meeting_password" class="block text-sm font-medium text-gray-700 mb-1">Toplantı Şifresi</label>
                    <input type="text" id="meeting_password" name="meeting_password" 
                           value="{{ $course->meeting_password }}"
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <div class="flex justify-end">
                    <button type="button" 
                            onclick="toggleMeetingForm()"
                            class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                        İptal
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                        Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript kodu -->
<script>
    function toggleMeetingForm() {
        const form = document.getElementById('meetingForm');
        form.classList.toggle('hidden');
    }
</script>
        <!-- Duyurular Sekmesi -->
        <div x-show="activeTab === 'announcements'" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Duyuru Oluşturma Formu -->
<!-- Duyuru Oluşturma Formu -->
<div class="bg-white rounded-lg shadow-md">
    <div class="p-4 border-b bg-blue-50">
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
            <div class="mb-4">
                <div class="flex items-center">
                    <input type="checkbox" name="send_notification" id="send_notification" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="send_notification" class="ml-2 block text-sm text-gray-700">
                        Öğrencilere ve velilere SMS bildirimi gönder
                    </label>
                </div>
                <p class="mt-1 text-xs text-gray-500">Kursa kayıtlı ve onaylı öğrencilere ve velilerine bu duyuru hakkında bildirim SMS'i gönderilecektir.</p>
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
                <div class="p-4 border-b bg-blue-50">
                    <h3 class="text-lg font-semibold text-gray-800">Mevcut Duyurular</h3>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
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
        <div x-show="activeTab === 'assignments'" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Ödev Oluşturma Formu -->
<!-- Ödev Oluşturma Formu -->
<div class="bg-white rounded-lg shadow-md">
    <div class="p-4 border-b bg-green-50">
        <h3 class="text-lg font-semibold text-gray-800">Yeni Ödev Oluştur</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('ogretmen.course.create-homework', $course->id) }}" method="POST" enctype="multipart/form-data">
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
            <div class="mb-4">
                <label for="max_score" class="block text-sm font-medium text-gray-700 mb-1">Maksimum Puan</label>
                <input type="number" name="max_score" id="max_score" value="100" min="0" max="100" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="file_path" class="block text-sm font-medium text-gray-700 mb-1">Ödev Dosyası (İsteğe Bağlı)</label>
                <input type="file" name="file_path" id="file_path" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <p class="mt-1 text-sm text-gray-500">PDF, Word, Excel veya resim dosyaları yükleyebilirsiniz.</p>
            </div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input type="checkbox" name="send_notification" id="send_notification" value="1" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="send_notification" class="ml-2 block text-sm text-gray-700">
                        Öğrencilere ve velilere SMS bildirimi gönder
                    </label>
                </div>
                <p class="mt-1 text-xs text-gray-500">Kursa kayıtlı ve onaylı öğrencilere ve velilerine yeni ödev hakkında bildirim SMS'i gönderilecektir.</p>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Ödev Oluştur
                </button>
            </div>
        </form>
    </div>
</div>
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4 border-b bg-amber-50">
                    <h3 class="text-lg font-semibold text-gray-800">Öğrenci Ödev Teslimleriniz</h3>
                </div>
                <div class="p-6">
                    @if (!$submissions || $submissions->isEmpty())
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-gray-500">Henüz değerlendirilmemiş ödev teslimi bulunmamaktadır.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ödev</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Öğrenci</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durumu</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($submissions->take(5) as $submission)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $submission->homework->title }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">
                                                        {{ strtoupper(substr($submission->student->name, 0, 1)) }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">{{ $submission->student->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($submission->graded_at)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Değerlendirildi
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Bekliyor
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('ogretmen.submission.view', $submission->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <!-- Tümünü Gör Butonu -->
                            @if($submissions->count() > 5)
                                <div class="mt-4 text-center">
                                    <button 
                                        @click="$dispatch('open-modal', {'modalId': 'allSubmissionsModal'})" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500"
                                    >
                                        Tüm Teslimleri Görüntüle ({{ $submissions->count() }})
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div
    x-data="{ show: false }"
    x-show="show"
    x-on:open-modal.window="if ($event.detail.modalId === 'allSubmissionsModal') show = true"
    x-on:keydown.escape.window="show = false"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    style="display: none;"
>
    <div 
        @click.away="show = false"
        class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[80vh] overflow-hidden flex flex-col mx-4"
    >
        <div class="p-4 bg-amber-50 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Tüm Öğrenci Ödev Teslimleri</h3>
            <button @click="show = false" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ödev</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Öğrenci</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Tarihi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durumu</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($submissions as $submission)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $submission->homework->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($submission->student->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $submission->student->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $submission->student->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $submission->submitted_at->format('d.m.Y H:i') }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $submission->submitted_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($submission->graded_at)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Değerlendirildi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Bekliyor
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('ogretmen.submission.view', $submission->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('ogretmen.submission.evaluate', $submission->id) }}" class="text-amber-600 hover:text-amber-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="text-green-600 hover:text-green-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="p-4 bg-gray-50 border-t flex justify-end">
            <button 
                @click="show = false" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Kapat
            </button>
        </div>
    </div>
</div>
            <div x-show="activeTab === 'submissions'" class="mt-6">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-4 border-b bg-amber-50">
                        <h3 class="text-lg font-semibold text-gray-800">Öğrenci Ödev Teslimleriniz</h3>
                    </div>
                    <div class="p-6">
                        @if (!$submissions || $submissions->isEmpty())
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-gray-500">Henüz değerlendirilmemiş ödev teslimi bulunmamaktadır.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ödev</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Öğrenci</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Tarihi</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durumu</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($submissions as $submission)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $submission->homework->title }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">
                                                            {{ strtoupper(substr($submission->student->name, 0, 1)) }}
                                                        </div>
                                                        <div class="ml-3">
                                                            <div class="text-sm font-medium text-gray-900">{{ $submission->student->name }}</div>
                                                            <div class="text-sm text-gray-500">{{ $submission->student->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $submission->submitted_at->format('d.m.Y H:i') }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $submission->submitted_at->diffForHumans() }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($submission->graded_at)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Değerlendirildi
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Bekliyor
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('ogretmen.submission.view', $submission->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </a>
                                                        <a href="{{ route('ogretmen.submission.evaluate', $submission->id) }}" class="text-amber-600 hover:text-amber-900">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>
                                                        <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="text-green-600 hover:text-green-900">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                            </svg>
                                                        </a>
                                                    </div>
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
            <!-- Ödev Listesi -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4 border-b bg-green-50">
                    <h3 class="text-lg font-semibold text-gray-800">Mevcut Ödevler</h3>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    @if ($homeworks->isEmpty())
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-gray-500">Henüz ödev oluşturulmadı.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($homeworks as $homework)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900">{{ $homework->title }}</h4>
                                        <div class="flex items-center mt-1">
                                            <p class="text-sm text-gray-500 mr-2">Teslim: {{ $homework->due_date->format('d.m.Y') }}</p>
                                            @if ($homework->due_date->isPast())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Süresi Doldu
                                            </span>
                                        @elseif ($homework->due_date->isToday())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Bugün {{ $homework->due_date->format('H:i') }}'e kadar
                                            </span>
                                        @elseif ($homework->due_date->isTomorrow())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Yarın {{ $homework->due_date->format('H:i') }}'e kadar
                                            </span>
                                        @else
                                            @php
                                                // Doğru yönde hesaplama ve tam sayı dönüşümü
                                                $daysLeft = (int)now()->diffInDays($homework->due_date, false);
                                            @endphp
                                            
                                            @if ($daysLeft <= 3 && $daysLeft > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    {{ $daysLeft }} Gün Kaldı
                                                </span>
                                            @elseif ($daysLeft > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $daysLeft }} Gün Kaldı
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Süresi Doldu
                                                </span>
                                            @endif
                                        @endif
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Teslim Edilenler: {{ $homework->submission_count }} / {{ $students->count() }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="#" class="text-blue-600 hover:text-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="#" class="text-green-600 hover:text-green-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <a href="#" class="text-red-600 hover:text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-2 text-gray-700">
                                    <p class="line-clamp-2">{{ $homework->description }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Öğrenciler Sekmesi -->
        <div x-show="activeTab === 'students'" class="mt-6">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-5 border-b bg-purple-100">
                    <h3 class="text-xl font-bold text-purple-800">Kursa Kayıtlı Öğrenciler</h3>
                </div>
                <div class="p-6">
                    @if ($students->isEmpty())
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p class="mt-4 text-gray-600 text-lg">Bu kursa henüz öğrenci kaydı yapılmadı.</p>
                            <button class="mt-4 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50">
                                Öğrenci Ekle
                            </button>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($students as $student)
                            <div class="bg-white border border-gray-100 rounded-xl hover:shadow-xl transition-all duration-300 overflow-hidden">
                                <div class="p-5 bg-gradient-to-r from-purple-500 to-indigo-600">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-14 w-14 rounded-full bg-white flex items-center justify-center text-purple-700 font-bold text-xl shadow-md">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-lg font-bold text-white">{{ $student->name }}</h4>
                                            <p class="text-purple-100">{{ $student->email }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <div class="flex flex-col space-y-3">
                                        <div class="flex justify-between items-center">
                                            <p class="text-gray-500">Kayıt Tarihi:</p>
                                            <p class="font-medium">{{ $student->pivot ? $student->pivot->created_at->format('d.m.Y') : 'Belirtilmemiş' }}</p>
                                        </div>
                                        

                                        
                            
                                    </div>
                                    
                                    <div class="mt-5 pt-4 border-t border-gray-100 flex justify-between">

                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Öğrenci Ödevleri Modal -->
        <div
            x-data="{ 
                show: false,
                studentId: null,
                studentName: '',
                studentSubmissions: [],
                pendingSubmissions: [],
                completedSubmissions: [],
                activeSubTab: 'pending'
            }"
            x-show="show"
            x-on:open-student-modal.window="
                show = true;
                studentId = $event.detail.studentId;
                studentName = $event.detail.studentName;
                // Burada AJAX ile öğrencinin ödevlerini getirebilirsiniz
                // Şimdilik statik veri kullanıyoruz
                loadStudentSubmissions(studentId);
            "
            x-on:keydown.escape.window="show = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            style="display: none;"
        >
            <div 
                @click.away="show = false"
                class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[80vh] overflow-hidden flex flex-col mx-4"
            >
                <div class="p-4 bg-purple-50 border-b flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800" x-text="studentName + ' - Ödevleri'"></h3>
                    <button @click="show = false" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Öğrenci Ödevleri Alt Sekmeleri -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button 
                            @click="activeSubTab = 'pending'" 
                            :class="{'border-indigo-500 text-indigo-600': activeSubTab === 'pending', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeSubTab !== 'pending'}" 
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        >
                            Bekleyen Ödevler
                        </button>
                        <button 
                            @click="activeSubTab = 'completed'" 
                            :class="{'border-indigo-500 text-indigo-600': activeSubTab === 'completed', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeSubTab !== 'completed'}" 
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        >
                            Tamamlanan Ödevler
                        </button>
                    </nav>
                </div>
                
                <div class="p-6 overflow-y-auto">
                    <!-- Bekleyen Ödevler Tablosu -->
                    <div x-show="activeSubTab === 'pending'">
                        <div x-show="pendingSubmissions.length === 0" class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-gray-500">Bu öğrencinin değerlendirilmeyi bekleyen ödevi bulunmamaktadır.</p>
                        </div>
                        
                        <table x-show="pendingSubmissions.length > 0" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ödev</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Tarihi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durumu</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="submission in pendingSubmissions" :key="submission.id">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900" x-text="submission.homework.title"></div>
                                            <div class="text-xs text-gray-500" x-text="'Ödev ID: ' + submission.homework.id"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900" x-text="formatDate(submission.submitted_at)"></div>
                                            <div class="text-xs text-gray-500" x-text="timeAgo(submission.submitted_at)"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Değerlendirme Bekliyor
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a :href="'/ogretmen/submission/' + submission.id + '/view'" class="text-indigo-600 hover:text-indigo-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <a :href="'/ogretmen/submission/' + submission.id + '/evaluate'" class="text-amber-600 hover:text-amber-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <a :href="'/storage/' + submission.file_path" target="_blank" class="text-green-600 hover:text-green-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Tamamlanan Ödevler Tablosu -->
                    <div x-show="activeSubTab === 'completed'">
                        <div x-show="completedSubmissions.length === 0" class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="mt-2 text-gray-500">Bu öğrencinin tamamlanmış ödevi bulunmamaktadır.</p>
                        </div>
                        
                        <table x-show="completedSubmissions.length > 0" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ödev</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Tarihi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="submission in completedSubmissions" :key="submission.id">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900" x-text="submission.homework.title"></div>
                                            <div class="text-xs text-gray-500" x-text="'Değerlendirme Tarihi: ' + formatDate(submission.graded_at)"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900" x-text="formatDate(submission.submitted_at)"></div>
                                            <div class="text-xs text-gray-500" x-text="timeAgo(submission.submitted_at)"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div 
                                                    class="w-16 bg-gray-200 rounded-full h-2.5 mr-2"
                                                    :class="{
                                                        'bg-red-200': submission.score < 50,
                                                        'bg-yellow-200': submission.score >= 50 && submission.score < 70,
                                                        'bg-green-200': submission.score >= 70
                                                    }"
                                                >
                                                    <div 
                                                        class="h-2.5 rounded-full" 
                                                        :class="{
                                                            'bg-red-600': submission.score < 50,
                                                            'bg-yellow-600': submission.score >= 50 && submission.score < 70,
                                                            'bg-green-600': submission.score >= 70
                                                        }"
                                                        :style="'width: ' + submission.score + '%'"
                                                    ></div>
                                                </div>
                                                <span 
                                                    class="text-sm font-medium" 
                                                    :class="{
                                                        'text-red-700': submission.score < 50,
                                                        'text-yellow-700': submission.score >= 50 && submission.score < 70,
                                                        'text-green-700': submission.score >= 70
                                                    }"
                                                    x-text="submission.score + '/100'"
                                                ></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a :href="'/ogretmen/submission/' + submission.id + '/view'" class="text-indigo-600 hover:text-indigo-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <button
                                                    @click="showFeedback(submission)"
                                                    class="text-blue-600 hover:text-blue-900"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                    </svg>
                                                </button>
                                                <a :href="'/storage/' + submission.file_path" target="_blank" class="text-green-600 hover:text-green-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="p-4 bg-gray-50 border-t flex justify-end">
                    <button 
                        @click="show = false" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Kapat
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Geri Bildirim Modal -->
        <div
            x-data="{ 
                show: false,
                feedback: '',
                submissionTitle: ''
            }"
            x-show="show"
            x-on:show-feedback-modal.window="
                show = true;
                feedback = $event.detail.feedback;
                submissionTitle = $event.detail.title;
            "
            x-on:keydown.escape.window="show = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            style="display: none;"
        >
            <div 
                @click.away="show = false"
                class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[80vh] overflow-hidden flex flex-col mx-4"
            >
                <div class="p-4 bg-blue-50 border-b flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800" x-text="'Geri Bildirim: ' + submissionTitle"></h3>
                    <button @click="show = false" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="p-6 overflow-y-auto">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="prose prose-sm max-w-none" x-html="feedback"></div>
                    </div>
                </div>
                
                <div class="p-4 bg-gray-50 border-t flex justify-end">
                    <button 
                        @click="show = false" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Kapat
                    </button>
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