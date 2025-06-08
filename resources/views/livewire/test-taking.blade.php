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

                        <!-- Güvenlik Uyarıları -->
                        <div class="bg-red-50 border-2 border-red-200 rounded-lg p-6 mb-6">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.876c1.07 0 1.968-.863 1.968-1.928 0-.366-.149-.718-.414-.981L12.707 2.657a1.933 1.933 0 00-2.828 0L2.093 19.091c-.265.263-.414.615-.414.981 0 1.065.898 1.928 1.968 1.928z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <h3 class="text-lg font-bold text-red-800 mb-2">⚠️ Önemli Güvenlik Kuralları</h3>
                                    <ul class="text-sm text-red-700 space-y-1">
                                        <li>• <strong>Sekme değiştirme yasak:</strong> Başka sekmelere geçiş yaparsanız sınav otomatik sonlanır</li>
                                        <li>• <strong>Tarayıcıdan çıkma yasak:</strong> Tarayıcıyı kapatır veya minimize ederseniz sınav biter</li>
                                        <li>• <strong>Tam ekran modu:</strong> Sınav boyunca tam ekran modunda kalmalısınız</li>
                                        <li>• <strong>Alt+Tab yasak:</strong> Başka programlara geçiş yapamazsınız</li>
                                        <li>• <strong>F12 / Developer Tools yasak:</strong> Geliştirici araçları açılması durumunda sınav sonlanır</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Süre Uyarısı -->
                        @if($test->duration_minutes)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                <p class="text-yellow-800">
                                    ⏰ <strong>Süre:</strong> Bu test {{ $test->duration_minutes }} dakika süreli olup, süre dolduğunda otomatik olarak tamamlanacaktır.
                                </p>
                            </div>
                        @endif

                        <!-- Onay Checkbox -->
                        <div class="mb-6">
                            <label class="flex items-center justify-center space-x-3 cursor-pointer">
                                <input type="checkbox" id="rulesAccepted" class="w-5 h-5 text-[#e63946] border-2 border-gray-300 rounded focus:ring-[#e63946]">
                                <span class="text-gray-700 font-medium">Güvenlik kurallarını okudum ve kabul ediyorum</span>
                            </label>
                        </div>

                        <!-- Başla Butonu -->
                        <button id="startTestBtn" wire:click="startTest" disabled
                                class="bg-gray-400 text-white font-bold py-4 px-8 rounded-xl text-lg transition transform cursor-not-allowed">
                            🚀 Teste Başla
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkbox = document.getElementById('rulesAccepted');
                const startBtn = document.getElementById('startTestBtn');
                
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        startBtn.disabled = false;
                        startBtn.className = 'bg-[#e63946] hover:bg-[#d52936] text-white font-bold py-4 px-8 rounded-xl text-lg transition transform hover:scale-105 cursor-pointer';
                    } else {
                        startBtn.disabled = true;
                        startBtn.className = 'bg-gray-400 text-white font-bold py-4 px-8 rounded-xl text-lg transition transform cursor-not-allowed';
                    }
                });
            });
        </script>

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
                           class="bg-[#1a2e5a] hover:bg-[#0f1b3d] text-white font-bold py-3 px-6 rounded-lg transition text-center">
                            📊 Detaylı Sonuçları Gör
                        </a>
                        <button wire:click="$refresh" 
                                class="bg-[#e63946] hover:bg-[#d52936] text-white font-bold py-3 px-6 rounded-lg transition">
                            🔄 Testi Tekrar Çöz
                        </button>
                        <a href="{{ route('ogrenci.test-categories.show', $test->categories->first()->slug ?? '') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition text-center">
                            📋 Diğer Testlere Git
                        </a>
                    </div>
                </div>
            </div>
        </div>

    <!-- Test Çözme Ekranı -->
    @else
        <!-- Güvenlik Uyarı Modalı -->
        <div id="securityWarningModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-xl p-8 max-w-md mx-4 text-center">
                <div class="mb-4">
                    <svg class="h-16 w-16 text-red-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.876c1.07 0 1.968-.863 1.968-1.928 0-.366-.149-.718-.414-.981L12.707 2.657a1.933 1.933 0 00-2.828 0L2.093 19.091c-.265.263-.414.615-.414.981 0 1.065.898 1.928 1.968 1.928z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-red-600 mb-4">⚠️ Güvenlik İhlali Tespit Edildi!</h2>
                <p class="text-gray-700 mb-6">Sınav kurallarını ihlal ettiğiniz tespit edildi. Sınavınız sonlandırılacaktır.</p>
                <div class="space-y-3">
                    <button id="continueExamBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
                        🔄 Sınava Devam Et (Son Şans)
                    </button>
                    <button id="endExamBtn" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition">
                        ❌ Sınavı Sonlandır
                    </button>
                </div>
                <p class="text-sm text-gray-500 mt-4">Tekrar ihlal durumunda sınav otomatik olarak sonlanacaktır.</p>
            </div>
        </div>

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

                        <!-- Güvenlik Durumu -->
                        <div class="text-center">
                            <div id="securityStatus" class="text-lg font-bold text-green-600">🔒</div>
                            <div class="text-xs text-gray-600">Güvenli</div>
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
                                    class="bg-[#e63946] hover:bg-[#d52936] text-white px-3 md:px-4 py-2 rounded-lg text-xs md:text-sm font-medium transition 
                                           @if($isCompleting) opacity-50 cursor-not-allowed @endif">
                                <span wire:loading.remove wire:target="completeTest">Bitir</span>
                                <span wire:loading wire:target="completeTest">...</span>
                            </button>
                            
                            @if($isCompleting)
                                <button wire:click="$refresh" 
                                        class="bg-orange-500 hover:bg-orange-600 text-white px-2 md:px-3 py-2 rounded-lg text-xs font-medium transition">
                                    🔄
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

            <!-- Desktop: Yan yana / Mobile: Soru üstte, navigasyon altta -->
            <div class="lg:grid lg:grid-cols-4 lg:gap-6 space-y-6 lg:space-y-0">
                
                <!-- Desktop: Sol Taraf - Soru Navigasyonu -->
                <div class="order-2 lg:order-1 lg:col-span-1 hidden lg:block">
                    <div class="bg-white rounded-lg shadow-md p-4 border-2 border-[#1a2e5a] sticky top-24">
                        <h3 class="font-bold text-[#1a2e5a] mb-4">📋 Sorular</h3>
                        <div class="grid grid-cols-4 gap-2 mb-4">
                            @foreach($questions as $index => $question)
                                <button wire:click="goToQuestion({{ $index }})"
                                        class="w-10 h-10 rounded-lg text-sm font-medium transition
                                            @if($index == $currentQuestionIndex)
                                                bg-[#1a2e5a] text-white shadow-lg
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

                        <!-- Desktop Navigasyon Butonları -->
                        <div class="space-y-2">
                            <button wire:click="previousQuestion" 
                                    @if($currentQuestionIndex == 0) disabled @endif
                                    class="w-full flex items-center justify-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Önceki
                            </button>

                            <button wire:click="nextQuestion" 
                                    @if($currentQuestionIndex == count($questions) - 1) disabled @endif
                                    class="w-full flex items-center justify-center px-4 py-2 bg-[#1a2e5a] text-white rounded-lg hover:bg-[#0f1b3d] transition disabled:opacity-50 disabled:cursor-not-allowed">
                                Sonraki
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Ana Soru İçeriği -->
                <div class="order-1 lg:order-2 lg:col-span-3">
                    @if($currentQuestion)
                        <div class="bg-white rounded-lg shadow-md p-4 md:p-6 border-2 border-[#1a2e5a]" wire:key="question-{{ $currentQuestion['id'] }}">
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
                                <div class="text-gray-800 leading-relaxed text-base md:text-lg">
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
                                         class="border-2 rounded-lg p-3 md:p-4 cursor-pointer transition
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

                            <!-- Mobil Navigasyon Butonları (Soru içeriği altında) -->
                            <div class="flex justify-between mt-6 lg:hidden">
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

                <!-- Soru Navigasyonu (Sadece Mobilde görünür) -->
                <div class="order-2 lg:hidden">
                    <div class="bg-white rounded-lg shadow-md p-4 border-2 border-[#1a2e5a]">
                        <h3 class="font-bold text-[#1a2e5a] mb-4 text-center">📋 Soru Navigasyonu</h3>
                        
                        <!-- Mobilde 6 sütun, Tablet'te 8 sütun -->
                        <div class="grid grid-cols-6 sm:grid-cols-8 gap-2">
                            @foreach($questions as $index => $question)
                                <button wire:click="goToQuestion({{ $index }})"
                                        class="w-10 h-10 rounded-lg text-sm font-medium transition
                                            @if($index == $currentQuestionIndex)
                                                bg-[#1a2e5a] text-white shadow-lg transform scale-110
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

                        <!-- Mobilde legend -->
                        <div class="mt-4 text-center">
                            <div class="flex justify-center space-x-4 text-xs">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-[#1a2e5a] rounded mr-1"></div>
                                    <span>Şu an</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-100 border border-green-300 rounded mr-1"></div>
                                    <span>Cevaplı</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-gray-100 rounded mr-1"></div>
                                    <span>Boş</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Güvenlik JavaScript'i -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let violationCount = 0;
                let maxViolations = 2; // 2 uyarıdan sonra otomatik sonlandır
                let isExamActive = true;
                let hasWarningModalShown = false;
                
                const modal = document.getElementById('securityWarningModal');
                const continueBtn = document.getElementById('continueExamBtn');
                const endBtn = document.getElementById('endExamBtn');
                const securityStatus = document.getElementById('securityStatus');
                
                // Tam ekran modunu zorla
                function enterFullscreen() {
                    if (document.documentElement.requestFullscreen) {
                        document.documentElement.requestFullscreen();
                    } else if (document.documentElement.mozRequestFullScreen) {
                        document.documentElement.mozRequestFullScreen();
                    } else if (document.documentElement.webkitRequestFullscreen) {
                        document.documentElement.webkitRequestFullscreen();
                    } else if (document.documentElement.msRequestFullscreen) {
                        document.documentElement.msRequestFullscreen();
                    }
                }
                
                // Tam ekran modu kontrol et
                function isFullscreen() {
                    return !!(document.fullscreenElement || document.mozFullScreenElement || 
                             document.webkitFullscreenElement || document.msFullscreenElement);
                }
                
                // Güvenlik ihlali fonksiyonu
                function handleSecurityViolation(reason) {
                    if (!isExamActive) return;
                    
                    violationCount++;
                    console.log(`Güvenlik ihlali: ${reason} (${violationCount}/${maxViolations})`);
                    
                    // Livewire 3'te event gönderme
                    if (window.Livewire) {
                        window.Livewire.dispatch('handleSecurityViolation', { reason: reason });
                    }
                    
                    // Güvenlik durumunu güncelle
                    updateSecurityStatus();
                    
                    if (violationCount >= maxViolations) {
                        // Otomatik sonlandır
                        endExamAutomatically(reason);
                    } else {
                        // Uyarı göster
                        showWarningModal(reason);
                    }
                }
                
                // Güvenlik durumunu güncelle
                function updateSecurityStatus() {
                    if (violationCount === 0) {
                        securityStatus.innerHTML = '🔒';
                        securityStatus.className = 'text-lg font-bold text-green-600';
                        securityStatus.nextElementSibling.textContent = 'Güvenli';
                    } else if (violationCount === 1) {
                        securityStatus.innerHTML = '⚠️';
                        securityStatus.className = 'text-lg font-bold text-yellow-600';
                        securityStatus.nextElementSibling.textContent = 'Uyarı';
                    } else {
                        securityStatus.innerHTML = '🚨';
                        securityStatus.className = 'text-lg font-bold text-red-600';
                        securityStatus.nextElementSibling.textContent = 'Tehlike';
                    }
                }
                
                // Uyarı modalını göster
                function showWarningModal(reason) {
                    if (hasWarningModalShown) return;
                    hasWarningModalShown = true;
                    
                    modal.querySelector('h2').textContent = `⚠️ Güvenlik İhlali: ${reason}`;
                    modal.classList.remove('hidden');
                    
                    // 10 saniye sonra otomatik devam et
                    setTimeout(() => {
                        if (!modal.classList.contains('hidden')) {
                            modal.classList.add('hidden');
                            hasWarningModalShown = false;
                        }
                    }, 10000);
                }
                
                // Sınavı otomatik sonlandır
                function endExamAutomatically(reason) {
                    isExamActive = false;
                    alert(`Sınav sonlandırıldı: ${reason}\nMaximum güvenlik ihlali sayısına ulaşıldı.`);
                    
                    // Livewire ile sınavı sonlandır
                    if (window.Livewire) {
                        Livewire.dispatch('forceCompleteTest', reason);
                    }
                    
                    // 3 saniye sonra sayfayı yönlendir (Livewire işlemi tamamlanana kadar bekle)
                    setTimeout(() => {
                        if (window.location.pathname.includes('/test-taking/')) {
                            window.location.href = '/ogrenci/test-categories';
                        }
                    }, 3000);
                }
                
                // Modal buton olayları
                continueBtn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    hasWarningModalShown = false;
                    enterFullscreen(); // Tekrar tam ekrana geç
                });
                
                endBtn.addEventListener('click', () => {
                    endExamAutomatically('Kullanıcı tarafından sonlandırıldı');
                });
                
                // Tam ekran modu kontrolleri
                document.addEventListener('fullscreenchange', () => {
                    if (!isFullscreen() && isExamActive) {
                        handleSecurityViolation('Tam ekran modundan çıkış');
                    }
                });
                
                // Visibility API - Sekme değiştirme/minimize etme
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden && isExamActive) {
                        handleSecurityViolation('Sekme değiştirme/Tarayıcı minimize');
                    }
                });
                
                // Page Visibility - Fokus kaybı
                window.addEventListener('blur', () => {
                    if (isExamActive) {
                        handleSecurityViolation('Pencere fokus kaybı');
                    }
                });
                
                // Alt+Tab ve diğer kısayol tuşları
                document.addEventListener('keydown', (e) => {
                    if (!isExamActive) return;
                    
                    // Alt+Tab
                    if (e.altKey && e.key === 'Tab') {
                        e.preventDefault();
                        handleSecurityViolation('Alt+Tab kısayolu');
                        return false;
                    }
                    
                    // F12 (Developer Tools)
                    if (e.key === 'F12') {
                        e.preventDefault();
                        handleSecurityViolation('Developer Tools açılması (F12)');
                        return false;
                    }
                    
                    // Ctrl+Shift+I (Developer Tools)
                    if (e.ctrlKey && e.shiftKey && e.key === 'I') {
                        e.preventDefault();
                        handleSecurityViolation('Developer Tools açılması (Ctrl+Shift+I)');
                        return false;
                    }
                    
                    // Ctrl+Shift+J (Console)
                    if (e.ctrlKey && e.shiftKey && e.key === 'J') {
                        e.preventDefault();
                        handleSecurityViolation('Console açılması (Ctrl+Shift+J)');
                        return false;
                    }
                    
                    // Ctrl+U (View Source)
                    if (e.ctrlKey && e.key === 'u') {
                        e.preventDefault();
                        handleSecurityViolation('Kaynak kodunu görüntüleme (Ctrl+U)');
                        return false;
                    }
                    
                    // Ctrl+S (Save)
                    if (e.ctrlKey && e.key === 's') {
                        e.preventDefault();
                        handleSecurityViolation('Sayfayı kaydetme girişimi (Ctrl+S)');
                        return false;
                    }
                    
                    // Windows tuşu
                    if (e.key === 'Meta' || e.key === 'Super') {
                        e.preventDefault();
                        handleSecurityViolation('Windows tuşu');
                        return false;
                    }
                });
                
                // Sağ tık menüsünü engelle
                document.addEventListener('contextmenu', (e) => {
                    if (isExamActive) {
                        e.preventDefault();
                        handleSecurityViolation('Sağ tık menüsü');
                        return false;
                    }
                });
                
                // Console açılmasını tespit et (DevTools Detection)
                let devtools = {open: false, orientation: null};
                setInterval(() => {
                    if (!isExamActive) return;
                    
                    if (window.outerHeight - window.innerHeight > 200 || 
                        window.outerWidth - window.innerWidth > 200) {
                        if (!devtools.open) {
                            devtools.open = true;
                            handleSecurityViolation('Developer Tools tespit edildi');
                        }
                    } else {
                        devtools.open = false;
                    }
                }, 500);
                
                // Mobil cihazlarda app switch detection
                let lastActiveTime = Date.now();
                setInterval(() => {
                    if (!isExamActive) return;
                    
                    if (Date.now() - lastActiveTime > 5000 && !document.hidden) {
                        // 5 saniyeden fazla inaktiflik
                        handleSecurityViolation('Uygulama değiştirme (mobil)');
                    }
                }, 1000);
                
                // Mouse/touch aktivitesini takip et
                ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'].forEach(event => {
                    document.addEventListener(event, () => {
                        lastActiveTime = Date.now();
                    }, { passive: true });
                });
                
                // Sayfa yüklendiğinde tam ekrana geç
                setTimeout(() => {
                    enterFullscreen();
                }, 1000);
                
                // Sayfa kapatılmaya çalışıldığında uyar
                window.addEventListener('beforeunload', (e) => {
                    if (isExamActive) {
                        e.preventDefault();
                        e.returnValue = 'Sınav devam ediyor. Çıkmak istediğinizden emin misiniz?';
                        return e.returnValue;
                    }
                });
                
                // Print screen engelleme (tam olarak engellenemez ama tespit edilebilir)
                document.addEventListener('keyup', (e) => {
                    if (e.key === 'PrintScreen' && isExamActive) {
                        handleSecurityViolation('Print Screen tuşu');
                    }
                });
                
                // Clipboard operations engelleme
                document.addEventListener('copy', (e) => {
                    if (isExamActive) {
                        e.preventDefault();
                        handleSecurityViolation('Kopyalama girişimi');
                    }
                });
                
                document.addEventListener('paste', (e) => {
                    if (isExamActive) {
                        e.preventDefault();
                        handleSecurityViolation('Yapıştırma girişimi');
                    }
                });
                
                // Mouse selection engelleme
                document.addEventListener('selectstart', (e) => {
                    if (isExamActive) {
                        e.preventDefault();
                        return false;
                    }
                });
                
                // Drag & drop engelleme
                document.addEventListener('dragstart', (e) => {
                    if (isExamActive) {
                        e.preventDefault();
                        return false;
                    }
                });
                
                console.log('Sınav güvenlik sistemi aktif edildi.');
            });
        </script>
    @endif
</div>