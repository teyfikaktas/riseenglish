@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Ödev: {{ $homework->title }}</h2>
        <p><span class="font-medium">Son Teslim Tarihi:</span> 
           {{ \Carbon\Carbon::parse($homework->due_date)->format('d.m.Y') }}
        </p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Teslimler</h2>

        @if($homework->submissions->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-3 rounded">
                Bu ödeve henüz teslim yapılmamış.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Öğrenci</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teslim Tarihi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosyalar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detay</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($homework->submissions as $submission)
                        <tr>
                            <td class="px-6 py-4">{{ $submission->student->name }}</td>
                            <td class="px-6 py-4">{{ $submission->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-4">
                                @foreach($submission->files as $file)
                                    <a href="{{ route('ogretmen.private-lessons.submission-file.download', [
                                            'homeworkId' => $homework->id,
                                            'fileId'     => $file->id
                                        ]) }}"
                                        class="text-blue-600 hover:underline block">
                                        {{ $file->original_filename }}
                                    </a>
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('ogretmen.private-lessons.submission.view', $submission->id) }}"
                                   class="text-green-600 hover:text-green-900">Detay</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
