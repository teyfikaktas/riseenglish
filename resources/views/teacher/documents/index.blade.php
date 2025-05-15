@extends('layouts.app')

@section('title', 'Kurs Belgeleri - ' . $course->name)

@section('content')
<div class="container mx-auto px-4">
    <h1 class="mt-4 text-2xl font-semibold">{{ $course->name }} - Belge Yönetimi</h1>
    <nav class="flex mt-2 text-sm text-gray-500" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1">
            <li>
                <a href="{{ route('ogretmen.panel') }}" class="text-blue-600 hover:underline">Panel</a>
            </li>
            <li><span class="mx-1">/</span></li>
            <li>
                <a href="{{ route('ogretmen.course.detail', $course->id) }}" class="text-blue-600 hover:underline">{{ $course->name }}</a>
            </li>
            <li><span class="mx-1">/</span></li>
            <li class="text-gray-500">Belgeler</li>
        </ol>
    </nav>

    {{-- @include('partials.alerts') --}}  {{-- Removed as requested --}}

    <div class="mt-4">
        <div class="flex flex-col mb-4">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-3 flex justify-between items-center border-b">
                    <span class="text-gray-800 font-medium"><i class="fas fa-file-alt mr-1"></i> Belge Listesi</span>
                    <a href="{{ route('ogretmen.documents.create', $course->id) }}"
                       class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded">
                        <i class="fas fa-plus"></i> Yeni Belge Ekle
                    </a>
                </div>
                <div class="p-4">
                    @if($documents->isEmpty())
                        <div class="p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded">
                            Bu kursa henüz belge eklenmemiş. Yeni belge eklemek için "Yeni Belge Ekle" butonuna tıklayın.
                        </div>
                    @else
                        <div class="flex flex-wrap -mx-2">
                            @foreach($documents as $document)
                                <div class="w-full md:w-1/3 px-2 mb-4">
                                    <div class="h-full border {{ $document->is_active ? 'border-blue-500' : 'border-gray-300' }} bg-white shadow rounded-lg transform transition hover:-translate-y-1 hover:shadow-lg">
                                        <div class="p-4">
                                            <div class="text-center mb-3">
                                                @php
                                                    $fileType = explode('/', $document->file_type)[1] ?? 'file';
                                                    $iconClass = 'fa-file-alt';
                                                    if (strpos($fileType, 'pdf') !== false) {
                                                        $iconClass = 'fa-file-pdf';
                                                    } elseif (strpos($fileType, 'word') !== false || strpos($fileType, 'doc') !== false) {
                                                        $iconClass = 'fa-file-word';
                                                    } elseif (strpos($fileType, 'excel') !== false || strpos($fileType, 'sheet') !== false || strpos($fileType, 'xls') !== false) {
                                                        $iconClass = 'fa-file-excel';
                                                    } elseif (strpos($fileType, 'powerpoint') !== false || strpos($fileType, 'presentation') !== false || strpos($fileType, 'ppt') !== false) {
                                                        $iconClass = 'fa-file-powerpoint';
                                                    } elseif (strpos($fileType, 'image') !== false || strpos($fileType, 'jpg') !== false || strpos($fileType, 'jpeg') !== false || strpos($fileType, 'png') !== false) {
                                                        $iconClass = 'fa-file-image';
                                                    } elseif (strpos($fileType, 'zip') !== false || strpos($fileType, 'rar') !== false || strpos($fileType, 'archive') !== false) {
                                                        $iconClass = 'fa-file-archive';
                                                    } elseif (strpos($fileType, 'text') !== false || strpos($fileType, 'txt') !== false) {
                                                        $iconClass = 'fa-file-alt';
                                                    } elseif (strpos($fileType, 'audio') !== false || strpos($fileType, 'mp3') !== false || strpos($fileType, 'wav') !== false) {
                                                        $iconClass = 'fa-file-audio';
                                                    } elseif (strpos($fileType, 'video') !== false || strpos($fileType, 'mp4') !== false || strpos($fileType, 'mov') !== false) {
                                                        $iconClass = 'fa-file-video';
                                                    }
                                                @endphp
                                                <i class="fas {{ $iconClass }} text-blue-500 text-4xl"></i>
                                            </div>

                                            <h5 class="text-lg font-semibold">{{ $document->title }}</h5>

                                            @if($document->description)
                                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($document->description, 50) }}</p>
                                            @endif

                                            <ul class="mt-3 space-y-1">
                                                <li class="flex justify-between text-sm">
                                                    <span>Dosya Adı:</span>
                                                    <span class="text-gray-600">{{ Str::limit($document->file_name, 20) }}</span>
                                                </li>
                                                <li class="flex justify-between text-sm">
                                                    <span>Boyut:</span>
                                                    <span class="text-gray-600">{{ $document->formatted_size }}</span>
                                                </li>
                                                <li class="flex justify-between text-sm">
                                                    <span>Yüklenme Tarihi:</span>
                                                    <span class="text-gray-600">{{ $document->created_at->format('d.m.Y H:i') }}</span>
                                                </li>
                                                <li class="flex justify-between text-sm">
                                                    <span>Durum:</span>
                                                    <span class="{{ $document->is_active ? 'text-green-500' : 'text-red-500' }}">
                                                        {{ $document->is_active ? 'Aktif' : 'Pasif' }}
                                                    </span>
                                                </li>
                                                <li class="flex justify-between text-sm">
                                                    <span>Öğrenci İndirebilir:</span>
                                                    <span class="{{ $document->students_can_download ? 'text-green-500' : 'text-red-500' }}">
                                                        {{ $document->students_can_download ? 'Evet' : 'Hayır' }}
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="px-4 py-3 border-t flex space-x-2">
                                            <a href="{{ route('ogretmen.documents.download', [$course->id, $document->id]) }}"
                                               class="flex-1 text-center border border-blue-500 text-blue-500 text-sm py-1 rounded hover:bg-blue-50">
                                                <i class="fas fa-download mr-1"></i> İndir
                                            </a>
                                            <a href="{{ route('ogretmen.documents.edit', [$course->id, $document->id]) }}"
                                               class="flex-1 text-center border border-yellow-500 text-yellow-600 text-sm py-1 rounded hover:bg-yellow-50">
                                                <i class="fas fa-edit mr-1"></i> Düzenle
                                            </a>
                                            <div x-data="{ open: false }" class="flex-1">
                                                <button @click="open = true"
                                                        class="w-full text-center border border-red-500 text-red-600 text-sm py-1 rounded hover:bg-red-50">
                                                    <i class="fas fa-trash mr-1"></i> Sil
                                                </button>
                                                <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                    <div class="bg-white rounded-lg overflow-hidden shadow-lg w-full max-w-md">
                                                        <div class="px-4 py-2 flex justify-between items-center border-b">
                                                            <h5 class="text-lg font-medium">Belge Silme Onayı</h5>
                                                            <button @click="open = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                                                        </div>
                                                        <div class="p-4">
                                                            <p>"{{ $document->title }}" başlıklı belgeyi silmek istediğinize emin misiniz?</p>
                                                            <p class="text-red-600">Bu işlem geri alınamaz!</p>
                                                        </div>
                                                        <div class="px-4 py-2 flex justify-end space-x-2 border-t">
                                                            <button @click="open = false"
                                                                    class="px-3 py-1 text-sm text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                                                                İptal
                                                            </button>
                                                            <form action="{{ route('ogretmen.documents.destroy', [$course->id, $document->id]) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700">
                                                                    Evet, Sil
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
