// resources/js/game.js - Kelime Blast Phaser Oyunu (Tamamen Yeniden)

// Kelime verileri
window.WordData = []; // Boş başlat

// Dil Seçim Sahnesi
class LanguageSelectionScene extends Phaser.Scene {
    constructor() {
        super({ key: 'LanguageSelectionScene' });
    }
    
    async create() {
        const { width, height } = this.scale;
        
        this.add.rectangle(width/2, height/2, width, height, 0x1e1b4b);
        this.createStars();
        
        this.add.text(width/2, height * 0.25, 'Dil Seçin', {
            fontSize: '48px',
            fill: '#a855f7',
            fontFamily: 'Arial',
            fontStyle: 'bold'
        }).setOrigin(0.5);
        
        const loadingText = this.add.text(width/2, height/2, 'Diller yükleniyor...', {
            fontSize: '24px',
            fill: '#ffffff',
            fontFamily: 'Arial'
        }).setOrigin(0.5);
        
        const languages = await WordAPI.getLanguages();
        loadingText.destroy();
        
        this.createLanguageButtons(languages);
    }
    
    createLanguageButtons(languages) {
        const { width, height } = this.scale;
        
        const languageNames = {
            'en': 'İngilizce',
            'de': 'Almanca',
            'tr': 'Türkçe'
        };
        
        languages.forEach((lang, index) => {
            const x = width/2;
            const y = height * 0.4 + (index * 80);
            
            const button = this.add.rectangle(x, y, 300, 60, 0x6366f1)
                .setStrokeStyle(3, 0xffffff)
                .setInteractive({ useHandCursor: true });
            
            const buttonText = this.add.text(x, y, languageNames[lang] || lang, {
                fontSize: '24px',
                fill: '#ffffff',
                fontFamily: 'Arial',
                fontStyle: 'bold'
            }).setOrigin(0.5);
            
            button.on('pointerdown', () => {
                this.scene.start('CategorySelectionScene', { selectedLanguage: lang });
            });
        });
    }
    
    createStars() {
        for (let i = 0; i < 50; i++) {
            const x = Phaser.Math.Between(0, this.scale.width);
            const y = Phaser.Math.Between(0, this.scale.height);
            const star = this.add.circle(x, y, Phaser.Math.Between(1, 3), 0xa855f7, 0.7);
            
            this.tweens.add({
                targets: star,
                alpha: 0.2,
                duration: Phaser.Math.Between(2000, 4000),
                yoyo: true,
                repeat: -1
            });
        }
    }
}
class CategorySelectionScene extends Phaser.Scene {
    constructor() {
        super({ key: 'CategorySelectionScene' });
    }
    
    init(data) {
        this.selectedLanguage = data.selectedLanguage;
    }
    
    async create() {
        const { width, height } = this.scale;
        
        this.add.rectangle(width/2, height/2, width, height, 0x1e1b4b);
        this.createStars();
        
        this.add.text(width/2, height * 0.15, 'Kategori Seçin', {
            fontSize: '42px',
            fill: '#a855f7',
            fontFamily: 'Arial',
            fontStyle: 'bold'
        }).setOrigin(0.5);
        
        const loadingText = this.add.text(width/2, height/2, 'Kategoriler yükleniyor...', {
            fontSize: '24px',
            fill: '#ffffff',
            fontFamily: 'Arial'
        }).setOrigin(0.5);
        
        const categories = await WordAPI.getCategories(this.selectedLanguage);
        loadingText.destroy();
        
        this.createScrollableCategories(categories);
        this.createBackButton();
    }
    
createScrollableCategories(categories) {
    const { width, height } = this.scale;
    
    const scrollAreaY = height * 0.25;
    const scrollAreaHeight = height * 0.65;
    const buttonHeight = 80;
    const topPadding = 40; // ✅ Üstten boşluk
    
    // ✅ CONTAINER
    this.categoryContainer = this.add.container(0, scrollAreaY);
    
    // ✅ KATEGORİLERİ EKLE
    categories.forEach((category, index) => {
        const y = topPadding + (index * buttonHeight); // ✅ topPadding ile başla
        
        const button = this.add.rectangle(width/2, y, 400, 70, parseInt(category.color.replace('#', '0x')))
            .setStrokeStyle(3, 0xffffff)
            .setInteractive({ useHandCursor: true });
        
        const nameText = this.add.text(width/2 - 150, y, category.name, {
            fontSize: '22px',
            fill: '#ffffff',
            fontFamily: 'Arial',
            fontStyle: 'bold'
        }).setOrigin(0, 0.5);
        
        const setCountText = this.add.text(width/2 + 150, y, `${category.total_sets} Set`, {
            fontSize: '18px',
            fill: '#ffffff',
            fontFamily: 'Arial'
        }).setOrigin(1, 0.5);
        
        this.categoryContainer.add([button, nameText, setCountText]);
        
        button.on('pointerdown', () => {
            this.scene.start('SetSelectionScene', { 
                selectedLanguage: this.selectedLanguage,
                category: category
            });
        });
    });
    
    // ✅ MASK
    const maskGraphics = this.make.graphics();
    maskGraphics.fillStyle(0xffffff);
    maskGraphics.fillRect(0, scrollAreaY, width, scrollAreaHeight);
    const mask = maskGraphics.createGeometryMask();
    this.categoryContainer.setMask(mask);
    
    // ✅ SCROLL HESAPLAMA
    const totalHeight = topPadding + (categories.length * buttonHeight) + topPadding; // Alt boşluk da ekle
    const maxScroll = Math.max(0, totalHeight - scrollAreaHeight);
    
    console.log('📊 Scroll Info:', {
        containerY: scrollAreaY,
        scrollAreaHeight,
        totalHeight,
        maxScroll,
        topPadding,
        firstButtonY: topPadding,
        categories: categories.length
    });
    
    // ⭐ Sadece scroll gerekiyorsa butonları göster
    if (maxScroll > 0) {
        const btnSize = 35;
        const btnX = width - 50;
        const btnCenterY = scrollAreaY + scrollAreaHeight / 2;
        const btnSpacing = 100;
        
        // YUKARI BUTONU
        const upBtn = this.add.circle(btnX, btnCenterY - btnSpacing, btnSize, 0x6366f1, 0.9)
            .setStrokeStyle(3, 0xffffff)
            .setInteractive({ useHandCursor: true })
            .setDepth(2000);
        
        this.add.text(btnX, btnCenterY - btnSpacing, '▲', {
            fontSize: '28px',
            fill: '#ffffff',
            fontFamily: 'Arial',
            fontStyle: 'bold'
        }).setOrigin(0.5).setDepth(2000);
        
        // AŞAĞI BUTONU
        const downBtn = this.add.circle(btnX, btnCenterY + btnSpacing, btnSize, 0x6366f1, 0.9)
            .setStrokeStyle(3, 0xffffff)
            .setInteractive({ useHandCursor: true })
            .setDepth(2000);
        
        this.add.text(btnX, btnCenterY + btnSpacing, '▼', {
            fontSize: '28px',
            fill: '#ffffff',
            fontFamily: 'Arial',
            fontStyle: 'bold'
        }).setOrigin(0.5).setDepth(2000);
        
        // ✅ YUKARI TIKLA
        upBtn.on('pointerdown', () => {
            const currentOffset = this.categoryContainer.y - scrollAreaY;
            const newOffset = Math.min(currentOffset + 120, 0);
            const newY = scrollAreaY + newOffset;
            
            console.log('⬆️ Scroll Up:', { 
                currentY: this.categoryContainer.y, 
                currentOffset, 
                newY 
            });
            
            this.tweens.add({
                targets: this.categoryContainer,
                y: newY,
                duration: 300,
                ease: 'Power2'
            });
        });
        
        // ✅ AŞAĞI TIKLA
        downBtn.on('pointerdown', () => {
            const currentOffset = this.categoryContainer.y - scrollAreaY;
            const newOffset = Math.max(currentOffset - 120, -maxScroll);
            const newY = scrollAreaY + newOffset;
            
            console.log('⬇️ Scroll Down:', { 
                currentY: this.categoryContainer.y, 
                currentOffset, 
                newY,
                maxScroll
            });
            
            this.tweens.add({
                targets: this.categoryContainer,
                y: newY,
                duration: 300,
                ease: 'Power2'
            });
        });
    }
}
    createBackButton() {
        const backButton = this.add.text(50, 50, '← Geri', {
            fontSize: '24px',
            fill: '#ffffff',
            fontFamily: 'Arial'
        }).setInteractive({ useHandCursor: true });
        
        backButton.on('pointerdown', () => {
            this.scene.start('LanguageSelectionScene');
        });
    }
    
