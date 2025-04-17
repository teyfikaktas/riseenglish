@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <!-- Başlık ve Geri Butonu -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Ders Raporu</h1>
            <div class="flex space-x-4">
                <a href="{{ route('ogretmen.private-lessons.session.show', $session->id) }}" class="flex items-center text-blue-600 hover:text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Ders Detaylarına Dön
                </a>
                <a href="{{ route('ogretmen.private-lessons.session.editReport', $session->id) }}" class="flex items-center text-green-600 hover:text-green-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828l-11.414 11.414a.5.5 0 01-.146.146L3 21l1.586-4.586a.5.5 0 01.146-.146L15.414 5.586z" />
                    </svg>
                    Raporu Düzenle
                </a>
            </div>
        </div>

        <!-- Ders Bilgileri -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Ders Bilgileri</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Ders:</p>
                    <p class="font-medium">{{ $session->privateLesson->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Tarih:</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Saat:</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Öğrenci:</p>
                    <p class="font-medium">{{ $session->student->name }}</p>
                </div>
            </div>
        </div>

        <!-- Rapor İçeriği -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <!-- Çözülen Sorular -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Çözülen Sorular</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600">Çözülen Soru</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $report->questions_solved }}</p>
                    </div>
                    <div class="p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-gray-600">Doğru</p>
                        <p class="text-2xl font-bold text-green-600">{{ $report->questions_correct }}</p>
                    </div>
                    <div class="p-4 bg-red-50 rounded-lg">
                        <p class="text-sm text-gray-600">Yanlış</p>
                        <p class="text-2xl font-bold text-red-600">{{ $report->questions_wrong }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">Boş</p>
                        <p class="text-2xl font-bold text-gray-600">{{ $report->questions_unanswered }}</p>
                    </div>
                </div>
            </div>

            <!-- Çözülen Denemeler / Soru Çözüm -->
            @if($report->examResults && $report->examResults->count() > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        {{ isset($report->content_type) && $report->content_type == 'soru_cozum' ? 'Soru Çözüm' : 'Çözülen Denemeler' }}
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ders</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doğru</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yanlış</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Boş</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Toplam</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($report->examResults as $examResult)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $examResult->subject_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                                            {{ $examResult->questions_correct }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                                            {{ $examResult->questions_wrong }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                            {{ $examResult->questions_unanswered }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                            {{ $examResult->questions_correct + $examResult->questions_wrong + $examResult->questions_unanswered }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-medium">
                                            {{ $examResult->questions_correct - ($examResult->questions_wrong * 0.25) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Değerlendirme -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ders Değerlendirmesi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-800 mb-2">Artıları</h4>
                        <div class="prose prose-sm max-w-none">
                            {!! nl2br(e($report->pros)) !!}
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-800 mb-2">Eksileri</h4>
                        <div class="prose prose-sm max-w-none">
                            {!! nl2br(e($report->cons)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Derse Katılım -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-800 mb-2">Derse Katılım</h4>
                <div class="p-4 bg-gray-50 rounded-lg prose prose-sm max-w-none">
                    {!! nl2br(e($report->participation)) !!}
                </div>
            </div>
            <a href="{{ route('ogretmen.private-lessons.session.pdfReport', $session->id) }}" 
                class="px-6 py-2 bg-purple-600 border border-transparent rounded-md text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                     <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                 </svg>
                 PDF İndir
             </a>
            <!-- Sadece öğretmen için notlar - sadece öğretmen tarafından görülebilir -->
            <div class="mt-8 p-4 border border-yellow-300 bg-yellow-50 rounded-lg">
                <h4 class="font-medium text-yellow-800 mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    Öğretmen Notları (Sadece sizin tarafınızdan görülebilir)
                </h4>
                <div class="prose prose-sm max-w-none text-yellow-800">
                    {!! nl2br(e($report->teacher_notes)) !!}
                </div>
            </div>
        </div>

        <!-- İşlem Butonları -->
        <div class="flex justify-end space-x-4 mt-6">
            <a href="{{ route('ogretmen.private-lessons.session.editReport', $session->id) }}" 
               class="px-6 py-2 bg-blue-600 border border-transparent rounded-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Raporu Düzenle
            </a>
            <button type="button" 
                    onclick="if(confirm('Bu raporu silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')) { document.getElementById('delete-report-form').submit(); }" 
                    class="px-6 py-2 bg-red-600 border border-transparent rounded-md text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Raporu Sil
            </button>
            <form id="delete-report-form" action="{{ route('ogretmen.private-lessons.session.deleteReport', $session->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>
@endsection