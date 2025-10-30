@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Ödev Ekle</h1>
        <a href="{{ route('ogretmen.private-lessons.session.show', $session->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Geri</a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <p class="mb-2"><span class="font-medium">Ders:</span> {{ $session->privateLesson->name }}</p>
            
            @php
                $isGroupLesson = $session->group_id !== null;
                
                if ($isGroupLesson) {
                    $groupSessions = $session->groupSessions()->with('student')->get();
                    $students = $groupSessions->pluck('student')->filter();
                } else {
                    $students = collect([$session->student]);
                }
            @endphp
            
            @if($isGroupLesson)
                <div class="mb-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold bg-purple-100 text-purple-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        GRUP DERSİ
                    </span>
                </div>
                <div class="mt-3">
                    <p class="font-medium mb-2">Öğrenciler:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($students as $student)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                {{ $student->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @else
                <p><span class="font-medium">Öğrenci:</span> {{ $session->student->name }}</p>
            @endif
            
            <p class="mt-2"><span class="font-medium">Tarih:</span> {{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}</p>
        </div>set
        
        @if($isGroupLesson)
            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>📌 Not:</strong> Bu ödev <strong>{{ $students->count() }} öğrenciye</strong> birden verilecektir.
                </p>
            </div>
        @endif
        
        <form action="{{ route('ogretmen.private-lessons.homework.store', $session->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Ödev Başlığı:</label>
                <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required value="{{ old('title') }}">
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Ödev Açıklaması:</label>
                <textarea name="description" id="description" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('description') }}</textarea>
            </div>
            
            <div class="mb-4">
                <label for="due_date" class="block text-gray-700 text-sm font-bold mb-2">Son Teslim Tarihi:</label>
                <input type="date" name="due_date" id="due_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required value="{{ old('due_date', \Carbon\Carbon::now()->addDays(7)->format('Y-m-d')) }}">
            </div>
            
            <div class="mb-4">
                <label for="file" class="block text-gray-700 text-sm font-bold mb-2">Örnek Dosya (Opsiyonel):</label>
                <input type="file" name="file" id="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p class="text-sm text-gray-500 mt-1">Maksimum dosya boyutu: 10MB</p>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    {{ $isGroupLesson ? "Tüm Öğrencilere Ödev Ekle" : "Ödev Ekle" }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection