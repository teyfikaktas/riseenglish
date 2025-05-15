@extends('layouts.app')

@section('title', 'Yeni Belge Ekle - ' . $course->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold mb-4">{{ $course->name }} - Yeni Belge Ekle</h1>

    <nav class="text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1">
            <li>
                <a href="{{ route('ogretmen.panel') }}" class="text-blue-600 hover:underline">Panel</a>
            </li>
            <li><span class="mx-2">/</span></li>
            <li>
                <a href="{{ route('ogretmen.course.detail', $course->id) }}" class="text-blue-600 hover:underline">
                    {{ $course->name }}
                </a>
            </li>
            <li><span class="mx-2">/</span></li>
            <li>
                <a href="{{ route('ogretmen.documents.index', $course->id) }}" class="text-blue-600 hover:underline">
                    Belgeler
                </a>
            </li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-500">Yeni Belge Ekle</li>
        </ol>
    </nav>

    {{-- @include('partials.alerts') --}} {{-- Kaldırıldı --}}

    <div class="flex justify-center">
        <div class="w-full lg:w-2/3">
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b flex items-center">
                    <i class="fas fa-file-upload text-gray-700 mr-2"></i>
                    <h2 class="text-lg font-medium">Yeni Belge Ekle</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('ogretmen.documents.store', $course->id) }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">
                                Belge Başlığı <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="title"
                                id="title"
                                value="{{ old('title') }}"
                                required
                                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                            >
                            @error('title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Açıklama
                            </label>
                            <textarea
                                name="description"
                                id="description"
                                rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700">
                                Dosya <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="file"
                                name="file"
                                id="file"
                                required
                                class="mt-1 block w-full text-sm text-gray-700 @error('file') border-red-500 @enderror"
                            >
                            <p class="text-gray-500 text-xs mt-1">
                                İzin verilen maksimum dosya boyutu: 20MB
                            </p>
                            @error('file')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6 flex items-start">
                            <input
                                type="checkbox"
                                name="students_can_download"
                                id="students_can_download"
                                value="1"
                                {{ old('students_can_download', '1') == '1' ? 'checked' : '' }}
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
                                <i class="fas fa-save mr-1"></i> Belgeyi Yükle
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
