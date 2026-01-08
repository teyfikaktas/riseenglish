{{-- resources/views/partials/preloader.blade.php --}}

<div id="preloader">
  <div class="logo-container">
    <div class="logo" id="logo">
      <span class="cursor" id="cursor"></span>
    </div>

    <div class="tagline" id="tagline">
      <p>Bu bir <span class="hakan-hoca">Hakan Hoca</span> dil öğrenme platformudur</p>
      <p class="loading-text">Yükleniyor...</p>

      <div class="loading-bar" id="loadingBar">
        <div class="progress" id="progress"></div>
      </div>
    </div>
  </div>
</div>

<style>
#preloader {
  position: fixed;
  inset: 0;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: #1e3a5f;
  z-index: 99999;
  transition: opacity 0.6s ease, visibility 0.6s ease;
  overflow: hidden;
}

#preloader.fade-out {
  opacity: 0;
  visibility: hidden;
}

#preloader .logo-container {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  max-width: 100%;
  padding: 0 16px;
  box-sizing: border-box;

  /* iOS/Android: animasyon kaynaklı taşmaları kesin keser */
  overflow: hidden;
}

#preloader .logo {
  font-weight: 700;
  font-family: system-ui, -apple-system, sans-serif;
  letter-spacing: -0.025em;
  display: flex;
  justify-content: center;
  position: relative;
  width: 100%;
}

#preloader .letter {
  display: inline-block;
  transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
  color: #ffffff;
  position: absolute;
  white-space: pre;
  opacity: 0;
  transform: translateY(20px);
  will-change: transform, opacity, left;
}

#preloader .letter.visible {
  opacity: 1;
  transform: translateY(0);
}

#preloader .letter.accent {
  color: #ff4757;
}

#preloader .letter.fade-out {
  opacity: 0;
  transform: scale(0.5);
  filter: blur(4px);
}

#preloader .letter.com {
  color: rgba(255, 255, 255, 0.5);
  opacity: 0;
  transform: translateX(-10px);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

#preloader .letter.com.visible {
  opacity: 1;
  transform: translateX(0);
}

#preloader .cursor {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: #ff4757;
  animation: preloaderBlink 0.5s infinite;
  transition: all 0.3s ease;
  opacity: 0;
}

#preloader .cursor.visible {
  opacity: 1;
}

@keyframes preloaderBlink {
  0%, 100% { opacity: 1; }
  50% { opacity: 0; }
}

#preloader .tagline {
  text-align: center;
  margin-top: 2rem;
  opacity: 0;
  transform: translateY(10px);
  transition: opacity 0.5s ease, transform 0.5s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  padding: 0 16px;
  box-sizing: border-box;

  /* okunabilirlik ve taşma kontrolü */
  max-width: 34rem;
}

#preloader .tagline.show {
  opacity: 1;
  transform: translateY(0);
}

/* iOS + Android: metin her zaman kırılabilir ve ölçeklenebilir */
#preloader .tagline p {
  color: rgba(255, 255, 255, 0.7);
  font-size: clamp(12px, 3.5vw, 18px);
  line-height: 1.35;
  letter-spacing: 0.03em; /* 0.05em küçük ekranda taşma yapabilir */
  margin: 0;
  padding: 0 8px;

  overflow-wrap: anywhere;
  word-break: break-word;
  hyphens: auto;
}

#preloader .tagline .hakan-hoca {
  color: #ff4757;
  font-weight: 700;
}

#preloader .tagline .loading-text {
  color: rgba(255, 255, 255, 0.5);
  font-size: clamp(11px, 3.0vw, 14px);
  line-height: 1.3;
  margin-top: 0.5rem;
}

#preloader .loading-bar {
  height: 3px;
  margin-top: 1rem;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 2px;
  overflow: hidden;
  transition: width 0.7s cubic-bezier(0.4, 0, 0.2, 1);

  /* width JS ile set ediliyor, yine de güvenlik için */
  max-width: 100%;
}

#preloader .loading-bar .progress {
  height: 100%;
  width: 0%;
  background: linear-gradient(90deg, #ff4757, #ff6b7a);
  border-radius: 2px;
  transition: width 0.1s ease-out;
  box-shadow: 0 0 10px rgba(255, 71, 87, 0.5);
}

body.preloader-active {
  overflow: hidden;
}

.page-content {
  opacity: 0;
  transition: opacity 0.5s ease;
}

.page-content.loaded {
  opacity: 1;
}
</style>

