@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Geri Butonu ve Başlık -->
    <div class="flex items-center mb-6">
        <a href="{{ route('ogrenci.private-lessons.lesson', $session->private_lesson_id) }}" class="mr-4 text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $session->privateLesson->name ?? 'Ders Seansı' }}</h1>
            <p class="text-gray-600">{{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }} {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}</p>
        </div>
    </div>

    <!-- Durum Kartı -->
    @php
        // Status'e göre Türkçe karşılık ve renk belirleyelim
        switch ($session->status) {
            case 'pending':
                $statusText = 'Beklemede';
                $statusDesc = 'Bu ders henüz onaylanmamıştır.';
                $statusColor = 'bg-yellow-100 border-yellow-500 text-yellow-800';
                break;
            case 'approved':
                $statusText = 'Onaylandı';
                $statusDesc = 'Bu ders onaylanmıştır, belirtilen tarih ve saatte gerçekleşecektir.';
                $statusColor = 'bg-green-100 border-green-500 text-green-800';
                break;
            case 'cancelled':
                $statusText = 'İptal Edildi';
                $statusDesc = 'Bu ders iptal edilmiştir.';
                $statusColor = 'bg-gray-100 border-gray-500 text-gray-800';
                break;
            case 'completed':
                $statusText = 'Tamamlandı';
                $statusDesc = 'Bu ders başarıyla tamamlanmıştır.';
                $statusColor = 'bg-blue-100 border-blue-500 text-blue-800';
                break;
            default:
                $statusText = 'Bilinmiyor';
                $statusDesc = 'Ders durumu belirtilmemiştir.';
                $statusColor = 'bg-red-100 border-red-500 text-red-800';
                break;
        }
        
        // Tarih ve gün bilgisi
        $days = ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'];
        $dayText = isset($days[$session->day_of_week]) ? $days[$session->day_of_week] : 'Belirsiz';
        
        // Dersin zamanı geçmiş mi kontrolü
        $sessionDateTime = \Carbon\Carbon::parse($session->start_date . ' ' . $session->start_time);
        $isPast = $sessionDateTime->isPast();
    @endphp
    
    <div class="mb-8 p-6 {{ $statusColor }} border-l-4 rounded-lg shadow-sm">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold">{{ $statusText }}</h2>
                <p>{{ $statusDesc }}</p>
            </div>
            <div>
                @if($session->status == 'completed')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-600 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Ders Tamamlandı
                    </span>
                @elseif($session->status == 'cancelled')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-600 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        İptal Edildi
                    </span>
                @elseif($isPast && $session->status != 'completed')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-600 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        Süresi Geçti
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-600 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        Planlandı
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Ders Detayları -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Seans Bilgileri -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-800">Seans Bilgileri</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tarih</p>
                        <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }} ({{ $dayText }})</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Saat</p>
                        <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Konum</p>
                        <p class="font-medium text-gray-800">{{ $session->location ?? 'Belirtilmemiş' }}</p>
                    </div>
                    {{-- <div>
                        <p class="text-sm text-gray-600 mb-1">Ücret</p>
                        <p class="font-medium text-gray-800">{{ $session->fee }} ₺</p>
                    </div> --}}
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600 mb-1">Notlar</p>
                        <p class="font-medium text-gray-800">{{ $session->notes ?? 'Not bulunmamaktadır.' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Öğretmen Bilgileri -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-800">Öğretmen</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center mr-4 text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $session->teacher->name ?? 'Belirtilmemiş' }}</h3>
                        <p class="text-gray-600">{{ $session->teacher->email ?? '' }}</p>
                    </div>
                </div>
                @if(isset($session->teacher->phone))
                <div class="mt-3">
                    <p class="text-sm text-gray-600 mb-1">İletişim</p>
                    <p class="font-medium text-gray-800">{{ $session->teacher->phone }}</p>
                </div>
                @endif
                <div class="mt-6 text-center">
                    <a href="#" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">İletişime Geç</a>
                </div>
            </div>
        </div>
    </div>

    @if($session->status == 'completed')
    <!-- Materyaller ve Ödevler (Sadece tamamlanan dersler için) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Ders Materyalleri -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-800">Ders Materyalleri</h2>
            </div>
            <div class="p-6">
                @if($session->materials && $session->materials->count() > 0)
                    <div class="space-y-3">
                        @foreach($session->materials as $material)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 p-2 rounded-full mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $material->title }}</p>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($material->created_at)->format('d.m.Y') }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('ogrenci.private-lessons.material.download', $material->id) }}" class="text-blue-600 hover:text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-gray-600">Bu derse ait materyal bulunmamaktadır.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Ödevler -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-800">Ödevler</h2>
            </div>
            <div class="p-6">
                @if($session->homeworks && $session->homeworks->count() > 0)
                    <div class="space-y-4">
                        @foreach($session->homeworks as $homework)
                            @php
                                // Son teslim tarihi geçmiş mi?
                                $dueDate = \Carbon\Carbon::parse($homework->due_date);
                                $now = \Carbon\Carbon::now();
                                $isOverdue = $now->isAfter($dueDate);
                                
                                // Öğrenci teslim etmiş mi?
                                $submitted = $homework->submissions && $homework->submissions->where('student_id', Auth::id())->count() > 0;
                            @endphp
                            <div class="border rounded-lg overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 flex justify-between items-center">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $homework->title }}</h3>
                                        <p class="text-xs text-gray-500">Son Teslim: {{ $dueDate->format('d.m.Y H:i') }}</p>
                                    </div>
                                    <div>
                                        @if($submitted)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Teslim Edildi
                                            </span>
                                        @elseif($isOverdue)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                Süresi Doldu
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Bekliyor
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-4 border-t">
                                    <p class="text-sm text-gray-600 mb-3">{{ \Illuminate\Support\Str::limit($homework->description, 100) }}</p>
                                    <div class="flex justify-end">
                                        <a href="{{ route('ogrenci.private-lessons.homework', $homework->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Detayları Gör →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-gray-600">Bu derse ait ödev bulunmamaktadır.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection