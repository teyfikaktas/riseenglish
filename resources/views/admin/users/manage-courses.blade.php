<!-- resources/views/admin/users/manage-courses.blade.php -->
@extends('layouts.app')

@section('title', 'Kullanıcı Kurslarını Yönet')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">{{ $user->name }} - Kursları Yönet</h1>
            </div>
        </div>
        <p class="text-gray-600 mt-2">Kullanıcının kurs kayıtlarını görüntüleyin, düzenleyin veya yeni kurs kaydı oluşturun.</p>
    </div>

    <!-- Kullanıcı Özet Bilgileri -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-800">Kullanıcı Bilgileri</h2>
        </div>
        <div class="p-6">
            <div class="flex flex-col md:flex-row items-center">
                <div class="flex-shrink-0 h-20 w-20 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl font-bold mb-4 md:mb-0 md:mr-6">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
<!-- Düzeltilmiş kod (Spatie permission paketi kullanılıyorsa): -->
<div class="mt-1">
    @foreach($user->getRoleNames() as $roleName)
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">
            {{ ucfirst($roleName) }}
        </span>
    @endforeach
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kayıtlı Kurslar -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-800">Kayıtlı Kurslar</h2>
        </div>
        <div class="p-6">
            @if($enrolledCourses->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kurs</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eğitmen</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kayıt Tarihi</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ödeme</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($enrolledCourses as $course)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($course->thumbnail)
                                                <img class="h-10 w-10 rounded object-cover" src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded bg-green-500 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($course->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $course->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $course->level->name ?? 'Belirtilmemiş' }} · {{ $course->type->name ?? 'Belirtilmemiş' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $course->teacher->name ?? 'Belirtilmemiş' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $course->pivot->enrollment_date ? date('d.m.Y', strtotime($course->pivot->enrollment_date)) : 'Belirtilmemiş' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            1 => 'bg-yellow-100 text-yellow-800', // Beklemede
                                            2 => 'bg-blue-100 text-blue-800',    // Devam Ediyor
                                            3 => 'bg-green-100 text-green-800',  // Tamamlandı
                                            4 => 'bg-red-100 text-red-800'       // İptal Edildi
                                        ];
                                        $statusName = [
                                            1 => 'Beklemede',
                                            2 => 'Devam Ediyor',
                                            3 => 'Tamamlandı',
                                            4 => 'İptal Edildi'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$course->pivot->status_id] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusName[$course->pivot->status_id] ?? 'Belirsiz' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($course->pivot->payment_completed)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Tamamlandı
                                        </span>
                                        @if($course->pivot->paid_amount)
                                        <div class="text-sm mt-1">{{ number_format($course->pivot->paid_amount, 2) }} ₺</div>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Tamamlanmadı
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="openEditModal('{{ $course->id }}')" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Düzenle">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button onclick="confirmUnenroll('{{ $course->id }}', '{{ $course->name }}')" class="text-red-600 hover:text-red-900" title="Kaydı Sil">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <form id="unenroll-form-{{ $course->id }}" action="{{ route('admin.users.unenrollCourse', ['user' => $user->id, 'course' => $course->id]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $enrolledCourses->links() }}
                </div>
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">Bu kullanıcı henüz hiçbir kursa kayıtlı değil.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Yeni Kurs Kaydı -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-800">Yeni Kurs Kaydı</h2>
        </div>
        <div class="p-6">
            <!-- Arama ve Filtreleme -->
            <div class="mb-6">
                <form action="{{ route('admin.users.manageCourses', $user) }}" method="GET" class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                    <div class="md:w-1/3">
                        <label for="search" class="block text-sm font-medium text-gray-700">Kurs Ara</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Kurs adı...">
                    </div>
                    <div class="md:w-1/4">
                        <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select id="category" name="category" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Tüm Kategoriler</option>
                            <!-- Kategoriler listelenecek -->
                        </select>
                    </div>
                    <div class="md:w-1/4">
                        <label for="level" class="block text-sm font-medium text-gray-700">Seviye</label>
                        <select id="level" name="level" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Tüm Seviyeler</option>
                            <!-- Seviyeler listelenecek -->
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Filtrele
                        </button>
                    </div>
                </form>
            </div>

            <!-- Mevcut Kurslar -->
            @if($availableCourses->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kurs</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eğitmen</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fiyat</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kayıt/Kapasite</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($availableCourses as $course)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($course->thumbnail)
                                                <img class="h-10 w-10 rounded object-cover" src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded bg-green-500 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($course->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $course->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $course->level->name ?? 'Belirtilmemiş' }} · {{ $course->type->name ?? 'Belirtilmemiş' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $course->teacher->name ?? 'Belirtilmemiş' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    <div>{{ $course->start_date ? date('d.m.Y', strtotime($course->start_date)) : '-' }} - {{ $course->end_date ? date('d.m.Y', strtotime($course->end_date)) : '-' }}</div>
                                    @if($course->start_time && $course->end_time)
                                    <div class="mt-1">{{ date('H:i', strtotime($course->start_time)) }} - {{ date('H:i', strtotime($course->end_time)) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($course->discount_price)
                                        <span class="line-through text-red-500">{{ number_format($course->price, 2) }} ₺</span>
                                        <span class="font-bold block">{{ number_format($course->discount_price, 2) }} ₺</span>
                                    @else
                                        <span>{{ number_format($course->price, 2) }} ₺</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                    {{ $course->students_count ?? 0 }} / {{ $course->max_students ?? '∞' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="openEnrollModal('{{ $course->id }}', '{{ $course->name }}', {{ $course->discount_price ?? $course->price }})" class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Kaydet
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $availableCourses->appends(['enrolled_page' => $enrolledCourses->currentPage()])->links() }}
                </div>
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">Kullanıcının kaydedilebileceği aktif kurs bulunmamaktadır.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Kayıt Ekleme Modalı -->
    <div id="enrollModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Kursa Kaydet</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeEnrollModal()">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="enrollForm" action="{{ route('admin.users.enrollCourse', $user) }}" method="POST">
                @csrf
                <input type="hidden" id="course_id" name="course_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kurs</label>
                    <p id="courseName" class="text-sm font-medium text-gray-900"></p>
                </div>
                
                <div class="mb-4">
                    <label for="status_id" class="block text-sm font-medium text-gray-700">Durum</label>
                    <select id="status_id" name="status_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="1">Beklemede</option>
                        <option value="2">Devam Ediyor</option>
                        <option value="3">Tamamlandı</option>
                        <option value="4">İptal Edildi</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="payment_completed" name="payment_completed" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <label for="payment_completed" class="ml-2 block text-sm text-gray-700">Ödeme Tamamlandı</label>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="paid_amount" class="block text-sm font-medium text-gray-700">Ödenen Tutar (₺)</label>
                    <input type="number" step="0.01" id="paid_amount" name="paid_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notlar</label>
                    <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                </div>
                
                <div class="flex justify-end">
                    <button type="button" onclick="closeEnrollModal()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                        İptal
                    </button>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Kayıt Düzenleme Modalı -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Kurs Kaydını Düzenle</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeEditModal()">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit_status_id" class="block text-sm font-medium text-gray-700">Durum</label>
                    <select id="edit_status_id" name="status_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="1">Beklemede</option>
                        <option value="2">Devam Ediyor</option>
                        <option value="3">Tamamlandı</option>
                        <option value="4">İptal Edildi</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="edit_payment_completed" name="payment_completed" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <label for="edit_payment_completed" class="ml-2 block text-sm text-gray-700">Ödeme Tamamlandı</label>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="edit_paid_amount" class="block text-sm font-medium text-gray-700">Ödenen Tutar (₺)</label>
                    <input type="number" step="0.01" id="edit_paid_amount" name="paid_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                
                <div class="mb-4">
                    <label for="edit_final_grade" class="block text-sm font-medium text-gray-700">Final Notu (0-100)</label>
                    <input type="number" min="0" max="100" id="edit_final_grade" name="final_grade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                
                <div class="mb-4">
                    <label for="edit_notes" class="block text-sm font-medium text-gray-700">Notlar</label>
                    <textarea id="edit_notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                </div>
                
                <div class="flex justify-end">
                    <button type="button" onclick="closeEditModal()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                        İptal
                    </button>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openEnrollModal(courseId, courseName, price) {
        document.getElementById('course_id').value = courseId;
        document.getElementById('courseName').textContent = courseName;
        document.getElementById('paid_amount').value = price;
        document.getElementById('enrollModal').classList.remove('hidden');
    }
    
    function closeEnrollModal() {
        document.getElementById('enrollModal').classList.add('hidden');
    }
    
    function openEditModal(courseId) {
        const form = document.getElementById('editForm');
        form.action = "{{ route('admin.users.updateCourseEnrollment', ['user' => $user->id, 'course' => '']) }}/" + courseId;
        
        // AJAX ile mevcut kurs kayıt bilgilerini getir
        fetch(`/admin/users/{{ $user->id }}/courses/${courseId}/enrollment-data`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_status_id').value = data.status_id;
                document.getElementById('edit_payment_completed').checked = data.payment_completed;
                document.getElementById('edit_paid_amount').value = data.paid_amount;
                document.getElementById('edit_final_grade').value = data.final_grade;
                document.getElementById('edit_notes').value = data.notes;
            })
            .catch(error => console.error('Error:', error));
        
        document.getElementById('editModal').classList.remove('hidden');
    }
    
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
    
    function confirmUnenroll(courseId, courseName) {
        if (confirm(`"${courseName}" kursundan kullanıcı kaydını silmek istediğinizden emin misiniz?`)) {
            document.getElementById(`unenroll-form-${courseId}`).submit();
        }
    }
    
    // ESC tuşu ile modal'ları kapat
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeEnrollModal();
            closeEditModal();
        }
    });
</script>
@endsection