<div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
    <div class="h-20 p-4 flex items-center justify-between text-white"
         style="background: linear-gradient(135deg, {{ $set->color }}, {{ $set->color }}aa);">
        <div>
            <h3 class="font-bold text-lg">{{ $set->name }}</h3>
            <p class="text-sm opacity-90">{{ $set->user_words_count }} kelime</p>
        </div>
        <a href="{{ route('word-sets.edit', $set) }}"
           class="p-2 bg-white/20 rounded-lg hover:bg-white/30 transition-colors">
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
            <a href="{{ route('word-sets.show', $set) }}"
               class="bg-[#1a2e5a] hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Kelimeleri Gör
            </a>
        </div>
    </div>
</div>