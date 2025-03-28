<!-- resources/views/teacher/submission_evaluate.blade.php -->
@extends('layouts.app')

@section('title', 'Ödev Değerlendirme')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-amber-50 border-b">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">Ödev Değerlendirme</h1>
                <a href="{{ route('ogretmen.course.detail', $course->id) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kursa Dön
                </a>
            </div>
        </div>

        <div class="p-6">
            <!-- Ödev ve Öğrenci Bilgileri -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-medium text-gray-800 mb-3">Ödev Bilgileri</h2>
                    <div class="space-y-2">
                        <div>
                            <span class="text-gray-600 font-medium">Ödev:</span>
                            <span class="ml-2">{{ $submission->homework->title }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">Açıklama:</span>
                            <p class="mt-1 text-gray-700">{{ $submission->homework->description }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">Son Teslim Tarihi:</span>
                            <span class="ml-2">{{ $submission->homework->due_date->format('d.m.Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">Maksimum Puan:</span>
                            <span class="ml-2">{{ $submission->homework->max_score }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-medium text-gray-800 mb-3">Öğrenci Bilgileri</h2>
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($submission->student->name, 0, 1)) }}
                        </div>
                        <div class="ml-4">
                            <div class="font-medium text-gray-900">{{ $submission->student->name }}</div>
                            <div class="text-gray-500">{{ $submission->student->email }}</div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div>
                            <span class="text-gray-600 font-medium">Teslim Tarihi:</span>
                            <span class="ml-2">{{ $submission->submitted_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">Teslim Durumu:</span>
                            @if ($submission->submitted_at <= $submission->homework->due_date)
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Zamanında
                                </span>
                            @else
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Geç Teslim ({{ $submission->submitted_at->diffInDays($submission->homework->due_date) }} gün)
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Öğrenci Teslimi ve Yorum -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-3">Öğrenci Teslimi</h2>
                
                @if ($submission->file_path)
                    <div class="mb-4">
                        <span class="text-gray-600 font-medium">Yüklenen Dosya:</span>
                        <div class="mt-2 flex items-center">
                            <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                {{ basename($submission->file_path) }}
                            </a>
                        </div>
                    </div>
                @endif

                @if ($submission->comment)
                    <div>
                        <span class="text-gray-600 font-medium">Öğrenci Yorumu:</span>
                        <div class="mt-2 p-3 bg-white rounded border border-gray-200">
                            <p class="text-gray-700">{{ $submission->comment }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 italic">Öğrenci herhangi bir yorum eklememiş.</p>
                @endif
            </div>

            <!-- Değerlendirme Formu -->
<!-- Değerlendirme Formu -->
<div class="bg-white border rounded-lg shadow-sm">
    <div class="px-4 py-3 bg-blue-50 border-b">
        <h2 class="text-lg font-medium text-gray-800">Değerlendirme</h2>
    </div>
    <div class="p-4">
        <form action="{{ route('ogretmen.submission.save-evaluation', $submission->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="score" class="block text-sm font-medium text-gray-700 mb-1">Puan (0-100)</label>
                <input type="number" name="score" id="score" min="0" max="100" value="{{ old('score', $submission->score) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                <p class="mt-1 text-sm text-gray-500">Maksimum puan: {{ $submission->homework->max_score }}</p>
            </div>
            <div class="mb-4">
                <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1">Geri Bildirim</label>
                <textarea name="feedback" id="feedback" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('feedback', $submission->feedback) }}</textarea>
            </div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input type="checkbox" name="send_notification" id="send_notification" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="send_notification" class="ml-2 block text-sm text-gray-700">
                        Öğrenciye ve velisine değerlendirme bildirimi gönder
                    </label>
                </div>
                <p class="mt-1 text-xs text-gray-500">Öğrenciye ve velisine ödevin değerlendirildiğine dair SMS bildirimi gönderilecektir.</p>
            </div>
            <div class="flex justify-end space-x-3">
                <a href="{{ route('ogretmen.course.detail', $course->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    İptal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Değerlendirmeyi Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
        </div>
    </div>
</div>
@endsection