@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col space-y-6">
        <!-- Header -->
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Sınav Oluştur
                    </a>
                @endhasrole
                <a href="{{ route('word-sets.create') }}" 
                   class="bg-[#e63946] hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
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

        @if($wordSets->count() > 0)
            <!-- Kelime Setleri Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($wordSets as $set)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                        <!-- Set Header -->
                        <div class="h-20 p-4 flex items-center justify-between text-white" style="background: linear-gradient(135deg, {{ $set->color }}, {{ $set->color }}aa);">
                            <div>
                                <h3 class="font-bold text-lg">{{ $set->name }}</h3>
                                <p class="text-sm opacity-90">{{ $set->words_count }} kelime</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('word-sets.edit', $set) }}" 
                                   class="p-2 bg-white/20 rounded-lg hover:bg-white/30 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Set Content -->
                        <div class="p-4">
                            @if($set->description)
                                <p class="text-gray-600 text-sm mb-4">{{ $set->description }}</p>
                            @endif
                            
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">
                                    {{ $set->created_at->diffForHumans() }}
                                </span>
                                <a href="{{ route('word-sets.show', $set) }}" 
                                   class="bg-[#1a2e5a] hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    Kelimeleri Gör
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Boş Durum -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <div class="mb-6">
                        <svg class="w-24 h-24 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-3">İlk Kelime Setinizi Oluşturun!</h3>
                    <p class="text-gray-500 mb-6 leading-relaxed">
                        Henüz hiç kelime setiniz yok. Kelime setleri oluşturarak İngilizce kelimelerinizi kategorilere ayırabilir, 
                        daha organize bir şekilde öğrenebilirsiniz. Örneğin "İş Hayatı", "Günlük Kelimeler" gibi setler oluşturabilirsiniz.
                    </p>
                    <a href="{{ route('word-sets.create') }}" 
                       class="inline-flex items-center gap-2 bg-[#e63946] hover:bg-red-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        İlk Setimi Oluştur
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection