<div>
    <!-- Filtreleme Formu -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <label for="studentName" class="block text-sm font-medium text-gray-700">Öğrenci Adı</label>
            <input type="text" wire:model.debounce.300ms="studentName" id="studentName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Öğrenci adını girin...">
        </div>
        <div class="flex-1">
            <label for="courseName" class="block text-sm font-medium text-gray-700">Kurs Adı</label>
            <input type="text" wire:model.debounce.300ms="courseName" id="courseName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Kurs adını girin...">
        </div>
    </div>

    <!-- Yükleniyor Göstergesi -->
    <div wire:loading class="flex justify-center my-4">
        <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    <!-- Tablo -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Öğrenci</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kurs</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ödev Başlığı</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gönderim Tarihi</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($recentHomeworks as $submission)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                @if ($submission->student && $submission->student->name)
                                    {{ strtoupper(substr($submission->student->name, 0, 1)) }}
                                @else
                                    N/A
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $submission->student->name ?? 'Öğrenci Bulunamadı' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if ($submission->homework && $submission->homework->course)
                            {{ $submission->homework->course->name }}
                        @else
                            Kurs Bulunamadı
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $submission->homework->title ?? 'Ödev Başlığı Yok' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $submission->submitted_at ? $submission->submitted_at->format('d.m.Y H:i') : 'Tarih Yok' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('ogretmen.submission.view', $submission->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Görüntüle</a>
                        <a href="{{ route('ogretmen.submission.evaluate', $submission->id) }}" class="text-indigo-600 hover:text-indigo-900">Değerlendir</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Filtrelere uygun ödev bulunamadı.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $recentHomeworks->links(data: ['scrollTo' => false]) }}
    </div>
</div>