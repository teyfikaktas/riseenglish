class GameScene extends Phaser.Scene {
    constructor() {
        super({ key: 'GameScene' });
        this.score = 0;
        this.timeLeft = 60;
    }

    create() {
        // Arka plan
        this.add.rectangle(400, 300, 800, 600, 0x1e1b4b);
        
        // Score text
        this.scoreText = this.add.text(16, 16, 'Score: 0', {
            fontSize: '32px',
            fill: '#ffffff'
        });

        // Timer
        this.timerText = this.add.text(16, 60, 'Time: 60', {
            fontSize: '32px', 
            fill: '#ffffff'
        });

        // İlk soruyu başlat
        this.nextQuestion();
        
        // Timer başlat
        this.gameTimer = this.time.addEvent({
            delay: 1000,
            callback: this.updateTimer,
            callbackScope: this,
            loop: true
        });
    }

    nextQuestion() {
        // Mevcut Livewire mantığını buraya taşı
        // Bubble'ları oluştur
        // Click events ekle
    }
}

window.GameScene = GameScene;