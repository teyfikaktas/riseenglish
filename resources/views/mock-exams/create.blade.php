@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#1a2e5a]">Yeni Deneme Sınavı Oluştur</h1>
            <p class="text-gray-600 mt-2">Öğrencileriniz için kod ile erişilebilen bir deneme sınavı hazırlayın</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('mock-exams.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Sınav Bilgileri -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-[#1a2e5a] mb-4">Sınav Bilgileri</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sınav Adı</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent"
                               placeholder="Örn: 1. Ünite Deneme Sınavı"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama (Opsiyonel)</label>
                        <textarea name="description"
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent"
                                  placeholder="Deneme sınavı hakkında kısa bir açıklama">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Başlangıç Tarihi ve Saati</label>
                        <input type="datetime-local"
                               name="start_time"
                               value="{{ old('start_time') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Soru Başı Süre (Saniye)</label>
                        <input type="number"
                               name="time_per_question"
                               min="5"
                               max="300"
                               value="{{ old('time_per_question', 30) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a2e5a] focus:border-transparent"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Önerilen: 20-60 saniye arası</p>
                    </div>
                </div>
            </div>

            <!-- Kelime Seti Seçimi (TEK set) -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-[#1a2e5a] mb-4">Kelime Seti Seçin</h2>
                <p class="text-sm text-gray-600 mb-4">Deneme sınavında kullanılacak <strong>tek bir kelime seti</strong> seçin. Setteki tüm kelimeler sınavda karışık sırayla sorulacak.</p>

                @if($categoryTree->isNotEmpty() || $uncategorizedSets->isNotEmpty())

                    @php
                    function mockExamHasSets($category, $categorizedSets) {
                        if (isset($categorizedSets[$category->id]) && $categorizedSets[$category->id]->count() > 0) return true;
                        foreach ($category->children as $child) {
                            if (mockExamHasSets($child, $categorizedSets)) return true;
                        }
                        return false;
                    }

                    function renderMockExamCategory($category, $categorizedSets, $depth = 0) {
                        if (!mockExamHasSets($category, $categorizedSets)) return '';
                        $sets   = $categorizedSets[$category->id] ?? collect();
                        $catId  = 'mock-cat-' . $category->id;
                        $color  = $category->color;
                        $name   = htmlspecialchars($category->name);
                        $cnt    = $sets->count();
                        $badge  = $cnt > 0 ? "<span style='font-size:12px;color:#9ca3af;margin-left:6px;'>({$cnt} set)</span>" : '';
                        $pl     = $depth * 16;

                        $html  = "<div style='margin-left:{$pl}px;margin-bottom:8px;'>";
                        $html .= "<div style='border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;'>";
                        $html .= "<button type='button' onclick=\"toggleMockCat('{$catId}')\" style='width:100%;display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:#f9fafb;border:none;cursor:pointer;'>";
                        $html .= "<div style='display:flex;align-items:center;gap:8px;'>";
                        $html .= "<span style='width:10px;height:10px;border-radius:50%;background:{$color};display:inline-block;'></span>";
                        $html .= "<span style='font-weight:600;color:#1a2e5a;font-size:14px;'>{$name}</span>{$badge}";
                        $html .= "</div>";
                        $html .= "<svg id='{$catId}-icon' style='width:16px;height:16px;color:#9ca3af;transition:transform 0.2s;' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/></svg>";
                        $html .= "</button>";
                        $html .= "<div id='{$catId}' style='display:none;padding:12px;background:#fff;border-top:1px solid #e5e7eb;'>";

                        foreach ($category->children as $child) {
                            $html .= renderMockExamCategory($child, $categorizedSets, 0);
                        }

                        foreach ($sets as $set) {
                            $wc    = $set->words_count ?? $set->word_count ?? 0;
                            $sname = htmlspecialchars($set->name);
                            $sdesc = $set->description ? "<p style='font-size:12px;color:#6b7280;margin:2px 0 0 28px;'>" . htmlspecialchars($set->description) . "</p>" : '';
                            $html .= "
                            <label style='display:flex;flex-direction:column;padding:10px 12px;border:1px solid #e5e7eb;border-radius:8px;margin-bottom:6px;cursor:pointer;transition:border-color 0.15s;' onmouseover=\"this.style.borderColor='#1a2e5a'\" onmouseout=\"this.style.borderColor='#e5e7eb'\">
                                <div style='display:flex;align-items:center;gap:10px;'>
                                    <input type='radio' name='word_set_id' value='{$set->id}' required style='width:16px;height:16px;accent-color:#1a2e5a;flex-shrink:0;'>
                                    <span style='width:10px;height:10px;border-radius:3px;background:{$set->color};display:inline-block;flex-shrink:0;'></span>
                                    <span style='font-weight:500;color:#111827;font-size:14px;'>{$sname}</span>
                                    <span style='font-size:12px;color:#9ca3af;margin-left:auto;'>{$wc} kelime</span>
                                </div>
                                {$sdesc}
                            </label>";
                        }

                        $html .= "</div></div></div>";
                        return $html;
                    }
                    @endphp

                    {{-- Kategorili setler --}}
                    @foreach($categoryTree as $category)
                        @if(mockExamHasSets($category, $categorizedSets))
                            {!! renderMockExamCategory($category, $categorizedSets, 0) !!}
                        @endif
                    @endforeach

                    {{-- Kategorisiz setler --}}
                    @if($uncategorizedSets->isNotEmpty())
                        <div class="mt-4">
                            <p class="text-sm font-semibold text-gray-500 mb-2">Kategorisiz</p>
                            <div class="space-y-2">
                                @foreach($uncategorizedSets as $set)
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-[#1a2e5a] cursor-pointer transition-all">
                                        <input type="radio" name="word_set_id" value="{{ $set->id }}" required
                                               class="w-4 h-4 text-[#1a2e5a] focus:ring-[#1a2e5a]">
                                        <div class="ml-3 flex items-center gap-2 flex-1">
                                            <div class="w-3 h-3 rounded" style="background-color: {{ $set->color }}"></div>
                                            <span class="font-medium text-gray-900">{{ $set->name }}</span>
                                            <span class="text-sm text-gray-500 ml-auto">{{ $set->words_count ?? $set->word_count }} kelime</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>Kelime seti bulunmuyor.</p>
                    </div>
                @endif
            </div>

            <!-- Butonlar -->
            <div class="flex items-center justify-between">
                <a href="{{ route('mock-exams.index') }}"
                   class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                    İptal
                </a>
                <button type="submit"
                        class="bg-[#e63946] hover:bg-red-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                    Deneme Sınavını Oluştur
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleMockCat(id) {
    const el   = document.getElementById(id);
    const icon = document.getElementById(id + '-icon');
    const open = el.style.display === 'none' || el.style.display === '';
    el.style.display     = open ? 'block' : 'none';
    icon.style.transform = open ? 'rotate(180deg)' : 'rotate(0deg)';
}
</script>
@endsection