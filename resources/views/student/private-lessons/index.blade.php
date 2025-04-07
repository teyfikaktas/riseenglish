@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Ana Başlık -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Özel Derslerim</h1>
        <p class="text-gray-600">Tüm özel ders programınız ve geçmiş dersleriniz.</p>
    </div>

    <!-- Özel Derslerim -->
    <div class="bg-white rounded-lg shadow-md mt-4">
        <div class="border-b px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-800">Ders Programım</h2>
        </div>
        
        <div class="p-6">
            @if($groupedSessions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($groupedSessions as $lessonId => $lessonSessions)
                        @php
                            // Her ders grubu için ilk session'dan ders bilgilerini al
                            $firstSession = $lessonSessions->first();
                            $lessonName = $firstSession->privateLesson->name ?? 'Ders Bulunamadı';
                            $isActive = $firstSession->privateLesson ? $firstSession->privateLesson->is_active : false;
                            
                            // Renk belirle
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
                            $colorIndex = $loop->index % count($colors);
                            $cardColor = $colors[$colorIndex];
                            
                            // Aktif değilse opacity ekle
                            $opacityClass = $isActive ? '' : 'opacity-60';
                        @endphp

                        <div class="rounded-lg shadow-sm overflow-hidden border-l-4 {{ $cardColor }} {{ $opacityClass }}">
                            <div class="p-5">
                                <!-- Ders başlığı -->
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-xl font-bold text-gray-800">{{ $lessonName }}
                                        @if(!$isActive)
                                            <span class="ml-2 text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full">Pasif</span>
                                        @endif
                                    </h3>
                                    <span class="text-sm text-gray-600">{{ $lessonSessions->count() }} Seans</span>
                                </div>
                                
                                <!-- Öğretmen Bilgisi -->
                                <div class="mb-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3 text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Öğretmen</p>
                                            <p class="text-lg font-semibold text-gray-800">
                                                {{ $firstSession->teacher ? $firstSession->teacher->name : 'Öğretmen Yok' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Yaklaşan Seanslar -->
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-700 mb-2">Yaklaşan Seanslar</h4>
                                    <div class="space-y-2">
                                        @php
                                            // Bugünden başlayarak en fazla 3 yaklaşan seansı göster
                                            $upcomingSessions = $lessonSessions
                                                ->where('start_date', '>=', date('Y-m-d'))
                                                ->where('status', '!=', 'cancelled')
                                                ->sortBy('start_date')
                                                ->take(3);
                                        @endphp
                                        
                                        @forelse($upcomingSessions as $session)
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
                                            @endphp
                                            
                                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg">
                                                <div>
                                                    <span class="font-medium">{{ $dayText }}</span>, 
                                                    <span>{{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}</span>
                                                    <span class="text-sm text-gray-600 ml-2">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</span>
                                                </div>
                                                <span class="text-xs px-2 py-1 rounded-full {{ $statusColor }}">{{ $statusText }}</span>
                                            </div>
                                        @empty
                                            <p class="text-sm text-gray-500 italic">Yaklaşan seans bulunmamaktadır.</p>
                                        @endforelse
                                    </div>
                                </div>
                                
                                <!-- İşlem Butonları -->
                                <div class="mt-3 flex justify-end space-x-3">
                                    <a href="{{ route('ogrenci.private-lessons.lesson', $firstSession->private_lesson_id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                        Ders Detayları
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 p-6 rounded-lg text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-600 text-lg font-medium">
                        Şu anda aktif ders kaydınız bulunmamaktadır.
                    </p>
                    <p class="text-gray-500 mt-2">
                        Özel ders almak için lütfen öğretmeninizle veya yönetimle iletişime geçin.
                    </p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Hızlı Erişim Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <!-- Ödevler Kartı -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-5">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Ödevlerim</h3>
                </div>
                <p class="text-gray-600 mb-4">Öğretmenleriniz tarafından size verilen tüm ödevlere buradan erişebilirsiniz.</p>
                <a href="{{ route('ogrenci.private-lessons.homeworks') }}" class="inline-block text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Ödevlerime Git →
                </a>
            </div>
        </div>
        
        <!-- Materyaller Kartı -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-5">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Ders Materyalleri</h3>
                </div>
                <p class="text-gray-600 mb-4">Öğretmenlerinizin paylaştığı tüm ders materyallerine buradan erişebilirsiniz.</p>
                <a href="{{ route('ogrenci.private-lessons.materials') }}" class="inline-block text-green-600 hover:text-green-800 text-sm font-medium">
                    Materyallere Git →
                </a>
            </div>
        </div>
        
        {{-- <!-- Tamamlanan Dersler Kartı -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-5">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Tamamlanan Dersler</h3>
                </div>
                <p class="text-gray-600 mb-4">Tamamlanmış derslerinizin kayıtları ve geçmiş ders bilgileriniz.</p>
                <a href="{{ route('ogrenci.private-lessons.completed') }}" class="inline-block text-purple-600 hover:text-purple-800 text-sm font-medium">
                    Tamamlanan Derslere Git →
                </a>
            </div>
        </div> --}}
    </div>
</div>
@endsection