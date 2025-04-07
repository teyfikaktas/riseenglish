@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Ana Başlık -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ request('show_all') ? 'Tüm Özel Derslerim' : 'Aktif Özel Derslerim' }}</h1>
            <div class="flex space-x-4">
                <a href="{{ request('show_all') ? route('ogretmen.private-lessons.index') : route('ogretmen.private-lessons.index', ['show_all' => 1]) }}" 
                   class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                    {{ request('show_all') ? 'Sadece Aktifleri Göster' : 'İptal Edilenleri de Göster' }}
                </a>
            </div>
        </div>

        @livewire('private-lesson-calendar')

        <!-- Özel Derslerim -->
        <div class="bg-white rounded-lg shadow-md mt-8">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-800">{{ request('show_all') ? 'Tüm Özel Derslerim' : 'Aktif Özel Derslerim' }}</h2>
                <div class="flex space-x-4">
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Tümünü Gör</a>
                    <a href="{{ request('show_all') ? route('ogretmen.private-lessons.index') : route('ogretmen.private-lessons.index', ['show_all' => 1]) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        {{ request('show_all') ? 'Sadece Aktifleri Göster' : 'İptal Edilenleri de Göster' }}
                    </a>
                </div>
            </div>
            <div class="p-6">
                @php
                    // Dersleri private_lesson_id'ye göre gruplandır
                    $filteredSessions = request('show_all') 
                        ? $sessions 
                        : $sessions->filter(function($session) {
                            return $session->privateLesson && $session->privateLesson->is_active;
                        });
                    $groupedSessions = $filteredSessions->groupBy('private_lesson_id');
                @endphp

                @if($groupedSessions->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($groupedSessions as $lessonId => $lessonSessions)
                            @php
                                // Her ders grubu için ilk session'dan ders bilgilerini al
                                $firstSession = $lessonSessions->first();
                                $lessonName = $firstSession->privateLesson->name ?? 'Ders Bulunamadı';
                                $isActive = $firstSession->privateLesson ? $firstSession->privateLesson->is_active : false;
                                
                                // Aktif olmayan dersleri göstermek istemiyorsak, döngüden çık
                                if (!request('show_all') && !$isActive) {
                                    continue;
                                }
                                
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
                                    
                                    <!-- Öğrenci Bilgisi -->
                                    <div class="mb-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3 text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-lg font-semibold text-gray-800">
                                                    {{ $firstSession->student ? $firstSession->student->name : 'Öğrenci Yok' }}
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
                                                    ->sortBy('start_date')
                                                    ->take(3);
                                            @endphp
                                            
                                            @forelse($upcomingSessions as $session)
                                                @php
                                                    // Status'e göre Türkçe karşılık ve renk belirleyelim
                                                    switch ($session->status) {
                                                        case 'pending':
                                                            $statusText = 'Bekliyor';
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
                                        <a href="{{ route('ogretmen.private-lessons.showLesson', $firstSession->private_lesson_id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                            Ders Detayları
                                        </a>
                                        <a href="{{ route('ogretmen.private-lessons.editLesson', $firstSession->private_lesson_id) }}" class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                            Dersi Düzenle
                                        </a>
                                        
                                        <!-- Dersi Aktif/Pasif yapma butonu -->
                                        <a href="#" 
                                           onclick="event.preventDefault(); toggleLessonActive('{{ $firstSession->private_lesson_id }}', '{{ $isActive ? 'Pasif' : 'Aktif' }}');" 
                                           class="{{ $isActive ? 'text-red-600 hover:text-red-800' : 'text-blue-600 hover:text-blue-800' }} text-sm font-medium flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                            Dersi {{ $isActive ? 'Pasif Yap' : 'Aktif Yap' }}
                                        </a>
                                        
                                        <!-- Gizli form -->
                                        <form id="toggle-form-{{ $firstSession->private_lesson_id }}" 
                                              action="{{ route('ogretmen.private-lessons.toggleActive', $firstSession->private_lesson_id) }}" 
                                              method="POST" 
                                              style="display: none;">
                                            @csrf
                                        </form>
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
                            Yeni bir ders eklemek için "Yeni Ders Ekle" butonunu kullanabilirsiniz.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 flex flex-col items-end"></div>
    
    <!-- Modal for lesson deactivation confirmation -->
    <div id="toggleModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4" id="modalTitle"></h3>
            <p class="text-gray-600 mb-6" id="modalMessage"></p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    İptal
                </button>
                <button id="confirmButton" onclick="confirmToggle()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Onaylıyorum
                </button>
            </div>
        </div>
    </div>
    

    <script>
        let currentLessonId = null;
        let currentForm = null;
        
        function toggleLessonActive(lessonId, action) {
            currentLessonId = lessonId;
            currentForm = document.getElementById('toggle-form-' + lessonId);
            
            document.getElementById('modalTitle').textContent = 'Dersi ' + action + ' Yap';
            
            if (action === 'Pasif') {
                document.getElementById('modalMessage').textContent = 
                    'Bu işlem dersi pasif duruma getirecek ve öğrencinin dersi görüntülemesini engelleyecektir. ' +
                    'Bu işlem oluşturulmuş seansları iptal etmez. İlerleyen dersleri iptal etmek için takvimi kullanınız.';
                document.getElementById('confirmButton').className = 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700';
            } else {
                document.getElementById('modalMessage').textContent = 
                    'Bu işlem dersi aktif duruma getirecek ve öğrencinin tekrar görüntülemesini sağlayacaktır.';
                document.getElementById('confirmButton').className = 'px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700';
            }
            
            document.getElementById('toggleModal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('toggleModal').classList.add('hidden');
            currentLessonId = null;
            currentForm = null;
        }
        
        function confirmToggle() {
            if (currentForm) {
                currentForm.submit();
            }
            closeModal();
        }
    
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
            }, 1000);
        };
    </script>

@endsection