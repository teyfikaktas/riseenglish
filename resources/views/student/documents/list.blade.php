@extends('layouts.app')

@section('title', 'Ders Belgeleri')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold mb-4">Ders Belgeleri</h1>

    <nav class="text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1">
            <li>
                <a href="{{ route('ogrenci.kurslarim') }}" class="text-blue-600 hover:underline">Kurslarım</a>
            </li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-500">Ders Belgeleri</li>
        </ol>
    </nav>

    {{-- @include('partials.alerts') --}}  {{-- Kaldırıldı --}}

    {{-- Kurs Filtresi --}}
    @if(!$enrolledCourses->isEmpty())
        <div class="bg-gray-100 rounded-lg p-4 mb-6">
            <div class="flex items-center mb-2 text-gray-700">
                <i class="fas fa-filter mr-2"></i>
                <span class="font-medium">Kursa Göre Filtrele</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($enrolledCourses as $course)
                    <a href="{{ route('ogrenci.documents.index', $course->slug) }}"
                       class="block border border-blue-500 text-blue-500 text-center py-2 rounded hover:bg-blue-50">
                        {{ $course->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Belge Listesi --}}
    <div class="bg-white shadow rounded-lg">
        <div class="px-5 py-3 bg-blue-500 rounded-t-lg text-white flex items-center">
            <i class="fas fa-file-alt mr-2"></i>
            <span class="font-medium">Kurslarınızdaki Belgeler</span>
        </div>
        <div class="p-5">
            @if(empty($courseDocuments))
                <div class="text-center py-12 px-4">
                    <i class="fas fa-file-alt text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Belge Bulunamadı</h3>
                    <p class="text-gray-500 mb-4">
                        Kurslarınızda henüz belge bulunmamaktadır. Öğretmenleriniz belge eklediğinde burada görüntülenecektir.
                    </p>
                    <a href="{{ route('ogrenci.kurslarim') }}"
                       class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        <i class="fas fa-arrow-left mr-1"></i> Kurslarıma Dön
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($courseDocuments as $courseDocument)
                        <div class="relative bg-white border border-gray-200 rounded-lg shadow-sm transition transform hover:-translate-y-1 hover:shadow-lg">
                            <div class="bg-blue-500 text-white rounded-t-lg px-4 py-3 flex items-center justify-between">
                                <span class="font-medium">{{ $courseDocument['course']->name }}</span>
                                <span class="absolute top-3 right-3 bg-white text-blue-500 w-8 h-8 rounded-full flex items-center justify-center font-bold">
                                    {{ $courseDocument['count'] }}
                                </span>
                            </div>
                            <div class="px-4 py-4">
                                <h5 class="text-gray-700 font-medium mb-2">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $courseDocument['course']->teacher->name }}
                                </h5>
                                <hr class="mb-3">
                                <h6 class="text-gray-600 font-medium mb-2">Son Eklenen Belgeler</h6>
                                <ul class="space-y-2">
                                    @foreach($courseDocument['documents']->take(3) as $document)
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
                                        <li class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas {{ $iconClass }} text-blue-500 mr-2"></i>
                                                <span class="text-gray-700">{{ Str::limit($document->title, 20) }}</span>
                                            </div>
                                            <div>
                                                @if($document->students_can_download)
                                                    <a href="{{ route('ogrenci.documents.download', [$courseDocument['course']->slug, $document->id]) }}"
                                                       class="inline-flex items-center border border-blue-500 text-blue-500 text-sm px-2 py-1 rounded hover:bg-blue-50">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @else
                                                    <button class="inline-flex items-center border border-gray-300 text-gray-400 text-sm px-2 py-1 rounded" disabled title="İndirme kısıtlı">
                                                        <i class="fas fa-lock"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                                @if($courseDocument['count'] > 3)
                                    <div class="mt-3 text-center">
                                        <span class="text-gray-500 text-sm">{{ $courseDocument['count'] - 3 }} belge daha...</span>
                                    </div>
                                @endif
                            </div>
                            <div class="px-4 py-3 border-t">
                                <a href="{{ route('ogrenci.documents.index', $courseDocument['course']->slug) }}"
                                   class="block bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded">
                                    <i class="fas fa-folder-open mr-1"></i> Tüm Belgeleri Görüntüle
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
