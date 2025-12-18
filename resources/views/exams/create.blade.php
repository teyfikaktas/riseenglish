@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#1a2e5a]">Yeni Sınav Oluştur</h1>
            <p class="text-gray-600 mt-2">Öğrencileriniz için kelime sınavı hazırlayın</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('exams.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Sınav Bilgileri -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-[#1a2e5a] mb-4">Sınav Bilgileri</h2>
                
                <div class="space-y-4">
                    <!-- Toplu Sınav Oluştur -->
                    <div>
                        <label class="flex items-center p-3 bg-blue-50 rounded-lg border-2 border-blue-200 cursor-pointer hover:bg-blue-100">
                            <input type="checkbox" 
                                   id="is_recurring"
                                   name="is_recurring" 
                                   value="1"
                                   class="w-5 h-5 text-[#1a2e5a] rounded focus:ring-[#1a2e5a]">
                            <span class="ml-3 font-semibold text-gray-900">📅 Toplu Sınav Oluştur (Her Gün)</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sınav Adı</label>
                        <input type="text" 
                               name="exam_name" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent"
                               placeholder="Örn: 1. Ünite Kelime Sınavı"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama (Opsiyonel)</label>
                        <textarea name="description" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent"
                                  placeholder="Sınav hakkında kısa bir açıklama"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Başlangıç Tarihi ve Saati</label>
                        <input type="datetime-local" 
                               name="start_time" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent"
                               required>
                    </div>

                    <!-- Bitiş Tarihi (Toplu sınav için) -->
                    <div id="end_date_container" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bitiş Tarihi ve Saati</label>
                        <input type="datetime-local" 
                               name="end_date" 
                               id="end_date"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Başlangıç ve bitiş tarihi arasındaki her gün sınav oluşturulacak</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Soru Başı Süre (Saniye)</label>
                        <input type="number" 
                               name="time_per_question" 
                               min="5" 
                               max="300" 
                               value="30"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent"
                               placeholder="Her soru için ayrılan süre (saniye)"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Önerilen: 20-60 saniye arası</p>
                    </div>
                </div>
            </div>

            <!-- Grup Seçimi -->
            @if(isset($groups) && count($groups) > 0)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-[#1a2e5a] mb-4">Hızlı Grup Seçimi</h2>
                <p class="text-sm text-gray-600 mb-4">Bir gruba tıklayarak o gruptaki tüm öğrencileri seçebilirsiniz</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($groups as $group)
                        <button type="button" 
                                class="group-select-btn text-left p-4 border-2 border-gray-200 rounded-lg hover:border-[#1a2e5a] hover:bg-blue-50 transition-all cursor-pointer"
                                data-group-id="{{ $group->id }}"
                                data-student-ids="{{ $group->students->pluck('id')->join(',') }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-gray-900">{{ $group->name }}</span>
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{{ $group->students_count }} öğrenci</span>
                            </div>
                            @if($group->teacher)
                            <p class="text-xs text-gray-500">Öğretmen: {{ $group->teacher->name }}</p>
                            @endif
                            @if($group->description)
                            <p class="text-xs text-gray-400 mt-1">{{ Str::limit($group->description, 50) }}</p>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Öğrenci Seçimi -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-[#1a2e5a] mb-4">Öğrencileri ve Setlerini Seçin</h2>
                
                @if(isset($students) && count($students) > 0)
                    <!-- Arama Kutusu -->
                    <div class="mb-4">
                        <input type="text" 
                               id="student-search"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent"
                               placeholder="🔍 Öğrenci ara (isim veya email)...">
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center p-3 bg-gray-50 rounded-lg border-2 border-gray-200 cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" 
                                   id="select-all-students"
                                   class="w-5 h-5 text-[#1a2e5a] rounded focus:ring-[#1a2e5a]">
                            <span class="ml-3 font-semibold text-gray-900">Tüm Öğrencileri Seç</span>
                        </label>
                    </div>

                    <div id="students-list" class="space-y-4 max-h-[600px] overflow-y-auto border border-gray-200 rounded-lg p-4">
                        @foreach($students as $student)
                            <div class="student-item border-2 border-gray-200 rounded-lg p-4" 
                                 data-student-id="{{ $student->id }}"
                                 data-student-name="{{ strtolower($student->name) }}" 
                                 data-student-email="{{ strtolower($student->email) }}">
                                
                                <!-- Öğrenci Başlığı -->
                                <div class="flex items-center mb-3">
                                    <input type="checkbox" 
                                           name="students[]" 
                                           value="{{ $student->id }}"
                                           class="student-checkbox w-5 h-5 text-[#1a2e5a] rounded focus:ring-[#1a2e5a]">
                                    <div class="ml-3">
                                        <span class="font-bold text-gray-900 text-lg">{{ $student->name }}</span>
                                        <span class="text-sm text-gray-500 ml-2">({{ $student->email }})</span>
                                    </div>
                                </div>

                                <!-- Öğrencinin Setleri -->
                                @if($student->wordSets && count($student->wordSets) > 0)
                                    <div class="ml-8 space-y-2">
                                        <p class="text-sm font-semibold text-gray-600 mb-2">Bu öğrencinin setleri:</p>
                                        @foreach($student->wordSets as $set)
                                            <label class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 cursor-pointer transition-all">
                                                <input type="checkbox" 
                                                       name="word_sets[]" 
                                                       value="{{ $set->id }}"
                                                       class="w-4 h-4 text-[#1a2e5a] rounded focus:ring-[#1a2e5a]">
                                                <div class="ml-3 flex items-center gap-2 flex-1">
                                                    <div class="w-3 h-3 rounded" style="background-color: {{ $set->color }}"></div>
                                                    <span class="font-medium text-gray-900">{{ $set->name }}</span>
                                                    <span class="text-sm text-gray-500">({{ $set->words_count ?? $set->word_count }} kelime)</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="ml-8 text-sm text-gray-400 italic">
                                        Bu öğrencinin henüz seti yok
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div id="no-results" class="hidden text-center py-8 text-gray-500">
                        <p>Arama kriterlerine uygun öğrenci bulunamadı.</p>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>Sistemde kayıtlı öğrenci bulunamadı.</p>
                    </div>
                @endif
            </div>

            <!-- Genel Kelime Setleri -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-[#1a2e5a] mb-4">Genel Kelime Setleri</h2>
                
                @if(isset($teacherWordSets) && count($teacherWordSets) > 0)
                    <div class="space-y-3">
                        @foreach($teacherWordSets as $set)
                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#1a2e5a] cursor-pointer transition-all">
                                <input type="checkbox" 
                                       name="word_sets[]" 
                                       value="{{ $set['id'] }}"
                                       class="w-5 h-5 text-[#1a2e5a] rounded focus:ring-[#1a2e5a]">
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded" style="background-color: {{ $set['color'] }}"></div>
                                        <span class="font-semibold text-gray-900">{{ $set['name'] }}</span>
                                        <span class="text-sm text-gray-500">({{ $set['word_count'] }} kelime)</span>
                                    </div>
                                    @if($set['description'])
                                        <p class="text-sm text-gray-600 mt-1 ml-7">{{ $set['description'] }}</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>Genel kelime seti bulunmuyor.</p>
                    </div>
                @endif
            </div>

            <!-- Butonlar -->
            <div class="flex items-center justify-between">
                <a href="{{ route('word-sets.index') }}" 
                   class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                    İptal
                </a>
                <button type="submit"
                        class="bg-[#e63946] hover:bg-red-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                    Sınavı Oluştur
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Grup seçimi - Toggle yapısı
document.querySelectorAll('.group-select-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const studentIds = this.getAttribute('data-student-ids').split(',').filter(id => id);
        
        // Grup seçili mi kontrol et
        const isGroupSelected = this.classList.contains('group-selected');
        
        if (isGroupSelected) {
            // Grup seçiliyse, seçimi kaldır
            studentIds.forEach(studentId => {
                const studentItem = document.querySelector(`.student-item[data-student-id="${studentId}"]`);
                if (studentItem) {
                    const checkbox = studentItem.querySelector('.student-checkbox');
                    if (checkbox) {
                        checkbox.checked = false;
                    }
                }
            });
            
            // Buton stilini kaldır
            this.classList.remove('group-selected', 'bg-green-100', 'border-green-500');
            this.classList.add('border-gray-200');
            
        } else {
            // Grup seçili değilse, seç
            studentIds.forEach(studentId => {
                const studentItem = document.querySelector(`.student-item[data-student-id="${studentId}"]`);
                if (studentItem) {
                    const checkbox = studentItem.querySelector('.student-checkbox');
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                }
            });
            
            // Buton stilini ekle
            this.classList.add('group-selected', 'bg-green-100', 'border-green-500');
            this.classList.remove('border-gray-200');
        }
        
        // "Tüm öğrencileri seç" checkbox'ını güncelle
        updateSelectAllCheckbox();
    });
});

// Tüm öğrencileri seç
document.getElementById('select-all-students')?.addEventListener('change', function() {
    const visibleCheckboxes = document.querySelectorAll('.student-item:not(.hidden) .student-checkbox');
    visibleCheckboxes.forEach(cb => cb.checked = this.checked);
});

// Tekil öğrenci seçimi
document.querySelectorAll('.student-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectAllCheckbox);
});

