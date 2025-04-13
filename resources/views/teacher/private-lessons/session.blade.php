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
    <div class="bg-gray-50 p-3 rounded-lg shadow-sm">
        <p class="text-xs text-gray-500 mb-0.5">Ödeme Durumu</p>
        <p>
            @php
                $paymentColors = [
                    'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                    'paid' => 'bg-green-100 text-green-800 border-green-200',
                ];
                $paymentBadgeColor = $paymentColors[$session->payment_status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                $paymentText = $session->payment_status == 'pending' ? 'Ödeme Bekliyor' : 'Ödendi';
            @endphp
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border {{ $paymentBadgeColor }}">
                {{ $paymentText }}
            </span>
        </p>
    </div>
    <!-- In teacher.private-lessons.session.blade.php -->

@if(isset($isLessonCompleted) && $isLessonCompleted)
<div class="mt-4">
    <div class="flex justify-between items-center mb-2">
        <h3 class="text-lg font-semibold">Ödevler</h3>
        <a href="{{ route('ogretmen.private-lessons.homework.create', $session->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
            Ödev Ekle
        </a>
    </div>

    @if($session->homeworks && $session->homeworks->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Başlık</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Tarihi</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslimler</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($session->homeworks as $homework)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $homework->title }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($homework->due_date)->format('d.m.Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $homework->submissions->count() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('ogretmen.private-lessons.homework.submissions', $homework->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Teslimleri Gör</a>
                            
                            @if($homework->file_path)
                            <a href="{{ route('ogretmen.private-lessons.homework.download', $homework->id) }}" class="text-green-600 hover:text-green-900 mr-3">İndir</a>
                            @endif
                            
                            <form class="inline" action="{{ route('ogretmen.private-lessons.homework.delete', $homework->id) }}" method="POST" onsubmit="return confirm('Bu ödevi silmek istediğinize emin misiniz? Bu işlem geri alınamaz.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Sil</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-3 rounded">
            Bu derse henüz ödev eklenmemiş.
        </div>
    @endif
</div>
@endif
{{-- @if($isLessonCompleted)
    @php
        $reportExists = \App\Models\PrivateLessonReport::where('session_id', $session->id)->exists();
    @endphp
    
    @if($reportExists)
        <a href="{{ route('ogretmen.private-lessons.session.showReport', $session->id) }}" 
           class="flex items-center text-blue-600 hover:text-blue-800 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Ders Raporunu Görüntüle
        </a>
    @else
        <a href="{{ route('ogretmen.private-lessons.session.createReport', $session->id) }}" 
           class="flex items-center text-green-600 hover:text-green-800 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Ders Raporu Oluştur
        </a>
    @endif
@endif --}}
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
                
                    <a href="{{ route('ogretmen.private-lessons.material.create', $session->id) }}" class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-sm flex items-center text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Materyal Ekle
                    </a>
                    <p class="mt-1.5 text-xs text-gray-600">Ders materyallerini yükleyebilirsiniz.</p>
                
                <!-- Mevcut Materyaller Listesi -->
                @if($session->materials && $session->materials->count() > 0)
                    <div class="mt-3 border-t border-gray-100 pt-3">
                        <h6 class="text-xs font-medium text-gray-700 mb-2">Mevcut Materyaller</h6>
                        <ul class="space-y-2">
                            @foreach($session->materials as $material)
                                <li class="bg-gray-50 p-2 rounded-lg border border-gray-100 flex justify-between items-center">
                                    <div class="flex items-center space-x-2">
                                        <!-- Dosya tipine göre ikon -->
                                        <div class="text-indigo-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <a href="{{ route('ogretmen.private-lessons.material.download', $material->id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                                {{ $material->title }}
                                            </a>
                                            @if($material->description)
                                                <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($material->description, 50) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('ogretmen.private-lessons.material.download', $material->id) }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('ogretmen.private-lessons.material.delete', $material->id) }}" method="POST" onsubmit="return confirm('Bu materyali silmek istediğinizden emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
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