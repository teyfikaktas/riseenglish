@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Geri Butonu ve Başlık -->
    <div class="flex items-center mb-6">
        <a href="{{ route('ogrenci.private-lessons.index') }}" class="mr-4 text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Ödevlerim</h1>
            <p class="text-gray-600">Tüm derslere ait ödevleriniz ve teslim durumları</p>
        </div>
    </div>

    <!-- Filtre ve Arama -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="{{ route('ogrenci.private-lessons.homeworks') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Arama</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Ödev adı ara..." class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="w-full md:w-1/4">
                <label for="lesson_id" class="block text-sm font-medium text-gray-700 mb-1">Ders</label>
                <select name="lesson_id" id="lesson_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tüm Dersler</option>
                    @foreach($lessonList ?? [] as $id => $name)
                        <option value="{{ $id }}" {{ request('lesson_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-1/4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Durum</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tüm Durumlar</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Teslim Edilenler</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Teslim Edilmeyenler</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Süresi Geçenler</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Filtrele
                </button>
            </div>
        </form>
    </div>

    <!-- Ödev Listesi -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="border-b px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-800">Tüm Ödevler</h2>
        </div>
        <div class="p-6">
            @if($homeworks->count() > 0)
                <div class="space-y-6">
                    @foreach($homeworks as $homework)
                        @php
                            // Son teslim tarihi geçmiş mi?
                            $dueDate = \Carbon\Carbon::parse($homework->due_date);
                            $now = \Carbon\Carbon::now();
                            $isOverdue = $now->isAfter($dueDate);
                            
                            // Öğrenci teslim etmiş mi?
                            $submission = $homework->submissions->first();
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
                            
                            // Eğer notlandırıldıysa
                            if ($submitted && $submission->score !== null) {
                                $scoreText = $submission->score . ' / 100';
                            } else {
                                $scoreText = 'Henüz değerlendirilmedi';
                            }
                        @endphp
                        
                        <div class="border rounded-lg overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $homework->title }}</h3>
                                    <p class="text-sm text-gray-600">{{ $homework->session->privateLesson->name ?? 'Belirtilmemiş' }}</p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                    @if($submitted && $submission->score !== null)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                            {{ $scoreText }}
                                        </span>
                                    @endif
                                </div>
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
                                    <p class="text-gray-800">{{ \Illuminate\Support\Str::limit($homework->description, 200) }}</p>
                                </div>
                                
                                <div class="flex justify-end space-x-3">
                                    @if($homework->file_path)
                                    <a href="{{ route('ogrenci.private-lessons.homework.download', $homework->id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Dosyayı İndir
                                    </a>
                                    @endif
                                    
                                    <a href="{{ route('ogrenci.private-lessons.homework', $homework->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-sm">
                                        {{ $submitted ? 'Teslimimi Görüntüle' : 'Ödev Teslim Et' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $homeworks->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-600 text-lg font-medium">Ödev bulunamadı.</p>
                    <p class="text-gray-500 mt-2">Filtreleri değiştirerek tekrar aramayı deneyebilirsiniz.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection