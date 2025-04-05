<div>
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Ders Başlığı ve Durum -->
        <div class="p-6 border-b flex justify-between items-center" style="background-color: {{ $occurrence['color'] }}20">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $occurrence['title'] }}</h2>
                <p class="text-gray-600">{{ $occurrence['private_lesson_name'] }}</p>
            </div>
            <div class="flex items-center">
                <span class="mr-2">Durum:</span>
                <div class="relative inline-block text-left">
                    <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none" id="status-menu" aria-expanded="true" aria-haspopup="true">
                        @if($occurrence['status'] == 'scheduled')
                            <span class="inline-block w-2 h-2 rounded-full bg-blue-500 mr-2"></span> Planlandı
                        @elseif($occurrence['status'] == 'completed')
                            <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-2"></span> Tamamlandı
                        @elseif($occurrence['status'] == 'cancelled')
                            <span class="inline-block w-2 h-2 rounded-full bg-gray-500 mr-2"></span> İptal Edildi
                        @endif
                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="status-menu" id="status-dropdown">
                        <div class="py-1" role="none">
                            <button wire:click="updateStatus('scheduled')" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                <span class="inline-block w-2 h-2 rounded-full bg-blue-500 mr-2"></span> Planlandı
                            </button>
                            <button wire:click="updateStatus('completed')" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-2"></span> Tamamlandı
                            </button>
                            <button wire:click="updateStatus('cancelled')" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                <span class="inline-block w-2 h-2 rounded-full bg-gray-500 mr-2"></span> İptal Edildi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ders Bilgileri -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ders Bilgileri</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <span class="text-gray-500 w-32">Tarih:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($occurrence['lesson_date'])->format('d.m.Y') }}</span>
                    </div>
                    <div class="flex items-start">
                        <span class="text-gray-500 w-32">Saat:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($occurrence['start_time'])->format('H:i') }} - {{ \Carbon\Carbon::parse($occurrence['end_time'])->format('H:i') }}</span>
                    </div>
                    <div class="flex items-start">
                        <span class="text-gray-500 w-32">Süre:</span>
                        <span class="font-medium">{{ $occurrence['duration_minutes'] }} dakika</span>
                    </div>
                    <div class="flex items-start">
                        <span class="text-gray-500 w-32">Ücret:</span>
                        <span class="font-medium">{{ $occurrence['price'] }} ₺</span>
                    </div>
                    <div class="flex items-start">
                        <span class="text-gray-500 w-32">Konum:</span>
                        <span class="font-medium">{{ $occurrence['location'] }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Kişi Bilgileri</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <span class="text-gray-500 w-32">Öğretmen:</span>
                        <a href="#" class="font-medium text-blue-600 hover:underline">{{ $occurrence['teacher'] }}</a>
                    </div>
                    <div class="flex items-start">
                        <span class="text-gray-500 w-32">Öğrenci:</span>
                        <a href="#" class="font-medium text-blue-600 hover:underline">{{ $occurrence['student'] }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ders Notları -->
        <div class="p-6 border-t">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Öğretmen Notları</h3>
                <button wire:click="toggleEditMode" class="text-sm text-blue-600 hover:underline">
                    {{ $editMode ? 'İptal' : 'Düzenle' }}
                </button>
            </div>
            
            @if($editMode)
                <form wire:submit.prevent="saveNotes">
                    <textarea wire:model="occurrence.teacher_notes" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="4"></textarea>
                    <div class="mt-2 flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                            Kaydet
                        </button>
                    </div>
                </form>
            @else
                <p class="text-gray-700">{{ $occurrence['teacher_notes'] ?: 'Henüz not eklenmemiş.' }}</p>
            @endif
        </div>

        <!-- Ders Materyalleri -->
        <div class="p-6 border-t">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Ders Materyalleri</h3>
                <button class="text-sm text-blue-600 hover:underline">Materyal Ekle</button>
            </div>
            
            @if(count($occurrence['materials']) > 0)
                <div class="bg-gray-50 rounded-md border overflow-hidden">
                    <ul class="divide-y divide-gray-200">
                        @foreach($occurrence['materials'] as $material)
                            <li class="p-4 flex justify-between items-center hover:bg-gray-100">
                                <div>
                                    <h4 class="font-medium text-gray-800">{{ $material['title'] }}</h4>
                                    <p class="text-sm text-gray-600">{{ $material['description'] }}</p>
                                </div>
                                <a href="#" class="text-blue-600 hover:text-blue-800" title="İndir">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="text-gray-500 italic">Henüz materyal eklenmemiş.</p>
            @endif
        </div>

        <!-- Ödevler -->
        <div class="p-6 border-t">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Ödevler</h3>
                <button class="text-sm text-blue-600 hover:underline">Ödev Ekle</button>
            </div>
            
            @if(count($occurrence['homeworks']) > 0)
                <div class="bg-gray-50 rounded-md border overflow-hidden">
                    <ul class="divide-y divide-gray-200">
                        @foreach($occurrence['homeworks'] as $homework)
                            <li class="p-4 hover:bg-gray-100">
                                <div class="flex justify-between">
                                    <h4 class="font-medium text-gray-800">{{ $homework['title'] }}</h4>
                                    <span class="text-sm text-gray-600">Teslim: {{ \Carbon\Carbon::parse($homework['due_date'])->format('d.m.Y') }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $homework['description'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="text-gray-500 italic">Henüz ödev eklenmemiş.</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Durum dropdown menüsü için JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const statusMenu = document.getElementById('status-menu');
        const statusDropdown = document.getElementById('status-dropdown');
        
        if (statusMenu && statusDropdown) {
            statusMenu.addEventListener('click', function() {
                statusDropdown.classList.toggle('hidden');
            });
            
            document.addEventListener('click', function(e) {
                if (!statusMenu.contains(e.target) && !statusDropdown.contains(e.target)) {
                    statusDropdown.classList.add('hidden');
                }
            });
        }
    });
</script>
@endpush