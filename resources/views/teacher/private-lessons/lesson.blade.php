@extends('layouts.app')

@section('content')
    @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ $lesson->name }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('ogretmen.private-lessons.showAddSession', $lesson->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    <i class="fas fa-plus mr-1"></i> Yeni Seans Ekle
                </a>
                <a href="{{ route('ogretmen.private-lessons.editLesson', $lesson->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    <i class="fas fa-edit mr-1"></i> Düzenle
                </a>
                <a href="{{ route('ogretmen.private-lessons.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    <i class="fas fa-arrow-left mr-1"></i> Geri
                </a>
            </div>
        </div>

        <!-- Ders Bilgileri -->
        <div class="bg-white rounded-lg shadow-md mb-6 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-xl font-semibold mb-4">Ders Bilgileri</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">Öğrenci:</span> {{ $student ? $student->name : 'Öğrenci Atanmamış' }}</p>
                        <p><span class="font-medium">Ücret:</span> {{ number_format($lesson->price, 2) }} TL</p>
                        <p><span class="font-medium">Aktif:</span> {{ $lesson->is_active ? 'Evet' : 'Hayır' }}</p>
                        <p><span class="font-medium">Oluşturulma:</span> {{ $lesson->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                </div>
                <div>
                    <h2 class="text-xl font-semibold mb-4">Özet Bilgiler</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">Toplam Seans:</span> {{ $sessions->count() }}</p>
                        <p><span class="font-medium">Tamamlanan Seans:</span> {{ $sessions->where('status', 'completed')->count() }}</p>
                        <p><span class="font-medium">Kalan Seans:</span> {{ $sessions->whereIn('status', ['approved', 'active', 'scheduled'])->count() }}</p>
                        <p><span class="font-medium">İptal Edilen:</span> {{ $sessions->where('status', 'cancelled')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Derste İşlenen Konular Özeti -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">İşlenen Konular Özeti</h2>
                <div>
                    <span class="text-sm text-gray-500">Toplam İşlenen Konu: <span id="totalTopicsCount" class="font-semibold">0</span></span>
                </div>
            </div>
            
            @php
                // Tüm session ID'lerini alıyoruz
                $sessionIds = $sessions->pluck('id')->toArray();
                
                // Bu dersin tüm seanslarında işlenen konuları getiriyoruz
                $sessionTopics = \App\Models\SessionTopic::with(['topic.category', 'session'])
                    ->whereIn('session_id', $sessionIds)
                    ->get();
                
                // Konuları kategorilere göre gruplandırıyoruz
                $topicCounts = [];
                $topicsByCategory = [];
                $totalTopicsCount = 0;
                
                foreach ($sessionTopics as $sessionTopic) {
                    $totalTopicsCount++;
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
                        
                        if (!in_array($sessionTopic->topic, $topicsByCategory[$categoryId]['topics'])) {
                            $topicsByCategory[$categoryId]['topics'][] = $sessionTopic->topic;
                        }
                    }
                    
                    $topicCounts[$topicId]++;
                }
            @endphp
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('totalTopicsCount').textContent = '{{ $totalTopicsCount }}';
                });
            </script>
            
            @if(count($sessionTopics) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($topicsByCategory as $categoryId => $category)
                        <div class="border rounded-lg overflow-hidden">
                            <div class="bg-gray-100 px-4 py-2 font-medium">
                                {{ $category['name'] }}
                            </div>
                            <div class="p-4">
                                <ul class="space-y-3">
                                    @foreach($category['topics'] as $topic)
                                        <li class="flex items-start border-b border-gray-100 pb-2">
                                            <div class="flex-shrink-0 mt-1">
                                                <div class="flex items-center">
                                                    @for($i = 0; $i < $topicCounts[$topic->id]; $i++)
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <div class="flex items-center">
                                                    <p class="text-sm font-medium text-gray-800">{{ $topic->name }}</p>
                                                    <span class="ml-2 text-xs px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full">{{ $topic->level }}</span>
                                                    <span class="ml-2 text-xs text-gray-500">({{ $topicCounts[$topic->id] }} kez)</span>
                                                </div>
                                                @if($topic->description)
                                                    <p class="text-xs text-gray-600 mt-0.5">{{ $topic->description }}</p>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="flex justify-center mt-4">
                    <button type="button" id="printTopicsBtn" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                        </svg>
                        Konu Raporunu Yazdır
                    </button>
                </div>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('printTopicsBtn').addEventListener('click', function() {
                            const printContent = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.gap-4').outerHTML;
                            const student = "{{ $student ? $student->name : 'Öğrenci' }}";
                            const lesson = "{{ $lesson->name }}";
                            
                            const printWindow = window.open('', '_blank');
                            
                            printWindow.document.write(`
                                <html>
                                    <head>
                                        <title>${lesson} - İşlenen Konular Raporu</title>
                                        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
                                        <style>
                                            @media print {
                                                body { padding: 20px; }
                                                .break-inside-avoid { break-inside: avoid; }
                                            }
                                        </style>
                                    </head>
                                    <body class="bg-white">
                                        <div class="mb-6 text-center">
                                            <h1 class="text-2xl font-bold">${lesson}</h1>
                                            <p class="text-gray-600">Öğrenci: ${student}</p>
                                            <p class="text-gray-600">Tarih: ${new Date().toLocaleDateString('tr-TR')}</p>
                                        </div>
                                        <h2 class="text-xl font-semibold mb-4">İşlenen Konular Özeti</h2>
                                        ${printContent}
                                    </body>
                                </html>
                            `);
                            
                            printWindow.document.close();
                            setTimeout(() => {
                                printWindow.print();
                            }, 500);
                        });
                    });
                </script>
            @else
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-4 rounded-lg">
                    <p>Bu derste henüz işlenen konu kaydedilmemiş.</p>
                </div>
            @endif
        </div>

        <!-- Ders Seansları -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Seanslar</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saat</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ödeme</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sessions as $session)
                            @php
                                switch($session->status) {
                                    case 'pending':
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                        $statusText = 'Beklemede';
                                        break;
                                    case 'approved':
                                        $statusClass = 'bg-blue-100 text-blue-800';
                                        $statusText = 'Onaylandı';
                                        break;
                                    case 'active':
                                        $statusClass = 'bg-green-100 text-green-800';
                                        $statusText = 'Aktif';
                                        break;
                                    case 'completed':
                                        $statusClass = 'bg-purple-100 text-purple-800';
                                        $statusText = 'Tamamlandı';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'bg-red-100 text-red-800';
                                        $statusText = 'İptal Edildi';
                                        break;
                                    default:
                                        $statusClass = 'bg-gray-100 text-gray-800';
                                        $statusText = 'Bilinmiyor';
                                }
                                $paymentClass = $session->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                                $paymentText = $session->payment_status == 'paid' ? 'Ödenmiş' : 'Ödenmemiş';
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paymentClass }}">
                                        {{ $paymentText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2">
                                    <a href="{{ route('ogretmen.private-lessons.session.show', $session->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs flex items-center">
                                        <i class="fas fa-eye mr-1"></i> Detay
                                    </a>
                                    
                                    <a href="{{ route('ogretmen.private-lessons.session.topics', $session->id) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded text-xs flex items-center">
                                        <i class="fas fa-book mr-1"></i> Konular
                                    </a>
                                    
                                    <!-- Tek seansı sil -->
                                    <form action="{{ route('ogretmen.private-lessons.session.destroy', $session->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Bu seansı silmek istediğinize emin misiniz?');"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="delete_scope" value="this_only">
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs flex items-center">
                                            <i class="fas fa-trash mr-1"></i> Sil
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection