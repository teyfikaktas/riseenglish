@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#1a2e5a]">Kendi Sınavını Oluştur</h1>
            <p class="text-gray-600 mt-2">Setlerinden seç, belirlediğin tarihler arasında her gün sınavın hazır olsun!</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg mb-6">
                <p class="font-semibold">✅ {{ session('success') }}</p>
                <p class="text-sm mt-1">Sınavlarınız belirlenen tarihlerde aktif olacaktır. Başarılar!</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('exams.self-store') }}" method="POST" class="space-y-6" id="selfExamForm">
            @csrf

            <!-- Set Seçimi -->
  <div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-xl font-bold text-[#1a2e5a] mb-4">Kelime Setini Seç</h2>

    @if($wordSets->count() > 0)
        <div class="space-y-3">
            @foreach($wordSets as $set)
                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#1a2e5a] cursor-pointer transition-all">
                    <input type="radio"
                           name="word_set"
                           value="{{ $set->id }}"
                           class="w-5 h-5 text-[#1a2e5a] focus:ring-[#1a2e5a]"
                           required>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded" style="background-color: {{ $set->color }}"></div>
                            <span class="font-semibold text-gray-900">{{ $set->name }}</span>
                            <span class="text-sm text-gray-500">({{ $set->words_count ?? $set->word_count }} kelime)</span>
                        </div>
                        @if($set->description)
                            <p class="text-sm text-gray-600 mt-1 ml-7">{{ $set->description }}</p>
                        @endif
                    </div>
                </label>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <p>Henüz kelime setin yok. Önce bir set oluştur!</p>
        </div>
    @endif
</div>

            <!-- Süre Seçimi -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-[#1a2e5a] mb-4">Soru Başı Süre</h2>
                <div class="flex items-center gap-4">
                    <input type="range"
                           name="time_per_question"
                           id="time_slider"
                           min="15" max="30" value="15" step="5"
                           class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-[#1a2e5a]">
                    <span id="time_display" class="text-2xl font-bold text-[#1a2e5a] min-w-[60px] text-center">15 sn</span>
                </div>
                <p class="text-xs text-gray-500 mt-2">Her soru için ayrılan süre (15-30 saniye)</p>
            </div>

            <!-- Tarih Aralığı -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-[#1a2e5a] mb-4">Tarih Aralığı</h2>
                <p class="text-sm text-gray-600 mb-4">Başlangıç ve bitiş tarihi seç, aradaki her gün sınav oluşturulacak. (En fazla 7 gün)</p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Başlangıç</label>
                        <input type="date"
                               name="start_date"
                               id="start_date"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bitiş</label>
                        <input type="date"
                               name="end_date"
                               id="end_date"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent"
                               required>
                    </div>
                </div>
<!-- Sınav Saati -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-xl font-bold text-[#1a2e5a] mb-4">Sınav Saati</h2>
    <p class="text-sm text-gray-600 mb-4">Sınavların her gün kaçta aktif olacağını seç.</p>
    <input type="time"
           name="exam_time"
           id="exam_time"
           value="09:00"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent text-lg"
           required>
</div>
                <!-- Tarih özeti -->
                <div id="date_summary" class="hidden mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800" id="date_summary_text"></p>
                </div>

                <!-- Hata mesajı -->
                <div id="date_error" class="hidden mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-700">En fazla 7 günlük aralık seçebilirsiniz!</p>
                </div>
            </div>

            <!-- Bilgi Notu -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <p class="text-sm text-blue-800">
                    <span class="font-bold">ℹ️ Nasıl çalışır?</span><br>
                    Seçtiğin setlerden, belirlediğin tarih aralığında her gün bir sınav oluşturulur.
                    Sınavlarına "Sınavlarım" sayfasından erişebilirsin.
                </p>
            </div>

            <!-- Buton -->
            <div class="flex justify-end">
                <button type="submit"
                        id="submitBtn"
                        class="bg-[#e63946] hover:bg-red-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                    Sınavlarımı Oluştur
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Süre slider
document.getElementById('time_slider')?.addEventListener('input', function() {
    document.getElementById('time_display').textContent = this.value + ' sn';
});

const startInput = document.getElementById('start_date');
const endInput   = document.getElementById('end_date');
const summary    = document.getElementById('date_summary');
const summaryTxt = document.getElementById('date_summary_text');
const dateError  = document.getElementById('date_error');
const submitBtn  = document.getElementById('submitBtn');

function checkDates() {
    const start = startInput.value;
    const end   = endInput.value;

    summary.classList.add('hidden');
    dateError.classList.add('hidden');
    submitBtn.disabled = false;
    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');

    if (!start || !end) return;

    const s = new Date(start);
    const e = new Date(end);

    if (e < s) {
        endInput.value = start;
        return checkDates();
    }

    const diffDays = Math.round((e - s) / (1000 * 60 * 60 * 24)) + 1;

    if (diffDays > 7) {
        dateError.classList.remove('hidden');
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        return;
    }

    summaryTxt.textContent = `📅 ${diffDays} gün boyunca (${start} → ${end}) her gün sınav oluşturulacak.`;
    summary.classList.remove('hidden');
}

// Başlangıç seçilince bitiş min'i ayarla ve max 7 gün sınırla
startInput?.addEventListener('change', function() {
    endInput.min = this.value;

    const maxDate = new Date(this.value);
    maxDate.setDate(maxDate.getDate() + 6);
    endInput.max = maxDate.toISOString().split('T')[0];

    // Bitiş tarihini otomatik ayarla (eğer boşsa veya aralık dışıysa)
    if (!endInput.value || endInput.value < this.value) {
        endInput.value = this.value;
    }
    if (endInput.value > endInput.max) {
        endInput.value = endInput.max;
    }

    checkDates();
});

endInput?.addEventListener('change', checkDates);
</script>
@endsection