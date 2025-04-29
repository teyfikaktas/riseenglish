// resources/js/chain-breaker.js

import { gsap } from "gsap";

document.addEventListener('DOMContentLoaded', function() {
    // Zincir verisi
    let chainData = {
        days: 0,
        maxDays: 365,
        level: 'Bronz',
        levels: [
            { name: 'Bronz', threshold: 0, color: '#CD7F32' },
            { name: 'Demir', threshold: 30, color: '#71797E' },
            { name: 'Gümüş', threshold: 60, color: '#C0C0C0' },
            { name: 'Altın', threshold: 90, color: '#FFD700' },
            { name: 'Platin', threshold: 180, color: '#E5E4E2' },
            { name: 'Zümrüt', threshold: 240, color: '#50C878' },
            { name: 'Elmas', threshold: 300, color: '#B9F2FF' },
            { name: 'MASTER', threshold: 365, color: '#9370DB' }
        ]
    };

    // DOM elementleri
    const chainContainer = document.getElementById('chain-links-container');
    const dayCountElement = document.getElementById('dayCount');
    const currentLevelElement = document.getElementById('currentLevel');
    const addDayBtn = document.getElementById('add-day-btn');
    const breakChainBtn = document.getElementById('break-chain-btn');

    // Eğer DOM elementleri yoksa fonksiyonu sonlandır
    if (!chainContainer || !dayCountElement || !currentLevelElement || !addDayBtn || !breakChainBtn) {
        return;
    }

    // Sayfa yüklenirken localStorage'dan verileri al
    initializeChainData();

    // Zincir halkalarını oluştur
    function createChain() {
        chainContainer.innerHTML = '';
        
        for (let i = 0; i < chainData.maxDays; i++) {
            const link = document.createElement('div');
            link.className = 'chain-link w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold';
            
            if (i < chainData.days) {
                // Tamamlanmış gün
                const currentLevel = getCurrentLevel();
                link.classList.add('text-white');
                link.innerHTML = '<i class="fas fa-check"></i>';
                link.style.backgroundColor = currentLevel.color;
            } else {
                // Tamamlanmamış gün
                link.classList.add('bg-gray-200', 'text-gray-400');
                link.textContent = (i + 1).toString();
            }
            
            chainContainer.appendChild(link);
        }
        
        // Update counter and level display
        dayCountElement.textContent = `${chainData.days}/${chainData.maxDays}`;
        
        const currentLevel = getCurrentLevel();
        currentLevelElement.textContent = currentLevel.name;
        currentLevelElement.style.color = currentLevel.color;
    }

    // localStorage'dan verileri al
    function initializeChainData() {
        const savedData = localStorage.getItem('chainData');
        if (savedData) {
            try {
                const parsedData = JSON.parse(savedData);
                chainData.days = parsedData.days || 0;
                chainData.level = parsedData.level || 'Bronz';
            } catch (e) {
                console.error("Chain data couldn't be parsed:", e);
            }
        }
        
        createChain();
    }

    // Verileri localStorage'a kaydet
    function saveChainData() {
        localStorage.setItem('chainData', JSON.stringify(chainData));
    }

    // Mevcut seviyeyi hesapla
    function getCurrentLevel() {
        let level = chainData.levels[0]; // Default to first level
        
        for (let i = chainData.levels.length - 1; i >= 0; i--) {
            if (chainData.days >= chainData.levels[i].threshold) {
                level = chainData.levels[i];
                break;
            }
        }
        
        return level;
    }

    // Gün ekle
    addDayBtn.addEventListener('click', function() {
        if (chainData.days < chainData.maxDays) {
            const newLink = chainContainer.children[chainData.days];
            
            // Animasyonlu güncelleme
            gsap.to(newLink, {
                scale: 1.5,
                duration: 0.3,
                backgroundColor: getCurrentLevel().color,
                color: "#FFFFFF",
                onComplete: function() {
                    gsap.to(newLink, {
                        scale: 1,
                        duration: 0.2,
                        ease: "bounce.out"
                    });
                    
                    chainData.days++;
                    saveChainData();
                    createChain();
                    
                    // Check if level up occurred
                    const newLevel = getCurrentLevel();
                    if (newLevel.name !== chainData.level) {
                        chainData.level = newLevel.name;
                        saveChainData();
                        
                        // Level up animation
                        gsap.to(currentLevelElement, {
                            scale: 1.5,
                            duration: 0.5,
                            color: newLevel.color,
                            ease: "elastic.out",
                            onComplete: function() {
                                gsap.to(currentLevelElement, {
                                    scale: 1,
                                    duration: 0.3
                                });
                            }
                        });
                        
                        // Seviye atlama bildirimi
                        showLevelUpMessage(newLevel.name);
                    }
                }
            });
        }
    });

    // Zinciri sıfırla
    breakChainBtn.addEventListener('click', function() {
        if (chainData.days === 0) return;
        
        if (!confirm('Zincirinizi sıfırlamak istediğinizden emin misiniz? Bu işlem geri alınamaz.')) {
            return;
        }
        
        // Animate chain breaking
        const links = document.querySelectorAll('.chain-link');
        
        gsap.to(links, {
            scale: 0,
            opacity: 0,
            stagger: 0.01,
            duration: 0.5,
            onComplete: function() {
                chainData.days = 0;
                chainData.level = 'Bronz';
                saveChainData();
                createChain();
                
                gsap.from(links, {
                    scale: 0.5,
                    opacity: 0,
                    stagger: 0.01,
                    duration: 0.5
                });
            }
        });
    });
    
    // Seviye atlama mesajı göster
    function showLevelUpMessage(levelName) {
        // Basit bir alert ile bildiriyoruz, istenirse daha estetik bir bildirim oluşturulabilir
        alert(`Tebrikler! Yeni seviyeye ulaştınız: ${levelName}`);
    }

    // İlk zinciri oluştur
    createChain();
});

// Konfeti Efekti
function createConfetti() {
    const confettiCount = 200;
    const confettiContainer = document.createElement('div');
    confettiContainer.className = 'fixed inset-0 pointer-events-none z-50';
    document.body.appendChild(confettiContainer);
    
    for (let i = 0; i < confettiCount; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'absolute w-2 h-2 rounded-full';
        
        // Rastgele renk
        const colors = ['#e63946', '#1a2e5a', '#FFD700', '#50C878', '#B9F2FF', '#9370DB'];
        const color = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.backgroundColor = color;
        
        // Rastgele başlangıç pozisyonu
        confetti.style.left = `${Math.random() * 100}vw`;
        confetti.style.top = `${Math.random() * 20 - 20}vh`;
        
        confettiContainer.appendChild(confetti);
        
        // GSAP ile animasyon
        gsap.to(confetti, {
            y: `${100 + Math.random() * 20}vh`,
            x: `${(Math.random() - 0.5) * 50}vw`,
            rotation: Math.random() * 360,
            duration: 1 + Math.random() * 3,
            ease: "power1.out",
            onComplete: function() {
                confetti.remove();
                
                // Tüm konfetiler bittiğinde container'ı kaldır
                if (confettiContainer.children.length === 0) {
                    confettiContainer.remove();
                }
            }
        });
    }
}

// Level-up animation olayını dinle
document.addEventListener('level-up-animation', function() {
    createConfetti();
});

export { createConfetti };