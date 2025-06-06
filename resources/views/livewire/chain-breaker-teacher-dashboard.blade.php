<div>
    <!-- Bildirim (Toast) Bileşeni -->
    <div x-data="{ show: false, message: '' }"
        x-on:show-success.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
        x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
        class="fixed top-16 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center"
        style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd" />
        </svg>
        <span x-text="message"></span>
    </div>

    <div class="bg-white rounded-xl shadow-xl overflow-hidden">
        <!-- Başlık -->
        <div class="bg-gradient-to-r from-[#1a2e5a] to-[#e63946] p-6">
            <h2 class="text-2xl sm:text-3xl font-bold text-white flex items-center">
                <i class="fas fa-link mr-3"></i>
                Öğrenci Zinciri Kırma Takibi
            </h2>
            <p class="text-white/80 mt-2">Öğrencilerinizin ders çalışma takibini yapın</p>
        </div>

        <!-- Arama -->
        <div class="p-6 border-b">
            <div class="max-w-md">
                <label class="block text-sm font-medium text-gray-700 mb-2">Öğrenci Ara</label>
                <div class="relative">
                    <input type="text" wire:model.live="searchTerm" placeholder="İsim, soyisim veya email ile ara..."
                        class="w-full rounded-lg border-gray-300 pl-10 pr-4 py-2 focus:border-blue-500 focus:ring-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Öğrenci Listesi -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Öğrenci</th>
                        <th
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider md:table-cell hidden">
                            Toplam Gün</th>
                        <th
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider md:table-cell hidden">
                            Seviye</th>
                        <th
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider md:table-cell hidden">
                            Son Aktivite</th>
                        <th
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider md:table-cell hidden">
                            İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($students as $student)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($student->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $student->name }} {{ $student->surname }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $student->email }}
                                            </div>
                                            <!-- Mobil görünümde gösterilecek özet bilgiler -->
                                            <div class="md:hidden mt-2 flex space-x-3 text-xs">
                                                @if ($student->chainProgress)
                                                    <span class="text-gray-700">
                                                        <span
                                                            class="font-bold text-[#e63946]">{{ $student->chainProgress->days_completed }}</span>
                                                        gün
                                                    </span>
                                                    <span
                                                        class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full text-white"
                                                        style="background-color: {{ $student->chainProgress->getLevelColor() }}">
                                                        {{ $student->chainProgress->getCurrentLevel() }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Mobil görünümde detay butonu -->
                                    <div class="md:hidden">
                                        <a href="{{ route('ogretmen.student.chain-detail', $student->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm inline-flex items-center">
                                            <i class="fas fa-eye mr-1"></i> Detay
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center md:table-cell hidden">
                                @if ($student->chainProgress)
                                    <span class="text-2xl font-bold text-[#e63946]">
                                        {{ $student->chainProgress->days_completed }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center md:table-cell hidden">
                                @if ($student->chainProgress)
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full text-white"
                                        style="background-color: {{ $student->chainProgress->getLevelColor() }}">
                                        {{ $student->chainProgress->getCurrentLevel() }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center md:table-cell hidden">
                                @if ($student->chainActivities->isNotEmpty())
                                    <span class="text-sm text-gray-600">
                                        {{ $student->chainActivities->first()->created_at->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center md:table-cell hidden">
                                <a href="{{ route('ogretmen.student.chain-detail', $student->id) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-xs inline-flex items-center">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Sayfalama -->
        <div class="px-6 py-4 border-t">
            {{ $students->links() }}
        </div>
    </div>

    <!-- Öğrenci Detay Modal -->
    @if ($selectedStudent)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
            wire:click.self="closeStudentModal">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" wire:click.stop>
                <!-- Modal Header -->
                <div class="sticky top-0 bg-gradient-to-r from-[#1a2e5a] to-[#e63946] p-6 rounded-t-xl">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-white">
                                {{ $selectedStudent->name }} {{ $selectedStudent->surname }}
                            </h3>
                            <p class="text-white/80">Zinciri Kırma Detayları</p>
                        </div>
                        <button wire:click="closeStudentModal" class="text-white hover:text-gray-200">
                            <i class="fas fa-times text-2xl"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <!-- İstatistikler -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                            <div class="text-sm opacity-80">Toplam Gün</div>
                            <div class="text-3xl font-bold mt-2">
                                {{ $selectedStudent->chainProgress->days_completed ?? 0 }}
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
                            <div class="text-sm opacity-80">Seviye</div>
                            <div class="text-2xl font-bold mt-2">
                                {{ $selectedStudent->chainProgress->getCurrentLevel() ?? 'Başlangıç' }}
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white">
                            <div class="text-sm opacity-80">Toplam Çalışma</div>
                            <div class="text-3xl font-bold mt-2">
                                {{ $selectedStudent->chainActivities->count() }}
                            </div>
                        </div>
                    </div>

                    <!-- Gün Ayarlama -->
                    <div class="bg-gray-50 rounded-xl p-6 mb-8" x-data="{ isUpdated: false, clearFields: false }"
                        x-on:student-days-updated.window="if($event.detail.studentId === {{ $selectedStudent->id }}) { 
                         isUpdated = true; 
                         clearFields = true;
                         setTimeout(() => isUpdated = false, 2000); 
                     }">
                        <h4 class="text-lg font-bold text-[#1a2e5a] mb-4">
                            <i class="fas fa-edit mr-2"></i>Gün Sayısı Ayarla
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ekle/Çıkar (Gün)
                                </label>
                                <input type="number" wire:model="adjustDays"
                                    x-effect="if(clearFields) { 
                                    $el.value = ''; 
                                    setTimeout(() => clearFields = false, 100);
                                }"
                                    class="w-full rounded-lg border-gray-300" placeholder="+5 veya -3">
                                @error('adjustDays')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Sebep
                                </label>
                                <input type="text" wire:model="adjustReason"
                                    x-effect="if(clearFields) { 
                                    $el.value = ''; 
                                    setTimeout(() => clearFields = false, 100);
                                }"
                                    class="w-full rounded-lg border-gray-300"
                                    placeholder="Neden gün ekliyorsunuz veya çıkarıyorsunuz?">
                                @error('adjustReason')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <button wire:click="adjustStudentDays"
                                class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-4 rounded-lg transition-all"
                                :class="{ 'ring-4 ring-green-400 ring-opacity-50': isUpdated }">
                                <i class="fas fa-save mr-2"></i>Güncelle
                            </button>
                        </div>
                    </div>

                    <!-- Son Aktiviteler -->
                    <div>
                        <h4 class="text-lg font-bold text-[#1a2e5a] mb-4">
                            <i class="fas fa-history mr-2"></i>Son Aktiviteler
                        </h4>

                        @php
                            $activitiesByDate = $selectedStudent->chainActivities
                                ->sortByDesc('activity_date')
                                ->groupBy(function ($activity) {
                                    return $activity->activity_date->format('Y-m-d');
                                });
                        @endphp

                        <div class="space-y-4">
                            @foreach ($activitiesByDate as $date => $activities)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-center mb-3">
                                        <h5 class="font-bold text-gray-800">
                                            {{ \Carbon\Carbon::parse($date)->locale('tr')->isoFormat('DD MMMM YYYY, dddd') }}
                                        </h5>
                                        <button wire:click="viewDateActivities('{{ $date }}')"
                                            class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Detay
                                        </button>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ $activities->count() }} çalışma kaydı
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Aktivite Detay Modal -->
    @if ($showActivityDetail && $selectedDate)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
            wire:click.self="closeActivityModal">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" wire:click.stop>
                <div class="sticky top-0 bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-t-xl">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-white">
                                {{ \Carbon\Carbon::parse($selectedDate)->locale('tr')->isoFormat('DD MMMM YYYY') }}
                            </h3>
                            <p class="text-white/80">Günlük Çalışma Detayları</p>
                        </div>
                        <button wire:click="closeActivityModal" class="text-white hover:text-gray-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        @foreach ($selectedActivities as $activity)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-book text-blue-500 text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        @if ($activity->content)
                                            <p class="text-gray-700">{{ $activity->content }}</p>
                                        @endif
                                        @if ($activity->file_name)
                                            <a href="{{ Storage::url($activity->file_path) }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-800 text-sm mt-1 inline-flex items-center">
                                                <i class="fas fa-file mr-1"></i> {{ $activity->file_name }}
                                            </a>
                                        @endif
                                        <div class="mt-2 text-xs text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $activity->created_at->format('H:i') }}
                                            @if ($activity->is_adjustment)
                                                <span class="ml-2 bg-yellow-100 text-yellow-800 px-2 py-1 rounded">
                                                    Öğretmen Düzenlemesi
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' || e.key === 'Esc') {
                @this.closeStudentModal();
                @this.closeActivityModal();
            }
        });
    </script>
@endpush
