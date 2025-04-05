@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-4 md:p-5 border border-gray-100 max-w-5xl mx-auto">
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-indigo-800">Ders Detayları</h1>
        <a href="{{ route('ogretmen.private-lessons.index') }}" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 flex items-center text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
            </svg>
            Takvime Dön
        </a>
    </div>
    
    <!-- Başlık Bilgisi -->
    <div class="border-b border-gray-100 pb-3 mb-4">
        <h4 class="text-xl font-bold text-gray-800">{{ $session->privateLesson ? $session->privateLesson->name : 'Ders' }}</h4>
        <p class="text-sm text-gray-600 mt-1">{{ $session->title ?? $session->privateLesson->name ?? 'Özel Ders' }}</p>
    </div>
    
    <!-- Temel Bilgiler - Daha kompakt grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">
        <div class="bg-gray-50 p-3 rounded-lg shadow-sm">
            <p class="text-xs text-gray-500 mb-0.5">Öğrenci</p>
            <p class="font-medium text-gray-800 text-sm">{{ $session->student ? $session->student->name : 'Öğrenci Atanmamış' }}</p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg shadow-sm">
            <p class="text-xs text-gray-500 mb-0.5">Öğretmen</p>
            <p class="font-medium text-gray-800 text-sm">{{ $session->teacher ? $session->teacher->name : 'Öğretmen Atanmamış' }}</p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg shadow-sm">
            <p class="text-xs text-gray-500 mb-0.5">Tarih</p>
            <p class="font-medium text-gray-800 text-sm">{{ Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}</p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg shadow-sm">
            <p class="text-xs text-gray-500 mb-0.5">Saat</p>
            <p class="font-medium text-gray-800 text-sm">{{ Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ Carbon\Carbon::parse($session->end_time)->format('H:i') }}</p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg shadow-sm">
            <p class="text-xs text-gray-500 mb-0.5">Konum</p>
            <p class="font-medium text-gray-800 text-sm">{{ $session->location ?? 'Belirtilmemiş' }}</p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg shadow-sm">
            <p class="text-xs text-gray-500 mb-0.5">Durum</p>
            <p>
                @php
                    $statusColors = [
                        'scheduled' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'completed' => 'bg-green-100 text-green-800 border-green-200',
                        'cancelled' => 'bg-gray-100 text-gray-800 border-gray-200',
                        'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                        'active' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'rejected' => 'bg-red-100 text-red-800 border-red-200',
                        'approved' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                    ];
                    $badgeColor = $statusColors[$session->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border {{ $badgeColor }}">
                    {{ $statuses[$session->status] ?? $session->status }}
                </span>
            </p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg shadow-sm">
            <p class="text-xs text-gray-500 mb-0.5">Ücret</p>
            <p class="font-medium text-gray-800 text-sm">₺{{ $session->fee ?? ($session->privateLesson ? $session->privateLesson->price : 0) }}</p>
        </div>
    </div>
    
    <!-- Notlar Bölümü - Daha kompakt -->
    @if($session->notes)
    <div class="bg-gray-50 p-3 rounded-lg shadow-sm mb-4">
        <p class="text-xs text-gray-500 mb-1">Notlar</p>
        <div class="bg-white p-2 rounded-lg border border-gray-100">
            <p class="text-gray-800 text-sm">{{ $session->notes }}</p>
        </div>
    </div>
    @endif
    
    <!-- Aksiyon Kartları - Daha kompakt -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
        <!-- Ders Tamamlama Kartı - Geçmiş ders kısıtlaması kaldırıldı -->
        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100">
            <div class="flex flex-col">
                <h5 class="text-xs font-semibold text-gray-700 mb-2">Ders Durumu</h5>
                
                @if($isLessonCompleted)
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Ders Tamamlandı
                        </span>
                    </div>
                @else
                    <form action="{{ route('ogretmen.private-lessons.complete', $session->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 shadow-sm flex items-center text-xs focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Dersi Tamamla
                        </button>
                        <p class="mt-1.5 text-xs text-gray-600">Veliye ve öğrenciye SMS gönderilecektir.</p>
                    </form>
                @endif
            </div>
        </div>
        
        <!-- Materyal Yükleme Kartı -->
        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100">
            <div class="flex flex-col">
                <h5 class="text-xs font-semibold text-gray-700 mb-2">Ders Materyalleri</h5>
                
                @if(!$isLessonCompleted)
                    <button disabled class="px-3 py-1.5 bg-gray-200 text-gray-500 rounded-lg transition-colors shadow-sm cursor-not-allowed flex items-center text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Materyal Ekle
                    </button>
                    <p class="mt-1.5 text-xs text-orange-600">Ders tamamlanmadan materyal yüklenemez.</p>
                @else
                    <a href="#" class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-sm flex items-center text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Materyal Ekle
                    </a>
                    <p class="mt-1.5 text-xs text-gray-600">Ders materyallerini yükleyebilirsiniz.</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Alt Butonlar - Daha kompakt -->
    <div class="flex justify-end space-x-3 mt-5 pt-3 border-t border-gray-100">
        <a href="{{ route('ogretmen.private-lessons.index') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-all duration-200 text-sm">
            Takvime Dön
        </a>
        <a href="{{ route('ogretmen.private-lessons.edit', $session->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-sm text-sm">
            Düzenle
        </a>
    </div>
</div>

<!-- Bildirim Sistemi -->
<div id="notification-container" class="fixed top-4 right-4 z-50"></div>

<script>
    // Bildirim sistemi
    function showNotification(message, type = 'success') {
        const container = document.getElementById('notification-container');
        const notification = document.createElement('div');
        notification.className = 'flex items-center p-3 mb-2 rounded-lg shadow-lg transform transition-all duration-300 opacity-0 translate-x-full max-w-md';

        if (type === 'success') {
            notification.classList.add('bg-green-600', 'text-white');
            notification.innerHTML = `
                <div class="flex-shrink-0 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm">${message}</p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <button class="text-white focus:outline-none hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            `;
        } else {
            notification.classList.add('bg-red-600', 'text-white');
            notification.innerHTML = `
                <div class="flex-shrink-0 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm">${message}</p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <button class="text-white focus:outline-none hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            `;
        }

        container.appendChild(notification);

        setTimeout(() => {
            notification.classList.remove('opacity-0', 'translate-x-full');
            notification.classList.add('opacity-100', 'translate-x-0');
        }, 10);

        const timeout = setTimeout(() => {
            notification.classList.add('opacity-0', 'translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);

        notification.querySelector('button').addEventListener('click', () => {
            clearTimeout(timeout);
        });
    }

    // Session flash mesajlarını göster
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showNotification("{{ session('success') }}", 'success');
        @endif
        
        @if(session('error'))
            showNotification("{{ session('error') }}", 'error');
        @endif
    });
</script>
@endsection