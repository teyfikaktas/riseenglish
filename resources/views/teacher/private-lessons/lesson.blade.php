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
<div class="bg-gray-800 rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-white">İşlenen Konular Özeti</h2>
        <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-300">Toplam İşlenen Konu: <span id="totalTopicsCount" class="font-semibold text-white">0</span></span>
            
            <!-- İşlenmemiş Konuları Göster/Gizle Butonu -->
            <button type="button" 
                    id="toggleUnprocessedTopics" 
                    class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-1 rounded text-sm">
                İşlenmemiş Konuları Göster
            </button>
        </div>
    </div>
    
    @php
        // [PHP kodu aynı kalıyor]
        // Tüm session ID'lerini alıyoruz
        $sessionIds = $sessions->pluck('id')->toArray();
        
        // Bu dersin tüm seanslarında işlenen konuları getiriyoruz
        $sessionTopics = \App\Models\SessionTopic::with(['topic.category', 'session'])
            ->whereIn('session_id', $sessionIds)
            ->get();
        
        // İşlenen konuların ID'lerini topla
        $processedTopicIds = $sessionTopics->pluck('topic_id')->unique()->toArray();
        
        // Tüm konuları kategorileriyle birlikte al
        $allTopics = \App\Models\Topic::with('category')
            ->where('is_active', true)
            ->orderBy('topic_category_id')
            ->orderBy('order')
            ->get();
        
        // İşlenmemiş konuları ayır
        $unprocessedTopics = $allTopics->filter(function($topic) use ($processedTopicIds) {
            return !in_array($topic->id, $processedTopicIds);
        });
        
        // İşlenmemiş konuları kategorilere göre grupla
        $unprocessedByCategory = [];
        foreach ($unprocessedTopics as $topic) {
            $categoryId = $topic->category->id;
            if (!isset($unprocessedByCategory[$categoryId])) {
                $unprocessedByCategory[$categoryId] = [
                    'name' => $topic->category->name,
                    'topics' => []
                ];
            }
            $unprocessedByCategory[$categoryId]['topics'][] = $topic;
        }
        
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
            
            // İşlenmemiş konuları göster/gizle
            const toggleButton = document.getElementById('toggleUnprocessedTopics');
            const unprocessedSection = document.getElementById('unprocessedTopicsSection');
            
            if (toggleButton && unprocessedSection) {
                toggleButton.addEventListener('click', function() {
                    if (unprocessedSection.classList.contains('hidden')) {
                        unprocessedSection.classList.remove('hidden');
                        toggleButton.textContent = 'İşlenmemiş Konuları Gizle';
                        toggleButton.classList.remove('bg-gray-600', 'hover:bg-gray-500');
                        toggleButton.classList.add('bg-red-600', 'hover:bg-red-500');
                    } else {
                        unprocessedSection.classList.add('hidden');
                        toggleButton.textContent = 'İşlenmemiş Konuları Göster';
                        toggleButton.classList.remove('bg-red-600', 'hover:bg-red-500');
                        toggleButton.classList.add('bg-gray-600', 'hover:bg-gray-500');
                    }
                });
            }
        });
    </script>
    
    <!-- İşlenen Konular -->
    @if(count($sessionTopics) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6" id="processedTopicsGrid">
            @foreach($topicsByCategory as $categoryId => $category)
                <div class="border border-gray-600 rounded-lg overflow-hidden">
                    <div class="bg-gray-700 px-4 py-2 font-medium text-white category-header">
                        {{ $category['name'] }}
                    </div>
                    <div class="p-4 bg-gray-750">
                        <ul class="space-y-3">
                            @foreach($category['topics'] as $topic)
                                <li class="flex items-start border-b border-gray-600 pb-2 topic-item">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="flex items-center">
                                            @for($i = 0; $i < $topicCounts[$topic->id]; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-400 check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="ml-2">
                                        <div class="flex items-center">
                                            <p class="text-sm font-medium text-gray-200 topic-name">{{ $topic->name }}</p>
                                            <span class="ml-2 text-xs px-2 py-0.5 bg-blue-900 text-blue-200 rounded-full level-badge">{{ $topic->level }}</span>
                                            <span class="ml-2 text-xs text-gray-400 count-badge">({{ $topicCounts[$topic->id] }} kez)</span>
                                        </div>
                                        @if($topic->description)
                                            <p class="text-xs text-gray-400 mt-0.5 topic-description">{{ $topic->description }}</p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-yellow-900 border border-yellow-700 text-yellow-200 p-4 rounded-lg mb-6">
            <p>Bu derste henüz işlenen konu kaydedilmemiş.</p>
        </div>
    @endif
    
    <!-- İşlenmemiş Konular (Varsayılan olarak gizli) -->
    <div id="unprocessedTopicsSection" class="hidden">
        <h3 class="text-lg font-semibold mb-4 text-red-400">İşlenmemiş Konular</h3>
        @if(count($unprocessedByCategory) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6" id="unprocessedTopicsGrid">
                @foreach($unprocessedByCategory as $categoryId => $category)
                    <div class="border border-red-800 rounded-lg overflow-hidden">
                        <div class="bg-red-900 px-4 py-2 font-medium text-red-200 category-header">
                            {{ $category['name'] }}
                        </div>
                        <div class="p-4 bg-gray-750">
                            <ul class="space-y-3">
                                @foreach($category['topics'] as $topic)
                                    <li class="flex items-start border-b border-red-900 pb-2 topic-item">
                                        <div class="flex-shrink-0 mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-400 x-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                        <div class="ml-2">
                                            <div class="flex items-center">
                                                <p class="text-sm font-medium text-gray-200 topic-name">{{ $topic->name }}</p>
                                                <span class="ml-2 text-xs px-2 py-0.5 bg-red-900 text-red-200 rounded-full level-badge">{{ $topic->level }}</span>
                                            </div>
                                            @if($topic->description)
                                                <p class="text-xs text-gray-400 mt-0.5 topic-description">{{ $topic->description }}</p>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-green-900 border border-green-700 text-green-200 p-4 rounded-lg mb-6">
                <p>Tebrikler! Tüm konular işlenmiş.</p>
            </div>
        @endif
    </div>
    
    <!-- Yazdır Butonu -->
    <div class="flex justify-center mt-4">
        <button type="button" id="printTopicsBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
            </svg>
            Konu Raporunu Yazdır
        </button>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('printTopicsBtn').addEventListener('click', function() {
                const processedTopicsGrid = document.getElementById('processedTopicsGrid');
                const unprocessedSection = document.getElementById('unprocessedTopicsSection');
                const isUnprocessedVisible = !unprocessedSection.classList.contains('hidden');
                
                const student = "{{ $student ? $student->name : 'Öğrenci' }}";
                const lesson = "{{ $lesson->name }}";
                
                const printWindow = window.open('', '_blank');
                
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                        <head>
                            <title>${lesson} - Konu Raporu</title>
                            <style>
                                body {
                                    font-family: Arial, sans-serif;
                                    padding: 20px;
                                    color: #000;
                                    background: #fff;
                                }
                                .header {
                                    text-align: center;
                                    margin-bottom: 30px;
                                    border-bottom: 2px solid #333;
                                    padding-bottom: 10px;
                                }
                                .header h1 {
                                    margin: 0;
                                    font-size: 24px;
                                    color: #333;
                                }
                                .header p {
                                    margin: 5px 0;
                                    color: #666;
                                }
                                .section-title {
                                    font-size: 20px;
                                    margin: 20px 0 10px 0;
                                    color: #333;
                                    border-bottom: 1px solid #ddd;
                                    padding-bottom: 5px;
                                }
                                .grid {
                                    display: flex;
                                    flex-wrap: wrap;
                                    gap: 20px;
                                }
                                .category {
                                    flex: 1;
                                    min-width: 300px;
                                    border: 1px solid #ddd;
                                    border-radius: 8px;
                                    margin-bottom: 15px;
                                }
                                .category-header {
                                    background: #f5f5f5;
                                    padding: 10px 15px;
                                    font-weight: bold;
                                    border-bottom: 1px solid #ddd;
                                    border-radius: 8px 8px 0 0;
                                }
                                .category-content {
                                    padding: 15px;
                                }
                                .topic-item {
                                    display: flex;
                                    align-items: flex-start;
                                    padding: 8px 0;
                                    border-bottom: 1px solid #eee;
                                }
                                .topic-item:last-child {
                                    border-bottom: none;
                                }
                                .check-icons {
                                    display: flex;
                                    margin-right: 10px;
                                    margin-top: 2px;
                                }
                                .check-icon {
                                    color: #22c55e;
                                    margin-right: 2px;
                                }
                                .x-icon {
                                    color: #ef4444;
                                    margin-right: 10px;
                                }
                                .topic-info {
                                    flex: 1;
                                }
                                .topic-title {
                                    display: flex;
                                    align-items: center;
                                    gap: 8px;
                                }
                                .topic-name {
                                    font-weight: 500;
                                }
                                .level-badge {
                                    background: #e5e7eb;
                                    padding: 2px 8px;
                                    border-radius: 12px;
                                    font-size: 11px;
                                    color: #374151;
                                }
                                .count-badge {
                                    color: #6b7280;
                                    font-size: 11px;
                                }
                                .topic-description {
                                    color: #6b7280;
                                    font-size: 12px;
                                    margin-top: 4px;
                                }
                                .unprocessed-section .section-title {
                                    color: #dc2626;
                                }
                                .unprocessed-section .category-header {
                                    background: #fee2e2;
                                    color: #dc2626;
                                }
                                .unprocessed-section .level-badge {
                                    background: #fee2e2;
                                    color: #dc2626;
                                }
                                @media print {
                                    body { padding: 10px; }
                                    .category { break-inside: avoid; }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="header">
                                <h1>${lesson}</h1>
                                <p>Öğrenci: ${student}</p>
                                <p>Tarih: ${new Date().toLocaleDateString('tr-TR')}</p>
                            </div>
                            
                            <div class="section-title">İşlenen Konular</div>
                            <div class="grid">
                                ${Array.from(processedTopicsGrid.children).map(category => {
                                    const categoryName = category.querySelector('.category-header').textContent.trim();
                                    const topics = Array.from(category.querySelectorAll('.topic-item'));
                                    
                                    return `
                                        <div class="category">
                                            <div class="category-header">${categoryName}</div>
                                            <div class="category-content">
                                                ${topics.map(topic => {
                                                    const checkIcons = topic.querySelectorAll('.check-icon').length;
                                                    const topicName = topic.querySelector('.topic-name').textContent.trim();
                                                    const level = topic.querySelector('.level-badge').textContent.trim();
                                                    const count = topic.querySelector('.count-badge').textContent.trim();
                                                    const description = topic.querySelector('.topic-description')?.textContent.trim() || '';
                                                    
                                                    return `
                                                        <div class="topic-item">
                                                            <div class="check-icons">
                                                                ${'<span class="check-icon">✓</span>'.repeat(checkIcons)}
                                                            </div>
                                                            <div class="topic-info">
                                                                <div class="topic-title">
                                                                    <span class="topic-name">${topicName}</span>
                                                                    <span class="level-badge">${level}</span>
                                                                    <span class="count-badge">${count}</span>
                                                                </div>
                                                                ${description ? `<div class="topic-description">${description}</div>` : ''}
                                                            </div>
                                                        </div>
                                                    `;
                                                }).join('')}
                                            </div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                            
                            ${isUnprocessedVisible ? `
                                <div class="unprocessed-section">
                                    <div class="section-title">İşlenmemiş Konular</div>
                                    <div class="grid">
                                        ${Array.from(document.getElementById('unprocessedTopicsGrid').children).map(category => {
                                            const categoryName = category.querySelector('.category-header').textContent.trim();
                                            const topics = Array.from(category.querySelectorAll('.topic-item'));
                                            
                                            return `
                                                <div class="category">
                                                    <div class="category-header">${categoryName}</div>
                                                    <div class="category-content">
                                                        ${topics.map(topic => {
                                                            const topicName = topic.querySelector('.topic-name').textContent.trim();
                                                            const level = topic.querySelector('.level-badge').textContent.trim();
                                                            const description = topic.querySelector('.topic-description')?.textContent.trim() || '';
                                                            
                                                            return `
                                                                <div class="topic-item">
                                                                    <span class="x-icon">✗</span>
                                                                    <div class="topic-info">
                                                                        <div class="topic-title">
                                                                            <span class="topic-name">${topicName}</span>
                                                                            <span class="level-badge">${level}</span>
                                                                        </div>
                                                                        ${description ? `<div class="topic-description">${description}</div>` : ''}
                                                                    </div>
                                                                </div>
                                                            `;
                                                        }).join('')}
                                                    </div>
                                                </div>
                                            `;
                                        }).join('')}
                                    </div>
                                </div>
                            ` : ''}
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