// "Tüm öğrencileri seç" checkbox'ını güncelle
function updateSelectAllCheckbox() {
    const all = document.querySelectorAll('.student-item:not(.hidden) .student-checkbox');
    const checked = document.querySelectorAll('.student-item:not(.hidden) .student-checkbox:checked');
    const selectAll = document.getElementById('select-all-students');
    if (selectAll) selectAll.checked = all.length === checked.length && all.length > 0;
}

// Öğrenci arama
document.getElementById('student-search')?.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim();
    const studentItems = document.querySelectorAll('.student-item');
    const noResults = document.getElementById('no-results');
    let visibleCount = 0;

    studentItems.forEach(item => {
        const name = item.getAttribute('data-student-name');
        const email = item.getAttribute('data-student-email');
        
        if (name.includes(searchTerm) || email.includes(searchTerm)) {
            item.classList.remove('hidden');
            visibleCount++;
        } else {
            item.classList.add('hidden');
        }
    });

    // Sonuç bulunamadı mesajını göster/gizle
    if (noResults) {
        if (visibleCount === 0 && searchTerm !== '') {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    }

    // "Tüm öğrencileri seç" checkbox'ını güncelle
    updateSelectAllCheckbox();
});

// Toplu sınav checkbox toggle
document.getElementById('is_recurring')?.addEventListener('change', function() {
    const endDateContainer = document.getElementById('end_date_container');
    const endDateInput = document.getElementById('end_date');
    
    if (this.checked) {
        endDateContainer.classList.remove('hidden');
        endDateInput.required = true;
    } else {
        endDateContainer.classList.add('hidden');
        endDateInput.required = false;
    }
});
</script>
@endsection