    createStars() {
        for (let i = 0; i < 30; i++) {
            const x = Phaser.Math.Between(0, this.scale.width);
            const y = Phaser.Math.Between(0, this.scale.height);
            const star = this.add.circle(x, y, 1, 0x6366f1, 0.5);
            
            this.tweens.add({
                targets: star,
                alpha: 0.1,
                duration: Phaser.Math.Between(3000, 6000),
                yoyo: true,
                repeat: -1
            });
        }
    }
}
// Set Seçim Sahnesi
class SetSelectionScene extends Phaser.Scene {
    constructor() {
        super({ key: 'SetSelectionScene' });
    }
    
    init(data) {
        this.selectedLanguage = data.selectedLanguage;
        this.category = data.category;
    }
    
    create() {
        const { width, height } = this.scale;
        
        this.add.rectangle(width/2, height/2, width, height, 0x1e1b4b);
        this.createStars();
        
        this.add.text(width/2, height * 0.15, this.category.name, {
            fontSize: '36px',
            fill: '#a855f7',
            fontFamily: 'Arial',
            fontStyle: 'bold'
        }).setOrigin(0.5);
        
        this.add.text(width/2, height * 0.22, 'Set Seçin (50 kelime)', {
            fontSize: '20px',
            fill: '#ffffff',
            fontFamily: 'Arial'
        }).setOrigin(0.5);
        
        this.createSetButtons();
        this.createBackButton();
    }
    
createSetButtons() {
    const { width, height } = this.scale;
    const totalSets = this.category.total_sets;
    
    const scrollAreaY = height * 0.35;
    const scrollAreaHeight = height * 0.55; // Scroll alanı yüksekliği
    const buttonHeight = 70;
    const topPadding = 20;
    
    // ✅ CONTAINER oluştur
    this.setContainer = this.add.container(0, scrollAreaY);
    
    // ✅ SETLERI EKLE
    for (let i = 1; i <= totalSets; i++) {
        const y = topPadding + ((i - 1) * buttonHeight);
        
        const button = this.add.rectangle(width/2, y, 300, 60, 0x10b981)
            .setStrokeStyle(3, 0xffffff)
            .setInteractive({ useHandCursor: true });
        
        const buttonText = this.add.text(width/2, y, `Set ${i} (1-50)`, {
            fontSize: '22px',
            fill: '#ffffff',
            fontFamily: 'Arial',
            fontStyle: 'bold'
        }).setOrigin(0.5);
        
        this.setContainer.add([button, buttonText]);
        
        button.on('pointerdown', async () => {
            const loadingOverlay = this.add.rectangle(width/2, height/2, width, height, 0x000000, 0.8);
            const loadingMsg = this.add.text(width/2, height/2, 'Kelimeler yükleniyor...', {
                fontSize: '32px',
                fill: '#ffffff',
                fontFamily: 'Arial'
            }).setOrigin(0.5);
            
            const words = await WordAPI.getWordsBySet(this.category.id, i);
            window.WordData = words;
            
            this.scene.start('MenuScene');
        });
    }
    
    // ✅ MASK
    const maskGraphics = this.make.graphics();
    maskGraphics.fillStyle(0xffffff);
    maskGraphics.fillRect(0, scrollAreaY, width, scrollAreaHeight);
    const mask = maskGraphics.createGeometryMask();
    this.setContainer.setMask(mask);
    
    // ✅ SCROLL HESAPLAMA
    const totalHeight = topPadding + (totalSets * buttonHeight) + topPadding;
    const maxScroll = Math.max(0, totalHeight - scrollAreaHeight);
    
    console.log('📊 Set Scroll Info:', {
        totalSets,
        totalHeight,
        scrollAreaHeight,
        maxScroll
    });
    
    // ⭐ Scroll gerekiyorsa okları göster
    if (maxScroll > 0) {
        const btnSize = 35;
        const btnX = width - 50;
        const btnCenterY = scrollAreaY + scrollAreaHeight / 2;
        const btnSpacing = 100;
        
        // YUKARI BUTONU
        const upBtn = this.add.circle(btnX, btnCenterY - btnSpacing, btnSize, 0x6366f1, 0.9)
            .setStrokeStyle(3, 0xffffff)
            .setInteractive({ useHandCursor: true })
            .setDepth(2000);
        
        this.add.text(btnX, btnCenterY - btnSpacing, '▲', {
            fontSize: '28px',
            fill: '#ffffff',
            fontFamily: 'Arial',
            fontStyle: 'bold'
        }).setOrigin(0.5).setDepth(2000);
        
        // AŞAĞI BUTONU
        const downBtn = this.add.circle(btnX, btnCenterY + btnSpacing, btnSize, 0x6366f1, 0.9)
            .setStrokeStyle(3, 0xffffff)
            .setInteractive({ useHandCursor: true })
            .setDepth(2000);
        
        this.add.text(btnX, btnCenterY + btnSpacing, '▼', {
            fontSize: '28px',
            fill: '#ffffff',
            fontFamily: 'Arial',
            fontStyle: 'bold'
        }).setOrigin(0.5).setDepth(2000);
        
        // ✅ YUKARI TIKLA
        upBtn.on('pointerdown', () => {
            const currentOffset = this.setContainer.y - scrollAreaY;
            const newOffset = Math.min(currentOffset + 120, 0);
            const newY = scrollAreaY + newOffset;
            
            this.tweens.add({
                targets: this.setContainer,
                y: newY,
                duration: 300,
                ease: 'Power2'
            });
        });
        
        // ✅ AŞAĞI TIKLA
        downBtn.on('pointerdown', () => {
            const currentOffset = this.setContainer.y - scrollAreaY;
            const newOffset = Math.max(currentOffset - 120, -maxScroll);
            const newY = scrollAreaY + newOffset;
            
            this.tweens.add({
                targets: this.setContainer,
                y: newY,
                duration: 300,
                ease: 'Power2'
            });
        });
        
        // Glow animasyonu
        this.tweens.add({
            targets: [upBtn, downBtn],
            scaleX: 1.1,
            scaleY: 1.1,
            duration: 1500,
            yoyo: true,
            repeat: -1,
            ease: 'Sine.easeInOut'
        });
    }
}
    
    createBackButton() {
        const backButton = this.add.text(50, 50, '← Geri', {
            fontSize: '24px',
            fill: '#ffffff',
            fontFamily: 'Arial'
        }).setInteractive({ useHandCursor: true });
        
        backButton.on('pointerdown', () => {
            this.scene.start('CategorySelectionScene', { selectedLanguage: this.selectedLanguage });
        });
    }
    
    createStars() {
        for (let i = 0; i < 30; i++) {
            const x = Phaser.Math.Between(0, this.scale.width);
            const y = Phaser.Math.Between(0, this.scale.height);
            const star = this.add.circle(x, y, 1, 0x6366f1, 0.5);
            
            this.tweens.add({
                targets: star,
                alpha: 0.1,
                duration: Phaser.Math.Between(3000, 6000),
                yoyo: true,
                repeat: -1
            });
        }
    }
}
// Zorluk Seçim Sahnesi
class DifficultySelectionScene extends Phaser.Scene {
    constructor() {
        super({ key: 'DifficultySelectionScene' });
    }
    
    init(data) {
        this.selectedLanguage = data.selectedLanguage;
    }
    
