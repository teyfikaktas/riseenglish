@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-4 md:p-5 border border-gray-100 max-w-3xl mx-auto">
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-indigo-800">Materyal Ekle</h1>
        <a href="{{ route('ogretmen.private-lessons.session.show', $session->id) }}" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 flex items-center text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
            </svg>
            Geri Dön
        </a>
    </div>
    
    <!-- Ders Bilgileri -->
    <div class="bg-gray-50 p-3 rounded-lg shadow-sm mb-4">
        <h2 class="text-md font-semibold text-gray-700 mb-2">Ders Bilgileri</h2>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-xs text-gray-500 mb-0.5">Ders</p>
                <p class="font-medium text-gray-800 text-sm">{{ $session->privateLesson ? $session->privateLesson->name : 'Ders' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-0.5">Öğrenci</p>
                <p class="font-medium text-gray-800 text-sm">{{ $session->student ? $session->student->name : 'Öğrenci Atanmamış' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-0.5">Tarih</p>
                <p class="font-medium text-gray-800 text-sm">{{ Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-0.5">Saat</p>
                <p class="font-medium text-gray-800 text-sm">{{ Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ Carbon\Carbon::parse($session->end_time)->format('H:i') }}</p>
            </div>
        </div>
    </div>
    
    <!-- Materyal Ekleme Formu -->
    <form action="{{ route('ogretmen.private-lessons.material.store', $session->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Materyal Başlığı</label>
            <input type="text" name="title" id="title" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required value="{{ old('title') }}">
            @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Açıklama (Opsiyonel)</label>
            <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-6">
            <label for="material_file" class="block text-sm font-medium text-gray-700 mb-1">Dosya Yükle</label>
            <div class="border border-gray-300 border-dashed rounded-lg p-4">
                <div class="flex flex-col items-center space-y-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-sm text-gray-500">Dosya seçmek için tıklayın veya buraya sürükleyin</p>
                    <p class="text-xs text-gray-400">Maksimum dosya boyutu: 10MB</p>
                </div>
                <input type="file" name="material_file" id="material_file" class="hidden" onchange="updateFileLabel(this)" required>
            </div>
            <div id="file-label" class="text-sm text-indigo-600 mt-2 hidden"></div>
            @error('material_file')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex justify-end space-x-3">
            <a href="{{ route('ogretmen.private-lessons.session.show', $session->id) }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-all duration-200 text-sm">
                İptal
            </a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-sm text-sm">
                Materyali Kaydet
            </button>
        </div>
    </form>
</div>

<script>
    // Dosya seçme işlevi
    document.querySelector('#material_file').parentElement.addEventListener('click', function() {
        document.querySelector('#material_file').click();
    });
    
    // Seçilen dosya adını göster
    function updateFileLabel(input) {
        const fileLabel = document.getElementById('file-label');
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            fileLabel.textContent = 'Seçilen dosya: ' + fileName;
            fileLabel.classList.remove('hidden');
        } else {
            fileLabel.classList.add('hidden');
        }
    }
    
    // Drag & drop işlevselliği
    const dropArea = document.querySelector('#material_file').parentElement;
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropArea.classList.add('border-indigo-400', 'bg-indigo-50');
    }
    
    function unhighlight() {
        dropArea.classList.remove('border-indigo-400', 'bg-indigo-50');
    }
    
    dropArea.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        const fileInput = document.querySelector('#material_file');
        fileInput.files = files;
        updateFileLabel(fileInput);
    }
</script>
@endsection