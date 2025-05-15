<!-- resources/views/teacher/documents/list.blade.php -->
@extends('layouts.app')

@section('title', 'Belge Yönetimi')

@section('content')
<div class="container px-4 mx-auto">
    <h1 class="mt-4 text-2xl font-semibold">Belge Yönetimi</h1>
    <ol class="flex mb-4 text-sm">
        <li><a href="{{ route('ogretmen.panel') }}" class="text-blue-600 hover:text-blue-800">Panel</a></li>
        <li class="mx-2">/</li>
        <li class="text-gray-600">Belge Yönetimi</li>
    </ol>

    <!-- Kurs Filtresi -->
    <div class="mb-6">
        <div class="p-4 bg-gray-50 rounded-lg">
            <div class="flex justify-between items-center mb-3">
                <h5 class="font-medium flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Kursa Göre Filtrele
                </h5>
                <a href="#newDocumentModal" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" data-bs-toggle="modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Yeni Belge Ekle
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                @if($courses->isEmpty())
                    <div class="col-span-4">
                        <div class="p-3 text-sm bg-yellow-100 text-yellow-800 rounded-md">
                            Henüz aktif kursunuz bulunmamaktadır.
                        </div>
                    </div>
                @else
                    @foreach($courses as $course)
                        <div class="mb-2">
                            <a href="{{ route('ogretmen.documents.index', $course->id) }}" class="inline-block w-full px-4 py-2 text-sm font-medium text-blue-700 bg-white border border-blue-300 rounded-md hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ $course->name }}
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Belge Yönetimi İçeriği -->
    <div class="mb-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 border-b border-gray-200 bg-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="font-medium">Kurslarınızdaki Belgeler</span>
            </div>
            <div class="p-6 bg-white">
                @if(empty($courseDocuments))
                    <div class="text-center py-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-20 w-20 text-gray-300 mb-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Henüz Belge Eklenmemiş</h3>
                        <p class="mt-1 text-sm text-gray-500">Kurslarınıza belge eklemek için yukarıdaki "Yeni Belge Ekle" butonunu kullanabilirsiniz.</p>
                        <a href="#newDocumentModal" class="inline-flex items-center px-4 py-2 mt-6 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" data-bs-toggle="modal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Yeni Belge Ekle
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($courseDocuments as $courseDocument)
                            <div class="bg-white rounded-lg shadow overflow-hidden transform transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                                <div class="bg-blue-600 text-white p-4 relative">
                                    {{ $courseDocument['course']->name }}
                                    <span class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center bg-blue-700 text-white rounded-full font-bold">
                                        {{ $courseDocument['count'] }}
                                    </span>
                                </div>
                                <div class="p-4">
                                    <h5 class="font-medium mb-3">Son Eklenen Belgeler</h5>
                                    <ul class="divide-y divide-gray-200">
                                        @foreach($courseDocument['documents']->take(3) as $document)
                                            <li class="py-3 flex justify-between items-center">
                                                <div class="flex items-center">
                                                    @php
                                                        $fileType = explode('/', $document->file_type)[1] ?? 'file';
                                                        $iconClass = 'document-text';
                                                        
                                                        if (strpos($fileType, 'pdf') !== false) {
                                                            $iconClass = 'document-text';
                                                        } elseif (strpos($fileType, 'word') !== false || strpos($fileType, 'doc') !== false) {
                                                            $iconClass = 'document';
                                                        } elseif (strpos($fileType, 'excel') !== false || strpos($fileType, 'sheet') !== false || strpos($fileType, 'xls') !== false) {
                                                            $iconClass = 'table';
                                                        } elseif (strpos($fileType, 'powerpoint') !== false || strpos($fileType, 'presentation') !== false || strpos($fileType, 'ppt') !== false) {
                                                            $iconClass = 'presentation-chart-line';
                                                        } elseif (strpos($fileType, 'image') !== false || strpos($fileType, 'jpg') !== false || strpos($fileType, 'jpeg') !== false || strpos($fileType, 'png') !== false) {
                                                            $iconClass = 'photograph';
                                                        } elseif (strpos($fileType, 'zip') !== false || strpos($fileType, 'rar') !== false || strpos($fileType, 'archive') !== false) {
                                                            $iconClass = 'archive';
                                                        } elseif (strpos($fileType, 'text') !== false || strpos($fileType, 'txt') !== false) {
                                                            $iconClass = 'document-text';
                                                        } elseif (strpos($fileType, 'audio') !== false || strpos($fileType, 'mp3') !== false || strpos($fileType, 'wav') !== false) {
                                                            $iconClass = 'music-note';
                                                        } elseif (strpos($fileType, 'video') !== false || strpos($fileType, 'mp4') !== false || strpos($fileType, 'mov') !== false) {
                                                            $iconClass = 'film';
                                                        }
                                                    @endphp
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <span class="truncate max-w-[150px]">{{ $document->title }}</span>
                                                </div>
                                                <div>
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $document->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $document->is_active ? 'Aktif' : 'Pasif' }}
                                                    </span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    
                                    @if($courseDocument['count'] > 3)
                                        <div class="mt-3 text-center">
                                            <span class="text-sm text-gray-500">{{ $courseDocument['count'] - 3 }} belge daha...</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="px-4 py-3 bg-gray-50">
                                    <a href="{{ route('ogretmen.documents.index', $courseDocument['course']->id) }}" class="w-full flex justify-center items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                                        </svg>
                                        Tüm Belgeleri Yönet
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Yeni Belge Ekleme Modalı (Tamamen Tailwind) -->
<div id="newDocumentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="newDocumentModalLabel" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Arka Plan Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal Merkezleme -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal İçeriği -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <!-- Modal Başlık -->
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h5 class="text-lg font-medium text-gray-900" id="newDocumentModalLabel">Yeni Belge Ekle</h5>
                <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" data-modal-close>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Modal İçerik -->
            <div class="px-4 py-4 sm:p-6">
                <p class="text-gray-700">Lütfen belge eklemek istediğiniz kursu seçin:</p>
                
                @if($courses->isEmpty())
                    <div class="p-3 my-2 text-sm bg-yellow-100 text-yellow-800 rounded-md">
                        Henüz aktif kursunuz bulunmamaktadır. Belge eklemek için önce aktif bir kursa ihtiyacınız var.
                    </div>
                @else
                    <div class="mt-3 space-y-2">
                        @foreach($courses as $course)
                            <a href="{{ route('ogretmen.documents.create', $course->id) }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <h5 class="font-medium">{{ $course->name }}</h5>
                                </div>
                                <p class="mt-1 text-sm text-gray-600">{{ $course->description ? Str::limit($course->description, 100) : 'Açıklama yok' }}</p>
                                <div class="mt-2 text-xs text-gray-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    {{ $course->students->count() }} öğrenci
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Modal Altbilgi -->
            <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-gray-100 text-base font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" data-modal-close>
                    Kapat
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal JavaScript Kodu: Tailwind için gerekli -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal açma butonları
        const modalOpenButtons = document.querySelectorAll('[data-bs-toggle="modal"]');
        const modalCloseButtons = document.querySelectorAll('[data-modal-close]');
        const modal = document.getElementById('newDocumentModal');
        
        modalOpenButtons.forEach(button => {
            button.addEventListener('click', function() {
                modal.classList.remove('hidden');
            });
        });
        
        modalCloseButtons.forEach(button => {
            button.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
        });
        
        // Backdrop tıklandığında modalı kapat
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
        
        // ESC tuşu ile modalı kapat
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
@endsection