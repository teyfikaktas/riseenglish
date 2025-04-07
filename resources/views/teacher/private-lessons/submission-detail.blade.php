<!-- resources/views/teacher/private-lessons/submission-detail.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Ödev Teslimi Detayı</h1>
        <a href="{{ route('ogretmen.private-lessons.homework.submissions', $submission->homework_id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Geri</a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Ödev Bilgileri</h2>
        <div class="mb-4">
            <p><span class="font-medium">Ödev:</span> {{ $submission->homework->title }}</p>
            <p><span class="font-medium">Öğrenci:</span> {{ $submission->student->name }}</p>
            <p><span class="font-medium">Teslim Tarihi:</span> {{ $submission->created_at->format('d.m.Y H:i') }}</p>
            @if($submission->file_path)
            <p class="mt-2">
                <a href="{{ route('ogretmen.private-lessons.submission.download', $submission->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Ödevi İndir</a>
            </p>
            @endif
        </div>
    </div>
    
    @if($submission->submission_content)
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Öğrenci Cevabı</h2>
        <div class="bg-gray-50 p-4 rounded border border-gray-200">
            {!! nl2br(e($submission->submission_content)) !!}
        </div>
    </div>
    @endif
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Değerlendirme</h2>
        
        <form action="{{ route('ogretmen.private-lessons.submission.grade', $submission->id) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="score" class="block text-gray-700 text-sm font-bold mb-2">Puan (0-100):</label>
                <input type="number" name="score" id="score" min="0" max="100" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required value="{{ old('score', $submission->score) }}">
            </div>
            
            <div class="mb-4">
                <label for="teacher_feedback" class="block text-gray-700 text-sm font-bold mb-2">Öğretmen Geri Bildirimi:</label>
                <textarea name="teacher_feedback" id="teacher_feedback" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('teacher_feedback', $submission->teacher_feedback) }}</textarea>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Değerlendirmeyi Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
@endsection