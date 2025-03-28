<!-- resources/views/admin/users/create.blade.php -->
@extends('layouts.app')

@section('title', 'Yeni Kullanıcı Ekle')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Yeni Kullanıcı Ekle</h1>
            <p class="text-gray-600">Sisteme yeni bir kullanıcı ekleyin</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kullanıcılara Dön
        </a>
    </div>

    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p class="font-bold">Hata!</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- İsim -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">İsim</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    </div>
                    
                    <!-- Telefon -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                    
                    <!-- Veli Telefon Numarası (Öğrenciler için) -->
                    <div>
                        <label for="parent_phone_number" class="block text-sm font-medium text-gray-700 mb-1">Veli Telefon Numarası (Öğrenciler için)</label>
                        <input type="text" name="parent_phone_number" id="parent_phone_number" value="{{ old('parent_phone_number') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Şifre -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Şifre</label>
                        <input type="password" name="password" id="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    </div>
                    
                    <!-- Şifre Onay -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Şifre Onayı</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    </div>
                </div>
                
                <!-- Roller -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kullanıcı Rolleri</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach ($roles as $role)
                        <div class="flex items-center">
                            <input type="checkbox" name="roles[]" id="role_{{ $role['id'] }}" value="{{ $role['id'] }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ in_array($role['id'], old('roles', [])) ? 'checked' : '' }}>
                            <label for="role_{{ $role['id'] }}" class="ml-2 text-sm text-gray-700">{{ $role['name'] }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="window.location='{{ route('admin.users.index') }}'" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg">
                        İptal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Kullanıcı Ekle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Rol seçimi kontrolü
    document.addEventListener('DOMContentLoaded', function () {
        const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]');
        const submitButton = document.querySelector('button[type="submit"]');
        
        function checkRoles() {
            const checkedRoles = document.querySelectorAll('input[name="roles[]"]:checked');
            
            if (checkedRoles.length === 0) {
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }
        
        roleCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', checkRoles);
        });
        
        // Sayfa yüklendiğinde kontrol et
        checkRoles();
    });
</script>
@endsection