    async create() {
        const { width, height } = this.scale;
        
        this.add.rectangle(width/2, height/2, width, height, 0x1e1b4b);
        this.createStars();
        
        this.add.text(width/2, height * 0.25, 'Zorluk Seviyesi', {
            fontSize: '36px',
            fill: '#a855f7',
            fontFamily: 'Arial',
            fontStyle: 'bold'
        }).setOrigin(0.5);
        
        const loadingText = this.add.text(width/2, height/2, 'Yükleniyor...', {
            fontSize: '20px',
            fill: '#ffffff',
            fontFamily: 'Arial'
        }).setOrigin(0.5);
        
        const difficulties = await WordAPI.getDifficulties(this.selectedLanguage);
        loadingText.destroy();
        
        const allDifficulties = ['all', ...difficulties];
        this.createDifficultyButtons(allDifficulties);
        this.createBackButton();
    }
    
createDifficultyButtons(difficulties) {
    const { width, height } = this.scale;
    
    const difficultyNames = {
        'all': 'Tümü (Karışık)',
        'beginner': 'Başlangıç',
        'intermediate': 'Orta'
        // 'advanced' satırını kaldırın çünkü veritabanınızda yok
    };
    
    difficulties.forEach((difficulty, index) => {
        const x = width/2;
        const y = height * 0.4 + (index * 70);
        
        const button = this.add.rectangle(x, y, 300, 55, 0x10b981)
            .setStrokeStyle(3, 0xffffff)
            .setInteractive({ useHandCursor: true });
        
        const buttonText = this.add.text(x, y, difficultyNames[difficulty] || difficulty, {
            fontSize: '20px',
            fill: '#ffffff',
            fontFamily: 'Arial',
            fontStyle: 'bold'
        }).setOrigin(0.5);
        
        button.on('pointerdown', async () => {
            const loadingOverlay = this.add.rectangle(width/2, height/2, width, height, 0x000000, 0.8);
            const loadingMsg = this.add.text(width/2, height/2, 'Kelimeler yükleniyor...', {
                fontSize: '32px',
                fill: '#ffffff',
                fontFamily: 'Arial'
            }).setOrigin(0.5);
            
            const words = await WordAPI.getWords(this.selectedLanguage, difficulty === 'all' ? null : difficulty);
            window.WordData = words;
            
            this.scene.start('MenuScene');
        });
    });
}
    
    createBackButton() {
        const backButton = this.add.text(50, 50, '← Geri', {
            fontSize: '24px',
            fill: '#ffffff',
            fontFamily: 'Arial'
        }).setInteractive({ useHandCursor: true });
        
        backButton.on('pointerdown', () => {
            this.scene.start('LanguageSelectionScene');
        });
    }
    
    createStars() {
        for (let i = 0; i < 30; i++) {
            const x = Phaser.Math.Between(0, this.scale.width);
            const y = Phaser.Math.Between(0, this.scale.height);
            const star = this.add.circle(x, y, 1, 0x6366f1, 0.5);
            
            this.tweens.add({
                targets: star,
                alpha: 0.1,
                duration: Phaser.Math.Between(3000, 6000),
                yoyo: true,
                repeat: -1
            });
        }
    }
}
class WordAPI {
    static async getLanguages() {
        try {
            const response = await fetch('/api/languages');
            return await response.json();
        } catch (error) {
            console.error('Diller yüklenemedi:', error);
            return ['en'];
        }
    }
       static getAuthToken() {
        // Laravel Sanctum için meta tag'den al
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        return token;
    }
    static async getCategories(lang) {
        try {
            const response = await fetch(`/categories/${lang}`);
            return await response.json();
        } catch (error) {
            console.error('Kategoriler yüklenemedi:', error);
            return [];
        }
    }
    
    static async getWordsBySet(categoryId, page = 1) {
        try {
            const response = await fetch(`/api/words/${categoryId}/${page}`);
            const data = await response.json();
            
            if (data.words.length === 0) {
                throw new Error('Kelime bulunamadı');
            }
            
            return data.words;
        } catch (error) {
            console.error('Kelimeler yüklenemedi:', error);
            return [
                { english: 'Apple', turkish: 'Elma' },
                { english: 'Book', turkish: 'Kitap' }
            ];
        }
    }
}

// Ana Menü Sahnesi
class MenuScene extends Phaser.Scene {
    constructor() {
        super({ key: 'MenuScene' });
    }

    create() {
        const { width, height } = this.scale;

        // Arka plan
        this.add.rectangle(width/2, height/2, width, height, 0x1e1b4b);
        this.createStars();

        // Başlık
        const titleSize = Math.min(width/8, height/12, 60);
        const title = this.add.text(width/2, height * 0.3, 'Kelime Blast', {
            fontSize: titleSize + 'px',
            fill: '#a855f7',
            fontFamily: 'Arial',
            fontStyle: 'bold',
            stroke: '#ffffff',
            strokeThickness: 2
        }).setOrigin(0.5);

        // Alt başlık
        const subtitleSize = Math.min(width/25, height/30, 20);
        this.add.text(width/2, height * 0.4, 'İngilizce kelimelerin Türkçe karşılıklarını bul!', {
            fontSize: subtitleSize + 'px',
            fill: '#ffffff',
            fontFamily: 'Arial',
            align: 'center',
            wordWrap: { width: width - 40 }
        }).setOrigin(0.5);

        // Başlat butonu
        const buttonWidth = Math.min(width - 80, 300);
        const buttonHeight = Math.min(height/12, 60);
        
        // Button shadow
        const buttonShadow = this.add.rectangle(width/2 + 3, height * 0.55 + 3, buttonWidth, buttonHeight, 0x000000, 0.3);
        
        // Main button
        const startButton = this.add.rectangle(width/2, height * 0.55, buttonWidth, buttonHeight, 0x6366f1)
            .setStrokeStyle(3, 0xffffff)
            .setInteractive({ useHandCursor: true });

        // Button glow
        const buttonGlow = this.add.rectangle(width/2, height * 0.55, buttonWidth + 10, buttonHeight + 10, 0x6366f1, 0.3);

        const startText = this.add.text(width/2, height * 0.55, 'OYUNU BAŞLAT', {
            fontSize: Math.min(buttonHeight/3, 20) + 'px',
            fill: '#ffffff',
            fontFamily: 'Arial',
            fontStyle: 'bold'
        }).setOrigin(0.5);

        // Buton efektleri
        startButton.on('pointerover', () => {
            this.tweens.add({
                targets: [startButton, startText],
                scaleX: 1.05,
                scaleY: 1.05,
                duration: 200,
                ease: 'Power2'
            });
            startButton.setFillStyle(0x8b5cf6);
        });

        startButton.on('pointerout', () => {
            this.tweens.add({
                targets: [startButton, startText],
                scaleX: 1,
                scaleY: 1,
                duration: 200,
                ease: 'Power2'
            });
            startButton.setFillStyle(0x6366f1);
        });
// MenuScene'deki startButton.on('pointerdown') içine EKLE:

    startButton.on('pointerdown', () => {
        // iOS unlock
        const unlock = new SpeechSynthesisUtterance('');
        unlock.volume = 0;
        window.speechSynthesis.speak(unlock);
        
        this.tweens.add({
            targets: [startButton, startText],
            scaleX: 0.95,
            scaleY: 0.95,
            duration: 100,
            yoyo: true,
            ease: 'Power2',
            onComplete: () => {
                this.scene.start('GameScene');
            }
        });
    });
        // Animasyonlar
        this.tweens.add({
            targets: title,
            y: title.y - 10,
            duration: 2000,
            yoyo: true,
            repeat: -1,
            ease: 'Sine.easeInOut'
        });

        this.tweens.add({
            targets: buttonGlow,
            alpha: 0.1,
            duration: 1500,
            yoyo: true,
            repeat: -1,
            ease: 'Sine.easeInOut'
        });
    }

    createStars() {
        for (let i = 0; i < 50; i++) {
            const x = Phaser.Math.Between(0, this.scale.width);
            const y = Phaser.Math.Between(0, this.scale.height);
            const star = this.add.circle(x, y, Phaser.Math.Between(1, 3), 0xa855f7, 0.7);
            
            this.tweens.add({
                targets: star,
                alpha: 0.2,
                duration: Phaser.Math.Between(2000, 4000),
                yoyo: true,
                repeat: -1
            });
        }
    }
}

// Ana Oyun Sahnesi - Düzeltilmiş Tam Versiyon
class GameScene extends Phaser.Scene {
    constructor() {
        super({ key: 'GameScene' });
        this.score = 0;
        this.timeLeft = 60;
        this.streak = 0;
        this.totalQuestions = 0;
        this.correctAnswers = 0;
        this.currentWord = null;
        this.bubbles = [];
        this.gameActive = true;
        this.isMobile = false;
        this.visibilityHandler = null;
        this.blurHandler = null;
    }

