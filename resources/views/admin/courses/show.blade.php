@extends('layouts.app')

@section('title', $course->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Geri Butonu ve Başlık -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.courses.index') }}" class="mr-4 text-indigo-600 hover:text-indigo-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $course->name }}</h1>
            @if($course->is_featured)
                <span class="ml-3 px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">
                    Ana Sayfada
                </span>
            @endif
        </div>
        <div class="flex mt-4 sm:mt-0 space-x-3">
            <a href="{{ route('admin.courses.edit', $course->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Düzenle
            </a>
            <button onclick="confirmDelete()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                Sil
            </button>
        </div>
    </div>

    <!-- Bildirimler -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sol Kolon - Kurs Detayları -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Kurs Görseli (varsa) -->
                @if($course->thumbnail)
                <div class="w-full h-64 overflow-hidden">
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}" class="w-full h-full object-cover object-center">
                </div>
                @endif

                <!-- Kurs Bilgileri -->
                <div class="p-6">
                    <div class="flex flex-wrap items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $course->name }}</h2>
                        @if($course->category)
                            <span class="inline-block bg-blue-500 text-white text-xs px-2 py-1 rounded mt-2 sm:mt-0">
                                {{ $course->category->name }}
                            </span>
                        @endif
                    </div>

                    <!-- Kurs Durumu -->
                    <div class="flex flex-wrap items-center mb-4">
                        <span class="text-sm font-medium text-gray-500 mr-3">Durum:</span>
                        @if($course->is_active)
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                Aktif
                            </span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                Pasif
                            </span>
                        @endif
                        
                        @if($course->has_certificate)
                            <span class="ml-3 bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                Sertifikalı
                            </span>
                        @endif
                    </div>

                    <!-- Fiyat Bilgisi -->
                    <div class="mb-6">
                        @if($course->discount_price)
                            <div class="flex flex-wrap items-center">
                                <span class="text-3xl font-bold text-gray-800">{{ number_format($course->discount_price, 2) }} ₺</span>
                                <span class="ml-3 text-xl text-gray-500 line-through">{{ number_format($course->price, 2) }} ₺</span>
                                <span class="ml-3 bg-red-100 text-red-800 text-sm font-semibold px-2.5 py-0.5 rounded">
                                    %{{ $course->discount_percentage }} İndirim
                                </span>
                            </div>
                        @elseif($course->price)
                            <span class="text-3xl font-bold text-gray-800">{{ number_format($course->price, 2) }} ₺</span>
                        @else
                            <span class="text-lg text-gray-500">Fiyat bilgisi girilmemiş</span>
                        @endif
                    </div>

                    <!-- Açıklama -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Açıklama</h3>
                        <div class="prose max-w-none">
                            {!! nl2br(e($course->description)) !!}
                        </div>
                    </div>

                    <!-- Hedefler -->
                    @if($course->objectives)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Hedefler</h3>
                        <div class="prose max-w-none">
                            {!! nl2br(e($course->objectives)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sağ Kolon - Kurs Özet Bilgiler -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Kurs Detayları</h3>
                </div>
                <div class="p-6">
                    <ul class="space-y-4">
                        <!-- Öğretmen -->
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Öğretmen</span>
                                <span class="block text-sm text-gray-500">{{ $course->teacher->name ?? 'Belirlenmemiş' }}</span>
                            </div>
                        </li>

                        <!-- Seviye -->
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Seviye</span>
                                <span class="block text-sm text-gray-500">{{ $course->courseLevel->name ?? 'Belirlenmemiş' }}</span>
                            </div>
                        </li>

                        <!-- Kurs Tipi -->
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Kurs Tipi</span>
                                <span class="block text-sm text-gray-500">{{ $course->courseType->name ?? 'Belirlenmemiş' }}</span>
                            </div>
                        </li>

                        <!-- Kurs Frekansı -->
                        @if($course->courseFrequency)
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Kurs Sıklığı</span>
                                <span class="block text-sm text-gray-500">{{ $course->courseFrequency->name }}</span>
                            </div>
                        </li>
                        @endif

                        <!-- Toplam Saat -->
                        @if($course->total_hours)
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Toplam Saat</span>
                                <span class="block text-sm text-gray-500">{{ $course->total_hours }} saat</span>
                            </div>
                        </li>
                        @endif

                        <!-- Maksimum Öğrenci Sayısı -->
                        @if($course->max_students)
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Maksimum Öğrenci</span>
                                <span class="block text-sm text-gray-500">{{ $course->max_students }} kişi</span>
                            </div>
                        </li>
                        @endif

                        <!-- Tarih Aralığı -->
                        @if($course->start_date && $course->end_date)
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Tarih Aralığı</span>
                                <span class="block text-sm text-gray-500">
                                    {{ $course->start_date->format('d.m.Y') }} - {{ $course->end_date->format('d.m.Y') }}
                                </span>
                            </div>
                        </li>
                        @endif

                        <!-- Saat Aralığı -->
                        @if($course->start_time && $course->end_time)
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Saat Aralığı</span>
                                <span class="block text-sm text-gray-500">
                                    {{ $course->start_time->format('H:i') }} - {{ $course->end_time->format('H:i') }}
                                </span>
                            </div>
                        </li>
                        @endif

                        <!-- Lokasyon -->
                        @if($course->location)
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Konum</span>
                                <span class="block text-sm text-gray-500">{{ $course->location }}</span>
                            </div>
                        </li>
                        @endif

                        <!-- Görüntülenme Sırası -->
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Görüntülenme Sırası</span>
                                <span class="block text-sm text-gray-500">{{ $course->display_order ?? 0 }}</span>
                            </div>
                        </li>
                    </ul>

                    <!-- Online Toplantı Bilgileri -->
                    @if($course->meeting_link)
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold mb-4">Online Toplantı Bilgileri</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <a href="{{ $course->meeting_link }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 break-all">
                                    Toplantı Linki
                                </a>
                            </div>
                            @if($course->meeting_password)
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <span class="text-sm text-gray-500">
                                    Şifre: <span class="font-medium">{{ $course->meeting_password }}</span>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Silme Onay Modalı -->
<div id="deleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-start justify-between">
                <h3 class="text-lg font-medium text-gray-900">Kursu Sil</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeDeleteModal()">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-500">
                    <span class="font-medium">{{ $course->name }}</span> kursunu silmek istediğinize emin misiniz? Bu işlem geri alınamaz.
                </p>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300" onclick="closeDeleteModal()">
                    İptal
                </button>
                <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Evet, Sil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Kullanıcı ESC tuşuna bastığında modalı kapatır
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });

    // Modal dışına tıklandığında modalı kapatır
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>
@endsection