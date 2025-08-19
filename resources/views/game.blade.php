@extends('layouts.app')

@section('title', 'Kelime Blast')

@section('content')
<div class="fixed inset-0 w-full h-full bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 overflow-hidden">
    <!-- Game Container -->
    <div id="game-container" class="absolute inset-0 w-full h-full flex items-center justify-center">
        <!-- Loading -->
        <div id="loading" class="text-center text-white">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white mx-auto mb-4"></div>
            <h2 class="text-xl font-bold">Kelime Blast Yükleniyor...</h2>
            <p class="text-sm opacity-70">Lütfen bekleyin</p>
        </div>
    </div>

    <!-- Fullscreen Button -->
    <button id="fullscreen-btn" class="fixed top-5 right-5 bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg z-50">
        📱
    </button>
</div>
@endsection

@push('styles')
<style>
/* Sayfayı tamamen sıfırla */
html, body {
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
    height: 100% !important;
    overflow: hidden !important;
}

/* Layout wrapper'ları sıfırla */
.min-h-screen, .container, .max-w-7xl {
    margin: 0 !important;
    padding: 0 !important;
    max-width: none !important;
}

/* Game container */
#game-container {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    margin: 0 !important;
    padding: 0 !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    z-index: 10 !important;
}

/* Canvas tam ekran */
#game-container canvas {
    width: 100vw !important;
    height: 100vh !important;
    max-width: 100vw !important;
    max-height: 100vh !important;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    object-fit: contain !important;
}

/* Loading ekranı */
#loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 100;
}

/* Fullscreen button */
#fullscreen-btn {
    position: fixed !important;
    top: 20px !important;
    right: 20px !important;
    z-index: 1000 !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sayfa yüklendi');
    
    if (typeof Phaser === 'undefined') {
        console.error('Phaser yüklenemedi!');
        document.getElementById('loading').innerHTML = '<div class="text-red-500">Hata: Phaser yüklenemedi!</div>';
        return;
    }

    if (typeof window.GameConfig === 'undefined') {
        console.error('GameConfig bulunamadı!');
        document.getElementById('loading').innerHTML = '<div class="text-red-500">Hata: Oyun konfigürasyonu bulunamadı!</div>';
        return;
    }

    try {
        // Tam ekran config
        const config = {
            ...window.GameConfig,
            parent: 'game-container',
            width: window.innerWidth,
            height: window.innerHeight,
            scale: {
                mode: Phaser.Scale.RESIZE,
                autoCenter: Phaser.Scale.CENTER_BOTH
            }
        };

        const game = new Phaser.Game(config);
        
        game.events.once('ready', function() {
            document.getElementById('loading').style.display = 'none';
            console.log('Oyun tam ekran yüklendi!');
        });

        // Resize event
        window.addEventListener('resize', function() {
            if (game && game.scale) {
                game.scale.resize(window.innerWidth, window.innerHeight);
            }
        });

        document.getElementById('fullscreen-btn').addEventListener('click', function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        });

    } catch (error) {
        console.error('Oyun hatası:', error);
        document.getElementById('loading').innerHTML = '<div class="text-red-500">Oyun başlatılamadı: ' + error.message + '</div>';
    }
});
</script>
@endpush