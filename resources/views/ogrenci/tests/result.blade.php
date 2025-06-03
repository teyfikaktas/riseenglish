{{-- resources/views/ogrenci/tests/result.blade.php --}}

@extends('layouts.app')

@section('title', 'Test Sonucu')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#1a2e5a] via-[#2a4073] to-[#1a2e5a] py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Geri Dön Butonu -->
            <div class="mb-6">
                <a href="{{ route('ogrenci.tests.show', $result->test->slug) }}" 
                   class="flex items-center text-white hover:text-[#e63946] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Teste Geri Dön
                </a>
            </div>

            <!-- Ana Sonuç Kartı -->
            <div class="bg-white rounded-xl p-8 shadow-lg border-2 border-[#1a2e5a] mb-6">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-[#1a2e5a] mb-2">{{ $result->test->title }}</h1>
                    <p class="text-gray-600">Detaylı Test Sonucu</p>
                    <p class="text-sm text-gray-500 mt-2">
                        Tamamlanma Tarihi: {{ $result->completed_at->format('d.m.Y H:i') }}
                    </p>
                </div>

                <!-- Sonuç İstatistikleri -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                    <div class="text-center p-4 bg-green-50 rounded-lg border-2 border-green-200">
                        <div class="text-2xl font-bold text-green-600">{{ $result->correct_answers }}</div>
                        <div class="text-sm text-green-800">Doğru</div>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-lg border-2 border-red-200">
                        <div class="text-2xl font-bold text-red-600">{{ $result->wrong_answers }}</div>
                        <div class="text-sm text-red-800">Yanlış</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg border-2 border-gray-200">
                        <div class="text-2xl font-bold text-gray-600">{{ $result->empty_answers }}</div>
                        <div class="text-sm text-gray-800">Boş</div>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg border-2 border-blue-200">
                        <div class="text-2xl font-bold text-blue-600">%{{ number_format($result->percentage, 1) }}</div>
                        <div class="text-sm text-blue-800">Başarı</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg border-2 border-purple-200">
                        <div class="text-2xl font-bold text-purple-600">{{ $result->score ?? 0 }}</div>
                        <div class="text-sm text-purple-800">Puan</div>
                    </div>
                </div>

                <!-- Süre Bilgisi -->
                @if($result->duration_seconds)
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center bg-yellow-50 px-4 py-2 rounded-lg border border-yellow-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-yellow-800 font-medium">
                                Süre: {{ gmdate('H:i:s', $result->duration_seconds) }}
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Başarı Mesajı -->
                <div class="text-center mb-8">
                    @if($result->percentage >= 80)
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                            🏆 Mükemmel! Harika bir performans sergileddin!
                        </div>
                    @elseif($result->percentage >= 60)
                        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg">
                            👍 İyi! Başarılı bir sonuç aldın!
                        </div>
                    @elseif($result->percentage >= 40)
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg">
                            📈 Fena değil! Biraz daha çalışarak geliştirebilirsin!
                        </div>
                    @else
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            📚 Bu konuları tekrar çalışman faydalı olacak!
                        </div>
                    @endif
                </div>
            </div>

            <!-- Soru Detayları -->
            <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-[#1a2e5a]">
                <h2 class="text-2xl font-bold text-[#1a2e5a] mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Soru Detayları
                </h2>

                <div class="space-y-6">
                    @php
                        $questionAnswers = collect($result->userTestAnswers)->keyBy('question_id');
                    @endphp

                    @foreach($result->test->questions as $index => $question)
                        @php
                            $userAnswer = $questionAnswers->get($question->id);
                            $userSelectedChoice = $userAnswer ? $userAnswer->selectedChoice : null;
                            $correctChoice = $question->correctChoice;
                            $isCorrect = $userAnswer && $userAnswer->is_correct;
                            $isEmpty = !$userAnswer;
                        @endphp

                        <div class="border-2 rounded-lg p-6
                            @if($isCorrect) border-green-300 bg-green-50
                            @elseif($isEmpty) border-gray-300 bg-gray-50
                            @else border-red-300 bg-red-50
                            @endif
                        ">
                            <!-- Soru Başlığı -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold mr-3
                                        @if($isCorrect) bg-green-500
                                        @elseif($isEmpty) bg-gray-500
                                        @else bg-red-500
                                        @endif
                                    ">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <span class="font-bold
                                            @if($isCorrect) text-green-700
                                            @elseif($isEmpty) text-gray-700
                                            @else text-red-700
                                            @endif
                                        ">
                                            Soru {{ $index + 1 }}
                                        </span>
                                        @if($question->points)
                                            <span class="ml-2 bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">
                                                {{ $question->points }} Puan
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Durum İkonu -->
                                <div>
                                    @if($isCorrect)
                                        <span class="flex items-center text-green-600 font-bold">
                                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Doğru
                                        </span>
                                    @elseif($isEmpty)
                                        <span class="flex items-center text-gray-600 font-bold">
                                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            Boş
                                        </span>
                                    @else
                                        <span class="flex items-center text-red-600 font-bold">
                                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            Yanlış
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Soru Metni -->
                            <div class="mb-4">
                                <div class="text-gray-800 leading-relaxed">
                                    {!! nl2br(e($question->question_text)) !!}
                                </div>
                                
                                @if($question->question_image)
                                    <div class="mt-3">
                                        <img src="{{ asset('storage/' . $question->question_image) }}" 
                                             alt="Soru Görseli" 
                                             class="max-w-md h-auto rounded-lg border">
                                    </div>
                                @endif
                            </div>

                            <!-- Seçenekler -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                @foreach($question->choices as $choice)
                                    @php
                                        $isUserChoice = $userSelectedChoice && $userSelectedChoice->id == $choice->id;
                                        $isCorrectChoice = $choice->is_correct;
                                    @endphp
                                    
                                    <div class="flex items-center p-3 rounded-lg border-2
                                        @if($isCorrectChoice) border-green-400 bg-green-100
                                        @elseif($isUserChoice && !$isCorrectChoice) border-red-400 bg-red-100
                                        @else border-gray-200 bg-white
                                        @endif
                                    ">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center
                                                @if($isCorrectChoice) border-green-500 bg-green-500
                                                @elseif($isUserChoice && !$isCorrectChoice) border-red-500 bg-red-500
                                                @else border-gray-400
                                                @endif
                                            ">
                                                @if($isCorrectChoice)
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @elseif($isUserChoice && !$isCorrectChoice)
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <span class="font-medium mr-2
                                                @if($isCorrectChoice) text-green-800
                                                @elseif($isUserChoice && !$isCorrectChoice) text-red-800
                                                @else text-gray-800
                                                @endif
                                            ">{{ $choice->choice_letter }})</span>
                                            <span class="
                                                @if($isCorrectChoice) text-green-800
                                                @elseif($isUserChoice && !$isCorrectChoice) text-red-800
                                                @else text-gray-800
                                                @endif
                                            ">{{ $choice->choice_text }}</span>
                                            
                                            @if($isCorrectChoice)
                                                <span class="ml-2 text-green-600 font-semibold text-sm">✓ Doğru Cevap</span>
                                            @elseif($isUserChoice && !$isCorrectChoice)
                                                <span class="ml-2 text-red-600 font-semibold text-sm">✗ Seçtiğin</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Cevap Özeti -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Senin Cevabın:</span>
                                        @if($userSelectedChoice)
                                            <span class="ml-2 font-bold
                                                @if($isCorrect) text-green-600
                                                @else text-red-600
                                                @endif
                                            ">
                                                {{ $userSelectedChoice->choice_letter }}) {{ $userSelectedChoice->choice_text }}
                                            </span>
                                        @else
                                            <span class="ml-2 font-bold text-gray-600">Boş bırakıldı</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Doğru Cevap:</span>
                                        @if($correctChoice)
                                            <span class="ml-2 font-bold text-green-600">
                                                {{ $correctChoice->choice_letter }}) {{ $correctChoice->choice_text }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($userAnswer)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <span class="text-sm font-medium text-gray-600">Kazanılan Puan:</span>
                                        <span class="ml-2 font-bold text-purple-600">{{ $userAnswer->points_earned ?? 0 }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Açıklama (varsa) -->
                            {{-- @if($question->explanation)
                                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h4 class="font-bold text-blue-800 mb-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Açıklama
                                    </h4>
                                    <p class="text-blue-700 leading-relaxed">{{ $question->explanation }}</p>
                                </div>
                            @endif --}}
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Alt Butonlar -->
            <div class="flex flex-col md:flex-row gap-4 justify-center mt-8">
                <a href="{{ route('ogrenci.tests.show', $result->test->slug) }}" 
                   class="bg-[#e63946] hover:bg-[#d52936] text-white font-bold py-3 px-6 rounded-lg transition text-center">
                    🔄 Testi Tekrar Çöz
                </a>
                <a href="{{ route('ogrenci.test-categories.show', $result->test->categories->first()->slug ?? '') }}" 
                   class="bg-[#1a2e5a] hover:bg-[#0f1b3d] text-white font-bold py-3 px-6 rounded-lg transition text-center">
                    📋 Diğer Testlere Git
                </a>
                {{-- <a href="{{ route('ogrenci.tests.history') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition text-center">
                    📊 Test Geçmişi(Yakında)
                </a> --}}
                <a class="bg-gray-500 text-white font-bold py-3 px-6 rounded-lg text-center opacity-50 cursor-not-allowed">
    📊 Test Geçmişi(Yakında)
</a>
            </div>
        </div>
    </div>
</div>
@endsection