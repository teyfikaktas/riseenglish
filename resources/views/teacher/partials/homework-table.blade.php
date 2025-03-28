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