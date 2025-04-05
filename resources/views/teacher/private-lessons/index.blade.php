@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Ana Başlık -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Özel Derslerim</h1>

        <!-- Dashboard Üst Kartlar -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Kart 1: Kayıtlı Toplam Öğrenci -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-blue-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <h2 class="text-gray-600 text-sm uppercase tracking-wider">Kayıtlı Toplam Öğrenci</h2>
                            <p class="text-3xl font-semibold text-gray-800 mt-1">42</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 text-sm text-gray-500">
                        <span class="text-green-500 font-medium">↑ 12% </span>
                        <span>son aya göre</span>
                    </div>
                </div>
            </div>

            <!-- Kart 2: Aktif Dersler -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-green-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <h2 class="text-gray-600 text-sm uppercase tracking-wider">Aktif Dersler</h2>
                            <p class="text-3xl font-semibold text-gray-800 mt-1">18</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 text-sm text-gray-500">
                        <span class="text-green-500 font-medium">↑ 5% </span>
                        <span>son haftaya göre</span>
                    </div>
                </div>
            </div>

            <!-- Kart 3: Bu Ay Toplam Gelir -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-purple-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <h2 class="text-gray-600 text-sm uppercase tracking-wider">Bu Ay Toplam Gelir</h2>
                            <p class="text-3xl font-semibold text-gray-800 mt-1">₺8,450</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 text-sm text-gray-500">
                        <span class="text-green-500 font-medium">↑ 20% </span>
                        <span>geçen aya göre</span>
                    </div>
                </div>
            </div>

            <!-- Kart 4: Bekleyen Ödevler -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-yellow-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <h2 class="text-gray-600 text-sm uppercase tracking-wider">Bekleyen Ödevler</h2>
                            <p class="text-3xl font-semibold text-gray-800 mt-1">7</p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 text-sm text-gray-500">
                        <span class="text-red-500 font-medium">↑ 3 </span>
                        <span>yeni ödev</span>
                    </div>
                </div>
            </div>
        </div>
        @livewire('private-lesson-calendar')

        <!-- Ana İçerik Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sol Kısım (2 sütun) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Son Gelen Ödevler -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-between border-b px-6 py-4">
                        <h2 class="text-xl font-semibold text-gray-800">Son Gelen Ödevler</h2>
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Tümünü Gör</a>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Ödev 1 -->
                            <div class="flex items-start p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="bg-blue-100 p-2 rounded-full mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-medium text-gray-800">Matematik - İntegraller</h3>
                                        <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full">Bugün</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Ahmet Yılmaz tarafından gönderildi</p>
                                    <p class="text-sm text-gray-500 mt-1">Teslim tarihi: 15.04.2025</p>
                                </div>
                            </div>

                            <!-- Ödev 2 -->
                            <div class="flex items-start p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="bg-green-100 p-2 rounded-full mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-medium text-gray-800">Fizik - Elektrik Devreleri</h3>
                                        <span class="text-xs bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full">Dün</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Zeynep Kaya tarafından gönderildi</p>
                                    <p class="text-sm text-gray-500 mt-1">Teslim tarihi: 10.04.2025</p>
                                </div>
                            </div>

                            <!-- Ödev 3 -->
                            <div class="flex items-start p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="bg-purple-100 p-2 rounded-full mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-medium text-gray-800">İngilizce - Essay Yazma</h3>
                                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">2 gün önce</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Merve Demir tarafından gönderildi</p>
                                    <p class="text-sm text-gray-500 mt-1">Teslim tarihi: 05.04.2025</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Özel Derslerim -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-between border-b px-6 py-4">
                        <h2 class="text-xl font-semibold text-gray-800">Özel Derslerim</h2>
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Tümünü Gör</a>
                    </div>
                    <div class="p-6 grid grid-cols-1 gap-6">
                        @forelse($sessions as $key => $session)
                            @php
                                // Her ders için farklı renk belirleyelim
                                $colors = [
                                    'bg-blue-50 border-blue-500',
                                    'bg-green-50 border-green-500',
                                    'bg-purple-50 border-purple-500',
                                    'bg-yellow-50 border-yellow-500',
                                    'bg-pink-50 border-pink-500',
                                    'bg-indigo-50 border-indigo-500',
                                    'bg-red-50 border-red-500',
                                    'bg-orange-50 border-orange-500',
                                ];
                                $colorIndex = $key % count($colors);
                                $cardColor = $colors[$colorIndex];
                                
                                // Status'e göre Türkçe karşılık ve renk belirleyelim
                                switch ($session->status) {
                                    case 'pending':
                                        $statusText = 'Bekliyor';
                                        $statusColor = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                                        $iconColor = 'text-yellow-500';
                                        break;
                                    case 'approved':
                                        $statusText = 'Onaylandı';
                                        $statusColor = 'bg-green-100 text-green-800 border-green-300';
                                        $iconColor = 'text-green-500';
                                        break;
                                    case 'cancelled':
                                        $statusText = 'İptal Edildi';
                                        $statusColor = 'bg-gray-100 text-gray-800 border-gray-300';
                                        $iconColor = 'text-gray-500';
                                        break;
                                    case 'rejected':
                                        $statusText = 'Reddedildi';
                                        $statusColor = 'bg-red-100 text-red-800 border-red-300';
                                        $iconColor = 'text-red-500';
                                        break;
                                    default:
                                        $statusText = 'Bilinmiyor';
                                        $statusColor = 'bg-red-100 text-red-800 border-red-300';
                                        $iconColor = 'text-red-500';
                                        break;
                                }
                                
                                // Haftanın günü
                                $days = ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'];
                                $dayText = isset($days[$session->day_of_week]) ? $days[$session->day_of_week] : 'Belirsiz';
                            @endphp

                            <div class="rounded-lg shadow-sm overflow-hidden border-l-4 {{ $cardColor }}">
                                <div class="p-5">
                                    <!-- Üst kısım: Durum ve Ders Adı -->
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-gray-800">
                                                {{ $session->privateLesson->name ?? 'Ders Bulunamadı' }}
                                            </h3>
                                        </div>
                                        <div>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $statusColor }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 {{ $iconColor }}" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $statusText }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Orta kısım: Öğrenci Bilgisi -->
                                    <div class="mb-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3 text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-lg font-semibold text-gray-800">
                                                    {{ $session->student ? $session->student->name : 'Öğrenci Yok' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Alt kısım: Zaman Bilgileri -->
                                    <div class="flex flex-wrap -mx-2">
                                        <div class="px-2 w-1/2 md:w-1/4 mb-3">
                                            <div class="bg-gray-100 rounded p-2 h-full">
                                                <p class="text-xs text-gray-500 uppercase tracking-wider">Gün</p>
                                                <p class="font-medium text-gray-800">{{ $dayText }}</p>
                                            </div>
                                        </div>
                                        <div class="px-2 w-1/2 md:w-1/4 mb-3">
                                            <div class="bg-gray-100 rounded p-2 h-full">
                                                <p class="text-xs text-gray-500 uppercase tracking-wider">Tarih</p>
                                                <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="px-2 w-1/2 md:w-1/4 mb-3">
                                            <div class="bg-gray-100 rounded p-2 h-full">
                                                <p class="text-xs text-gray-500 uppercase tracking-wider">Başlangıç</p>
                                                <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}</p>
                                            </div>
                                        </div>
                                        <div class="px-2 w-1/2 md:w-1/4 mb-3">
                                            <div class="bg-gray-100 rounded p-2 h-full">
                                                <p class="text-xs text-gray-500 uppercase tracking-wider">Bitiş</p>
                                                <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- İşlem Butonları -->
                                    <div class="mt-2 flex justify-end space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                            Düzenle
                                        </button>
                                        <button class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            Sil
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-gray-50 border border-gray-200 p-6 rounded-lg text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-gray-600 text-lg font-medium">
                                    Şu anda aktif ders kaydınız bulunmamaktadır.
                                </p>
                                <p class="text-gray-500 mt-2">
                                    Yeni bir ders eklemek için "Yeni Ders Ekle" butonunu kullanabilirsiniz.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Sağ Kısım (1 sütun) -->
            <div class="space-y-8">
                <!-- Özel Ders Öğrencisi Ekle -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="border-b px-6 py-4 flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">Özel Ders Öğrencisi Ekle</h2>
                        <a href="{{ route('ogretmen.private-lessons.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-md transition-colors">
                            Yeni Ders Ekle
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-gray-600 mb-4 bg-blue-50 p-3 rounded-lg">
                            Yeni bir özel ders kaydı oluşturmak için "Yeni Ders Ekle" butonuna tıklayın. Özel ders taleplerini "Bekleyen Dersler" bölümünden görüntüleyebilirsiniz.
                        </div>
                        
                        <div class="border-t border-dashed border-gray-200 pt-4 mt-4">
                            <h3 class="font-medium text-gray-700 mb-2">Bekleyen Dersler</h3>
                            @if(isset($pendingSessions) && $pendingSessions->count() > 0)
                                <div class="space-y-3">
                                    @foreach($pendingSessions as $pendingSession)
                                        <div class="bg-yellow-50 p-3 rounded-lg border-l-4 border-yellow-400">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-medium text-gray-800">{{ $pendingSession->privateLesson->name ?? 'Ders Bulunamadı' }}</p>
                                                    <p class="text-sm text-gray-600">
                                                        <span class="font-medium">Öğrenci:</span> {{ $pendingSession->student ? $pendingSession->student->name : 'Yok' }}
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        <span class="font-medium">Gün:</span>
                                                        @php
                                                            $days = ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'];
                                                            $dayText = isset($days[$pendingSession->day_of_week]) ? $days[$pendingSession->day_of_week] : 'Belirsiz';
                                                        @endphp
                                                        {{ $dayText }}
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        <span class="font-medium">Tarih/Saat:</span> 
                                                        {{ \Carbon\Carbon::parse($pendingSession->start_date)->format('d.m.Y') }} / 
                                                        {{ \Carbon\Carbon::parse($pendingSession->start_time)->format('H:i') }}
                                                    </p>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-xs">Onayla</button>
                                                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs">Reddet</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">Bekleyen ders talebi bulunmamaktadır.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Son Duyurularım -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-between border-b px-6 py-4">
                        <h2 class="text-xl font-semibold text-gray-800">Son Duyurularım</h2>
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Tümünü Gör</a>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Duyuru 1 -->
                            <div class="p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                <div class="flex justify-between">
                                    <h3 class="font-medium text-gray-800">Tatil Duyurusu</h3>
                                    <span class="text-xs text-gray-500">3 gün önce</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">10-15 Nisan tarihleri arasında yurtdışında olacağım için dersler ertelenecektir.</p>
                            </div>

                            <!-- Duyuru 2 -->
                            <div class="p-3 bg-blue-50 border-l-4 border-blue-400 rounded">
                                <div class="flex justify-between">
                                    <h3 class="font-medium text-gray-800">Yeni Materyal</h3>
                                    <span class="text-xs text-gray-500">1 hafta önce</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">Matematik dersleri için yeni çalışma materyalleri eklenmiştir. Hemen kontrol edin.</p>
                            </div>

                            <!-- Duyuru 3 -->
                            <div class="p-3 bg-green-50 border-l-4 border-green-400 rounded">
                                <div class="flex justify-between">
                                    <h3 class="font-medium text-gray-800">Online Ders Fırsatı</h3>
                                    <span class="text-xs text-gray-500">2 hafta önce</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">Nisan ayı boyunca online dersler %15 indirimli olarak verilecektir.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Takvim Özeti -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-xl font-semibold text-gray-800">Bu Hafta</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <!-- Gün 1 -->
                            <div class="flex items-center">
                                <div class="w-12 text-center">
                                    <span class="block text-sm font-medium text-gray-500">Pzt</span>
                                    <span class="block text-lg font-bold text-gray-800">03</span>
                                </div>
                                <div class="flex-1 ml-4 pl-4 border-l border-gray-200">
                                    <div class="flex items-center p-2 bg-gray-50 rounded">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                        <span class="text-sm text-gray-600">15:00 - 17:00 | Ahmet - Matematik</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Gün 2 -->
                            <div class="flex items-center">
                                <div class="w-12 text-center">
                                    <span class="block text-sm font-medium text-gray-500">Sal</span>
                                    <span class="block text-lg font-bold text-gray-800">04</span>
                                </div>
                                <div class="flex-1 ml-4 pl-4 border-l border-gray-200">
                                    <div class="flex items-center p-2 bg-gray-50 rounded">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                        <span class="text-sm text-gray-600">10:00 - 12:00 | Zeynep - Fizik</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Gün 3 - Bugün -->
                            <div class="flex items-center">
                                <div class="w-12 text-center bg-blue-100 rounded-l py-1">
                                    <span class="block text-sm font-medium text-blue-700">Çar</span>
                                    <span class="block text-lg font-bold text-blue-800">05</span>
                                </div>
                                <div class="flex-1 ml-4 pl-4 border-l border-gray-200">
                                    <div class="flex items-center p-2 bg-blue-50 rounded">
                                        <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                                        <span class="text-sm text-gray-600">14:00 - 16:00 | Merve - İngilizce</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Diğer tüm script'lerinden sonra en alt kısma ekle -->
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('lessonCompleted', message => {
            showNotification(message || 'Ders başarıyla tamamlandı!', 'success');
        });

        Livewire.on('lessonError', message => {
            showNotification(message || 'İşlem sırasında bir hata oluştu.', 'error');
        });

        function showNotification(message, type = 'success') {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = 'flex items-center p-4 mb-3 rounded-lg shadow-lg transform transition-all duration-300 opacity-0 translate-x-full max-w-md';

            notification.classList.add(type === 'success' ? 'bg-green-600' : 'bg-red-600', 'text-white');
            notification.innerHTML = `
                <div class="flex-shrink-0 mr-3">
                    ${type === 'success' ? 
                        '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' :
                        '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'}
                </div>
                <div class="flex-1">
                    <p class="font-medium">${message}</p>
                </div>
                <div class="flex-shrink-0 ml-3">
                    <button class="text-white" onclick="this.parentElement.parentElement.remove()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                    </button>
                </div>
            `;

            container.appendChild(notification);

            setTimeout(() => notification.classList.replace('opacity-0', 'opacity-100'), 10);
            setTimeout(() => notification.remove(), 5000);
        }
    });

    // Bu fonksiyon MUTLAKA EN ALTTA VE GLOBAL OLMALI
    window.checkModalAndRedirect = function(lessonId) {
    setTimeout(() => {
        const modal = document.querySelector('.fixed.inset-0.bg-black');
        if (!modal) {
            console.error('Modal açılamadı, lessonId:', lessonId);
            Livewire.dispatch('lessonError', 'Ders detayları yüklenemedi. Lütfen tekrar deneyin veya yöneticiye bilgi verin.');
        }
    }, 1000); // Süreyi 500ms'den 1000ms'ye çıkar, gecikme olabilir
};
</script>

@endsection
