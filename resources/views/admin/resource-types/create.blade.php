@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.resource-types.index') }}" class="mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Yeni Kaynak Türü Ekle</h1>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.resource-types.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                <!-- Tür Adı -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tür Adı</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Açıklama -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                    <textarea name="description" id="description" rows="3" class="form-textarea rounded-md shadow-sm mt-1 block w-full">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- İkon -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">İkon (Font Awesome veya başka bir ikon kütüphanesi sınıfı)</label>
                    <div class="flex">
                        <input type="text" name="icon" id="icon" value="{{ old('icon') }}" placeholder="Örn: fas fa-file-pdf" class="form-input rounded-md shadow-sm mt-1 block w-full">
                    </div>
                    <p class="text-gray-500 text-xs mt-1">Kaynak türü için kullanılacak ikon sınıfı (opsiyonel).</p>
                    @error('icon')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    Kaynak Türü Oluştur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection