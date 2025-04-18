<!-- resources/views/livewire/private-lesson-calendar.blade.php -->
<div>
    @if($nextLesson)
    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl shadow-lg p-6 border border-indigo-100 mb-8 transform hover:shadow-xl transition-all duration-300">
        <div class="flex flex-col md:flex-row justify-between">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-xl font-bold text-indigo-800">Sıradaki Dersiniz</h2>
                </div>
                <p class="text-md text-gray-700 mb-2">
                    <span class="font-semibold">{{ $nextLesson['title'] }}</span> - 
                    <span class="text-indigo-600">{{ $nextLesson['student'] }}</span>
                </p>
                <p class="text-gray-600 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $nextLesson['date'] }}, {{ $nextLesson['start_time'] }} - {{ $nextLesson['end_time'] }}
                </p>
                <p class="text-gray-600 flex items-center mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $nextLesson['location'] }}
                </p>
            </div>
            
            <div class="flex flex-col items-center justify-center md:items-end space-y-3">
                <div class="bg-white p-3 rounded-lg shadow-sm border border-indigo-100 text-center">
                    <p class="text-sm text-gray-500">Kalan Süre</p>
                    <p class="text-xl font-bold text-indigo-700">{{ $nextLesson['time_left_formatted'] }}</p>
                </div>
                
                <a 
                    href="{{ route('ogretmen.private-lessons.session.show', $nextLesson['id']) }}" 
                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-md transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 text-sm inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Detayı Görüntüle
                </a>
            </div>
        </div>
    </div>
    @endif
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
        <!-- Başlık ve Navigasyon -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-indigo-800 mb-4 md:mb-0">Özel Ders Takvimi</h1>
            <div class="flex items-center space-x-3">
                <button wire:click="previousWeek" class="px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 rounded-lg flex items-center transition-all duration-200 text-indigo-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Önceki</span>
                </button>
                
                <!-- Flatpickr Tarih Seçici -->
                <div id="flatpickr-wrapper" class="relative">
                    <input 
                        type="text" 
                        id="flatpickr-date" 
                        class="hidden"
                        placeholder="Tarih Seçin" 
                    />
                    <button 
                    wire:click="openDatePicker" 
                    class="px-5 py-2.5 bg-indigo-100 text-indigo-800 font-medium rounded-lg border border-indigo-200 flex items-center">
                    <span>{{ $weekStart->format('d M Y') }} - {{ $weekStart->copy()->addDays(count($weekDates)-1)->format('d M Y') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </button>
                </div>
                
                <button wire:click="nextWeek" class="px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 rounded-lg flex items-center transition-all duration-200 text-indigo-700">
                    <span>Sonraki</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
        @if($showDatePicker)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full" x-data>
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-semibold text-gray-900">Tarih Seçin</h3>
              <button wire:click="$set('showDatePicker', false)" class="text-gray-500 hover:text-gray-700">
                <!-- kapatma ikonu -->
              </button>
            </div>
        
            <!-- Flatpickr input -->
            <input
              x-ref="picker"
              wire:model="selectedDate"
              x-init="flatpickr($refs.picker, {
                locale: 'tr',
                dateFormat: 'Y-m-d',
                defaultDate: '{{ $selectedDate }}',
                onChange(selectedDates, dateStr) {
                  @this.call('changeDate', dateStr);
                  @this.set('showDatePicker', false);
                }
              })"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
              placeholder="Tarih seçin"
            />
        
            <div class="mt-4 flex justify-end">
              <button
                wire:click="selectDate('{{ now('Europe/Istanbul')->format('Y-m-d') }}')"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
              >Bugün</button>
            </div>
        
          </div>
        </div>
        @endif
        
        <!-- Filtreler -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Durum</label>
                <div class="relative">
                    <select id="status" wire:change="filterByStatus($event.target.value)" class="block w-full pl-4 pr-10 py-3 text-base border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-xl shadow-sm appearance-none bg-white">
                        <option value="">Tüm Durumlar</option>
                        @foreach($statuses as $key => $status)
                            <option value="{{ $key }}" {{ $selectedStatus == $key ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <label for="view-type" class="block text-sm font-semibold text-gray-700 mb-2">Görünüm</label>
                <div class="flex flex-col space-y-3">
                    <div class="relative">
                        <select id="view-type" wire:change="changeViewType($event.target.value)" class="block w-full pl-4 pr-10 py-3 text-base border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-xl shadow-sm appearance-none bg-white">
                            <option value="week" {{ $viewType == 'week' ? 'selected' : '' }}>Haftalık</option>
                            <option value="day" {{ $viewType == 'day' ? 'selected' : '' }}>Günlük</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Yeni ders ekleme butonu ve Kompakt Görünüm Butonu -->
        <div class="flex flex-wrap justify-between items-center mb-8">
            <a href="{{ route('ogretmen.private-lessons.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-md transform hover:-translate-y-1 mb-4 sm:mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Yeni Ders Ekle
            </a>
            
            <button wire:click="toggleCompactView" class="inline-flex items-center px-4 py-2.5 {{ $compactView ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-lg transition-all duration-200 shadow-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
                {{ $compactView ? 'Normal Görünüm' : 'Kompakt Görünüm' }}
            </button>
        </div>

        <!-- Takvim -->
        <div class="overflow-x-auto rounded-xl shadow-lg">
            <div class="min-w-full">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-100">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-4 bg-indigo-50 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider sticky left-0 z-10 w-24 border-b border-r border-indigo-100">
                                Saat
                            </th>
                            @foreach($weekDates as $date)
                            <th scope="col" class="px-6 py-4 text-center {{ $date->isToday() ? 'bg-indigo-100 text-indigo-900' : 'bg-indigo-50 text-indigo-800' }} text-xs font-semibold uppercase tracking-wider border-b border-r border-indigo-100">
                                <div class="flex flex-col items-center">
                                    <div class="text-sm mb-1">{{ $date->locale('tr')->translatedFormat('D') }}</div>
                                    <div class="text-xl font-bold">{{ $date->format('d') }}</div>
                                    <div class="text-sm">{{ $date->locale('tr')->translatedFormat('M Y') }}</div>
                                </div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($timeSlots as $index => $timeSlot)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r sticky left-0 bg-indigo-50 z-10">
                                    <div class="flex items-center justify-center {{ $compactView ? 'h-8' : 'h-14' }}">
                                        {{ $timeSlot }}
                                    </div>
                                </td>
                                
                                @foreach($weekDates as $date)
                                    @php
                                        $dateFormatted = $date->format('Y-m-d');
                                        $hasEvents = isset($calendarData[$dateFormatted][$timeSlot]) && count($calendarData[$dateFormatted][$timeSlot]) > 0;
                                        $isCurrentHour = now()->format('Y-m-d H:i') === $date->format('Y-m-d ') . $timeSlot;
                                        
                                        // Bu hücreyi atlamamız gerekip gerekmediğini belirleme
                                        $skipCell = false;
                                        
                                        // Önceki satırlardaki olayları kontrol et (rowspan için)
                                        if ($index > 0) {
                                            for ($i = $index - 1; $i >= 0; $i--) {
                                                $prevTimeSlot = $timeSlots[$i];
                                                if (isset($calendarData[$dateFormatted][$prevTimeSlot])) {
                                                    foreach ($calendarData[$dateFormatted][$prevTimeSlot] as $occurrence) {
                                                        if (isset($occurrence['rowspan']) && $occurrence['rowspan'] > 1) {
                                                            $spans = $occurrence['rowspan'] - 1;
                                                            if ($i + $spans >= $index) {
                                                                $skipCell = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($skipCell) break;
                                            }
                                        }
                                    @endphp
                                    
                                    @if(!$skipCell)
                                        <td class="relative p-2 border-r {{ $date->isToday() ? 'bg-indigo-50/30' : '' }} {{ $isCurrentHour ? 'bg-yellow-50' : '' }}">
                                            @if($hasEvents)
                                                @foreach($calendarData[$dateFormatted][$timeSlot] as $occurrence)
                                                    @php
                                                        $statusColors = [
                                                            'scheduled' => ['bg' => 'bg-blue-600', 'text' => 'text-white', 'hover' => 'hover:bg-blue-700', 'border' => 'border-l-4 border-blue-800'],
                                                            'completed' => ['bg' => 'bg-green-600', 'text' => 'text-white', 'hover' => 'hover:bg-green-700', 'border' => 'border-l-4 border-green-800'],
                                                            'cancelled' => ['bg' => 'bg-gray-500', 'text' => 'text-white', 'hover' => 'hover:bg-gray-600', 'border' => 'border-l-4 border-gray-700'],
                                                            'pending' => ['bg' => 'bg-amber-500', 'text' => 'text-white', 'hover' => 'hover:bg-amber-600', 'border' => 'border-l-4 border-amber-700'],
                                                            'active' => ['bg' => 'bg-emerald-600', 'text' => 'text-white', 'hover' => 'hover:bg-emerald-700', 'border' => 'border-l-4 border-emerald-800'],
                                                            'rejected' => ['bg' => 'bg-red-600', 'text' => 'text-white', 'hover' => 'hover:bg-red-700', 'border' => 'border-l-4 border-red-800'],
                                                            'approved' => ['bg' => 'bg-indigo-600', 'text' => 'text-white', 'hover' => 'hover:bg-indigo-700', 'border' => 'border-l-4 border-indigo-800'],
                                                        ];
                                                        $colors = $statusColors[$occurrence['status']] ?? ['bg' => 'bg-indigo-600', 'text' => 'text-white', 'hover' => 'hover:bg-indigo-700', 'border' => 'border-l-4 border-indigo-800'];
                                                        
                                                        $startTime = Carbon\Carbon::parse($occurrence['start_time'])->format('H:i');
                                                        $endTime = Carbon\Carbon::parse($occurrence['end_time'])->format('H:i');
                                                        
                                                        // Gerçek ders süresini göster
                                                        $rowspan = isset($occurrence['rowspan']) && $occurrence['rowspan'] > 1 ? $occurrence['rowspan'] : 1;
                                                    @endphp
                                                    
                                                    <div class="mb-2 p-2 rounded-lg text-sm shadow-md {{ $colors['bg'] }} {{ $colors['text'] }} transition-all duration-200 hover:shadow-lg transform hover:-translate-y-1 hover:brightness-105 group"
                                                         @if($rowspan > 1) style="height: calc({{ $rowspan }} * {{ $compactView ? '2rem' : '3.5rem' }} - 0.5rem);" @endif>
                                                        <!-- Ders içeriği -->
                                                        <div 
                                                            onclick="window.location.href='{{ route('ogretmen.private-lessons.session.show', $occurrence['id']) }}'"
                                                            class="cursor-pointer h-full flex flex-col justify-between">
                                                            <div>
                                                                <div class="font-medium {{ $compactView ? 'text-xs' : '' }} text-white">{{ $occurrence['title'] }}</div>
                                                                <div class="flex justify-between items-center mt-1 {{ $compactView ? 'text-xs opacity-90' : 'text-xs opacity-95' }} text-white">
                                                                    <div>{{ $occurrence['student'] }}</div>
                                                                    <div>{{ $startTime }} - {{ $endTime }}</div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="mt-auto">
                                                                <div class="text-xs text-white/90">
                                                                    {{ $occurrence['location'] }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button 
                                                        onclick="window.location.href='{{ route('ogretmen.private-lessons.session.show', $occurrence['id']) }}'"
                                                        class="mt-2 w-full px-3 py-1 bg-white text-gray-800 rounded-md text-xs font-medium hover:bg-gray-100 transition-all duration-200">
                                                        Detayına Git
                                                    </button>
                                                @endforeach
                                            @else
                                                <div class="flex items-center justify-center w-full h-full">
                                                   <div class="{{ $compactView ? 'h-8' : 'h-14' }} w-full border border-dashed border-gray-200 rounded-lg flex items-center justify-center">
                                                       <span class="text-gray-300 text-xs">Ders yok</span>
                                                   </div>
                                                </div>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Renk Açıklamaları -->
        <div class="mt-8 bg-gray-50 p-5 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Ders Durumları</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
               <span class="flex items-center">
                   <span class="w-4 h-4 bg-blue-600 rounded-full mr-2"></span>
                   <span class="text-sm text-gray-700">Planlandı</span>
               </span>
               <span class="flex items-center">
                   <span class="w-4 h-4 bg-green-600 rounded-full mr-2"></span>
                   <span class="text-sm text-gray-700">Tamamlandı</span>
               </span>
               <span class="flex items-center">
                   <span class="w-4 h-4 bg-gray-500 rounded-full mr-2"></span>
                   <span class="text-sm text-gray-700">İptal Edildi</span>
               </span>
               <span class="flex items-center">
                   <span class="w-4 h-4 bg-amber-500 rounded-full mr-2"></span>
                   <span class="text-sm text-gray-700">Beklemede</span>
               </span>
               <span class="flex items-center">
                   <span class="w-4 h-4 bg-emerald-600 rounded-full mr-2"></span>
                   <span class="text-sm text-gray-700">Aktif</span>
               </span>
               <span class="flex items-center">
                   <span class="w-4 h-4 bg-red-600 rounded-full mr-2"></span>
                   <span class="text-sm text-gray-700">Reddedildi</span>
               </span>
            </div>
        </div>
    </div>
    
    <!-- Ders Detay Modalı -->
    @if($selectedLesson)
    <div id="lessonModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 backdrop-blur-sm" x-data>
        <div class="bg-white rounded-2xl max-w-2xl w-full mx-4 shadow-2xl transform transition-all duration-300 animate-fadeIn">
           <!-- Modal Header -->
           <div class="flex justify-between items-center border-b p-6">
               <h3 class="text-xl font-bold text-indigo-900">Ders Detayları</h3>
               <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700 transition-colors focus:outline-none">
                   <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                   </svg>
               </button>
           </div>
           
           <!-- Modal Content -->
           <div class="p-6">
               <div class="space-y-6">
                   <!-- Başlık Bilgisi -->
                   <div class="border-b border-gray-100 pb-4">
                       <h4 class="text-2xl font-bold text-gray-800">{{ $selectedLesson['title'] }}</h4>
                       <p class="text-md text-gray-600 mt-1">{{ $selectedLesson['private_lesson_name'] }}</p>
                   </div>
                   
                   <!-- Temel Bilgiler -->
                   <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                       <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
                           <p class="text-sm text-gray-500 mb-1">Öğrenci</p>
                           <p class="font-medium text-gray-800">{{ $selectedLesson['student'] }}</p>
                       </div>
                       <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
                           <p class="text-sm text-gray-500 mb-1">Öğretmen</p>
                           <p class="font-medium text-gray-800">{{ $selectedLesson['teacher'] }}</p>
                       </div>
                       <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
                           <p class="text-sm text-gray-500 mb-1">Tarih</p>
                           <p class="font-medium text-gray-800">{{ Carbon\Carbon::parse($selectedLesson['lesson_date'])->format('d.m.Y') }}</p>
                       </div>
                       <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
                           <p class="text-sm text-gray-500 mb-1">Saat</p>
                           <p class="font-medium text-gray-800">{{ Carbon\Carbon::parse($selectedLesson['start_time'])->format('H:i') }} - {{ Carbon\Carbon::parse($selectedLesson['end_time'])->format('H:i') }}</p>
                       </div>
                       <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
                           <p class="text-sm text-gray-500 mb-1">Konum</p>
                           <p class="font-medium text-gray-800">{{ $selectedLesson['location'] }}</p>
                       </div>
                       <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
                           <p class="text-sm text-gray-500 mb-1">Durum</p>
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
                                   $badgeColor = $statusColors[$selectedLesson['status']] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                               @endphp
                               <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $badgeColor }}">
                                   {{ $statuses[$selectedLesson['status']] ?? $selectedLesson['status'] }}
                               </span>
                           </p>
                       </div>
                       <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
                           <p class="text-sm text-gray-500 mb-1">Ücret</p>
                           <p class="font-medium text-gray-800">₺{{ $selectedLesson['price'] }}</p>
                       </div>
                   </div>
                   
                   <!-- Notlar Bölümü -->
                   @if($selectedLesson['notes'])
                   <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
                       <p class="text-sm text-gray-500 mb-2">Notlar</p>
                       <div class="bg-white p-3 rounded-lg border border-gray-100">
                           <p class="text-gray-800">{{ $selectedLesson['notes'] }}</p>
                       </div>
                   </div>
                   @endif
                   
                   <!-- Aksiyon Kartları -->
                   <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                       <!-- Ders Tamamlama Kartı -->
                       @php
                           $isLessonCompleted = $selectedLesson['status'] === 'completed';
                           $currentTime = now();
                           $lessonEndTime = Carbon\Carbon::parse($selectedLesson['lesson_date'] . ' ' . $selectedLesson['end_time']);
                           $isLessonPassed = $currentTime->isAfter($lessonEndTime);
                           $canCompleteLesson = !$isLessonPassed && !$isLessonCompleted;
                       @endphp
                       
                       <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                           <div class="flex flex-col">
                               <h5 class="text-sm font-semibold text-gray-700 mb-3">Ders Durumu</h5>
                               
                               @if($isLessonCompleted)
                                   <div class="flex items-center">
                                       <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                           <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                                               <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                           </svg>
                                           Ders Tamamlandı
                                       </span>
                                   </div>
                               @elseif($isLessonPassed)
                                   <button disabled class="px-4 py-2 bg-gray-200 text-gray-500 rounded-lg transition-colors shadow-sm cursor-not-allowed flex items-center">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                       </svg>
                                       Zamanı Geçmiş Ders
                                   </button>
                                   <p class="mt-2 text-xs text-orange-600">Saati geçilmiş dersler için tamamlandı girişi yapılamaz.</p>
                               @else
                                   <button 
                                       wire:click="completeLesson({{ $selectedLesson['id'] }})" 
                                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 shadow-md flex items-center transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                       </svg>
                                       Dersi Tamamla
                                   </button>
                                   <p class="mt-2 text-xs text-gray-600">Dersin tamamlandı bilgisi veliye ve öğrenciye SMS olarak gidecektir.</p>
                               @endif
                           </div>
                       </div>
                       
                       <!-- Materyal Yükleme Kartı -->
                       <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                           <div class="flex flex-col">
                               <h5 class="text-sm font-semibold text-gray-700 mb-3">Ders Materyalleri</h5>
                               
                               @if(!$isLessonCompleted)
                                   <button disabled class="px-4 py-2 bg-gray-200 text-gray-500 rounded-lg transition-colors shadow-sm cursor-not-allowed flex items-center">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                       </svg>
                                       Materyal Ekle
                                   </button>
                                   <p class="mt-2 text-xs text-orange-600">Ders tamamlanmadan materyal yüklenemez.</p>
                               @else
                                   <a 
                                       href="{{ route('ogretmen.private-lessons.materials.create', $selectedLesson['id']) }}" 
                                       class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-md flex items-center transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                       </svg>
                                       Materyal Ekle
                                   </a>
                                   <p class="mt-2 text-xs text-gray-600">Ders için kullanılan materyalleri yükleyebilirsiniz.</p>
                               @endif
                           </div>
                       </div>
                   </div>
                   
                   <!-- Alt Butonlar -->
                   <div class="flex justify-end space-x-4 mt-6 pt-4 border-t border-gray-100">
                       <button 
                           wire:click="closeModal" 
                           class="px-5 py-2.5 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-opacity-50">
                           Kapat
                       </button>
                       <a 
                           href="{{ route('ogretmen.private-lessons.edit', $selectedLesson['id']) }}" 
                           class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-md transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                           Düzenle
                       </a>
                   </div>
               </div>
           </div>
        </div>
    </div>
    @endif

    <!-- Bildirim Sistemi -->
    <div id="notification-container" class="fixed top-4 right-4 z-50"></div>

</div>

<script>
document.addEventListener('livewire:load', function () {
    // Flatpickr tarih seçici
    initFlatpickr();

    // Livewire sayfası yeniden render edildiğinde Flatpickr'ı yeniden başlat
    Livewire.hook('message.processed', () => {
        initFlatpickr();
    });

 // Konsola debug mesajları ekleyerek sorunu tespit edelim
function initFlatpickr() {
    console.log('initFlatpickr çağrıldı');
    const datePickerElement = document.getElementById('flatpickr-date');
    
    if (datePickerElement) {
        console.log('flatpickr-date elementi bulundu');
        try {
            const flatpickrInst = flatpickr("#flatpickr-date", {
                locale: "tr",
                dateFormat: "Y-m-d",
                defaultDate: "{{ $weekStart->format('Y-m-d') }}",
                onChange: function(selectedDates, dateStr) {
                    console.log('Tarih seçildi:', dateStr);
                    // Seçilen tarihi Livewire'a gönder
                    @this.call('changeDate', dateStr);
                }
            });
            
            console.log('Flatpickr başarıyla initialize edildi');
            
            // Flatpickr'ı manuel olarak tetiklemek için buton
            const triggerButton = document.getElementById('flatpickr-trigger');
            if (triggerButton) {
                console.log('flatpickr-trigger butonu bulundu');
                triggerButton.addEventListener('click', function() {
                    console.log('Trigger butona tıklandı');
                    flatpickrInst.open();
                });
            } else {
                console.error('flatpickr-trigger butonu bulunamadı!');
            }
        } catch(e) {
            console.error('Flatpickr initialize edilirken hata:', e);
        }
    } else {
        console.error('flatpickr-date elementi bulunamadı!');
    }
}

    // Ders tamamlandı bildirimi
    Livewire.on('lessonCompleted', function (message) {
       showNotification(message || 'Ders başarıyla tamamlandı!', 'success');
    });

    // Hata bildirimi
    Livewire.on('lessonError', function (message) {
       showNotification(message || 'İşlem sırasında bir hata oluştu.', 'error');
    });

    // Genel bildirim sistemi
    function showNotification(message, type = 'success') {
        const container = document.getElementById('notification-container');
        const notification = document.createElement('div');
        notification.className = 'flex items-center p-4 mb-3 rounded-lg shadow-lg transform transition-all duration-300 opacity-0 translate-x-full max-w-md';

        if (type === 'success') {
            notification.classList.add('bg-green-600', 'text-white');
            notification.innerHTML = `
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium">${message}</p>
                </div>
                <div class="flex-shrink-0 ml-3">
                    <button class="text-white focus:outline-none hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            `;
        } else {
            notification.classList.add('bg-red-600', 'text-white');
            notification.innerHTML = `
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium">${message}</p>
                </div>
                <div class="flex-shrink-0 ml-3">
                    <button class="text-white focus:outline-none hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
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
});
</script>