    create() {
        const { width, height } = this.scale;
        this.isMobile = width < 768;

        // Oyun değişkenlerini sıfırla
        this.score = 0;
        this.timeLeft = 60;
        this.streak = 0;
        this.totalQuestions = 0;
        this.correctAnswers = 0;
        this.currentWord = null;
        this.bubbles = [];
        this.gameActive = true;

        // Arka plan
        this.add.rectangle(width/2, height/2, width, height, 0x1e1b4b);
        this.createStars();

        // UI Elementleri
        this.createUI();

        // Shooter ekle
        this.createShooter();

        // İlk soruyu başlat
        this.nextQuestion();

        // Timer başlat
        this.gameTimer = this.time.addEvent({
            delay: 1000,
            callback: this.updateTimer,
            callbackScope: this,
            loop: true
        });

        // VİSİBİLİTY KONTROLÜ - Her oyun başladığında yeniden ekle
        this.setupCheatDetection();
    }
    initializeSpeech() {
        if (this.speechInitialized) return;
        
        // Sessiz bir ses çalarak sistemi unlock et
        const unlock = new SpeechSynthesisUtterance('');
        unlock.volume = 0;
        unlock.onend = () => {
            this.speechInitialized = true;
            console.log('✅ Ses sistemi aktif');
        };
        window.speechSynthesis.speak(unlock);
    }
    setupCheatDetection() {
        // Eski listener'ları temizle
        this.removeCheatDetection();

        // Yeni listener'ları ekle
        this.visibilityHandler = () => {
            if (document.hidden && this.scene.isActive('GameScene') && this.gameActive) {
                this.endGameDueToCheat();
            }
        };

        this.blurHandler = () => {
            if (this.scene.isActive('GameScene') && this.gameActive) {
                this.endGameDueToCheat();
            }
        };

        document.addEventListener('visibilitychange', this.visibilityHandler);
        window.addEventListener('blur', this.blurHandler);
    }

    removeCheatDetection() {
        if (this.visibilityHandler) {
            document.removeEventListener('visibilitychange', this.visibilityHandler);
            this.visibilityHandler = null;
        }
        if (this.blurHandler) {
            window.removeEventListener('blur', this.blurHandler);
            this.blurHandler = null;
        }
    }

    // Scene destroy olduğunda listener'ları temizle
    destroy() {
        this.removeCheatDetection();
        if (this.gameTimer) {
            this.gameTimer.destroy();
        }
        super.destroy();
    }

createUI() {
    const { width, height } = this.scale;
    
    // İngilizce kelime - üstte ortada
    this.wordText = this.add.text(width/2, height * 0.15, '', {
        fontSize: Math.min(width/15, height/20, 42) + 'px',
        fill: '#ffffff',
        fontFamily: 'Arial',
        fontStyle: 'bold',
        align: 'center',
        backgroundColor: 'rgba(30, 27, 75, 0.8)',
        padding: { x: width * 0.02, y: height * 0.01 }
    }).setOrigin(0.5);

    // Responsive boyutlar
    const isMobile = width < 768;
    const isTablet = width >= 768 && width < 1200;
    const isDesktop = width >= 1200;
    
    // Kenar boşlukları
    let sideMargin, topMargin;
    
    if (isMobile) {
        sideMargin = width * 0.08; // SABİT ORAN - 8%
        topMargin = height * 0.08; // SABİT ORAN - 8%
    } else if (isTablet) {
        sideMargin = Math.min(width * 0.15, 120);
        topMargin = Math.min(height * 0.15, 100);
    } else { // Desktop
        sideMargin = Math.min(width * 0.18, 180);
        topMargin = Math.min(height * 0.18, 140);
    }
    
    // Circle boyutu
    let circleSize;
    if (isMobile) {
        circleSize = Math.min(width * 0.08, height * 0.06, 45); // Mobilde biraz büyük
    } else {
        circleSize = Math.min(width * 0.045, height * 0.045, 40);
    }
    
    // Dikey boşluk
    let verticalSpacing;
    if (isMobile) {
        verticalSpacing = height * 0.09; // SABİT ORAN - yüksekliğin %9'u
    } else if (isTablet) {
        verticalSpacing = circleSize * 2.8;
    } else { // Desktop
        verticalSpacing = circleSize * 3.5;
    }
    
    // SAĞ ÜST - Timer
    this.timerBg = this.add.circle(width - sideMargin, topMargin, circleSize, 0xef4444, 0.9)
        .setStrokeStyle(4, 0xffffff);
    this.timerText = this.add.text(width - sideMargin, topMargin, '60', {
        fontSize: Math.min(circleSize * 0.6, 24) + 'px',
        fill: '#ffffff',
        fontFamily: 'Arial',
        fontStyle: 'bold'
    }).setOrigin(0.5);

    // SAĞ - Score (Timer'ın ALTINDA)
    this.scoreBg = this.add.circle(width - sideMargin, topMargin + verticalSpacing, circleSize, 0x10b981, 0.9)
        .setStrokeStyle(4, 0xffffff);
    this.scoreText = this.add.text(width - sideMargin, topMargin + verticalSpacing, '0', {
        fontSize: Math.min(circleSize * 0.6, 24) + 'px',
        fill: '#ffffff',
        fontFamily: 'Arial',
        fontStyle: 'bold'
    }).setOrigin(0.5);

    // SOL ÜST - Streak
    this.streakBg = this.add.circle(sideMargin, topMargin, circleSize, 0xf97316, 0.9)
        .setStrokeStyle(4, 0xffffff);
    this.streakText = this.add.text(sideMargin, topMargin, '0x', {
        fontSize: Math.min(circleSize * 0.5, 20) + 'px',
        fill: '#ffffff',
        fontFamily: 'Arial',
        fontStyle: 'bold'
    }).setOrigin(0.5);

    // SOL - 🔊 SES BUTONU (Streak'in ALTINDA)
    const soundBtn = this.add.circle(sideMargin, topMargin + verticalSpacing, circleSize, 0xf59e0b, 0.9)
        .setStrokeStyle(4, 0xffffff)
        .setInteractive({ useHandCursor: true });
    
    const soundIcon = this.add.text(sideMargin, topMargin + verticalSpacing, '🔊', {
        fontSize: Math.min(circleSize * 0.65, 26) + 'px'
    }).setOrigin(0.5);
    
    // Glow efekti
    const soundGlow = this.add.circle(sideMargin, topMargin + verticalSpacing, circleSize + 15, 0xf59e0b, 0.3);
    soundGlow.setDepth(-1);
    
    // Animasyon
    this.tweens.add({
        targets: soundGlow,
        alpha: 0.1,
        scaleX: 1.2,
        scaleY: 1.2,
        duration: 1500,
        yoyo: true,
        repeat: -1,
        ease: 'Sine.easeInOut'
    });
    
    // Hover efekti
    soundBtn.on('pointerover', () => {
        this.tweens.add({
            targets: [soundBtn, soundIcon],
            scaleX: 1.15,
            scaleY: 1.15,
            duration: 200,
            ease: 'Back.easeOut'
        });
    });
    
    soundBtn.on('pointerout', () => {
        this.tweens.add({
            targets: [soundBtn, soundIcon],
            scaleX: 1,
            scaleY: 1,
            duration: 200,
            ease: 'Back.easeOut'
        });
    });
    
    // Tıklama
    soundBtn.on('pointerdown', () => {
        this.tweens.add({
            targets: [soundBtn, soundIcon],
            scaleX: 0.9,
            scaleY: 0.9,
            duration: 100,
            yoyo: true,
            ease: 'Power2'
        });
        
        if (!this.speechInitialized) {
            this.initializeSpeech();
        }
        
        if (this.currentWord) {
            setTimeout(() => {
                this.speakWord(this.currentWord.english);
            }, 50);
        }
    });

    // Alt bilgi çubuğu
    this.infoBg = this.add.rectangle(width/2, height * 0.95, width * 0.9, height * 0.06, 0x1f2937, 0.9)
        .setStrokeStyle(2, 0x6366f1);
    
    this.infoText = this.add.text(width/2, height * 0.95, 'Soru: 0 | Doğru: 0 | Başarı: 0%', {
        fontSize: Math.min(width/40, height/40, 18) + 'px',
        fill: '#ffffff',
        fontFamily: 'Arial'
    }).setOrigin(0.5);

    // UI animasyonları
    this.tweens.add({
        targets: [this.timerBg, this.scoreBg, this.streakBg, soundBtn],
        scaleX: 1.1,
        scaleY: 1.1,
        duration: 2000,
        yoyo: true,
        repeat: -1,
        ease: 'Sine.easeInOut'
    });
}
    createStars() {
        for (let i = 0; i < 30; i++) {
            const x = Phaser.Math.Between(0, this.scale.width);
            const y = Phaser.Math.Between(0, this.scale.height);
            const star = this.add.circle(x, y, Phaser.Math.Between(1, 2), 0x6366f1, 0.5);
            
            this.tweens.add({
                targets: star,
                alpha: 0.1,
                duration: Phaser.Math.Between(3000, 6000),
                yoyo: true,
                repeat: -1
            });
        }
    }

