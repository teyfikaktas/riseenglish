@php
    function categoryHasSets($cat, $userId) {
        if ($cat->wordSets()->where('user_id', $userId)->exists()) return true;
        foreach ($cat->children as $child) {
            if (categoryHasSets($child, $userId)) return true;
        }
        return false;
    }
    $hasSets = categoryHasSets($category, auth()->id());
@endphp

@if($hasSets)
<div style="margin-left: {{ $depth * 16 }}px; margin-bottom: 8px; border-radius: 12px; overflow: hidden; border: 1px solid #d1d5db;">
    
    <button onclick="toggleCategory('cat-{{ $category->id }}')"
            style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:16px 20px; background:#ffffff; border:none; cursor:pointer; text-align:left;">
        <div style="display:flex; align-items:center; gap:12px;">
            <span style="width:12px; height:12px; border-radius:50%; background:{{ $category->color }}; flex-shrink:0; display:inline-block;"></span>
            <span style="font-weight:600; color:#1f2937; font-size:15px;">{{ $category->name }}</span>
            @php $directSetCount = $category->wordSets()->where('user_id', auth()->id())->count(); @endphp
            @if($directSetCount > 0)
                <span style="font-size:12px; color:#9ca3af;">({{ $directSetCount }} set)</span>
            @endif
        </div>
        <svg id="cat-icon-{{ $category->id }}" style="width:20px; height:20px; color:#9ca3af; transition:transform 0.2s;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div id="cat-{{ $category->id }}" style="display:none; border-top:1px solid #e5e7eb; background:#f9fafb;">
        
        @if($category->children->isNotEmpty())
            <div style="padding:12px;">
                @foreach($category->children as $child)
                    @include('word-sets._category-row', ['category' => $child, 'depth' => 0])
                @endforeach
            </div>
        @endif

        @php
            $directs = $category->wordSets()->where('user_id', auth()->id())->withCount('userWords')->get();
        @endphp
        @if($directs->isNotEmpty())
            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:16px; padding:16px;">
                @foreach($directs as $set)
                    @include('word-sets._set-card', ['set' => $set])
                @endforeach
            </div>
        @endif
    </div>
</div>
@endif