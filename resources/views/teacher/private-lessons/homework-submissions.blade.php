@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <!-- Başlık -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $homework->title }}</h1>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span>Son Tarih: <strong>{{ \Carbon\Carbon::parse($homework->due_date)->format('d.m.Y') }}</strong></span>
                    @if($isGroupLesson)
                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full font-medium">
                            👥 {{ count($studentData) }} Öğrenci
                        </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('ogretmen.private-lessons.show', $homework->session_id) }}"
               class="text-gray-600 hover:text-gray-900 font-medium">
                ← Geri
            </a>
        </div>
    </div>

    <!-- Öğrenci Listesi -->
    @if(empty($studentData) || collect($studentData)->every(fn($data) => !$data['submission']))
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
            <div class="text-gray-400 mb-2">
                <svg class="w-12 h-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <p class="text-gray-600">Henüz teslim yapılmamış</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($studentData as $data)
                @php
                    $student = $data['student'];
                    $submission = $data['submission'];
                    $studentHomework = $data['homework'];
                    $hasFeedback = $submission && ($submission->teacher_feedback || $submission->score);
                @endphp
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Öğrenci Header -->
                    <div class="px-6 py-4 {{ $hasFeedback ? 'bg-blue-50 border-b-2 border-blue-200' : ($submission ? 'bg-green-50 border-b-2 border-green-200' : 'bg-gray-50 border-b border-gray-200') }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($student->name ?? 'Ö', 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $student->name ?? 'Öğrenci' }}</h3>
                                    <div class="flex items-center gap-2 text-sm">
                                        @if($submission)
                                            @if($hasFeedback)
                                                <span class="text-blue-700 font-medium">✓ Değerlendirildi</span>
                                                @if($submission->score)
                                                    <span class="bg-blue-600 text-white px-2 py-0.5 rounded font-bold text-xs">
                                                        {{ $submission->score }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-green-700 font-medium">✓ Teslim edildi</span>
                                                <span class="text-gray-500">{{ $submission->created_at->format('d.m.Y H:i') }}</span>
                                            @endif
                                        @else
                                            <span class="text-yellow-700">⏳ Beklemede</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($submission)
                        <div class="p-6 space-y-4">
                            <!-- Öğrenci Açıklaması -->
                            @if($submission->submission_content)
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-1">Öğrenci Notu</p>
                                    <p class="text-gray-700 whitespace-pre-line">{{ $submission->submission_content }}</p>
                                </div>
                            @endif

                            <!-- Dosyalar -->
                            @if($submission->files && $submission->files->count() > 0)
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-2">Dosyalar ({{ $submission->files->count() }})</p>
                                    <div class="space-y-2">
                                        @foreach($submission->files as $file)
                                            <a href="{{ route('ogretmen.private-lessons.submission-file.download', ['homeworkId' => $studentHomework->id, 'fileId' => $file->id]) }}"
                                               class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition">
                                                <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span class="flex-1 text-sm font-medium text-gray-700">{{ $file->original_filename }}</span>
                                                <span class="text-xs text-gray-500">{{ number_format($file->file_size / 1024, 1) }} KB</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Değerlendirme Formu -->
                            <div class="border-t pt-4 mt-4">
                                <form action="{{ route('ogretmen.private-lessons.submission.grade', $submission->id) }}" method="POST" class="space-y-4">
                                    @csrf
                                    
                                    @if($hasFeedback)
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                            <p class="text-sm font-medium text-blue-900 mb-2">Mevcut Değerlendirme:</p>
                                            @if($submission->teacher_feedback)
                                                <p class="text-sm text-blue-800 mb-2 whitespace-pre-line">{{ $submission->teacher_feedback }}</p>
                                            @endif
                                            @if($submission->score)
                                                <span class="inline-block bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-bold">
                                                    {{ $submission->score }}/100
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Geri Bildirim</label>
                                        <textarea name="feedback" rows="3" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                                  placeholder="Öğrenciye notunuzu yazın...">{{ $submission->teacher_feedback ?? '' }}</textarea>
                                    </div>
                                    
                                    <div class="flex gap-4">
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Puan</label>
                                            <input type="number" name="score" min="0" max="100" 
                                                   value="{{ $submission->score ?? '' }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                   placeholder="0-100">
                                        </div>
                                        <div class="flex items-end">
                                            <button type="submit"
                                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                                                {{ $hasFeedback ? 'Güncelle' : 'Kaydet' }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="p-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>Teslim yapılmamış</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection