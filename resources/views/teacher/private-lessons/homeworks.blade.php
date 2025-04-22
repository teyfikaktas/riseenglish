@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Verdiğim Ödevler</h1>

    @if($homeworks->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md text-yellow-800">
            Henüz eklenmiş ödev yok.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($homeworks as $hw)
                @php
                    $studentName = $hw->session->student->name;
                    $lessonName  = $hw->session->privateLesson->name;
                    $dueDate     = \Carbon\Carbon::parse($hw->due_date)->format('d.m.Y');
                    $count       = $hw->submissions->count();
                @endphp

                <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4
                            hover:shadow-lg transition-shadow">
                    <!-- Başlık & Tag -->
                    <div class="px-5 py-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-800 truncate">{{ $hw->title }}</h2>
                    </div>

                    <!-- İçerik -->
                    <div class="p-5 space-y-3">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Öğrenci:</span> {{ $studentName }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Ders:</span> {{ $lessonName }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Son Teslim:</span> {{ $dueDate }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Teslim Sayısı:</span> {{ $count }}
                        </p>
                    </div>

                    <!-- Aksiyon Butonları -->
                    <div class="px-5 py-4 bg-gray-50 flex justify-end space-x-2">
                        <a href="{{ route('ogretmen.private-lessons.homework.submissions', $hw->id) }}"
                           class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm font-medium
                                  rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4 mr-1"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                            </svg>
                            Teslimleri Gör
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
