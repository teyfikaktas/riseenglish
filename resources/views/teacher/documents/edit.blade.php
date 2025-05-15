@extends('layouts.app')

@section('title', 'Belge Düzenle - ' . $document->title)

@section('content')
<div class="container mx-auto px-4">
    <h1 class="mt-4 text-2xl font-semibold">{{ $course->name }} - Belge Düzenle</h1>
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
            <li>
                <a href="{{ route('ogretmen.documents.index', $course->id) }}" class="text-blue-600 hover:underline">Belgeler</a>
            </li>
            <li><span class="mx-1">/</span></li>
            <li class="text-gray-500">Belge Düzenle</li>
        </ol>
    </nav>

    {{-- @include('partials.alerts') --}} {{-- Kaldırıldı --}}

    <div class="flex justify-center mt-6">
        <div class="w-full lg:w-2/3">
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b flex items-center">
                    <i class="fas fa-edit text-gray-700 mr-2"></i>
                    <h2 class="text-lg font-medium">{{ $document->title }} - Düzenle</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('ogretmen.documents.update', [$course->id, $document->id]) }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">
                                Belge Başlığı <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="title"
                                id="title"
                                value="{{ old('title', $document->title) }}"
                                required
                                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                            >
                            @error('title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Açıklama</label>
                            <textarea
                                name="description"
                                id="description"
                                rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                            >{{ old('description', $document->description) }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Mevcut Dosya</label>
                            <div class="mt-1 flex items-center border border-gray-200 rounded p-4 bg-gray-50">
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
                                <i class="fas {{ $iconClass }} text-blue-500 text-2xl mr-4"></i>
                                <div>
                                    <p class="font-medium">{{ $document->file_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $document->formatted_size }} &middot; {{ $document->created_at->format('d.m.Y H:i') }}</p>
                                </div>
                                <a
                                    href="{{ route('ogretmen.documents.download', [$course->id, $document->id]) }}"
                                    class="ml-auto inline-flex items-center border border-blue-500 text-blue-500 px-3 py-1 rounded text-sm hover:bg-blue-50"
                                >
                                    <i class="fas fa-download mr-1"></i> İndir
                                </a>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700">Yeni Dosya Yükle (İsteğe Bağlı)</label>
                            <input
                                type="file"
                                name="file"
                                id="file"
                                class="mt-1 block w-full text-sm text-gray-700 @error('file') border-red-500 @enderror"
                            >
                            <p class="text-gray-500 text-xs mt-1">Yeni bir dosya yüklerseniz, mevcut dosya değiştirilecektir. Maksimum boyut: 20MB.</p>
                            @error('file')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4 flex items-start">
                            <input
                                type="checkbox"
                                name="is_active"
                                id="is_active"
                                value="1"
                                {{ old('is_active', $document->is_active) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            >
                            <div class="ml-2">
                                <label for="is_active" class="block text-sm font-medium text-gray-700">Aktif</label>
                                <p class="text-gray-500 text-xs">Aktif olmayan belgeler öğrenciler tarafından görüntülenmez.</p>
                            </div>
                        </div>

                        <div class="mb-6 flex items-start">
                            <input
                                type="checkbox"
                                name="students_can_download"
                                id="students_can_download"
                                value="1"
                                {{ old('students_can_download', $document->students_can_download) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            >
                            <label for="students_can_download" class="ml-2 block text-sm font-medium text-gray-700">
                                Öğrenciler bu belgeyi indirebilsin
                            </label>
                        </div>

                        <div class="flex justify-between">
                            <a
                                href="{{ route('ogretmen.documents.index', $course->id) }}"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-4 py-2 rounded"
                            >
                                <i class="fas fa-arrow-left mr-1"></i> Geri Dön
                            </a>
                            <button
                                type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded"
                            >
                                <i class="fas fa-save mr-1"></i> Değişiklikleri Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Dosya boyutu kontrolü
    document.getElementById('file').addEventListener('change', function() {
        const fileInput = this;
        const maxSizeInMB = 20;
        const maxSizeInBytes = maxSizeInMB * 1024 * 1024;
        
        if (fileInput.files.length > 0) {
            const fileSize = fileInput.files[0].size;
            
            if (fileSize > maxSizeInBytes) {
                alert(`Dosya boyutu çok büyük! Maksimum ${maxSizeInMB}MB yükleyebilirsiniz.`);
                fileInput.value = ''; // Dosya seçimini temizle
            }
        }
    });
</script>
@endsection
