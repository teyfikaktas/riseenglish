<!-- resources/views/teacher/private-lessons/homework-submissions.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Ödev Teslimleri</h1>
        <a href="{{ route('ogretmen.private-lessons.session.show', $session->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Derse Dön</a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Ödev Bilgileri</h2>
        <div class="mb-4">
            <p><span class="font-medium">Başlık:</span> {{ $homework->title }}</p>
            <p><span class="font-medium">Açıklama:</span> {{ $homework->description }}</p>
            <p><span class="font-medium">Son Teslim Tarihi:</span> {{ \Carbon\Carbon::parse($homework->due_date)->format('d.m.Y') }}</p>
            @if($homework->file_path)
            <p class="mt-2">
                <a href="{{ route('ogretmen.private-lessons.homework.download', $homework->id) }}" class="text-blue-600 hover:underline">Ödev dosyasını indir</a>
            </p>
            @endif
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Teslimler</h2>
        
        @if($homework->submissions && $homework->submissions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Öğrenci</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Tarihi</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puan</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($homework->submissions as $submission)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $submission->student->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $submission->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($submission->score !== null)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $submission->score }} / 100
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Değerlendirilmedi
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('ogretmen.private-lessons.submission.view', $submission->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Detay</a>
                                
                                @if($submission->file_path)
                                <a href="{{ route('ogretmen.private-lessons.submission.download', $submission->id) }}" class="text-green-600 hover:text-green-900">İndir</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-3 rounded">
                Bu ödeve henüz teslim yapılmamış.
            </div>
        @endif
    </div>
</div>
@endsection