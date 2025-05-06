<div>
    <!-- Filtreleme Alanı -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 md:mb-0">
                    {{ $showAll ? 'Tüm Özel Derslerim' : 'Aktif Özel Derslerim' }}
                </h2>
                
                <div class="flex flex-wrap gap-2">
                    <!-- Toggle butonu methodla yönetiliyor -->
                    <button 
                        wire:click="toggleShowAll"
                        class="px-3 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium transition duration-150"
                    >
                        {{ $showAll ? 'Sadece Aktifleri Göster' : 'İptal Edilenleri de Göster' }}
                    </button>
                    
                    <a href="{{ route('ogretmen.private-lessons.create') }}" 
                        class="px-3 py-2 bg-green-500 text-white hover:bg-green-600 rounded-lg text-sm font-medium transition duration-150 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Yeni Ders Ekle
                    </a>
                </div>
            </div>

            <div class="flex flex-col space-y-4">
                <!-- Arama ve Hızlı Filtreler -->
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="w-full md:w-1/3 relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Ders adı veya öğrenci adı ara..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        @if($search)
                            <button 
                                wire:click="$set('search', '')" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                    </div>

                    <div class="w-full md:w-1/3">
                        <select 
                            wire:model.live="statusFilter" 
                            class="w-full py-2 pl-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Tüm Durumlar</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full md:w-1/3">
                        <select 
                            wire:model.live="studentFilter" 
                            class="w-full py-2 pl-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Tüm Öğrenciler</option>
                            @foreach($students as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Gelişmiş Filtreler -->
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="w-full md:w-1/2 flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/2">
                            <label for="startDateFilter" class="block text-sm text-gray-600 mb-1">Başlangıç Tarihi</label>
                            <input 
                                type="date" 
                                id="startDateFilter"
                                wire:model.live="startDateFilter" 
                                class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>
                        <div class="w-full md:w-1/2">
                            <label for="endDateFilter" class="block text-sm text-gray-600 mb-1">Bitiş Tarihi</label>
                            <input 
                                type="date" 
                                id="endDateFilter"
                                wire:model.live="endDateFilter" 
                                class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>
                    </div>
                    
                    <div class="w-full md:w-1/2 flex items-end">
                        <button 
                            wire:click="resetFilters" 
                            class="w-full px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-sm font-medium transition duration-150 flex items-center justify-center"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Filtreleri Sıfırla
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sonuçlar -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6">
            <div wire:loading class="flex items-center justify-center p-4">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="ml-2 text-gray-700">Yükleniyor...</span>
            </div>

            <div wire:loading.remove>
                @if($groupedSessions->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($groupedSessions as $lessonId => $lessonSessions)
                            @php
                                // Her ders grubu için ilk session'dan ders bilgilerini al
                                $firstSession = $lessonSessions->first();
                                $lessonName = $firstSession->privateLesson->name ?? 'Ders Bulunamadı';
                                $isActive = $firstSession->privateLesson ? $firstSession->privateLesson->is_active : false;
                                
                                // Renk belirle - Modern gradyanlı renkler
                                $colors = [
                                    'bg-gradient-to-r from-blue-50 to-blue-100 border-blue-500',
                                    'bg-gradient-to-r from-green-50 to-green-100 border-green-500',
                                    'bg-gradient-to-r from-purple-50 to-purple-100 border-purple-500',
                                    'bg-gradient-to-r from-yellow-50 to-yellow-100 border-yellow-500',
                                    'bg-gradient-to-r from-pink-50 to-pink-100 border-pink-500',
                                    'bg-gradient-to-r from-indigo-50 to-indigo-100 border-indigo-500',
                                    'bg-gradient-to-r from-red-50 to-red-100 border-red-500',
                                    'bg-gradient-to-r from-orange-50 to-orange-100 border-orange-500',
                                ];
                                $colorIndex = $loop->index % count($colors);
                                $cardColor = $colors[$colorIndex];
                                
                                // Aktif değilse opacity ekle
                                $opacityClass = $isActive ? '' : 'opacity-60';
                            @endphp

                            <div class="rounded-lg shadow-sm overflow-hidden border-l-4 {{ $cardColor }} {{ $opacityClass }} hover:shadow-md transition duration-300">
                                <div class="p-5">
                                    <!-- Ders başlığı -->
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-xl font-bold text-gray-800">{{ $lessonName }}
                                            @if(!$isActive)
                                                <span class="ml-2 text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full">Pasif</span>
                                            @endif
                                        </h3>
                                        <span class="text-sm bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $lessonSessions->count() }} Seans</span>
                                    </div>
                                    
                                    <!-- Öğrenci Bilgisi -->
                                    <div class="mb-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3 text-blue-600">
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
                                        <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Yaklaşan Seanslar
                                        </h4>
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
                                                
                                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-150">
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
                                    <div class="mt-3 flex flex-wrap justify-end gap-2">
                                        <a href="{{ route('ogretmen.private-lessons.showLesson', $firstSession->private_lesson_id) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center px-2 py-1 hover:bg-blue-50 rounded transition duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                            Detaylar
                                        </a>
                                        <a href="{{ route('ogretmen.private-lessons.editLesson', $firstSession->private_lesson_id) }}" 
                                           class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center px-2 py-1 hover:bg-green-50 rounded transition duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                            Düzenle
                                        </a>
                                        <a href="{{ route('ogretmen.private-lessons.material.create', $lessonSessions->first()->id) }}"
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center px-2 py-1 hover:bg-blue-50 rounded transition duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M8 2a2 2 0 00-2 2v4H3a1 1 0 000 2h3v4a2 2 0 002 2h4a1 1 0 100-2H9v-4h3a1 1 0 100-2H9V4a1 1 0 00-1-1z" />
                                            </svg>
                                            Materyal
                                        </a>
                                        
                                        <!-- Dersi Aktif/Pasif yapma butonu -->
                                        <button
                                           wire:click="toggleLessonActive('{{ $firstSession->private_lesson_id }}')"
                                           wire:loading.attr="disabled"
                                           class="{{ $isActive ? 'text-red-600 hover:text-red-800 hover:bg-red-50' : 'text-blue-600 hover:text-blue-800 hover:bg-blue-50' }} text-sm font-medium flex items-center px-2 py-1 rounded transition duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <span wire:loading.remove wire:target="toggleLessonActive('{{ $firstSession->private_lesson_id }}')">
                                                {{ $isActive ? 'Pasif Yap' : 'Aktif Yap' }}
                                            </span>
                                            <span wire:loading wire:target="toggleLessonActive('{{ $firstSession->private_lesson_id }}')">
                                                İşleniyor...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Filtrelenmiş sonuçlar için boş sonuç durumu -->
                    @if($search || $statusFilter || $startDateFilter || $endDateFilter || $studentFilter)
                        @if($groupedSessions->count() == 0)
                            <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-yellow-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <p class="text-gray-600 text-lg font-medium">
                                    Aramanıza uygun sonuç bulunamadı.
                                </p>
                                <p class="text-gray-500 mt-2">
                                    Lütfen farklı anahtar kelimeler ile tekrar deneyin veya filtreleri sıfırlayın.
                                </p>
                                <button 
                                    wire:click="resetFilters"
                                    class="mt-4 px-4 py-2 bg-yellow-200 text-yellow-800 rounded-lg hover:bg-yellow-300 transition duration-150 inline-flex items-center"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Filtreleri Sıfırla
                                </button>
                            </div>
                        @endif
                    @endif
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
                        <div class="mt-4">
                            <a href="{{ route('ogretmen.private-lessons.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Yeni Ders Ekle
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Toast Bildirimleri -->
    <div 
        x-data="{ show: false, message: '', type: 'success' }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
        @toast.window="
            show = true; 
            message = $event.detail.message; 
            type = $event.detail.type || 'success';
            setTimeout(() => { show = false }, 5000);
        "
        class="fixed bottom-4 right-4 p-4 rounded-lg shadow-xl z-50 flex items-center"
        :class="{
            'bg-green-100 text-green-800 border border-green-200': type === 'success',
            'bg-red-100 text-red-800 border border-red-200': type === 'error',
            'bg-blue-100 text-blue-800 border border-blue-200': type === 'info',
            'bg-yellow-100 text-yellow-800 border border-yellow-200': type === 'warning'
        }"
    >
        <div class="mr-3" :class="{
            'text-green-500': type === 'success',
            'text-red-500': type === 'error',
            'text-blue-500': type === 'info',
            'text-yellow-500': type === 'warning'
        }">
            <!-- Success Icon -->
            <svg x-show="type === 'success'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            
            <!-- Error Icon -->
            <svg x-show="type === 'error'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            
            <!-- Info Icon -->
            <svg x-show="type === 'info'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            
            <!-- Warning Icon -->
            <svg x-show="type === 'warning'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div>
            <p x-text="message" class="font-medium"></p>
        </div>
        <button @click="show = false" class="ml-4 text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>