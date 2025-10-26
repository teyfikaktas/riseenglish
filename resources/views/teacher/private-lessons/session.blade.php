@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-4 md:p-5 border border-gray-100 max-w-5xl mx-auto">
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-indigo-800">Ders Detayları</h1>
        <a href="{{ route('ogretmen.private-lessons.index') }}" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 flex items-center text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
            </svg>
            Takvime Dön
        </a>
    </div>
    <!-- İşlenen Konular Bölümü -->
<div class="mt-5 border-t border-gray-100 pt-4">
<div class="flex justify-between items-center mb-2">
    <h3 class="text-lg font-semibold">İşlenen Konular</h3>
    <div class="flex gap-2">
        <a href="{{ route('ogretmen.private-lessons.session.topics', $session->id) }}"
           class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded text-sm">
            Konuları Düzenle
        </a>
        <a href="{{ route('ogretmen.private-lessons.edit', $session->id) }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Düzenle
        </a>
    </div>
</div>

    @php
        $sessionTopics = \App\Models\SessionTopic::with(['topic.category'])
            ->join('topics', 'session_topics.topic_id', '=', 'topics.id')
            ->where('session_topics.session_id', $session->id)
            ->orderBy('topics.name')
            ->select('session_topics.*')
            ->get();
            
        // Group by topic and count occurrences
        $topicCounts = [];
        $topicsByCategory = [];
        
        foreach ($sessionTopics as $sessionTopic) {
            $topicId = $sessionTopic->topic_id;
            $categoryId = $sessionTopic->topic->category->id;
            $categoryName = $sessionTopic->topic->category->name;
            
            if (!isset($topicCounts[$topicId])) {
                $topicCounts[$topicId] = 0;
                
                if (!isset($topicsByCategory[$categoryId])) {
                    $topicsByCategory[$categoryId] = [
                        'name' => $categoryName,
                        'topics' => []
                    ];
                }
                
                $topicsByCategory[$categoryId]['topics'][] = $sessionTopic->topic;
            }
            
            $topicCounts[$topicId]++;
        }
    @endphp

    @if(count($sessionTopics) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($topicsByCategory as $categoryId => $category)
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                    <h4 class="font-medium text-gray-700 border-b border-gray-200 pb-2 mb-2">{{ $category['name'] }}</h4>
                    <ul class="space-y-2">
                        @foreach($category['topics'] as $topic)
                            <li class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="flex">
                                        @for($i = 0; $i < $topicCounts[$topic->id]; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <div class="ml-2">
                                    <p class="text-sm font-medium text-gray-800">{{ $topic->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $topic->description }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-3 rounded">
            Bu derste henüz işlenen konu kaydedilmemiş.
        </div>
    @endif
</div>
    <!-- Başlık Bilgisi -->
    <div class="border-b border-gray-100 pb-3 mb-4">
        <h4 class="text-xl font-bold text-gray-800">{{ $session->privateLesson ? $session->privateLesson->name : 'Ders' }}</h4>
        <p class="text-sm text-gray-600 mt-1">{{ $session->title ?? $session->privateLesson->name ?? 'Özel Ders' }}</p>
    </div>
    
<!-- Temel Bilgiler - Daha kompakt grid -->
<!-- Temel Bilgiler - Daha kompakt grid -->
<div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">
        @php
        // Grup dersi mi kontrol et
        $isGroupLesson = $session->group_id !== null;
        
        // Grup dersiyse tüm session'ları al
        if ($isGroupLesson) {
            $groupSessions = $session->groupSessions()->with('student')->get();
        } else {
            $groupSessions = collect([$session]);
        }
    @endphp

    @if($isGroupLesson)
        <!-- Grup Dersi Badge -->
        <div class="mb-4">
            <span class="bg-purple-100 text-purple-700 px-3 py-1.5 rounded-lg text-sm font-semibold inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                GRUP DERSİ - {{ $groupSessions->count() }} Öğrenci
            </span>
        </div>
    @endif

    <!-- Her Öğrenci için Ayrı Kart -->
    @foreach($groupSessions as $index => $studentSession)
        <div class="mb-6 {{ $isGroupLesson ? 'border-2 border-purple-200 rounded-xl p-4 bg-purple-50/30' : '' }}">
            @if($isGroupLesson)
                <h3 class="text-lg font-semibold text-purple-800 mb-3 flex items-center">
                    <span class="bg-purple-600 text-white w-7 h-7 rounded-full flex items-center justify-center text-sm mr-2">
                        {{ $index + 1 }}
                    </span>
                    {{ $studentSession->student ? $studentSession->student->name : 'Öğrenci ' . ($index + 1) }}
                </h3>
            @endif

            <!-- Temel Bilgiler Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 mb-0.5">Öğrenci</p>
                    <p class="font-medium text-gray-800 text-sm">{{ $studentSession->student ? $studentSession->student->name : 'Öğrenci Atanmamış' }}</p>
                </div>
                
                <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 mb-0.5">Öğretmen</p>
                    <p class="font-medium text-gray-800 text-sm">{{ $studentSession->teacher ? $studentSession->teacher->name : 'Öğretmen Atanmamış' }}</p>
                </div>
                
                <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 mb-0.5">Tarih</p>
                    <p class="font-medium text-gray-800 text-sm">{{ Carbon\Carbon::parse($studentSession->start_date)->format('d.m.Y') }}</p>
                </div>

                <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 mb-0.5">Saat</p>
                    <p class="font-medium text-gray-800 text-sm">{{ Carbon\Carbon::parse($studentSession->start_time)->format('H:i') }} - {{ Carbon\Carbon::parse($studentSession->end_time)->format('H:i') }}</p>
                </div>

                <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 mb-0.5">Konum</p>
                    <p class="font-medium text-gray-800 text-sm">{{ $studentSession->location ?? 'Belirtilmemiş' }}</p>
                </div>

                <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 mb-0.5">Durum</p>
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
                            $badgeColor = $statusColors[$studentSession->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border {{ $badgeColor }}">
                            {{ $statuses[$studentSession->status] ?? $studentSession->status }}
                        </span>
                    </p>
                </div>

                <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 mb-0.5">Ücret</p>
                    <p class="font-medium text-gray-800 text-sm">₺{{ $studentSession->fee ?? ($studentSession->privateLesson ? $studentSession->privateLesson->price : 0) }}</p>
                </div>

                <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 mb-0.5">Ödeme Durumu</p>
                    <p>
                        @php
                            $paymentColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'paid' => 'bg-green-100 text-green-800 border-green-200',
                                'partially_paid' => 'bg-orange-100 text-orange-800 border-orange-200',
                                'refunded' => 'bg-gray-100 text-gray-800 border-gray-200',
                                'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                            ];
                            $paymentBadge = $paymentColors[$studentSession->payment_status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border {{ $paymentBadge }}">
                            {{ ucfirst($studentSession->payment_status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>
    <!-- In teacher.private-lessons.session.blade.php -->
    @if(count($sessionTopics) > 0)
    <!-- Topic Notes Section -->
    <div class="mt-4 border-t border-gray-100 pt-3">
        <h4 class="font-medium text-gray-700 mb-2">Konu Notları</h4>
        
        <div class="space-y-2">
            @php
                $topicNotes = $sessionTopics->filter(function($item) {
                    return !empty($item->notes);
                });
            @endphp
            
            @if($topicNotes->count() > 0)
                @foreach($topicNotes as $topicNote)
                    <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-100">
                        <div class="flex justify-between">
                            <h5 class="font-medium text-yellow-800">{{ $topicNote->topic->name }}</h5>
                            <span class="text-xs text-yellow-600">{{ $topicNote->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <p class="text-sm text-yellow-700 mt-1">{{ $topicNote->notes }}</p>
                    </div>
                @endforeach
            @else
                <p class="text-sm text-gray-500 italic">Bu derste işlenen konulara ait not bulunmamaktadır.</p>
            @endif
        </div>
    </div>
@endif
<div class="mt-4">
    <div class="flex justify-between items-center mb-2">
        <h3 class="text-lg font-semibold">Ödevler</h3>
        <a href="{{ route('ogretmen.private-lessons.homework.create', $session->id) }}"
           class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
            Ödev Ekle
        </a>
    </div>

    @php
        // 🔥 Grup dersi mi kontrol et
        $isGroupLesson = $session->group_id !== null;
        
        // 🔥 Grup dersiyse TÜM öğrencilerin session'larını al
        if ($isGroupLesson) {
            $groupSessions = $session->groupSessions()->with('student')->get();
            $sessionIds = $groupSessions->pluck('id')->toArray();
            
            // Tüm ödevleri al ve başlığa göre grupla
            $allHomeworks = \App\Models\PrivateLessonHomework::whereIn('session_id', $sessionIds)
                ->with(['session.student', 'submissions'])
                ->get();
            
            $groupedHomeworks = $allHomeworks->groupBy('title');
        } else {
            $groupedHomeworks = $session->homeworks->groupBy('title');
        }
    @endphp

    @if($groupedHomeworks->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Başlık
                        </th>
                        @if($isGroupLesson)
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Öğrenciler
                            </th>
                        @endif
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Teslim Tarihi
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Teslimler
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            İşlemler
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($groupedHomeworks as $title => $homeworkGroup)
                        @php
                            $firstHomework = $homeworkGroup->first();
                            
                            // Toplam teslim sayısı
                            $totalSubmissions = $homeworkGroup->sum(function($hw) {
                                return $hw->submissions->count();
                            });
                            
                            // Öğrenci sayısı
                            $studentCount = $homeworkGroup->count();
                        @endphp
                        <tr>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $title }}</div>
                                    @if($firstHomework->description)
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ Str::limit($firstHomework->description, 60) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            @if($isGroupLesson)
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($homeworkGroup as $hw)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ $hw->session->student ? $hw->session->student->name : 'Öğrenci' }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                            @endif
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($firstHomework->due_date)->format('d.m.Y') }}
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($totalSubmissions > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ✓ {{ $totalSubmissions }}
                                        @if($isGroupLesson)
                                            / {{ $studentCount }}
                                        @endif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Teslim Yok
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('ogretmen.private-lessons.homework.submissions', $firstHomework->id) }}"
                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                    Teslimleri Gör
                                </a>

                                @if($firstHomework->file_path)
                                    <a href="{{ route('ogretmen.private-lessons.homework.download', $firstHomework->id) }}"
                                       class="text-green-600 hover:text-green-900 mr-3">
                                        İndir
                                    </a>
                                @endif

                                <form class="inline"
                                      action="{{ route('ogretmen.private-lessons.homework.delete', $firstHomework->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Bu ödevi silmek istediğinize emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Sil
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-3 rounded">
            Bu derse henüz ödev eklenmemiş.
        </div>
    @endif
</div>
    
    @php
        $reportExists = \App\Models\PrivateLessonReport::where('session_id', $session->id)->exists();
    @endphp
    
    @if($reportExists)
        <a href="{{ route('ogretmen.private-lessons.session.showReport', $session->id) }}" 
           class="flex items-center text-blue-600 hover:text-blue-800 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Ders Raporunu Görüntüle
        </a>
    @else
        <a href="{{ route('ogretmen.private-lessons.session.createReport', $session->id) }}" 
           class="flex items-center text-green-600 hover:text-green-800 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Ders Raporu Oluştur
        </a>
    @endif
    <!-- Notlar Bölümü - Daha kompakt -->
    @if($session->notes)
    <div class="bg-gray-50 p-3 rounded-lg shadow-sm mb-4">
        <p class="text-xs text-gray-500 mb-1">Notlar</p>
        <div class="bg-white p-2 rounded-lg border border-gray-100">
            <p class="text-gray-800 text-sm">{{ $session->notes }}</p>
        </div>
    </div>
    @endif
    
    <!-- Aksiyon Kartları - Daha kompakt -->

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
            <!-- Ders Durumu Kartı -->
            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100">
                <div class="flex flex-col">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Ders Durumu</h5>
        
                    @if($isLessonCompleted)
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9
                                             10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1
                                             0 001.414 0l4-4z"
                                          clip-rule="evenodd" />
                                </svg>
                                Ders Tamamlandı
                            </span>
        
                            <form action="{{ route('ogretmen.private-lessons.undo-complete', $session->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Dersi tekrar tamamlanmamış hâle getirmek istediğinize emin misiniz?');">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-200 text-xs flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Tamamlamayı Geri Al
                                </button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('ogretmen.private-lessons.complete', $session->id) }}"
                              method="POST">
                            @csrf
                            <button type="submit"
                                    class="px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 shadow-sm flex items-center text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M5 13l4 4L19 7" />
                                </svg>
                                Dersi Tamamla
                            </button>
                            <p class="mt-1.5 text-xs text-gray-600">
                                Veliye ve öğrenciye SMS gönderilecektir.
                            </p>
                        </form>
                    @endif
                </div>
            </div>
        
            <!-- Ders Materyalleri Kartı -->
            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100">
                <div class="flex flex-col">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Ders Materyalleri</h5>
        
                    <a href="{{ route('ogretmen.private-lessons.material.create', $session->id) }}"
                       class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-sm flex items-center text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Materyal Ekle
                    </a>
                    <p class="mt-1.5 text-xs text-gray-600">Ders materyallerini yükleyebilirsiniz.</p>
        
                    @if($session->materials && $session->materials->count() > 0)
                        <div class="mt-3 border-t border-gray-100 pt-3">
                            <h6 class="text-xs font-medium text-gray-700 mb-2">Mevcut Materyaller</h6>
                            <ul class="space-y-2">
                                @foreach($session->materials as $material)
                                    <li class="bg-gray-50 p-2 rounded-lg border border-gray-100 flex justify-between items-center">
                                        <div class="flex items-center space-x-2">
                                            <div class="text-indigo-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                                             01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <a href="{{ route('ogretmen.private-lessons.material.download', $material->id) }}"
                                                   class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                                    {{ $material->title }}
                                                </a>
                                                @if($material->description)
                                                    <p class="text-xs text-gray-500 mt-0.5">
                                                        {{ Str::limit($material->description, 50) }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('ogretmen.private-lessons.material.download', $material->id) }}"
                                               class="text-gray-500 hover:text-gray-700 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4
                                                             4V4" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('ogretmen.private-lessons.material.delete', $material->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Bu materyali silmek istediğinizden emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2
                                                                 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                                                                 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Alt Butonlar -->
        <div class="flex justify-end space-x-3 mt-5 pt-3 border-t border-gray-100">
            <a href="{{ route('ogretmen.private-lessons.index') }}"
               class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-all duration-200 text-sm">
                Takvime Dön
            </a>
            <a href="{{ route('ogretmen.private-lessons.edit', $session->id) }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-sm text-sm">
                Düzenle
            </a>
        </div>

<!-- Bildirim Sistemi -->
<div id="notification-container" class="fixed top-4 right-4 z-50"></div>

<script>
    // Bildirim sistemi
    function showNotification(message, type = 'success') {
        const container = document.getElementById('notification-container');
        const notification = document.createElement('div');
        notification.className = 'flex items-center p-3 mb-2 rounded-lg shadow-lg transform transition-all duration-300 opacity-0 translate-x-full max-w-md';

        if (type === 'success') {
            notification.classList.add('bg-green-600', 'text-white');
            notification.innerHTML = `
                <div class="flex-shrink-0 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm">${message}</p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <button class="text-white focus:outline-none hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            `;
        } else {
            notification.classList.add('bg-red-600', 'text-white');
            notification.innerHTML = `
                <div class="flex-shrink-0 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm">${message}</p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <button class="text-white focus:outline-none hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
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

    // Session flash mesajlarını göster
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showNotification("{{ session('success') }}", 'success');
        @endif
        
        @if(session('error'))
            showNotification("{{ session('error') }}", 'error');
        @endif
    });
</script>
@endsection