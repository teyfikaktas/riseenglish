@extends('layouts.app')

@section('title', 'Kurs Belgeleri - ' . $course->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold mb-4">{{ $course->name }} - Ders Belgeleri</h1>

    <nav class="text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1">
            <li>
                <a href="{{ route('ogrenci.kurslarim') }}" class="text-blue-600 hover:underline">Kurslarım</a>
            </li>
            <li><span class="mx-2">/</span></li>
            <li>
                <a href="{{ route('ogrenci.kurs-detay', $course->slug) }}" class="text-blue-600 hover:underline">{{ $course->name }}</a>
            </li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-500">Belgeler</li>
        </ol>
    </nav>

    {{-- @include('partials.alerts') --}}

    <div class="bg-white shadow rounded-lg">
        <div class="px-5 py-3 bg-blue-500 rounded-t-lg text-white flex items-center">
            <i class="fas fa-file-alt mr-2"></i>
            <span class="font-medium">Kurs Belgeleri</span>
        </div>
        <div class="p-5">
            @if($documents->isEmpty())
                <div class="text-center py-12 px-4">
                    <p class="text-gray-600">Bu kursa henüz belge eklenmemiş.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($documents as $document)
                        @if($document->is_active)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm transform transition hover:-translate-y-1 hover:shadow-lg">
                                <div class="p-5 text-center">
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
                                    <i class="fas {{ $iconClass }} text-blue-500 text-5xl mb-4"></i>
                                    <h5 class="text-lg font-semibold">{{ $document->title }}</h5>
                                    @if($document->description)
                                        <p class="text-gray-600 mt-2">{{ $document->description }}</p>
                                    @endif
                                </div>
                                <div class="px-5 pb-5">
                                    <ul class="space-y-2 text-sm text-gray-700">
                                        <li class="flex justify-between">
                                            <span>Dosya Adı:</span>
                                            <span class="text-gray-500">{{ Str::limit($document->file_name, 20) }}</span>
                                        </li>
                                        <li class="flex justify-between">
                                            <span>Boyut:</span>
                                            <span class="text-gray-500">{{ $document->formatted_size }}</span>
                                        </li>
                                        <li class="flex justify-between">
                                            <span>Yüklenme Tarihi:</span>
                                            <span class="text-gray-500">{{ $document->created_at->format('d.m.Y H:i') }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="px-5 pb-5">
                                    @if($document->students_can_download)
                                        <a href="{{ route('ogrenci.documents.download', [$course->slug, $document->id]) }}"
                                           class="block bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded">
                                            <i class="fas fa-download mr-1"></i> İndir
                                        </a>
                                    @else
                                        <button class="w-full bg-gray-300 text-gray-600 py-2 rounded cursor-not-allowed">
                                            <i class="fas fa-lock mr-1"></i> İndirme Kısıtlı
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