    createShooter() {
        const { width, height } = this.scale;
        
        // Responsive boyutlar
        const shooterSize = Math.min(width * 0.05, height * 0.05, 35);
        const shooterY = height * 0.85;
        
        this.shooter = this.add.container(width/2, shooterY);
        
        // Glow efekti - daha büyük ve renkli
        const glow = this.add.circle(0, 0, shooterSize + 20, 0x10b981, 0.4);
        const outerGlow = this.add.circle(0, 0, shooterSize + 35, 0x059669, 0.2);
        
        // Ana gövde - gradient efekti için katmanlar
        const outerBody = this.add.circle(0, 0, shooterSize + 3, 0x047857)
            .setStrokeStyle(4, 0xffffff);
        const body = this.add.circle(0, 0, shooterSize, 0x059669)
            .setStrokeStyle(2, 0x6ee7b7);
        const inner = this.add.circle(0, 0, shooterSize - 8, 0x10b981);
        const innerCore = this.add.circle(0, 0, shooterSize - 15, 0x34d399, 0.8);
        
        // Namlu - TERS YÖN (aşağıya doğru)
        const barrel = this.add.rectangle(0, shooterSize + 8, 12, 30, 0x374151)
            .setStrokeStyle(3, 0xffffff);
        
        // Namlu detayları
        const barrelTip = this.add.rectangle(0, shooterSize + 23, 8, 8, 0x1f2937)
            .setStrokeStyle(2, 0x6ee7b7);
        
        const barrelGrip1 = this.add.rectangle(0, shooterSize + 5, 14, 3, 0x6b7280);
        const barrelGrip2 = this.add.rectangle(0, shooterSize + 12, 14, 3, 0x6b7280);
        
        // Nişangah çizgileri - daha kalın ve görünür
        const crossSize = shooterSize * 0.7;
        const cross1 = this.add.rectangle(0, 0, 4, crossSize, 0xffffff, 0.9);
        const cross2 = this.add.rectangle(0, 0, crossSize, 4, 0xffffff, 0.9);
        
        // Merkez hedef - kırmızı nokta
        const centerTarget = this.add.circle(0, 0, 6, 0xef4444, 0.9)
            .setStrokeStyle(2, 0xffffff);
        const centerDot = this.add.circle(0, 0, 3, 0xffffff);
        
        // Dekoratif daireler
        const decorCircle1 = this.add.circle(0, 0, shooterSize - 5, 0x000000, 0)
            .setStrokeStyle(1, 0x6ee7b7, 0.5);
        const decorCircle2 = this.add.circle(0, 0, shooterSize - 12, 0x000000, 0)
            .setStrokeStyle(1, 0x34d399, 0.3);
        
        // Işık efekti
        const highlight = this.add.circle(-shooterSize/3, -shooterSize/3, shooterSize/3, 0xffffff, 0.3);
        
        // Container'a ekle - sıralama önemli!
        this.shooter.add([
            outerGlow, glow, outerBody, body, inner, innerCore,
            decorCircle1, decorCircle2, highlight,
            barrel, barrelGrip1, barrelGrip2, barrelTip,
            cross1, cross2, centerTarget, centerDot
        ]);
        
        // Mouse/Touch tracking
        this.input.on('pointermove', (pointer) => {
            if (!this.gameActive) return;
            
            const angle = Phaser.Math.Angle.Between(
                this.shooter.x, this.shooter.y,
                pointer.x, pointer.y
            );
            
            this.shooter.setRotation(angle - Math.PI/2);
        });
        
        // Bubble'a dokunma/tıklama
        this.input.on('pointerdown', (pointer) => {
            if (!this.gameActive) return;
            this.checkBubbleHit(pointer.x, pointer.y);
        });
        
        // Sürekli glow animasyonu
        this.tweens.add({
            targets: glow,
            alpha: 0.2,
            duration: 1500,
            yoyo: true,
            repeat: -1,
            ease: 'Sine.easeInOut'
        });
        
        // Outer glow animasyonu
        this.tweens.add({
            targets: outerGlow,
            alpha: 0.1,
            scaleX: 1.1,
            scaleY: 1.1,
            duration: 2000,
            yoyo: true,
            repeat: -1,
            ease: 'Sine.easeInOut'
        });
        
        // Merkez hedef pulse
        this.tweens.add({
            targets: centerTarget,
            scaleX: 1.2,
            scaleY: 1.2,
            duration: 1000,
            yoyo: true,
            repeat: -1,
            ease: 'Sine.easeInOut'
        });
        
        // Highlight animasyonu
        this.tweens.add({
            targets: highlight,
            alpha: 0.1,
            duration: 2500,
            yoyo: true,
            repeat: -1,
            ease: 'Sine.easeInOut'
        });
        
        // Hover efekti
        this.shooter.setInteractive(new Phaser.Geom.Circle(0, 0, shooterSize + 15), Phaser.Geom.Circle.Contains);
        
        this.shooter.on('pointerover', () => {
            this.tweens.add({
                targets: this.shooter,
                scaleX: 1.15,
                scaleY: 1.15,
                duration: 200,
                ease: 'Back.easeOut'
            });
            
            // Hover glow artışı
            this.tweens.add({
                targets: glow,
                alpha: 0.6,
                duration: 200
            });
        });
        
        this.shooter.on('pointerout', () => {
            this.tweens.add({
                targets: this.shooter,
                scaleX: 1,
                scaleY: 1,
                duration: 200,
                ease: 'Back.easeOut'
            });
            
            // Glow'u normale döndür
            this.tweens.add({
                targets: glow,
                alpha: 0.4,
                duration: 200
            });
        });
    }

    checkBubbleHit(x, y) {
        let hitBubble = null;
        let closestDistance = Infinity;
        
        // En yakın bubble'ı bul
        this.bubbles.forEach(bubble => {
            if (!bubble.active) return;
            
            const distance = Phaser.Math.Distance.Between(
                bubble.x, bubble.y, x, y
            );
            
            const hitRadius = this.isMobile ? 80 : 60;
            
            if (distance < hitRadius && distance < closestDistance) {
                closestDistance = distance;
                hitBubble = bubble;
            }
        });
        
        if (hitBubble) {
            this.shootBullet(hitBubble);
        }
    }

    shootBullet(targetBubble) {
        // Mermi oluştur
        const bullet = this.add.circle(this.shooter.x, this.shooter.y, 8, 0xfbbf24)
            .setStrokeStyle(2, 0xffffff);
        
        // Mermi glow
        const bulletGlow = this.add.circle(this.shooter.x, this.shooter.y, 15, 0xfbbf24, 0.5);
        
        // Hedefe git
        this.tweens.add({
            targets: [bullet, bulletGlow],
            x: targetBubble.x,
            y: targetBubble.y,
            duration: 300,
            ease: 'Power2',
            onComplete: () => {
                bullet.destroy();
                bulletGlow.destroy();
                this.selectAnswer(targetBubble);
            }
        });
        
        // Shooter recoil
        this.tweens.add({
            targets: this.shooter,
            scaleX: 0.9,
            scaleY: 0.9,
            duration: 100,
            yoyo: true,
            ease: 'Power2'
        });
    }

    nextQuestion() {
        if (!this.gameActive) return;

        this.clearBubbles();
        this.currentWord = Phaser.Utils.Array.GetRandom(window.WordData);
        this.wordText.setText(this.currentWord.english);
        
        // KELİMEYİ SESLENDIR
        this.speakWord(this.currentWord.english);
        
        this.createOptions();
        this.totalQuestions++;
        this.updateInfo();
    }
    speakWord(text) {
        // Önceki sesi durdur
        if (window.speechSynthesis.speaking) {
            window.speechSynthesis.cancel();
        }
        
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'en-US'; // İngilizce
        utterance.rate = 0.85; // Biraz yavaş
        utterance.pitch = 1.0; // Normal ton
        utterance.volume = 1.0; // Tam ses
        
        // Ses bittiğinde console log (opsiyonel)
        utterance.onend = () => {
            console.log('Kelime okundu: ' + text);
        };
        
        utterance.onerror = (e) => {
            console.error('Ses hatası:', e);
        };
        
        window.speechSynthesis.speak(utterance);
    }

