<!-- resources/views/teacher/private-lessons/add-homework.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Ödev Ekle</h1>
        <a href="{{ route('ogretmen.private-lessons.session.show', $session->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Geri</a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-4">
            <p><span class="font-medium">Ders:</span> {{ $session->privateLesson->name }}</p>
            <p><span class="font-medium">Öğrenci:</span> {{ $session->student->name }}</p>
            <p><span class="font-medium">Tarih:</span> {{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}</p>
        </div>
        
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
                    Ödev Ekle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection