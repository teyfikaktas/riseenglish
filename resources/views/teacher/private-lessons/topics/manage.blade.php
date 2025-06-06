@extends('layouts.app')

@section('content')
<div data-session-id="{{ $session->id }}" style="display: none;"></div>

<div class="bg-gray-800 rounded-xl shadow-lg p-4 md:p-5 border border-gray-600 max-w-5xl mx-auto">
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-indigo-300">İşlenen Konular</h1>
        <a href="{{ route('ogretmen.private-lessons.session.show', $session->id) }}" class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all duration-200 flex items-center text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
            </svg>
            Ders Detayına Dön
        </a>
    </div>
    
    <!-- Başlık Bilgisi -->
    <div class="border-b border-gray-600 pb-3 mb-4">
        <h4 class="text-xl font-bold text-white">{{ $session->privateLesson ? $session->privateLesson->name : 'Ders' }}</h4>
        <p class="text-sm text-gray-300 mt-1">
            <span class="font-medium">Öğrenci:</span> {{ $session->student ? $session->student->name : 'Öğrenci Atanmamış' }} | 
            <span class="font-medium">Tarih:</span> {{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }} | 
            <span class="font-medium">Saat:</span> {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
        </p>
    </div>
    
    <!-- Açıklama -->
    <div class="mb-4 p-3 bg-blue-900 rounded-lg border border-blue-700 text-blue-200">
        <p class="text-sm">
            <strong>Not:</strong> Bu sayfada ders sırasında işlenen konuları işaretleyebilirsiniz. Aynı konu birden çok kez işlendiğinde, konunun yanında görünen tik sayısı artar.
            İşlenen her konu öğrencinin ilerlemesinde kaydedilir. Bir konu işlendikten sonra en son işlenen konuyu çıkarmak için "Kaldır" butonunu kullanabilirsiniz.
        </p>
    </div>

    <!-- Topic Categories -->
    <div class="space-y-4">
        @foreach($categories as $category)
        <div class="border border-gray-600 rounded-lg overflow-hidden">
            <div class="bg-gray-700 px-4 py-2 font-medium text-white border-b border-gray-600">
                {{ $category->name }}
            </div>
            <div class="p-4 bg-gray-750">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($category->topics as $topic)
                    <div class="bg-gray-800 rounded-lg p-3 flex justify-between items-center border border-gray-600">
                        <div>
                            <div class="flex items-center">
                                <h5 class="font-medium text-gray-200">{{ $topic->name }}</h5>
                                <span class="ml-2 text-xs px-2 py-0.5 bg-blue-900 text-blue-200 rounded-full">{{ $topic->level }}</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $topic->description }}</p>
                            
                            <!-- Ticks for number of times covered -->
                            @if(isset($topicCounts[$topic->id]) && $topicCounts[$topic->id] > 0)
                            <div class="mt-1 flex items-center">
                                @for($i = 0; $i < $topicCounts[$topic->id]; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-400 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                @endfor
                                <span class="text-xs text-gray-400">({{ $topicCounts[$topic->id] }} kez işlendi)</span>
                            </div>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <!-- Add Topic Button -->
                            <button onclick="openTopicNoteModal({{ $topic->id }}, '{{ addslashes($topic->name) }}')" 
                                class="px-2 py-1 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-all duration-200 text-xs flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span>Ekle</span>
                            </button>
                            
                            <!-- Hızlı Ekle - AJAX ile -->
                            <button onclick="quickAddTopicAjax({{ $topic->id }}, '{{ addslashes($topic->name) }}')" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded-lg text-xs flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                                Hızlı
                            </button>
                            
                            <!-- Remove Topic Button (only if topic is covered at least once) -->
                            @if(isset($topicCounts[$topic->id]) && $topicCounts[$topic->id] > 0)
                            <form action="{{ route('ogretmen.private-lessons.session.topics.remove', $session->id) }}" method="POST" onsubmit="return confirm('Bu konuyu çıkarmak istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="topic_id" value="{{ $topic->id }}">
                                <button type="submit" class="px-2 py-1 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all duration-200 text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Dönüş Butonu -->
    <div class="flex justify-end mt-4">
        <a href="{{ route('ogretmen.private-lessons.session.show', $session->id) }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all duration-200 text-sm">
            Ders Detayına Dön
        </a>
    </div>
</div>

<!-- Topic Note Modal -->
<div id="topicNoteModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    
    <div class="bg-gray-800 rounded-lg shadow-xl z-10 w-full max-w-md mx-4">
        <div class="flex justify-between items-center border-b border-gray-600 px-4 py-3">
            <h3 class="text-lg font-semibold text-white" id="modalTopicTitle">Konu Notu Ekle</h3>
            <button type="button" onclick="closeTopicNoteModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="topicNoteForm" action="{{ route('ogretmen.private-lessons.session.topics.add', $session->id) }}" method="POST">
            @csrf
            <input type="hidden" name="topic_id" id="modalTopicId" value="">
            
            <div class="px-4 py-3">
                <label for="notes" class="block text-sm font-medium text-gray-200 mb-1">İşlenen Konu Hakkında Not</label>
                <textarea id="notes" name="notes" rows="4"
                          class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white"
                          placeholder="Öğrencinin bu konudaki performansı, dikkat edilmesi gereken noktalar, vb."></textarea>
                <p class="text-xs text-gray-400 mt-1">Bu not sadece sizin tarafınızdan görülebilir.</p>
            </div>
            
            <div class="flex justify-end px-4 py-3 bg-gray-700 rounded-b-lg">
                <button type="button" onclick="closeTopicNoteModal()" 
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition-all duration-200 text-sm mr-2">
                    İptal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 text-sm">
                    Ekle
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let selectedTopicId = null;

// Mevcut not modalı açma fonksiyonu
function openTopicNoteModal(topicId, topicName) {
    selectedTopicId = topicId;
    const modalTopicId = document.getElementById('modalTopicId');
    const modalTopicTitle = document.getElementById('modalTopicTitle');
    const notesField = document.getElementById('notes');
    const modal = document.getElementById('topicNoteModal');
    
    if (modalTopicId) modalTopicId.value = topicId;
    if (modalTopicTitle) modalTopicTitle.innerText = topicName + ' - Not Ekle';
    if (notesField) notesField.value = '';
    if (modal) modal.classList.remove('hidden');
}

// Not modalını kapatma
function closeTopicNoteModal() {
    const modal = document.getElementById('topicNoteModal');
    const notesField = document.getElementById('notes');
    
    if (modal) modal.classList.add('hidden');
    if (notesField) notesField.value = '';
    selectedTopicId = null;
}

// Basit AJAX hızlı ekleme
function quickAddTopicAjax(topicId, topicName) {
    const sessionId = {{ $session->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Butonu disable et
    event.target.disabled = true;
    const originalHtml = event.target.innerHTML;
    event.target.innerHTML = '<svg class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    fetch(`/ogretmen/private-lessons/session/${sessionId}/topics/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': csrfToken
        },
        body: `topic_id=${topicId}&notes=`
    })
    .then(response => {
        if (response.ok) {
            // Başarılı - scroll pozisyonunu koru
            const scrollPos = window.pageYOffset;
            sessionStorage.setItem('scrollPosition', scrollPos);
            location.reload();
        } else {
            throw new Error('İstek başarısız');
        }
    })
    .catch(error => {
        console.error('Hata:', error);
        alert('Konu eklenirken hata oluştu');
        // Butonu tekrar aktif et
        event.target.disabled = false;
        event.target.innerHTML = originalHtml;
    });
}

// DOM yüklendiğinde çalışacak kodlar
document.addEventListener('DOMContentLoaded', function() {
    // Scroll pozisyonunu geri getir
    const scrollPos = sessionStorage.getItem('scrollPosition');
    if (scrollPos) {
        window.scrollTo(0, parseInt(scrollPos));
        sessionStorage.removeItem('scrollPosition');
    }
    
    // ESC tuşu ile modal kapatma
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeTopicNoteModal();
        }
    });

    // Modal dışına tıklayınca kapatma
    const modal = document.getElementById('topicNoteModal');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeTopicNoteModal();
            }
        });
    }
});
</script>
@endsection