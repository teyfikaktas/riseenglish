@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-[#1a2e5a]">Kelimelerim</h1>
                <p class="text-gray-600 mt-1">Kişisel kelime setlerinizi yönetin ve öğrenin</p>
            </div>
            <div class="flex items-center gap-3">
                @hasrole('ogretmen')
                    <a href="{{ route('exams.create') }}"
                       class="bg-[#1a2e5a] hover:bg-blue-800 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Sınav Oluştur
                    </a>
                @endhasrole
                <a href="{{ route('word-sets.create') }}"
                   class="bg-[#e63946] hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Yeni Set Oluştur
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @php
        function hasSetsInCategory($category, $categorizedSets) {
            if (isset($categorizedSets[$category->id]) && $categorizedSets[$category->id]->count() > 0) return true;
            foreach ($category->children as $child) {
                if (hasSetsInCategory($child, $categorizedSets)) return true;
            }
            return false;
        }

        function renderSetCard($set) {
            $editUrl = route('word-sets.edit', $set);
            $showUrl = route('word-sets.show', $set);
            $color = $set->color;
            $name = htmlspecialchars($set->name);
            $wordCount = $set->words_count ?? 0;
            $desc = $set->description ? '<p style="color:#6b7280;font-size:13px;margin-bottom:12px;">' . htmlspecialchars($set->description) . '</p>' : '';
            $timeAgo = $set->created_at->diffForHumans();
            return "
            <div style='background:#fff;border-radius:12px;box-shadow:0 4px 6px rgba(0,0,0,0.07);overflow:hidden;border:1px solid #f3f4f6;'>
                <div style='height:80px;padding:16px;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(135deg,{$color},{$color}aa);'>
                    <div>
                        <div style='font-weight:700;font-size:16px;color:#fff;'>{$name}</div>
                        <div style='font-size:13px;color:rgba(255,255,255,0.85);'>{$wordCount} kelime</div>
                    </div>
                    <a href='{$editUrl}' style='padding:8px;background:rgba(255,255,255,0.2);border-radius:8px;display:flex;'>
                        <svg style='width:16px;height:16px;color:#fff;' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'/></svg>
                    </a>
                </div>
                <div style='padding:16px;'>
                    {$desc}
                    <div style='display:flex;justify-content:space-between;align-items:center;'>
                        <span style='font-size:12px;color:#9ca3af;'>{$timeAgo}</span>
                        <a href='{$showUrl}' style='background:#1a2e5a;color:#fff;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:500;text-decoration:none;'>Kelimeleri Gör</a>
                    </div>
                </div>
            </div>";
        }

        function renderCategory($category, $categorizedSets, $depth = 0) {
            if (!hasSetsInCategory($category, $categorizedSets)) return '';
            $sets = $categorizedSets[$category->id] ?? collect();
            $pl = $depth * 20;
            $catId = 'cat-' . $category->id;
            $color = $category->color;
            $name = htmlspecialchars($category->name);
            $cnt = $sets->count();
            $badge = $cnt > 0 ? "<span style='font-size:12px;color:#9ca3af;'>({$cnt} set)</span>" : '';

            $html  = "<div style='margin-left:{$pl}px;margin-bottom:8px;'>";
            $html .= "<div style='background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;'>";
            $html .= "<button onclick=\"toggleCat('{$catId}')\" style='width:100%;display:flex;align-items:center;justify-content:space-between;padding:14px 18px;background:transparent;border:none;cursor:pointer;'>";
            $html .= "<div style='display:flex;align-items:center;gap:10px;'>";
            $html .= "<span style='width:10px;height:10px;border-radius:50%;background:{$color};display:inline-block;'></span>";
            $html .= "<span style='font-weight:600;color:#1a2e5a;font-size:15px;'>{$name}</span>{$badge}";
            $html .= "</div>";
            $html .= "<svg id='{$catId}-icon' style='width:18px;height:18px;color:#9ca3af;transition:transform 0.2s;' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/></svg>";
            $html .= "</button>";
            $html .= "<div id='{$catId}' style='display:none;border-top:1px solid #e5e7eb;background:#f9fafb;padding:12px;'>";

            foreach ($category->children as $child) {
                $html .= renderCategory($child, $categorizedSets, 0);
            }

            if ($sets->count() > 0) {
                $html .= "<div style='display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;margin-top:8px;'>";
                foreach ($sets as $set) {
                    $html .= renderSetCard($set);
                }
                $html .= "</div>";
            }

            $html .= "</div></div></div>";
            return $html;
        }
        @endphp

        {{-- KATEGORİLER --}}
        @if($categoryTree->isNotEmpty())
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-3">Kategoriler</h2>
                @foreach($categoryTree as $category)
                    @if(hasSetsInCategory($category, $categorizedSets))
                        {!! renderCategory($category, $categorizedSets, 0) !!}
                    @endif
                @endforeach
            </div>
        @endif

        {{-- KATEGORİSİZ SETLER --}}
        @if($uncategorizedSets->isNotEmpty())
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-3">
                    Kategorisiz
                    <span class="text-sm font-normal text-gray-400">({{ $uncategorizedSets->count() }} set)</span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($uncategorizedSets as $set)
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                            <div class="h-20 p-4 flex items-center justify-between text-white" style="background: linear-gradient(135deg, {{ $set->color }}, {{ $set->color }}aa);">
                                <div>
                                    <h3 class="font-bold text-lg">{{ $set->name }}</h3>
                                    <p class="text-sm opacity-90">{{ $set->words_count }} kelime</p>
                                </div>
                                <a href="{{ route('word-sets.edit', $set) }}" class="p-2 bg-white/20 rounded-lg hover:bg-white/30 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            </div>
                            <div class="p-4">
                                @if($set->description)
                                    <p class="text-gray-600 text-sm mb-4">{{ $set->description }}</p>
                                @endif
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">{{ $set->created_at->diffForHumans() }}</span>
                                    <a href="{{ route('word-sets.show', $set) }}" class="bg-[#1a2e5a] hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        Kelimeleri Gör
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- HİÇ SET YOK --}}
        @if($categoryTree->isEmpty() && $uncategorizedSets->isEmpty())
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-700 mb-3">İlk Kelime Setinizi Oluşturun!</h3>
                    <p class="text-gray-500 mb-6 leading-relaxed">Henüz hiç kelime setiniz yok.</p>
                    <a href="{{ route('word-sets.create') }}" class="inline-flex items-center gap-2 bg-[#e63946] hover:bg-red-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        İlk Setimi Oluştur
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>

<script>
function toggleCat(id) {
    const el = document.getElementById(id);
    const icon = document.getElementById(id + '-icon');
    if (el.style.display === 'none' || el.style.display === '') {
        el.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    } else {
        el.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>

@endsection