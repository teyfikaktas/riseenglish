@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#e63946]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Sınavlarım
                    </h1>
                    <p class="text-gray-600 mt-1">Oluşturduğunuz sınavları görüntüleyin ve yönetin</p>
                </div>
                <a href="{{ route('exams.create') }}" 
                   class="inline-flex items-center gap-2 bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-3 px-6 rounded-lg shadow-lg transition transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Yeni Sınav Oluştur
                </a>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
            <form method="GET" action="{{ route('exams.index') }}" class="flex gap-3">
                <div class="flex-1 relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Sınav adı, açıklama veya tarih ile ara..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#e63946] focus:border-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit" class="bg-[#e63946] hover:bg-[#d62836] text-white font-semibold px-6 py-3 rounded-lg transition shadow-md">
                    Ara
                </button>
                @if(request('search'))
                    <a href="{{ route('exams.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-3 rounded-lg transition shadow-md">
                        Temizle
                    </a>
                @endif
            </form>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <div class="flex items-center">
                    <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <div class="flex items-center">
                    <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="font-semibold">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Search Results Info -->
        @if(request('search'))
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-lg shadow-md">
                <p class="font-semibold">
                    "{{ request('search') }}" için {{ $exams->total() }} sonuç bulundu.
                </p>
            </div>
        @endif

        <!-- Sınavlar Listesi -->
        @if($exams->count() > 0)
            <div class="grid gap-6">
                @foreach($exams as $exam)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 overflow-hidden border border-gray-200">
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <!-- Sınav Bilgileri -->
                                <div class="flex-1">
                                    <div class="flex items-start gap-4">
                                        <!-- Icon -->
                                        <div class="flex-shrink-0">
                                            <div class="w-16 h-16 bg-gradient-to-br from-[#e63946] to-[#d62836] rounded-xl flex items-center justify-center shadow-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Başlık ve Detaylar -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-2">
                                                <h3 class="text-xl font-bold text-gray-800 truncate">{{ $exam->name }}</h3>
                                                @if($exam->is_active)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                        </svg>
                                                        Pasif
                                                    </span>
                                                @endif
                                            </div>

                                            @if($exam->description)
                                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $exam->description }}</p>
                                            @endif

                                            <!-- Detay Bilgileri -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                                                <!-- Tarih -->
                                                <div class="flex items-center gap-2 text-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#e63946]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="text-gray-700">
                                                        {{ \Carbon\Carbon::parse($exam->start_time)->locale('tr')->isoFormat('D MMMM YYYY') }}
                                                    </span>
                                                </div>

                                                <!-- Saat -->
                                                <div class="flex items-center gap-2 text-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#e63946]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="text-gray-700">
                                                        {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }}
                                                    </span>
                                                </div>

                                                <!-- Öğrenci Sayısı -->
                                                <div class="flex items-center gap-2 text-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#e63946]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                    </svg>
                                                    <span class="text-gray-700">
                                                        {{ $exam->students_count }} Öğrenci
                                                    </span>
                                                </div>

                                                <!-- Kelime Seti Sayısı -->
                                                <div class="flex items-center gap-2 text-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#e63946]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                    <span class="text-gray-700">
                                                        {{ $exam->wordSets->count() }} Set
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Aksiyon Butonları -->
                                <div class="flex flex-col gap-2 lg:flex-row lg:items-center">
                                    <!-- Detay -->
    <a href="{{ route('exams.report', $exam) }}" 
       class="inline-flex items-center justify-center gap-2 bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-4 rounded-lg transition shadow-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
        </svg>
        Rapor İndir
    </a>

                                    <!-- Sil -->
                                    <button onclick="deleteExam({{ $exam->id }}, '{{ $exam->name }}')"
                                            class="inline-flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Sil
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $exams->appends(['search' => request('search')])->links() }}
            </div>

        @else
            <!-- Boş Durum -->
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    @if(request('search'))
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">Sonuç Bulunamadı</h3>
                        <p class="text-gray-600 mb-6">"{{ request('search') }}" için hiçbir sınav bulunamadı.</p>
                        <a href="{{ route('exams.index') }}" 
                           class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition">
                            Tüm Sınavları Göster
                        </a>
                    @else
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">Henüz Sınav Oluşturmadınız</h3>
                        <p class="text-gray-600 mb-6">Öğrencileriniz için ilk sınavınızı oluşturarak başlayın.</p>
                        <a href="{{ route('exams.create') }}" 
                           class="inline-flex items-center gap-2 bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-3 px-6 rounded-lg shadow-lg transition transform hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            İlk Sınavımı Oluştur
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Sınavı Sil</h3>
            <p class="text-gray-600 mb-6">
                <span id="examNameToDelete" class="font-semibold"></span> adlı sınavı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.
            </p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                    İptal
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Evet, Sil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteExam(examId, examName) {
    document.getElementById('examNameToDelete').textContent = examName;
    document.getElementById('deleteForm').action = `/exams/${examId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection