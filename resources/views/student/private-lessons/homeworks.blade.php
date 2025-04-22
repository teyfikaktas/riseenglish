@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Tüm Ödevlerim</h1>

    @if($homeworks->isEmpty())
        <div class="bg-yellow-100 border border-yellow-500 text-yellow-700 p-4 rounded">
            Henüz bir ödev ataması yapılmamış.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg shadow">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Başlık</th>
                        <th class="px-4 py-2 border">Ders</th>
                        <th class="px-4 py-2 border">Son Teslim</th>
                        <th class="px-4 py-2 border">Durum</th>
                        <th class="px-4 py-2 border">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($homeworks as $hw)
                    @php
                        // İlk teslim (veya null)
                        $submission = $hw->submissions->first();
                        $isSubmitted = (bool)$submission;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $hw->title }}</td>
                        <td class="px-4 py-2 border">{{ $hw->session->privateLesson->name }}</td>
                        <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($hw->due_date)->format('d.m.Y') }}</td>
                        <td class="px-4 py-2 border">
                            @if($isSubmitted)
                                <span class="text-green-600 font-semibold">Teslim Edildi</span>
                            @else
                                <span class="text-red-600 font-semibold">Bekliyor</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border space-x-2">
                            @if($isSubmitted)
                                <a href="{{ route('ogrenci.private-lessons.submission-file.download', $submission->id) }}"
                                   class="text-blue-600 hover:underline">Dosyayı İndir</a>
                            @else
                                <a href="{{ route('ogrenci.private-lessons.homework', $hw->id) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Teslim Et</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