    createOptions() {
        const { width, height } = this.scale;
        const correctAnswer = this.currentWord.turkish;
        
        const wrongAnswers = window.WordData
            .filter(word => word.turkish !== correctAnswer)
            .map(word => word.turkish);
        
        const selectedWrong = Phaser.Utils.Array.Shuffle(wrongAnswers).slice(0, 3);
        const allOptions = Phaser.Utils.Array.Shuffle([correctAnswer, ...selectedWrong]);

        // Responsive pozisyonlar
        let positions;
        if (this.isMobile) {
            const centerX = width / 2;
            const centerY = height / 2;
            const radius = Math.min(width, height) * 0.25;
            
            positions = [
                { x: centerX - radius, y: centerY - radius/2 },
                { x: centerX + radius, y: centerY - radius/2 },
                { x: centerX - radius, y: centerY + radius/2 },
                { x: centerX + radius, y: centerY + radius/2 }
            ];
        } else {
            positions = [
                { x: width * 0.25, y: height * 0.35 },
                { x: width * 0.75, y: height * 0.35 },
                { x: width * 0.25, y: height * 0.65 },
                { x: width * 0.75, y: height * 0.65 }
            ];
        }

        Phaser.Utils.Array.Shuffle(positions);

        allOptions.forEach((option, index) => {
            this.createBubble(option, positions[index], option === correctAnswer);
        });
    }

    createBubble(text, position, isCorrect) {
        const bubbleSize = this.isMobile ? 50 : 60;
        const fontSize = this.isMobile ? '14px' : '18px';
        
        const bubble = this.add.container(position.x, position.y);

        // Glow
        const glow = this.add.circle(0, 0, bubbleSize + 20, 0x6366f1, 0.3);
        
        // Ana bubble
        const circle = this.add.circle(0, 0, bubbleSize, 0x8b5cf6, 0.9)
            .setStrokeStyle(3, 0xffffff);

        // Inner glow
        const inner = this.add.circle(0, -10, 15, 0xffffff, 0.3);

        // Text
        const bubbleText = this.add.text(0, 0, text, {
            fontSize: fontSize,
            fill: '#ffffff',
            fontFamily: 'Arial',
            fontStyle: 'bold',
            align: 'center',
            wordWrap: { width: bubbleSize * 1.8 }
        }).setOrigin(0.5);

        bubble.add([glow, circle, inner, bubbleText]);
        bubble.setSize(bubbleSize * 2, bubbleSize * 2);
        bubble.setInteractive({ useHandCursor: true });
        bubble.isCorrect = isCorrect;

        // Entrance animation
        bubble.setScale(0);
        this.tweens.add({
            targets: bubble,
            scaleX: 1,
            scaleY: 1,
            duration: 500,
            delay: this.bubbles.length * 100,
            ease: 'Back.easeOut'
        });

        // X hareketi
        this.tweens.add({
            targets: bubble,
            x: position.x + Phaser.Math.Between(-80, 80),
            duration: Phaser.Math.Between(1200, 2000),
            yoyo: true,
            repeat: -1,
            ease: 'Sine.easeInOut',
            delay: Phaser.Math.Between(0, 300)
        });

        // Y hareketi
        this.tweens.add({
            targets: bubble,
            y: position.y + Phaser.Math.Between(-35, 35),
            duration: Phaser.Math.Between(1200, 2000),
            yoyo: true,
            repeat: -1,
            ease: 'Sine.easeInOut'
        });

        // Glow animasyonu
        this.tweens.add({
            targets: glow,
            alpha: Phaser.Math.FloatBetween(0.1, 0.5),
            duration: Phaser.Math.Between(1000, 2000),
            yoyo: true,
            repeat: -1,
            ease: 'Sine.easeInOut'
        });

        this.bubbles.push(bubble);
    }

    endGameDueToCheat() {
        if (!this.gameActive) return;
        
        console.log('🚫 Oyun sonlandırıldı - Tab değiştirildi');
        
        // Hemen oyunu bitir
        this.gameActive = false;
        
        if (this.gameTimer) this.gameTimer.destroy();
        this.clearBubbles();
        
        // Cheat detection'ı temizle
        this.removeCheatDetection();
        
        // Cheat mesajı göster ve Game Over'a git
        this.showCheatMessage();
    }

    showCheatMessage() {
        const { width, height } = this.scale;
        
        // Arka plan overlay
        const overlay = this.add.rectangle(width/2, height/2, width, height, 0x000000, 0.8);
        
        // Cheat mesajı
        const cheatMessage = this.add.text(width/2, height/2, '🚫 OYUN BİTTİ!\n\nTab değiştirme tespit edildi!', {
            fontSize: this.isMobile ? '24px' : '32px',
            fill: '#ef4444',
            fontFamily: 'Arial',
            fontStyle: 'bold',
            align: 'center',
            backgroundColor: 'rgba(0, 0, 0, 0.9)',
            padding: { x: 30, y: 20 }
        }).setOrigin(0.5);
        
        // 2 saniye sonra Game Over'a git
        this.time.delayedCall(2000, () => {
            this.scene.start('GameOverScene', {
                score: this.score,
                correctAnswers: this.correctAnswers,
                totalQuestions: this.totalQuestions,
                streak: this.streak,
                cheated: true
            });
        });
    }

    selectAnswer(selectedBubble) {
        this.gameActive = false;

        const isCorrect = selectedBubble.isCorrect;
        
        if (isCorrect) {
            this.handleCorrectAnswer(selectedBubble);
            // Doğru cevap için 2 saniye bekle
            this.time.delayedCall(1000, () => {
                this.gameActive = true;
                this.nextQuestion();
            });
        } else {
            this.handleWrongAnswer(selectedBubble);
            // Yanlış cevap için HEMEN yeni soru (500ms sonra)
            this.time.delayedCall(100, () => {
                this.gameActive = true;
                this.nextQuestion();
            });
        }
    }

    handleCorrectAnswer(bubble) {
        this.correctAnswers++;
        this.streak++;
        
        const basePoints = 10;
        const streakBonus = Math.min(this.streak * 2, 50);
        const timeBonus = this.timeLeft > 50 ? 5 : 0;
        const earnedPoints = basePoints + streakBonus + timeBonus;
        
        this.score += earnedPoints;

        // Success effects
        this.tweens.add({
            targets: bubble,
            scaleX: 1.5,
            scaleY: 1.5,
            alpha: 0,
            duration: 1000,
            ease: 'Power2'
        });

        this.createParticles(bubble.x, bubble.y, 0x10b981);
        this.showResultMessage(`+${earnedPoints} Puan! 🎉`, 0x10b981);
        this.updateUI();
    }

    handleWrongAnswer(bubble) {
        this.streak = 0;

        // Shake effect
        this.tweens.add({
            targets: bubble,
            x: bubble.x + 10,
            duration: 100,
            yoyo: true,
            repeat: 3,
            ease: 'Power2'
        });

        // Show correct answer
        const correctBubble = this.bubbles.find(b => b.isCorrect);
        if (correctBubble) {
            this.tweens.add({
                targets: correctBubble,
                scaleX: 1.3,
                scaleY: 1.3,
                duration: 1000,
                yoyo: true,
                ease: 'Bounce.easeOut'
            });
        }

        this.showResultMessage(`Yanlış! Doğrusu: ${this.currentWord.turkish}`, 0xef4444);
        this.updateUI();
    }

    showResultMessage(message, color) {
        const { width, height } = this.scale;
        const fontSize = this.isMobile ? '20px' : '28px';
        
        const messageText = this.add.text(width/2, height * 0.3, message, {
            fontSize: fontSize,
            fill: '#' + color.toString(16).padStart(6, '0'),
            fontFamily: 'Arial',
            fontStyle: 'bold',
            align: 'center',
            backgroundColor: 'rgba(0,0,0,0.8)',
            padding: { x: 20, y: 10 },
            wordWrap: { width: width - 40 }
        }).setOrigin(0.5);

        messageText.setAlpha(0);
        this.tweens.add({
            targets: messageText,
            alpha: 1,
            duration: 300,
            ease: 'Power2'
        });

        this.time.delayedCall(1500, () => {
            this.tweens.add({
                targets: messageText,
                alpha: 0,
                duration: 300,
                onComplete: () => messageText.destroy()
            });
        });
    }

    createParticles(x, y, color) {
        for (let i = 0; i < 15; i++) {
            const particle = this.add.circle(x, y, Phaser.Math.Between(3, 8), color);
            
            this.tweens.add({
                targets: particle,
                x: x + Phaser.Math.Between(-100, 100),
                y: y + Phaser.Math.Between(-100, 100),
                alpha: 0,
                scaleX: 0,
                scaleY: 0,
                duration: 1000,
                ease: 'Power2',
                onComplete: () => particle.destroy()
            });
        }
    }

