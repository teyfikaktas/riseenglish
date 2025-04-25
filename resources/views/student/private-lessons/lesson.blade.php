@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Geri Butonu ve Başlık -->
    <div class="flex items-center mb-6">
        <a href="{{ route('ogrenci.private-lessons.index') }}" class="mr-4 text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $lesson->name }}</h1>
            <p class="text-gray-600">Özel ders detayları ve seans bilgileri</p>
        </div>
    </div>

    <!-- Ders Bilgileri ve Öğretmen Kartı -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Ders Bilgileri -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-800">Ders Bilgileri</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Ders Adı</p>
                        <p class="font-medium text-gray-800">{{ $lesson->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Toplam Seans</p>
                        <p class="font-medium text-gray-800">{{ $sessions->count() }} Seans</p>
                    </div>
                    {{-- <div>
                        <p class="text-sm text-gray-600 mb-1">Ücret</p>
                        <p class="font-medium text-gray-800">{{ $sessions->first()->fee ?? 0 }} ₺ / Seans</p>
                    </div> --}}
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Durum</p>
                        <p class="font-medium">
                            @if($lesson->is_active)
                                <span class="text-green-600">Aktif</span>
                            @else
                                <span class="text-red-600">Pasif</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Başlangıç Tarihi</p>
                        <p class="font-medium text-gray-800">{{ $sessions->min('start_date') ? \Carbon\Carbon::parse($sessions->min('start_date'))->format('d.m.Y') : 'Belirsiz' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Bitiş Tarihi</p>
                        <p class="font-medium text-gray-800">{{ $sessions->max('start_date') ? \Carbon\Carbon::parse($sessions->max('start_date'))->format('d.m.Y') : 'Belirsiz' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Öğretmen Kartı -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-800">Öğretmen Bilgileri</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center mr-4 text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $teacher->name ?? 'Belirtilmemiş' }}</h3>
                        <p class="text-gray-600">{{ $teacher->email ?? '' }}</p>
                    </div>
                </div>
                @if(isset($teacher->phone))
                <div class="mt-3">
                    <p class="text-sm text-gray-600 mb-1">İletişim</p>
                    <p class="font-medium text-gray-800">{{ $teacher->phone }}</p>
                </div>
                @endif
                {{-- <div class="mt-6 text-center">
                    <a href="#" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">İletişime Geç</a>
                </div> --}}
            </div>
        </div>
    </div>

    <!-- Seanslar Tablosu -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="border-b px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-800">Seanslar</h2>
        </div>
        <div class="p-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saat</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Konum</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($sessions as $session)
                        @php
                            // Status'e göre Türkçe karşılık ve renk belirleyelim
                            switch ($session->status) {
                                case 'pending':
                                    $statusText = 'Beklemede';
                                    $statusColor = 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'approved':
                                    $statusText = 'Onaylandı';
                                    $statusColor = 'bg-green-100 text-green-800';
                                    break;
                                case 'cancelled':
                                    $statusText = 'İptal Edildi';
                                    $statusColor = 'bg-gray-100 text-gray-800';
                                    break;
                                case 'completed':
                                    $statusText = 'Tamamlandı';
                                    $statusColor = 'bg-blue-100 text-blue-800';
                                    break;
                                default:
                                    $statusText = 'Bilinmiyor';
                                    $statusColor = 'bg-red-100 text-red-800';
                                    break;
                            }
                            
                            // Haftanın günü
                            $days = ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'];
                            $dayText = isset($days[$session->day_of_week]) ? $days[$session->day_of_week] : 'Belirsiz';
                            
                            // Geçmiş/Gelecek kontrolü
                            $sessionDateTime = \Carbon\Carbon::parse($session->start_date . ' ' . $session->start_time);
                            $isPast = $sessionDateTime->isPast();
                        @endphp
                        <tr class="{{ $isPast && $session->status != 'completed' ? 'bg-gray-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $dayText }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @php
                                        $location = $session->location ?? 'Belirtilmemiş';
                                        $isLink = filter_var($location, FILTER_VALIDATE_URL);
                                    @endphp
                                    
                                    @if($isLink)
                                        <a href="{{ $location }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline flex items-center">
                                            <span>Toplantı Linki</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @else
                                        {{ $location }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="{{ route('ogrenci.private-lessons.session', $session->id) }}" class="text-blue-600 hover:text-blue-800">Detaylar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Materyaller ve Ödevler -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Materyaller -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-800">Ders Materyalleri</h2>
            </div>
            <div class="p-6">
                @php
                $materials = collect();
                foreach($sessions as $session) {
                    if ($session->materials && $session->materials->count() > 0) {
                        $materials = $materials->merge($session->materials);
                    }
                }
            @endphp
                
                @if($materials->count() > 0)
                    <div class="space-y-3">
                        @foreach($materials->take(5) as $material)
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
                    
                    @if($materials->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('ogrenci.private-lessons.materials') }}?lesson_id={{ $lesson->id }}" class="inline-block text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Tüm Materyalleri Gör ({{ $materials->count() }})
                            </a>
                        </div>
                    @endif
                    @else
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-gray-600 text-lg font-medium">Henüz materyal bulunmamaktadır.</p>
                        <p class="text-gray-500 mt-2">Öğretmeniniz ders tamamlandığında materyal ekleyebilir.</p>
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
                @php
                $homeworks = collect();
                foreach($sessions as $session) {
                    if ($session->homeworks && $session->homeworks->count() > 0) {
                        $homeworks = $homeworks->merge($session->homeworks);
                    }
                }
            @endphp
                
                @if($homeworks->count() > 0)
                    <div class="space-y-4">
                        @foreach($homeworks->take(5) as $homework)
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
                    
                    @if($homeworks->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('ogrenci.private-lessons.homeworks') }}?lesson_id={{ $lesson->id }}" class="inline-block text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Tüm Ödevleri Gör ({{ $homeworks->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-gray-600 text-lg font-medium">Henüz ödev bulunmamaktadır.</p>
                        <p class="text-gray-500 mt-2">Öğretmeniniz ders tamamlandığında ödev ekleyebilir.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
