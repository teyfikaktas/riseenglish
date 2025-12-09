<!-- resources/views/admin/users/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Kullanıcı Düzenle')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Kullanıcı Düzenle</h1>
            <p class="text-gray-600">{{ $user->name }} kullanıcı bilgilerini düzenleyin</p>
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
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- İsim -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">İsim</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    </div>
                    
                    <!-- Telefon -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                    
                    <!-- Veli Telefon Numarası (Öğrenciler için) -->
                    <div>
                        <label for="parent_phone_number" class="block text-sm font-medium text-gray-700 mb-1">Veli Telefon Numarası (Öğrenciler için)</label>
                        <input type="text" name="parent_phone_number" id="parent_phone_number" value="{{ old('parent_phone_number', $user->parent_phone_number) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                    
                    <!-- İkinci Veli Telefon Numarası (Öğrenciler için) -->
                    <div>
                        <label for="parent_phone_number_2" class="block text-sm font-medium text-gray-700 mb-1">İkinci Veli Telefon Numarası</label>
                        <input type="text" name="parent_phone_number_2" id="parent_phone_number_2" value="{{ old('parent_phone_number_2', $user->parent_phone_number_2) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Şifre -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
                        <input type="password" name="password" id="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                    
                    <!-- Şifre Onay -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Şifre Onayı</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                </div>
                
                <!-- Roller -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kullanıcı Rolleri</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach ($roles as $role)
                        <div class="flex items-center">
                            <input type="checkbox" name="roles[]" id="role_{{ $role['id'] }}" value="{{ $role['id'] }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ in_array($role['id'], old('roles', $userRoles)) ? 'checked' : '' }}>
                            <label for="role_{{ $role['id'] }}" class="ml-2 text-sm text-gray-700">{{ $role['name'] }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Gruplar (Sadece öğrenciler için) -->
                <div class="mb-6" id="groups-section">
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Gruplar <span class="text-gray-500 text-xs">(Öğrenci rolü seçili ise görünür)</span>
                        </label>
                    </div>
                    
                    <!-- Grup Arama -->
                    @if(!$groups->isEmpty())
                    <div class="mb-4">
                        <input type="text" id="groupSearch" placeholder="Grup ara..." class="w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                    @endif
                    
                    <div id="groupsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($groups as $group)
                        <div class="group-item flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50" data-group-name="{{ strtolower($group->name) }}" data-teacher-name="{{ $group->teacher ? strtolower($group->teacher->name) : '' }}">
                            <input type="checkbox" name="groups[]" id="group_{{ $group->id }}" value="{{ $group->id }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ in_array($group->id, old('groups', $userGroups)) ? 'checked' : '' }}>
                            <label for="group_{{ $group->id }}" class="ml-2 text-sm text-gray-700 flex-1 cursor-pointer">
                                <span class="font-medium">{{ $group->name }}</span>
                                @if($group->teacher)
                                <span class="block text-xs text-gray-500">Öğretmen: {{ $group->teacher->name }}</span>
                                @endif
                                @if($group->description)
                                <span class="block text-xs text-gray-400">{{ Str::limit($group->description, 40) }}</span>
                                @endif
                            </label>
                        </div>
                        @endforeach
                        @if($groups->isEmpty())
                        <p id="noGroupsMsg" class="text-gray-500 text-sm col-span-full">
                            Henüz grup oluşturulmamış. <a href="{{ route('admin.groups.index') }}" class="text-blue-600 hover:underline">Grup Yönetimi</a> sayfasından grup oluşturabilirsiniz.
                        </p>
                        @endif
                    </div>
                    
                    <div id="noResultsMsg" class="hidden text-gray-500 text-sm mt-4">
                        Arama sonucu bulunamadı.
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="window.location='{{ route('admin.users.show', $user->id) }}'" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg">
                        İptal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Kullanıcıyı Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]');
    const submitButton = document.querySelector('button[type="submit"]');
    const groupsSection = document.getElementById('groups-section');
    const groupSearch = document.getElementById('groupSearch');
    const groupItems = document.querySelectorAll('.group-item');
    const noResultsMsg = document.getElementById('noResultsMsg');
    
    // Grup arama fonksiyonu
    if (groupSearch) {
        groupSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;
            
            groupItems.forEach(item => {
                const groupName = item.dataset.groupName;
                const teacherName = item.dataset.teacherName;
                
                if (groupName.includes(searchTerm) || teacherName.includes(searchTerm)) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });
            
            // Sonuç bulunamadı mesajı
            if (visibleCount === 0 && searchTerm !== '') {
                noResultsMsg.classList.remove('hidden');
            } else {
                noResultsMsg.classList.add('hidden');
            }
        });
    }
    
    // Rol kontrolü
    function checkRoles() {
        const checkedRoles = document.querySelectorAll('input[name="roles[]"]:checked');
        
        if (checkedRoles.length === 0) {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        // Öğrenci rolü seçiliyse grup seçimini göster
        const isStudent = Array.from(checkedRoles).some(cb => cb.value === 'ogrenci');
        if (isStudent) {
            groupsSection.classList.remove('opacity-50');
            if (groupSearch) groupSearch.disabled = false;
        } else {
            groupsSection.classList.add('opacity-50');
            if (groupSearch) groupSearch.disabled = true;
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