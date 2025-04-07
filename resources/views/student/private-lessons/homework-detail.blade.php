@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Geri Butonu ve Başlık -->
    <div class="flex items-center mb-6">
        <a href="{{ route('ogrenci.private-lessons.homeworks') }}" class="mr-4 text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $homework->title }}</h1>
            <p class="text-gray-600">{{ $homework->session->privateLesson->name ?? 'Özel Ders' }}</p>
        </div>
    </div>

    @php
        // Son teslim tarihi geçmiş mi?
        $dueDate = \Carbon\Carbon::parse($homework->due_date);
        $now = \Carbon\Carbon::now();
        $isOverdue = $now->isAfter($dueDate);
        
        // Öğrenci teslim etmiş mi?
        $submitted = $submission !== null;
        
        // Durum renklerini belirle
        if ($submitted) {
            $statusClass = 'bg-green-100 border-green-500 text-green-800';
            $statusText = 'Teslim Edildi';
        } elseif ($isOverdue) {
            $statusClass = 'bg-red-100 border-red-500 text-red-800';
            $statusText = 'Süresi Doldu';
        } else {
            $statusClass = 'bg-yellow-100 border-yellow-500 text-yellow-800';
            $statusText = 'Teslim Edilmedi';
        }
    @endphp

    <!-- Durum Kartı -->
    <div class="mb-8 p-6 {{ $statusClass }} border-l-4 rounded-lg shadow-sm">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold">{{ $statusText }}</h2>
                @if($submitted)
                    <p>Bu ödevi {{ \Carbon\Carbon::parse($submission->submission_date)->format('d.m.Y H:i') }} tarihinde teslim ettiniz.</p>
                @elseif($isOverdue)
                    <p>Bu ödevin son teslim tarihi geçmiştir. Öğretmeninizle iletişime geçiniz.</p>
                @else
                    <p>Son teslim tarihi: {{ $dueDate->format('d.m.Y H:i') }}</p>
                @endif
            </div>
            <div>
                @if($submitted && $submission->score !== null)
                    <div class="text-center bg-white rounded-lg shadow-sm p-3">
                        <p class="text-sm text-gray-600">Puan</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $submission->score }} <span class="text-sm text-gray-500">/ 100</span></p>
                    </div>
                @elseif(!$isOverdue && !$submitted)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-600 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        {{ $dueDate->diffForHumans(['parts' => 2]) }} kaldı
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Ödev İçeriği ve Teslim Formu -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Ödev Detayları -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-800">Ödev Detayları</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Son Teslim Tarihi</p>
                        <p class="font-medium text-gray-800">{{ $dueDate->format('d.m.Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Öğretmen</p>
                        <p class="font-medium text-gray-800">{{ $homework->session->teacher->name ?? 'Belirtilmemiş' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Ders Tarihi</p>
                        <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($homework->session->start_date)->format('d.m.Y') }}</p>
                    </div>
                </div>
                
                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-1">Açıklama</p>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-800">{{ $homework->description }}</p>
                    </div>
                </div>
                
                @if($homework->file_path)
                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-1">Ödev Dosyası</p>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $homework->original_filename ?? 'Ödev Dosyası' }}</p>
                                <p class="text-xs text-gray-500">Öğretmen tarafından eklenmiş dosya</p>
                            </div>
                        </div>
                        <a href="{{ route('ogrenci.private-lessons.homework.download', $homework->id) }}" class="text-blue-600 hover:text-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Teslim Formu veya Teslim Bilgileri -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-800">{{ $submitted ? 'Teslim Bilgileri' : 'Ödev Teslimi' }}</h2>
            </div>
            <div class="p-6">
                @if($submitted)
                    <!-- Teslim Bilgileri -->
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Teslim Tarihi</p>
                            <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($submission->submission_date)->format('d.m.Y H:i') }}</p>
                            @if($submission->is_late)
                                <p class="text-xs text-red-600 mt-1">Geç teslim edildi</p>
                            @endif
                        </div>
                        
                        @if($submission->content)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Açıklamanız</p>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-gray-800">{{ $submission->content }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($submission->file_path)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Yüklenen Dosya</p>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-green-100 p-2 rounded-full mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $submission->original_filename ?? 'Ödev Tesliminiz' }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('ogrenci.private-lessons.submission.download', $submission->id) }}" class="text-green-600 hover:text-green-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @endif
                        
                        @if($submission->teacher_feedback)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Öğretmen Değerlendirmesi</p>
                            <div class="p-3 bg-blue-50 rounded-lg">
                                <p class="text-gray-800">{{ $submission->teacher_feedback }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if(!$isOverdue && $now < $dueDate->addDays(7))
                        <div class="mt-6 text-center">
                            <a href="#" onclick="toggleEditForm()" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Teslimimi Güncelle
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Gizli Düzenleme Formu -->
                    <div id="editForm" class="hidden mt-6 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Teslimimi Güncelle</h3>
                        <form action="{{ route('ogrenci.private-lessons.homework.submit', $homework->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Açıklama (İsteğe Bağlı)</label>
                                <textarea id="content" name="content" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Ödeviniz hakkında açıklama ekleyebilirsiniz">{{ $submission->content }}</textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Ödev Dosyası</label>
                                <input type="file" id="file" name="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <p class="text-xs text-gray-500 mt-1">Maksimum dosya boyutu: 10MB</p>
                            </div>
                            
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="toggleEditForm()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    İptal
                                </button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Güncelle
                                </button>
                            </div>
                        </form>
                    </div>
                @elseif(!$isOverdue)
                    <!-- Yeni Teslim Formu -->
                    <form action="{{ route('ogrenci.private-lessons.homework.submit', $homework->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Açıklama (İsteğe Bağlı)</label>
                            <textarea id="content" name="content" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Ödeviniz hakkında açıklama ekleyebilirsiniz"></textarea>
                        </div>
                        
                        <div class="mb-6">
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Ödev Dosyası</label>
                            <input type="file" id="file" name="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            <p class="text-xs text-gray-500 mt-1">Maksimum dosya boyutu: 10MB</p>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Ödevi Teslim Et
                            </button>
                        </div>
                    </form>
                @else
                    <!-- Süresi Geçmiş Uyarısı -->
                    <div class="text-center py-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-600">Bu ödevin son teslim tarihi geçmiştir.</p>
                        <p class="text-gray-500 mt-2">Öğretmeninizle iletişime geçerek ek süre isteyebilirsiniz.</p>
                        <div class="mt-6">
                            <a href="#" class="inline-block px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition">
                                Öğretmene Mesaj Gönder
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($submitted)
<script>
    function toggleEditForm() {
        const editForm = document.getElementById('editForm');
        if (editForm.classList.contains('hidden')) {
            editForm.classList.remove('hidden');
        } else {
            editForm.classList.add('hidden');
        }
    }
</script>
@endif
@endsection