{{-- resources/views/livewire/test-taking.blade.php --}}

<div class="min-h-screen bg-gradient-to-br from-[#1a2e5a] via-[#2a4073] to-[#1a2e5a]">
    <!-- Test Başlamadan Önce -->
    @if(!$isStarted)
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-3xl mx-auto">
                <!-- Geri Dön Butonu -->
                <div class="mb-6">
                    <a href="{{ route('ogrenci.tests.show', $test->slug) }}" class="flex items-center text-white hover:text-[#e63946] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Test Detayına Dön
                    </a>
                </div>

                <!-- Test Başlama Kartı -->
                <div class="bg-white rounded-xl p-8 shadow-lg border-2 border-[#1a2e5a]">
                    <div class="text-center">
                        <h1 class="text-3xl font-bold text-[#1a2e5a] mb-4">{{ $test->title }}</h1>
                        <p class="text-gray-600 mb-6">{{ $test->description }}</p>
                        
                        <!-- Test Bilgileri -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">{{ count($questions) }}</div>
                                <div class="text-sm text-blue-800">Toplam Soru</div>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">
                                    {{ $test->duration_minutes ?: 'Sınırsız' }}
                                </div>
                                <div class="text-sm text-green-800">
                                    {{ $test->duration_minutes ? 'Dakika' : 'Süre' }}
                                </div>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <div class="text-xl font-bold text-purple-600">{{ $test->difficulty_level ?: 'Karma' }}</div>
                                <div class="text-sm text-purple-800">Zorluk</div>
                            </div>
                        </div>

                        <!-- Uyarılar -->
                        @if($test->duration_minutes)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                <p class="text-yellow-800">
                                    ⚠️ <strong>Dikkat:</strong> Bu test {{ $test->duration_minutes }} dakika süreli olup, süre dolduğunda otomatik olarak tamamlanacaktır.
                                </p>
                            </div>
                        @endif

                        <!-- Başla Butonu -->
                        <button wire:click="startTest" 
                                class="bg-[#e63946] hover:bg-[#d52936] text-white font-bold py-4 px-8 rounded-xl text-lg transition transform hover:scale-105">
                            🚀 Teste Başla
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <!-- Test Sonuçları -->
    @elseif($showResults)
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl p-8 shadow-lg border-2 border-[#1a2e5a]">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-[#1a2e5a] mb-2">Test Tamamlandı! 🎉</h1>
                        <h2 class="text-xl text-gray-600">{{ $test->title }}</h2>
                    </div>

                    <!-- Sonuç Özeti -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-3xl font-bold text-blue-600">{{ $userTestResult->correct_answers }}</div>
                            <div class="text-sm text-blue-800">Doğru</div>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <div class="text-3xl font-bold text-red-600">{{ $userTestResult->wrong_answers }}</div>
                            <div class="text-sm text-red-800">Yanlış</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-3xl font-bold text-gray-600">{{ $userTestResult->empty_answers }}</div>
                            <div class="text-sm text-gray-800">Boş</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-3xl font-bold text-green-600">%{{ number_format($userTestResult->percentage, 1) }}</div>
                            <div class="text-sm text-green-800">Başarı</div>
                        </div>
                    </div>

                    <!-- Başarı Mesajı -->
                    <div class="text-center mb-8">
                        @if($userTestResult->percentage >= 80)
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                                🏆 Mükemmel! Harika bir performans sergileddin!
                            </div>
                        @elseif($userTestResult->percentage >= 60)
                            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg">
                                👍 İyi! Başarılı bir sonuç aldın!
                            </div>
                        @elseif($userTestResult->percentage >= 40)
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg">
                                📈 Fena değil! Biraz daha çalışarak geliştirebilirsin!
                            </div>
                        @else
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                                📚 Bu konuları tekrar çalışman faydalı olacak!
                            </div>
                        @endif
                    </div>

                    <!-- Aksiyon Butonları -->
                    <div class="flex flex-col md:flex-row gap-4 justify-center">
                        <a href="{{ route('ogrenci.tests.result', $userTestResult->id) }}" 
                           class="bg-[#1a2e5a] hover:bg-[#0f1b3d] text-white font-bold py-3 px-6 rounded-lg transition">
                            📊 Detaylı Sonuçları Gör
                        </a>
                        <button wire:click="$refresh" 
                                class="bg-[#e63946] hover:bg-[#d52936] text-white font-bold py-3 px-6 rounded-lg transition">
                            🔄 Testi Tekrar Çöz
                        </button>
                        <a href="{{ route('ogrenci.test-categories.show', $test->categories->first()->slug ?? '') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition">
                            📋 Diğer Testlere Git
                        </a>
                    </div>
                </div>
            </div>
        </div>

    <!-- Test Çözme Ekranı -->
    @else
        <div class="container mx-auto px-4 py-4">
            <!-- Üst Bar -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6 border-2 border-[#1a2e5a]">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <!-- Test Bilgisi -->
                    <div class="mb-4 md:mb-0">
                        <h1 class="text-xl font-bold text-[#1a2e5a]">{{ $test->title }}</h1>
                        <p class="text-sm text-gray-600">
                            Soru {{ $currentQuestionIndex + 1 }} / {{ count($questions) }}
                        </p>
                    </div>

                    <!-- İlerleme ve Süre -->
                    <div class="flex items-center space-x-6">
                        <!-- İlerleme -->
                        <div class="text-center">
                            <div class="text-lg font-bold text-[#1a2e5a]">{{ $answeredCount }}/{{ count($questions) }}</div>
                            <div class="text-xs text-gray-600">Cevaplanan</div>
                        </div>

                        <!-- Süre -->
                        @if($test->duration_minutes)
                            <div class="text-center" x-data="{ timeRemaining: @entangle('timeRemaining') }" 
                                 x-init="
                                    setInterval(() => {
                                        if (timeRemaining > 0) {
                                            timeRemaining--;
                                        } else {
                                            $wire.timeUp();
                                        }
                                    }, 1000)
                                 ">
                                <div class="text-lg font-bold" 
                                     :class="timeRemaining < 300 ? 'text-red-600' : 'text-green-600'">
                                    <span x-text="Math.floor(timeRemaining / 60) + ':' + (timeRemaining % 60).toString().padStart(2, '0')"></span>
                                </div>
                                <div class="text-xs text-gray-600">Kalan Süre</div>
                            </div>
                        @endif

                        <!-- Test Bitir Butonları -->
                        <div class="flex space-x-2">
                            <button wire:click="completeTest" 
                                    wire:confirm="Testi bitirmek istediğinizden emin misiniz?"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                    @if($isCompleting) disabled @endif
                                    class="bg-[#e63946] hover:bg-[#d52936] text-white px-4 py-2 rounded-lg text-sm font-medium transition 
                                           @if($isCompleting) opacity-50 cursor-not-allowed @endif">
                                <span wire:loading.remove wire:target="completeTest">Testi Bitir</span>
                                <span wire:loading wire:target="completeTest">Bitiriliyor...</span>
                            </button>
                            
                            @if($isCompleting)
                                <button wire:click="$refresh" 
                                        class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded-lg text-xs font-medium transition">
                                    🔄 Yenile
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- İlerleme Çubuğu -->
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-[#1a2e5a] h-2 rounded-full transition-all duration-300" 
                             style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Sol Taraf - Soru Navigasyonu -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-4 border-2 border-[#1a2e5a]">
                        <h3 class="font-bold text-[#1a2e5a] mb-4">📋 Sorular</h3>
                        <div class="grid grid-cols-5 lg:grid-cols-4 gap-2">
                            @foreach($questions as $index => $question)
                                <button wire:click="goToQuestion({{ $index }})"
                                        class="w-10 h-10 rounded-lg text-sm font-medium transition
                                            @if($index == $currentQuestionIndex)
                                                bg-[#1a2e5a] text-white
                                            @elseif(isset($answers[$question['id']]) && $answers[$question['id']] !== null)
                                                bg-green-100 text-green-700 border border-green-300
                                            @else
                                                bg-gray-100 text-gray-600 hover:bg-gray-200
                                            @endif
                                        ">
                                    {{ $index + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sağ Taraf - Soru İçeriği -->
                <div class="lg:col-span-3">
                    @if($currentQuestion)
                        <div class="bg-white rounded-lg shadow-md p-6 border-2 border-[#1a2e5a]" wire:key="question-{{ $currentQuestion['id'] }}">
                            <!-- Soru Metni -->
                            <div class="mb-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h2 class="text-lg font-bold text-[#1a2e5a]">
                                        Soru {{ $currentQuestionIndex + 1 }}
                                    </h2>
                                    @if($currentQuestion['points'])
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">
                                            {{ $currentQuestion['points'] }} Puan
                                        </span>
                                    @endif
                                </div>
                                <div class="text-gray-800 leading-relaxed text-lg">
                                    {!! nl2br(e($currentQuestion['question_text'])) !!}
                                </div>
                                
                                @if(isset($currentQuestion['question_image']) && $currentQuestion['question_image'])
                                    <div class="mt-4">
                                        <img src="{{ asset('storage/' . $currentQuestion['question_image']) }}" 
                                             alt="Soru Görseli" 
                                             class="max-w-full h-auto rounded-lg border">
                                    </div>
                                @endif
                            </div>

                            <!-- Seçenekler -->
                            <div class="space-y-3">
                                @foreach($currentQuestion['choices'] as $choice)
                                    @php
                                        $isSelected = $currentAnswer == $choice['id'];
                                        $isCorrect = $choice['is_correct'] ?? false;
                                        $isWrong = !$isCorrect && $isSelected && $showCorrectAnswers;
                                        $shouldShowCorrect = $isCorrect && $showCorrectAnswers;
                                    @endphp
                                    <div wire:key="choice-{{ $currentQuestion['id'] }}-{{ $choice['id'] }}" 
                                         wire:click="selectAnswer({{ $currentQuestion['id'] }}, {{ $choice['id'] }})"
                                         class="border-2 rounded-lg p-4 cursor-pointer transition
                                            @if($showCorrectAnswers)
                                                @if($isWrong)
                                                    border-red-500 bg-red-50
                                                @elseif($shouldShowCorrect)
                                                    border-green-500 bg-green-50
                                                @else
                                                    border-gray-300 bg-white
                                                @endif
                                            @else
                                                @if($isSelected)
                                                    border-[#1a2e5a] bg-blue-50
                                                @else
                                                    border-gray-300 hover:border-[#1a2e5a] hover:bg-gray-50
                                                @endif
                                            @endif
                                        ">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mr-3 mt-1">
                                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                                    @if($showCorrectAnswers)
                                                        @if($isWrong)
                                                            border-red-500 bg-red-500
                                                        @elseif($shouldShowCorrect)
                                                            border-green-500 bg-green-500
                                                        @else
                                                            border-gray-400
                                                        @endif
                                                    @else
                                                        @if($isSelected)
                                                            border-[#1a2e5a] bg-[#1a2e5a]
                                                        @else
                                                            border-gray-400
                                                        @endif
                                                    @endif
                                                ">
                                                    @if($isSelected || $shouldShowCorrect)
                                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                                    @endif
                                                    
                                                    @if($showCorrectAnswers && $isWrong)
                                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @elseif($showCorrectAnswers && $shouldShowCorrect)
                                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <span class="font-medium mr-2
                                                    @if($showCorrectAnswers)
                                                        @if($isWrong) text-red-800
                                                        @elseif($shouldShowCorrect) text-green-800
                                                        @else text-gray-800
                                                        @endif
                                                    @else
                                                        text-gray-800
                                                    @endif
                                                ">{{ $choice['choice_letter'] }})</span>
                                                <span class="
                                                    @if($showCorrectAnswers)
                                                        @if($isWrong) text-red-800
                                                        @elseif($shouldShowCorrect) text-green-800
                                                        @else text-gray-800
                                                        @endif
                                                    @else
                                                        text-gray-800
                                                    @endif
                                                ">{{ $choice['choice_text'] }}</span>
                                                
                                                @if($showCorrectAnswers && $shouldShowCorrect)
                                                    <span class="ml-2 text-green-600 font-semibold">✓ Doğru Cevap</span>
                                                @elseif($showCorrectAnswers && $isWrong)
                                                    <span class="ml-2 text-red-600 font-semibold">✗ Yanlış</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Navigasyon Butonları -->
                            <div class="flex justify-between mt-8">
                                <button wire:click="previousQuestion" 
                                        @if($currentQuestionIndex == 0) disabled @endif
                                        class="flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Önceki
                                </button>

                                <button wire:click="nextQuestion" 
                                        @if($currentQuestionIndex == count($questions) - 1) disabled @endif
                                        class="flex items-center px-4 py-2 bg-[#1a2e5a] text-white rounded-lg hover:bg-[#0f1b3d] transition disabled:opacity-50 disabled:cursor-not-allowed">
                                    Sonraki
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>