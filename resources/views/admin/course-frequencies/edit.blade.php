@extends('layouts.app')

@section('title', 'Kurs Frekansı Düzenle')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <!-- Başlık ve Geri Butonu -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Kurs Frekansı Düzenle</h1>
            <a href="{{ route('admin.course-frequencies.index') }}" class="flex items-center text-indigo-600 hover:text-indigo-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Listeye Dön
            </a>
        </div>

        <!-- Formlar için hata bildirimleri -->
        @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">Lütfen aşağıdaki hataları düzeltiniz:</p>
                    <ul class="mt-2 text-sm list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Form -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <form action="{{ route('admin.course-frequencies.update', $frequency->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Frekans Adı <span class="text-red-600">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $frequency->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    <p class="mt-1 text-xs text-gray-500">Örnek: Haftalık, Aylık, Günlük vb.</p>
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                    <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $frequency->description) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Frekans ile ilgili açıklayıcı bilgiler yazabilirsiniz.</p>
                </div>
                
                <div class="mb-6">
                    <label for="sessions_per_week" class="block text-sm font-medium text-gray-700 mb-2">Haftalık Seans Sayısı <span class="text-red-600">*</span></label>
                    <input type="number" name="sessions_per_week" id="sessions_per_week" value="{{ old('sessions_per_week', $frequency->sessions_per_week) }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    <p class="mt-1 text-xs text-gray-500">Haftada kaç seans yapılacağını belirtin.</p>
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_active', $frequency->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Aktif olarak ayarla</label>
                    </div>
                    <p class="mt-1 ml-6 text-xs text-gray-500">İşaretlendiğinde bu frekans kurs oluşturma sırasında seçilebilir olacaktır.</p>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition-all duration-300">
                        Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection