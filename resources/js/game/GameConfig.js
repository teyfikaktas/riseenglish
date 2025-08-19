// Oyun Konfigürasyonu
window.GameConfig = {
    type: Phaser.AUTO,
    width: window.innerWidth * 0.9,  // Ekranın %90'ı
    height: window.innerHeight * 0.9, // Ekranın %90'ı
    backgroundColor: '#1e1b4b',
    scale: {
        mode: Phaser.Scale.FIT,
        autoCenter: Phaser.Scale.CENTER_BOTH,
        min: {
            width: 600,   // Minimum genişlik artırıldı
            height: 400   // Minimum yükseklik artırıldı
        },
        max: {
            width: 1400,  // Maximum genişlik artırıldı
            height: 900   // Maximum yükseklik artırıldı
        }
    },
    physics: {
        default: 'arcade',
        arcade: {
            gravity: { y: 0 },
            debug: false
        }
    },
    scene: [MenuScene, GameScene, GameOverScene]
};