    clearBubbles() {
        this.bubbles.forEach(bubble => {
            if (bubble.active) {
                this.tweens.add({
                    targets: bubble,
                    scaleX: 0,
                    scaleY: 0,
                    alpha: 0,
                    duration: 300,
                    onComplete: () => bubble.destroy()
                });
            }
        });
        this.bubbles = [];
    }

    updateTimer() {
        if (!this.gameActive && this.timeLeft > 0) return;
        
        this.timeLeft--;
        this.timerText.setText(this.timeLeft.toString());
        
        if (this.timeLeft <= 10) {
            this.timerText.setColor('#ffffff');
            this.timerBg.setFillStyle(0xff0000);
            
            // Warning pulse
            this.tweens.add({
                targets: this.timerBg,
                scaleX: 1.2,
                scaleY: 1.2,
                duration: 500,
                yoyo: true,
                ease: 'Power2'
            });
        }

        if (this.timeLeft <= 0) {
            this.endGame();
        }
    }

    updateUI() {
        this.scoreText.setText(this.score.toString());
        this.streakText.setText(this.streak + 'x');
        this.updateInfo();

        // Score pulse animation
        this.tweens.add({
            targets: this.scoreBg,
            scaleX: 1.3,
            scaleY: 1.3,
            duration: 200,
            yoyo: true,
            ease: 'Power2'
        });
    }

    updateInfo() {
        const accuracy = this.totalQuestions > 0 ? 
            Math.round((this.correctAnswers / this.totalQuestions) * 100) : 0;
        
        if (this.isMobile) {
            this.infoText.setText(`Soru: ${this.totalQuestions} | Doğru: ${this.correctAnswers}`);
        } else {
            this.infoText.setText(`Soru: ${this.totalQuestions} | Doğru: ${this.correctAnswers} | Başarı: ${accuracy}%`);
        }
    }

endGame() {
    this.gameActive = false;
    
    // SESİ DURDUR
    if (window.speechSynthesis.speaking) {
        window.speechSynthesis.cancel();
    }
    
    if (this.gameTimer) this.gameTimer.destroy();
    this.clearBubbles();

    // Accuracy bonus
    if (this.correctAnswers > 0 && this.totalQuestions > 0) {
        const accuracyBonus = Math.round((this.correctAnswers / this.totalQuestions) * 20);
        this.score += accuracyBonus;
    }

    this.scene.start('GameOverScene', {
        score: this.score,
        correctAnswers: this.correctAnswers,
        totalQuestions: this.totalQuestions,
        streak: this.streak
    });
}
}

// Game Over Sahnesi
class GameOverScene extends Phaser.Scene {
    constructor() {
        super({ key: 'GameOverScene' });
    }

    init(data) {
        this.finalScore = data.score || 0;
        this.correctAnswers = data.correctAnswers || 0;
        this.totalQuestions = data.totalQuestions || 0;
        this.maxStreak = data.streak || 0;
    }

    create() {
        const { width, height } = this.scale;
        const isMobile = width < 768;
        const isSmall = width < 480;

        // Arka plan
        this.add.rectangle(width/2, height/2, width, height, 0x1e1b4b);
        
        // Animated background bubbles
        this.createAnimatedBackground();
        this.createStars();

        // Panel boyutu - RESPONSIVE
        const panelWidth = Math.min(
            width * (isSmall ? 0.95 : isMobile ? 0.9 : 0.85),
            isSmall ? 340 : isMobile ? 400 : 600
        );
        const panelHeight = Math.min(
            height * (isSmall ? 0.75 : isMobile ? 0.7 : 0.65),
            isSmall ? 450 : isMobile ? 480 : 580
        );
        
        // Panel shadow
        const panelShadow = this.add.rectangle(
            width/2 + 5, 
            height/2 + 5, 
            panelWidth, 
            panelHeight, 
            0x000000, 
            0.3
        );
        
        // Ana panel
        const panel = this.add.rectangle(
            width/2, 
            height/2, 
            panelWidth, 
            panelHeight, 
            0x1f2937, 
            0.95
        ).setStrokeStyle(4, 0x6366f1);
        
        // Panel glow
        const panelGlow = this.add.rectangle(
            width/2, 
            height/2, 
            panelWidth + 20, 
            panelHeight + 20, 
            0x6366f1, 
            0.1
        );

        // Başlık - RESPONSIVE
        const titleSize = isSmall ? 32 : isMobile ? 40 : 56;
        const title = this.add.text(
            width/2, 
            height/2 - panelHeight * 0.38, 
            'Oyun Bitti!', 
            {
                fontSize: titleSize + 'px',
                fill: '#a855f7',
                fontFamily: 'Arial',
                fontStyle: 'bold',
                stroke: '#ffffff',
                strokeThickness: 2
            }
        ).setOrigin(0.5);

        // Başlık animasyonu
        this.tweens.add({
            targets: title,
            scaleX: 1.05,
            scaleY: 1.05,
            duration: 2000,
            yoyo: true,
            repeat: -1,
            ease: 'Sine.easeInOut'
        });

        // İstatistikler
        const accuracy = this.totalQuestions > 0 ? 
            Math.round((this.correctAnswers / this.totalQuestions) * 100) : 0;

        const statSize = isSmall ? 16 : isMobile ? 18 : 24;
        const valueSize = isSmall ? 18 : isMobile ? 20 : 28;
        const iconSize = isSmall ? 18 : isMobile ? 20 : 26;
        
        const stats = [
            { label: 'Final Puan', value: this.finalScore, color: '#10b981', icon: '🏆' },
            { label: 'Doğru Cevap', value: `${this.correctAnswers}/${this.totalQuestions}`, color: '#6366f1', icon: '✅' },
            { label: 'Başarı Oranı', value: `${accuracy}%`, color: '#f97316', icon: '📊' },
            { label: 'En Uzun Seri', value: `${this.maxStreak}x`, color: '#ef4444', icon: '🔥' }
        ];

        // İstatistikleri yerleştir - RESPONSIVE
        const statsStartY = height/2 - panelHeight * 0.18;
        const statSpacing = panelHeight * (isSmall ? 0.12 : isMobile ? 0.11 : 0.1);
        const leftMargin = panelWidth * (isSmall ? 0.25 : 0.3);
        const rightMargin = panelWidth * (isSmall ? 0.25 : 0.3);
        
        stats.forEach((stat, index) => {
            const y = statsStartY + (index * statSpacing);
            const leftX = width/2 - leftMargin;
            const rightX = width/2 + rightMargin;
            
            // İkon
            this.add.text(leftX - (isSmall ? 30 : 38), y, stat.icon, {
                fontSize: iconSize + 'px'
            }).setOrigin(0.5);
            
            // Label
            this.add.text(leftX, y, stat.label + ':', {
                fontSize: statSize + 'px',
                fill: '#ffffff',
                fontFamily: 'Arial'
            }).setOrigin(0, 0.5);

            // Value - animasyonlu
            const valueText = this.add.text(rightX, y, stat.value, {
                fontSize: valueSize + 'px',
                fill: stat.color,
                fontFamily: 'Arial',
                fontStyle: 'bold',
                stroke: '#000000',
                strokeThickness: 1
            }).setOrigin(1, 0.5);
            
            // Value animasyonu
            valueText.setScale(0);
            this.tweens.add({
                targets: valueText,
                scaleX: 1,
                scaleY: 1,
                duration: 500,
                delay: index * 200,
                ease: 'Back.easeOut'
            });
        });

        // Butonlar - RESPONSIVE
        const buttonWidth = isSmall ? 140 : isMobile ? 160 : 200;
        const buttonHeight = isSmall ? 45 : isMobile ? 50 : 60;
        const buttonSpacing = isSmall ? 160 : isMobile ? 190 : 260;
        const buttonY = height/2 + panelHeight * 0.36;
        
        this.createBeautifulButton(
            width/2 - buttonSpacing/2, 
            buttonY, 
            buttonWidth, 
            buttonHeight, 
            'Tekrar Oyna', 
            0x10b981,
            () => { this.scene.start('GameScene'); },
            isSmall,
            isMobile
        );

        this.createBeautifulButton(
            width/2 + buttonSpacing/2, 
            buttonY, 
            buttonWidth, 
            buttonHeight, 
            'Ana Menü', 
            0x6366f1,
            () => { this.scene.start('MenuScene'); },
            isSmall,
            isMobile
        );

        // Kutlama efekti
        if (accuracy >= 80) {
            this.celebrateHighScore();
        } else if (accuracy >= 60) {
            this.goodScore();
        }

        // Panel glow animasyonu
        this.tweens.add({
            targets: panelGlow,
            alpha: 0.05,
            duration: 2000,
            yoyo: true,
            repeat: -1,
            ease: 'Sine.easeInOut'
        });
    }

