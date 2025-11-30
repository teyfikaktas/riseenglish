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

            <!-- Öğrenci Seçimi -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-[#1a2e5a] mb-4">Öğrencileri Seçin</h2>
                
                @if(isset($students) && count($students) > 0)
                    <div class="mb-4">
                        <label class="flex items-center p-3 bg-gray-50 rounded-lg border-2 border-gray-200 cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" 
                                   id="select-all-students"
                                   class="w-5 h-5 text-[#1a2e5a] rounded focus:ring-[#1a2e5a]">
                            <span class="ml-3 font-semibold text-gray-900">Tüm Öğrencileri Seç</span>
                        </label>
                    </div>

                    <div class="space-y-2 max-h-96 overflow-y-auto border border-gray-200 rounded-lg p-3">
                        @foreach($students as $student)
                            <label class="flex items-center p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition-colors">
                                <input type="checkbox" 
                                       name="students[]" 
                                       value="{{ $student->id }}"
                                       class="student-checkbox w-5 h-5 text-[#1a2e5a] rounded focus:ring-[#1a2e5a]">
                                <div class="ml-3">
                                    <span class="font-medium text-gray-900">{{ $student->name }}</span>
                                    <span class="text-sm text-gray-500 ml-2">({{ $student->email }})</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>Sistemde kayıtlı öğrenci bulunamadı.</p>
                    </div>
                @endif
            </div>

            <!-- Kelime Setleri Seçimi -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-[#1a2e5a] mb-4">Kelime Setlerini Seçin</h2>
                
                @if(count($wordSets) > 0)
                    <div class="space-y-3">
                        @foreach($wordSets as $set)
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
                        <p>Henüz kelime setiniz yok.</p>
                        <a href="{{ route('word-sets.create') }}" class="text-[#e63946] hover:underline mt-2 inline-block">
                            Kelime seti oluşturun
                        </a>
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
document.getElementById('select-all-students')?.addEventListener('change', function() {
    document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = this.checked);
});

document.querySelectorAll('.student-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const all = document.querySelectorAll('.student-checkbox');
        const checked = document.querySelectorAll('.student-checkbox:checked');
        const selectAll = document.getElementById('select-all-students');
        if (selectAll) selectAll.checked = all.length === checked.length;
    });
});
</script>
@endsection