<div class="min-h-screen relative overflow-hidden" style="background: radial-gradient(ellipse at center, #1e1b4b 0%, #0f0c29 50%, #0a0614 100%);">
    
    <!-- Animated Stars Background -->
    <div class="absolute inset-0 z-0">
        @for($i = 0; $i < 80; $i++)
            <div class="absolute animate-pulse" 
                 style="left: {{ rand(0, 100) }}%; top: {{ rand(0, 100) }}%; 
                        animation-delay: {{ rand(0, 3000) }}ms;
                        animation-duration: {{ rand(2000, 4000) }}ms;">
                <div class="w-1 h-1 bg-indigo-400 rounded-full opacity-{{ rand(30, 70) }}"></div>
            </div>
        @endfor
        
        @for($i = 0; $i < 12; $i++)
            <div class="absolute text-indigo-300 opacity-40 animate-ping text-xs" 
                 style="left: {{ rand(0, 100) }}%; top: {{ rand(0, 100) }}%; 
                        animation-delay: {{ rand(0, 5000) }}ms; 
                        animation-duration: {{ rand(2000, 4000) }}ms;">
                ✦
            </div>
        @endfor
    </div>

    <div class="relative z-10 h-screen">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-5">
            <div class="text-white text-xl font-medium opacity-90">
                @if($gameStarted && ! empty($currentWord))
                    {{ $currentWord['english'] }}
                @else
                    Kelime Blast
                @endif
            </div>
            
            <div class="flex items-center space-x-3">
                @if($gameStarted)
                    <div class="bg-slate-800/60 backdrop-blur-sm rounded-full px-4 py-2 border border-red-400/30">
                        <div class="text-white text-sm font-medium">{{ $timeLeft }}s</div>
                    </div>
                    @if($streak > 0)
                        <div class="bg-orange-600/60 backdrop-blur-sm rounded-full px-4 py-2 border border-orange-400/30">
                            <div class="text-white text-sm font-medium">{{ $streak }}x</div>
                        </div>
                    @endif
                @endif
                
                <div class="bg-green-600/60 backdrop-blur-sm rounded-full px-4 py-2 border border-green-400/30">
                    <div class="text-white text-sm font-medium">{{ $score }}</div>
                </div>
            </div>
        </div>

        <!-- Dil Seçim Ekranı -->
        @if(! $gameStarted && ! $gameFinished && ! $languageSelected)
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center bg-slate-900/30 backdrop-blur-md rounded-2xl p-8 border border-white/10 shadow-2xl max-w-md">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl">🌍</span>
                        </div>
                        <h2 class="text-3xl font-light text-white mb-2">Dil Seçin</h2>
                        <p class="text-white/70">Hangi dilde kelime öğrenmek istiyorsunuz?</p>
                    </div>
                    
                    <div class="space-y-3">
                        <button wire:click="selectLanguage('en')" 
                                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700
                                       text-white px-6 py-4 rounded-xl font-semibold text-lg transition-all duration-300
                                       transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center space-x-3">
                            <span class="text-2xl">🇬🇧</span>
                            <span>İngilizce</span>
                        </button>
                        
                        <button wire:click="selectLanguage('de')" 
                                class="w-full bg-gradient-to-r from-red-500 to-orange-600 hover:from-red-600 hover:to-orange-700
                                       text-white px-6 py-4 rounded-xl font-semibold text-lg transition-all duration-300
                                       transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center space-x-3">
                            <span class="text-2xl">🇩🇪</span>
                            <span>Almanca</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Zorluk Seçim Ekranı -->
        @if(! $gameStarted && ! $gameFinished && $languageSelected && ! $difficultySelected)
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center bg-slate-900/30 backdrop-blur-md rounded-2xl p-8 border border-white/10 shadow-2xl max-w-md">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl">🎯</span>
                        </div>
                        <h2 class="text-3xl font-light text-white mb-2">Zorluk Seviyesi</h2>
                        <p class="text-white/70">{{ $selectedLanguage == 'en' ? 'İngilizce' : 'Almanca' }} için seviyenizi seçin</p>
                    </div>
                    
                    <div class="space-y-3">
                        @if(in_array('beginner', $availableDifficulties))
                            <button wire:click="selectDifficulty('beginner')" 
                                    class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700
                                           text-white px-6 py-4 rounded-xl font-semibold text-lg transition-all duration-300
                                           transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center space-x-3">
                                <span class="text-2xl">🌱</span>
                                <div class="text-left">
                                    <div>Başlangıç</div>
                                    <div class="text-sm opacity-80">Temel kelimeler</div>
                                </div>
                            </button>
                        @endif
                        
                        @if(in_array('intermediate', $availableDifficulties))
                            <button wire:click="selectDifficulty('intermediate')" 
                                    class="w-full bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700
                                           text-white px-6 py-4 rounded-xl font-semibold text-lg transition-all duration-300
                                           transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center space-x-3">
                                <span class="text-2xl">⚡</span>
                                <div class="text-left">
                                    <div>Orta</div>
                                    <div class="text-sm opacity-80">Orta seviye kelimeler</div>
                                </div>
                            </button>
                        @endif
                        
                        @if(in_array('advanced', $availableDifficulties))
                            <button wire:click="selectDifficulty('advanced')" 
                                    class="w-full bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700
                                           text-white px-6 py-4 rounded-xl font-semibold text-lg transition-all duration-300
                                           transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center space-x-3">
                                <span class="text-2xl">🏆</span>
                                <div class="text-left">
                                    <div>İleri</div>
                                    <div class="text-sm opacity-80">Zor kelimeler</div>
                                </div>
                            </button>
                        @endif
                        
                        <button wire:click="selectDifficulty('all')" 
                                class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700
                                       text-white px-6 py-4 rounded-xl font-semibold text-lg transition-all duration-300
                                       transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center space-x-3">
                            <span class="text-2xl">🎲</span>
                            <div class="text-left">
                                <div>Karışık</div>
                                <div class="text-sm opacity-80">Tüm seviyeler</div>
                            </div>
                        </button>
                    </div>
                    
                    <button wire:click="goBackToLanguage" 
                            class="mt-4 text-white/60 hover:text-white text-sm transition-colors duration-300">
                        ← Dil seçimine dön
                    </button>
                </div>
            </div>
        @endif

        <!-- Oyun Başlangıç Ekranı -->
        @if(! $gameStarted && ! $gameFinished && $languageSelected && $difficultySelected)
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center">
                    <h1 class="text-5xl font-light mb-6 bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">
                        Kelime Blast
                    </h1>
                    <div class="mb-4 text-white/70">
                        <div class="text-lg mb-2">
                            {{ $selectedLanguage == 'en' ? '🇬🇧 İngilizce' : '🇩🇪 Almanca' }} - 
                            @if($selectedDifficulty == 'beginner') 🌱 Başlangıç
                            @elseif($selectedDifficulty == 'intermediate') ⚡ Orta
                            @elseif($selectedDifficulty == 'advanced') 🏆 İleri
                            @else 🎲 Karışık
                            @endif
                        </div>
                        <div class="text-sm opacity-60">Veritabanından rastgele kelimeler ile yarış!</div>
                    </div>
                    <button wire:click="startGame" 
                            class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700
                                   text-white px-8 py-4 rounded-full font-semibold text-lg transition-all duration-300
                                   transform hover:scale-105 shadow-lg hover:shadow-xl">
                        Oyunu Başlat
                    </button>
                    <div class="mt-4">
                        <button wire:click="resetSelections" 
                                class="text-white/60 hover:text-white text-sm transition-colors duration-300">
                            ← Seçimleri değiştir
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- End Screen -->
        @if($gameFinished)
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center bg-slate-900/30 backdrop-blur-md rounded-2xl p-8 border border-white/10 shadow-2xl">
                    <h2 class="text-3xl font-light text-white mb-6">Oyun Bitti</h2>
                    
                    <div class="mb-4 text-white/70 text-sm">
                        {{ $selectedLanguage == 'en' ? '🇬🇧 İngilizce' : '🇩🇪 Almanca' }} - 
                        @if($selectedDifficulty == 'beginner') 🌱 Başlangıç
                        @elseif($selectedDifficulty == 'intermediate') ⚡ Orta  
                        @elseif($selectedDifficulty == 'advanced') 🏆 İleri
                        @else 🎲 Karışık
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6 text-white text-sm">
                        <div class="bg-white/5 rounded-lg p-4 backdrop-blur-sm">
                            <div class="text-2xl font-bold">{{ $score }}</div>
                            <div class="opacity-70">Puan</div>
                        </div>
                        <div class="bg-white/5 rounded-lg p-4 backdrop-blur-sm">
                            <div class="text-2xl font-bold">{{ $correctAnswers }}/{{ $totalQuestions }}</div>
                            <div class="opacity-70">Doğru</div>
                        </div>
                    </div>
                    
                    <button wire:click="resetGame" 
                            class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700
                                   text-white px-6 py-3 rounded-full font-semibold transition-all duration-300 transform hover:scale-105">
                        Tekrar Oyna
                    </button>
                </div>
            </div>
        @endif

        <!-- Game Area & Bubbles -->
        @if($gameStarted && ! empty($currentWord))
            @if($showResult)
                <div class="absolute top-24 left-1/2 transform -translate-x-1/2 z-50">
                    <div class="bg-slate-900/90 backdrop-blur-xl text-white px-8 py-6 rounded-2xl text-center
                                border border-white/30 shadow-2xl">
                        <div class="text-xl font-medium mb-4">{{ $resultMessage }}</div>
                        <button wire:click="proceedToNextQuestion" 
                                class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700
                                       text-white px-8 py-4 rounded-full font-medium transition-all duration-300 transform hover:scale-105">
                            Sonraki Soru
                        </button>
                    </div>
                </div>
            @endif

            <div class="absolute left-4 right-4 top-32 bottom-24" style="padding: 80px; overflow: visible;">
                <div class="relative w-full h-full" id="game-area" style="min-height: 400px; overflow: visible;">
                    @foreach($currentOptions as $index => $option)
                        <div class="absolute bubble-item gsap-bubble-{{ $index }}"
                             style="left: {{ $option['x'] }}%; top: {{ $option['y'] }}%;
                                    transform: translate(-50%, -50%); z-index: 20;"
                             data-index="{{ $index }}">
                            
                            <!-- Animated Border Ring -->
                            <div class="absolute -inset-2 w-36 h-36 rounded-full animate-spin-slow opacity-80 ring-glow"
                                 style="background: conic-gradient(from 0deg, #6366f1, #8b5cf6, #a855f7, #6366f1);">
                                <div class="absolute inset-1 rounded-full bg-slate-900/50 backdrop-blur-sm"
                                     style="width: calc(100% - 8px); height: calc(100% - 8px); top: 4px; left: 4px;"></div>
                            </div>

                            <!-- Main Bubble Button -->
                            <button wire:click="selectAnswer('{{ base64_encode(json_encode($option)) }}')"
                                    wire:key="bubble-{{ $totalQuestions }}-{{ $index }}"
                                    class="relative w-32 h-32 bg-gradient-to-br from-indigo-500 via-purple-500 to-violet-600
                                           hover:from-indigo-400 hover:via-purple-400 hover:to-violet-500
                                           text-white font-bold rounded-full shadow-2xl transition-all duration-300
                                           hover:scale-125 hover:shadow-3xl border-3 border-white/30
                                           flex items-center justify-center text-center z-30 bubble-pulse
                                           @if($nextWordDelay || $showResult) pointer-events-none opacity-60 @endif"
                                    style="box-shadow: 0 15px 50px rgba(99,102,241,0.6), inset 0 4px 15px rgba(255,255,255,0.15);
                                           text-shadow: 0 3px 10px rgba(0,0,0,0.7);
                                           filter: drop-shadow(0 0 20px rgba(139,92,246,0.4));">
                                
                                <div class="absolute top-4 left-6 w-8 h-6 bg-white/40 rounded-full blur-md animate-pulse"></div>
                                <span class="relative z-10 leading-tight px-2 text-sm font-bold text-shadow-strong">
                                    {{ $option['text'] }}
                                </span>
                            </button>

                            <!-- Glow & Particles -->
                            <div class="absolute inset-0 rounded-full bg-gradient-to-br from-indigo-400/30 to-purple-600/30 blur-2xl scale-150 opacity-70 animate-pulse"></div>
                            <div class="absolute inset-0 rounded-full bg-gradient-to-tl from-violet-400/20 to-pink-500/20 blur-xl scale-125 opacity-60 animate-ping" style="animation-delay: 0.5s;"></div>
                            <div class="particle particle-1"></div>
                            <div class="particle particle-2"></div>
                            <div class="particle particle-3"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Bottom Shooter Character -->
        <div class="fixed bottom-20 left-1/2 transform -translate-x-1/2 z-20">
            <div class="relative">
                <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 to-green-500 rounded-full border-3 border-white/30 flex items-center justify-center shadow-2xl"
                     style="box-shadow: 0 0 40px rgba(16,185,129,0.5);">
                    <div class="text-3xl">🎯</div>
                </div>
                <div class="absolute inset-0 rounded-full bg-emerald-400 opacity-20 blur-xl scale-150 animate-pulse"></div>
                <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-24 h-3 bg-gradient-to-r from-transparent via-white/10 to-transparent rounded-full"></div>
            </div>
        </div>

        <!-- Bottom Info Bar -->
        <div class="fixed bottom-0 left-0 right-0 bg-slate-900/40 backdrop-blur-md p-4 border-t border-white/10 z-10">
            <div class="flex items-center justify-between text-white text-sm">
                <div class="opacity-80">Soru: {{ $totalQuestions }} | Doğru: {{ $correctAnswers }}</div>
                <div class="opacity-80">Başarı: {{ $this->getAccuracy() }}%</div>
            </div>
        </div>
    </div>

    <!-- GSAP CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    
    <!-- Ana Oyun Script'i -->
    <script>
        window.kelimeBlast = { timer: null, initialized: false };

        function startGameTimer() {
            if (window.kelimeBlast.timer) {
                clearInterval(window.kelimeBlast.timer);
            }
            window.kelimeBlast.timer = setInterval(() => {
                @this.call('updateTimer');
            }, 1000);
            console.log('⏰ Timer başlatıldı');
        }

        function animateBubbles() {
            const area = document.getElementById('game-area');
            const bubbles = document.querySelectorAll('.bubble-item');
            if (!area || bubbles.length === 0 || typeof gsap === 'undefined') {
                console.log('❌ Animasyon için element veya gsap yok');
                return false;
            }
            bubbles.forEach((b, i) => {
                function move() {
                    const range = 80,
                          x = (Math.random() - 0.5) * 2 * range,
                          y = (Math.random() - 0.5) * 2 * range,
                          dur = 2 + Math.random() * 3;
                    gsap.to(b, { x, y, duration: dur, ease: 'power2.inOut', onComplete: move });
                }
                setTimeout(move, i * 200);
                gsap.to(b, { scale:1.02, duration:3, repeat:-1, yoyo:true, ease:'sine.inOut', delay:i*0.1 });
            });
            console.log(`✅ ${bubbles.length} balon animasyonu başlatıldı`);
            return true;
        }

        function initKelimeBlast() {
            if (window.kelimeBlast.initialized) return;
            console.log('🚀 init', { started: {{ $gameStarted ? 'true' : 'false' }} });
            @if($gameStarted)
                startGameTimer();
            @endif
            setTimeout(() => {
                if (animateBubbles()) window.kelimeBlast.initialized = true;
            }, 300);
        }

        function restartBlast() {
            window.kelimeBlast.initialized = false;
            @if($gameStarted)
                startGameTimer();
            @endif
            setTimeout(animateBubbles, 200);
        }

        document.addEventListener('DOMContentLoaded', () => setTimeout(initKelimeBlast, 100));
        document.addEventListener('livewire:init',    () => setTimeout(initKelimeBlast, 100));
        document.addEventListener('livewire:navigated',() => restartBlast());
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph.updated', () => restartBlast());
        }
        if (document.readyState !== 'loading') {
            setTimeout(initKelimeBlast, 100);
        }
    </script>

    <!-- CSS (tam eksiksiz) -->
    <style>
        /* GSAP animasyonları için optimize edilmiş bubble stili */
        .bubble-item {
            will-change: transform;
            backface-visibility: hidden;
            position: absolute !important;
            transform: translate(-50%, -50%) !important;
            pointer-events: auto;
            transition: none !important;
        }
        #game-area {
            position: relative !important;
            width: 100% !important;
            height: 100% !important;
            min-height: 400px !important;
            overflow: visible !important;
        }
        .bubble-item:hover {
            transform: translate(-50%, -50%) scale(1.1) !important;
            z-index: 100 !important;
        }
        .bubble-item:hover button {
            transform: scale(1.15) rotate(5deg) !important;
            box-shadow: 0 25px 70px rgba(99,102,241,0.9),
                        inset 0 6px 25px rgba(255,255,255,0.25),
                        0 0 60px rgba(139,92,246,0.8) !important;
        }
        .bubble-pulse { animation: bubblePulse 6s ease-in-out infinite; }
        @keyframes bubblePulse {
            0%,100% { box-shadow:0 15px 50px rgba(99,102,241,0.5), inset 0 4px 15px rgba(255,255,255,0.12), 0 0 25px rgba(139,92,246,0.3); }
            50%   { box-shadow:0 18px 55px rgba(99,102,241,0.6), inset 0 5px 18px rgba(255,255,255,0.15), 0 0 35px rgba(139,92,246,0.4); }
        }
        .ring-glow { filter: drop-shadow(0 0 12px rgba(99,102,241,0.4)); animation:ringPulse 8s ease-in-out infinite; }
        @keyframes ringPulse {
            0%,100% { filter:drop-shadow(0 0 12px rgba(99,102,241,0.4)); opacity:0.75; }
            50%   { filter:drop-shadow(0 0 18px rgba(99,102,241,0.6)); opacity:0.9; }
        }
        .particle { position:absolute; width:3px; height:3px; background:radial-gradient(circle,#a855f7,#6366f1); border-radius:50%; pointer-events:none; opacity:0.6; }
        .particle-1 { top:-8px; left:25%; animation:particleFloat1 8s ease-in-out infinite; }
        .particle-2 { top:60%; right:-6px; animation:particleFloat2 6s ease-in-out infinite; animation-delay:2s; }
        .particle-3 { bottom:-6px; left:75%; animation:particleFloat3 10s ease-in-out infinite; animation-delay:4s; }
        @keyframes particleFloat1 {
            0%,100% { transform:translateY(0) translateX(0) scale(1); opacity:0.4; }
            50%   { transform:translateY(-8px) translateX(3px) scale(1.2); opacity:0.7; }
        }
        @keyframes particleFloat2 {
            0%,100% { transform:translateY(0) translateX(0) scale(1); opacity:0.3; }
            50%   { transform:translateY(6px) translateX(-4px) scale(1.1); opacity:0.6; }
        }
        @keyframes particleFloat3 {
            0%,100% { transform:translateY(0) translateX(0) scale(1); opacity:0.5; }
            50%   { transform:translateY(-6px) translateX(-2px) scale(1.15); opacity:0.8; }
        }
        .text-shadow-strong { text-shadow:0 4px 12px rgba(0,0,0,0.8),0 2px 6px rgba(0,0,0,0.6); }
        .animate-spin-slow { animation:spin-slow 6s linear infinite; }
        @keyframes spin-slow { from{transform:rotate(0)} to{transform:rotate(360deg)} }
        .bubble-item * { will-change:auto; }
        @media (max-width:768px) {
            .bubble-item button { width:28px; height:28px; font-size:12px; }
            .particle { width:2px; height:2px; }
        }
        .game-paused .bubble-item { animation-play-state:paused; }
    </style>
</div>