    createBeautifulButton(x, y, width, height, text, color, callback, isSmall, isMobile) {
        // Button shadow
        const shadow = this.add.rectangle(
            x + 4, 
            y + 4, 
            width, 
            height, 
            0x000000, 
            0.4
        );
        
        // Main button
        const button = this.add.rectangle(x, y, width, height, color)
            .setStrokeStyle(isSmall ? 3 : 4, 0xffffff)
            .setInteractive({ useHandCursor: true });

        // Button glow
        const glow = this.add.rectangle(
            x, 
            y, 
            width + 15, 
            height + 15, 
            color, 
            0.3
        );

        const fontSize = isSmall ? 15 : isMobile ? 17 : 22;
        const buttonText = this.add.text(x, y, text, {
            fontSize: fontSize + 'px',
            fill: '#ffffff',
            fontFamily: 'Arial',
            fontStyle: 'bold',
            stroke: '#000000',
            strokeThickness: isSmall ? 1 : 2
        }).setOrigin(0.5);

        // Button effects
        button.on('pointerover', () => {
            this.tweens.add({
                targets: [button, buttonText],
                scaleX: 1.08,
                scaleY: 1.08,
                duration: 200,
                ease: 'Power2'
            });
            
            this.tweens.add({
                targets: glow,
                alpha: 0.6,
                scaleX: 1.1,
                scaleY: 1.1,
                duration: 200
            });
        });

        button.on('pointerout', () => {
            this.tweens.add({
                targets: [button, buttonText],
                scaleX: 1,
                scaleY: 1,
                duration: 200,
                ease: 'Power2'
            });
            
            this.tweens.add({
                targets: glow,
                alpha: 0.3,
                scaleX: 1,
                scaleY: 1,
                duration: 200
            });
        });

        button.on('pointerdown', () => {
            this.tweens.add({
                targets: [button, buttonText],
                scaleX: 0.95,
                scaleY: 0.95,
                duration: 100,
                yoyo: true,
                ease: 'Power2',
                onComplete: callback
            });
        });
        
        // Initial animation
        button.setScale(0);
        buttonText.setScale(0);
        this.tweens.add({
            targets: [button, buttonText],
            scaleX: 1,
            scaleY: 1,
            duration: 600,
            delay: 1000,
            ease: 'Back.easeOut'
        });
    }

    createAnimatedBackground() {
        const { width, height } = this.scale;
        const isMobile = width < 768;
        
        // Floating bubbles - responsive sayıda
        const bubbleCount = isMobile ? 10 : 15;
        
        for (let i = 0; i < bubbleCount; i++) {
            const x = Phaser.Math.Between(50, width - 50);
            const y = Phaser.Math.Between(50, height - 50);
            const size = Phaser.Math.Between(
                isMobile ? 15 : 20, 
                isMobile ? 40 : 60
            );
            const color = Phaser.Utils.Array.GetRandom([
                0x6366f1, 0x8b5cf6, 0xa855f7, 0x10b981, 0xf97316
            ]);
            
            const bubble = this.add.circle(x, y, size, color, 0.1)
                .setStrokeStyle(2, color, 0.3);
            
            // Random movement - X axis
            this.tweens.add({
                targets: bubble,
                x: x + Phaser.Math.Between(-150, 150),
                duration: Phaser.Math.Between(8000, 15000),
                yoyo: true,
                repeat: -1,
                ease: 'Sine.easeInOut'
            });
            
            // Random movement - Y axis
            this.tweens.add({
                targets: bubble,
                y: y + Phaser.Math.Between(-150, 150),
                duration: Phaser.Math.Between(6000, 12000),
                yoyo: true,
                repeat: -1,
                ease: 'Sine.easeInOut',
                delay: Phaser.Math.Between(0, 3000)
            });
            
            // Scale animation
            this.tweens.add({
                targets: bubble,
                scaleX: Phaser.Math.FloatBetween(0.8, 1.2),
                scaleY: Phaser.Math.FloatBetween(0.8, 1.2),
                duration: Phaser.Math.Between(3000, 6000),
                yoyo: true,
                repeat: -1,
                ease: 'Sine.easeInOut'
            });
            
            // Alpha animation
            this.tweens.add({
                targets: bubble,
                alpha: Phaser.Math.FloatBetween(0.05, 0.15),
                duration: Phaser.Math.Between(2000, 4000),
                yoyo: true,
                repeat: -1,
                ease: 'Sine.easeInOut'
            });

            // Rotation
            this.tweens.add({
                targets: bubble,
                rotation: Phaser.Math.Between(-Math.PI, Math.PI),
                duration: Phaser.Math.Between(10000, 20000),
                repeat: -1,
                ease: 'Linear'
            });
        }
    }

    celebrateHighScore() {
        const { width, height } = this.scale;
        const isMobile = width < 768;
        
        // Konfeti efekti - responsive sayıda
        const confettiCount = isMobile ? 30 : 50;
        
        for (let i = 0; i < confettiCount; i++) {
            const confetti = this.add.circle(
                Phaser.Math.Between(0, width),
                -10,
                Phaser.Math.Between(3, 8),
                Phaser.Utils.Array.GetRandom([
                    0xf59e0b, 0x10b981, 0x6366f1, 0xa855f7
                ])
            );

            this.tweens.add({
                targets: confetti,
                y: height + 10,
                x: confetti.x + Phaser.Math.Between(-100, 100),
                rotation: Phaser.Math.Between(0, 6),
                duration: Phaser.Math.Between(3000, 5000),
                ease: 'Power2',
                onComplete: () => confetti.destroy()
            });
        }
    }

    goodScore() {
        const { width, height } = this.scale;
        const isMobile = width < 768;
        
        // Orta seviye başarı için mini konfeti - responsive
        const confettiCount = isMobile ? 15 : 20;
        
        for (let i = 0; i < confettiCount; i++) {
            const confetti = this.add.circle(
                width/2 + Phaser.Math.Between(-100, 100),
                height/2 - 100,
                Phaser.Math.Between(3, 6),
                Phaser.Utils.Array.GetRandom([0x10b981, 0x6366f1])
            );

            this.tweens.add({
                targets: confetti,
                x: confetti.x + Phaser.Math.Between(-200, 200),
                y: confetti.y + Phaser.Math.Between(200, 400),
                alpha: 0,
                duration: Phaser.Math.Between(2000, 3000),
                ease: 'Power2',
                onComplete: () => confetti.destroy()
            });
        }
    }

    createStars() {
        const { width, height } = this.scale;
        const isMobile = width < 768;
        
        // Yıldız sayısı responsive
        const starCount = isMobile ? 20 : 30;
        
        for (let i = 0; i < starCount; i++) {
            const x = Phaser.Math.Between(0, width);
            const y = Phaser.Math.Between(0, height);
            const star = this.add.circle(
                x, 
                y, 
                Phaser.Math.Between(1, 2), 
                0x6366f1, 
                0.5
            );
            
            this.tweens.add({
                targets: star,
                alpha: 0.1,
                duration: Phaser.Math.Between(3000, 6000),
                yoyo: true,
                repeat: -1
            });
        }
    }
}
// Oyun Konfigürasyonu
// Scene config'i güncelle
window.GameConfig = {
    type: Phaser.AUTO,
    width: window.innerWidth,
    height: window.innerHeight,
    backgroundColor: '#1e1b4b',
    scale: {
        mode: Phaser.Scale.RESIZE,
        autoCenter: Phaser.Scale.CENTER_BOTH,
        min: { width: 320, height: 480 },
        max: { width: 1920, height: 1080 }
    },
    scene: [LanguageSelectionScene, CategorySelectionScene, SetSelectionScene, MenuScene, GameScene, GameOverScene]
};

// Global tanımlamalar
window.CategorySelectionScene = CategorySelectionScene;
window.SetSelectionScene = SetSelectionScene;

// Global olarak sınıfları tanımla
window.LanguageSelectionScene = LanguageSelectionScene;
window.MenuScene = MenuScene;
window.GameScene = GameScene;
window.GameOverScene = GameOverScene;