<script>
(function () {
  document.body.classList.add('preloader-active');

  const logo = document.getElementById('logo');
  const cursor = document.getElementById('cursor');
  const tagline = document.getElementById('tagline');
  const progress = document.getElementById('progress');
  const loadingBar = document.getElementById('loadingBar');
  const preloader = document.getElementById('preloader');
  const container = document.querySelector('#preloader .logo-container');

  if (!logo || !preloader || !container) return;

  const initialText = "Rise Your English";
  const finalMapping = [
    [0, 0, false], [1, 1, false], [2, 2, false],
    [3, null, false], [4, null, false], [5, null, false],
    [6, null, false], [7, null, false], [8, null, false],
    [9, null, false], [10, 3, true], [11, 4, true],
    [12, 5, true], [13, 6, true], [14, 7, true],
    [15, 8, true], [16, 9, true],
  ];

  const letters = [];
  const comLetters = [];
  let fontSize = 48;

  // (Eski yöntem; artık kullanılmıyor, ama kalsın isterseniz)
  function getFontSize() {
    const w = window.innerWidth;
    if (w <= 350) return 10;
    if (w <= 390) return 18;
    if (w <= 430) return 28;
    if (w <= 550) return 34;
    if (w <= 768) return 48;
    return 72;
  }

  function getCharWidth(char) {
    const test = document.createElement('span');
    test.style.cssText =
      `font-size:${fontSize}px;font-weight:700;font-family:system-ui,-apple-system,sans-serif;` +
      `position:absolute;visibility:hidden;white-space:pre;letter-spacing:-0.025em;`;
    test.textContent = char === ' ' ? '\u00A0' : char;
    document.body.appendChild(test);
    const width = test.offsetWidth;
    document.body.removeChild(test);
    return width;
  }

  function calculatePositions(text) {
    const widths = [...text].map(c => getCharWidth(c));
    const totalWidth = widths.reduce((a, b) => a + b, 0);
    const positions = [];
    let x = -totalWidth / 2;
    for (let i = 0; i < text.length; i++) {
      positions.push(x);
      x += widths[i];
    }
    return { positions, totalWidth, widths };
  }

  // iOS + Android: metni container'a sığdıran font-size (binary search)
  function fitFontSizeToWidth(text, maxWidth, min = 16, max = 72) {
    let lo = min, hi = max, best = min;

    while (lo <= hi) {
      const mid = (lo + hi) >> 1;
      fontSize = mid;

      const { totalWidth } = calculatePositions(text);
      if (totalWidth <= maxWidth) {
        best = mid;
        lo = mid + 1;
      } else {
        hi = mid - 1;
      }
    }
    return best;
  }

  function clearLogo() {
    // cursor elementini koru, diğerlerini sil
    const keepCursor = cursor && cursor.parentNode === logo ? cursor : null;
    logo.innerHTML = '';
    if (keepCursor) logo.appendChild(keepCursor);

    letters.length = 0;
    comLetters.length = 0;
  }

  function init() {
    clearLogo();

    // container genişliğine göre güvenli alan (padding payı)
    const safeWidth = Math.max(0, container.clientWidth - 32);

    // initial text'e göre font-size'ı sığdır
    fontSize = fitFontSizeToWidth(initialText, safeWidth, 16, 72);

    logo.style.height = (fontSize * 1.4) + 'px';
    logo.style.fontSize = fontSize + 'px';

    if (cursor) {
      cursor.style.height = (fontSize * 0.8) + 'px';
      cursor.style.width = Math.max(2, Math.floor(fontSize * 0.06)) + 'px';
    }

    const { positions: initialPositions, totalWidth } = calculatePositions(initialText);

    if (loadingBar) {
      loadingBar.style.width = Math.min(totalWidth, safeWidth) + 'px';
    }

    [...initialText].forEach((char, i) => {
      const span = document.createElement('span');
      span.className = 'letter';
      span.textContent = char === ' ' ? '\u00A0' : char;
      span.style.left = `calc(50% + ${initialPositions[i]}px)`;
      span.style.fontSize = fontSize + 'px';
      logo.appendChild(span);
      letters.push(span);
    });

    const comText = ".com";
    [...comText].forEach((char) => {
      const span = document.createElement('span');
      span.className = 'letter com';
      span.textContent = char;
      span.style.fontSize = fontSize + 'px';
      logo.appendChild(span);
      comLetters.push(span);
    });

    if (cursor) {
      cursor.style.right = 'auto';
      cursor.style.left = `calc(50% + ${Math.min(totalWidth, safeWidth) / 2 + 8}px)`;
    }
  }

  function showLetters() {
    if (cursor) cursor.classList.add('visible');
    letters.forEach((letter, i) => {
      setTimeout(() => letter.classList.add('visible'), i * 35);
    });
  }

  function morph() {
    const finalText = "RisEnglish";
    const safeWidth = Math.max(0, container.clientWidth - 32);

    // final text daha kısa ama yine de width hesaplayalım
    const { positions: finalPositions, totalWidth: finalWidthRaw } = calculatePositions(finalText);
    const finalWidth = Math.min(finalWidthRaw, safeWidth);

    if (loadingBar) loadingBar.style.width = finalWidth + 'px';

    finalMapping.forEach(([initialIdx, finalPos, isAccent]) => {
      const letter = letters[initialIdx];
      if (!letter) return;

      if (finalPos === null) {
        letter.classList.add('fade-out');
      } else {
        letter.style.left = `calc(50% + ${finalPositions[finalPos]}px)`;
        if (isAccent) letter.classList.add('accent');
      }
    });

    if (cursor) cursor.style.left = `calc(50% + ${finalWidth / 2 + 8}px)`;
  }

  function showCom() {
    const fullText = "RisEnglish.com";
    const safeWidth = Math.max(0, container.clientWidth - 32);

    const { positions, totalWidth: totalWidthRaw } = calculatePositions(fullText);
    const totalWidth = Math.min(totalWidthRaw, safeWidth);

    let convergingIndex = 0;
    finalMapping.forEach(([initialIdx, finalPos]) => {
      if (finalPos !== null && letters[initialIdx]) {
        letters[initialIdx].style.left = `calc(50% + ${positions[convergingIndex]}px)`;
        convergingIndex++;
      }
    });

    comLetters.forEach((letter, i) => {
      letter.style.left = `calc(50% + ${positions[10 + i]}px)`;
      setTimeout(() => letter.classList.add('visible'), i * 100);
    });

    if (loadingBar) loadingBar.style.width = totalWidth + 'px';
    if (cursor) cursor.style.left = `calc(50% + ${totalWidth / 2 + 8}px)`;
  }

  function animateProgress() {
    if (!progress) return;
    let currentProgress = 0;
    const interval = setInterval(() => {
      currentProgress += 0.8;
      if (currentProgress >= 100) {
        currentProgress = 100;
        clearInterval(interval);
      }
      progress.style.width = currentProgress + '%';
    }, 20);
  }

  function hidePreloader() {
    preloader.classList.add('fade-out');
    document.body.classList.remove('preloader-active');

    const pageContent = document.querySelector('.page-content');
    if (pageContent) pageContent.classList.add('loaded');

    setTimeout(() => {
      preloader.style.display = 'none';
    }, 600);
  }

  let pageLoaded = false;
  let animationComplete = false;

  function checkAndHide() {
    if (pageLoaded && animationComplete) {
      hidePreloader();
    }
  }

  // iOS/Android: resize ve orientation değişiminde layout'u yeniden hesapla
  let resizeTimer = null;
  function onResize() {
    if (!preloader || preloader.classList.contains('fade-out')) return;
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      // Animasyonu en baştan oynatmak istemiyorsanız sadece init çağırmak yeter.
      // Burada güvenli yol: init + mevcut görünürlük sınıflarını korumaya çalışmak.
      const lettersWereVisible = letters.some(l => l.classList.contains('visible'));
      const taglineShown = tagline && tagline.classList.contains('show');

      init();

      if (lettersWereVisible) {
        // Harfleri görünür tut (kaba ama stabil)
        letters.forEach(l => l.classList.add('visible'));
      }
      if (taglineShown && tagline) tagline.classList.add('show');
    }, 80);
  }

  window.addEventListener('resize', onResize, { passive: true });
  window.addEventListener('orientationchange', onResize, { passive: true });

  window.addEventListener('load', () => {
    pageLoaded = true;
    checkAndHide();
  });

  setTimeout(() => {
    pageLoaded = true;
    checkAndHide();
  }, 8000);

  // Başlat
  init();
  setTimeout(showLetters, 100);
  setTimeout(morph, 1400);
  setTimeout(showCom, 2200);
  setTimeout(() => { if (cursor) cursor.style.opacity = '0'; }, 3000);

  setTimeout(() => {
    if (tagline) tagline.classList.add('show');
    setTimeout(animateProgress, 300);
  }, 3300);

  setTimeout(() => {
    animationComplete = true;
    checkAndHide();
  }, 6000);
})();
</script>
