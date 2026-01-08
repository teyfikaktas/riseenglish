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
  max-width: 100vw;
  padding: 0 16px;
  box-sizing: border-box;
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
  max-width: 100%;
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
  max-width: 34rem;
}

#preloader .tagline.show {
  opacity: 1;
  transform: translateY(0);
}

#preloader .tagline p {
  color: rgba(255, 255, 255, 0.7);
  font-size: clamp(11px, 3vw, 18px);
  line-height: 1.35;
  letter-spacing: 0.02em;
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
  font-size: clamp(10px, 2.5vw, 14px);
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

/* Mobile-specific overrides */
@media (max-width: 480px) {
  #preloader .tagline {
    margin-top: 1.5rem;
  }
  
  #preloader .tagline p {
    font-size: clamp(10px, 3.2vw, 14px);
    letter-spacing: 0.01em;
  }
  
  #preloader .tagline .loading-text {
    font-size: clamp(9px, 2.8vw, 12px);
  }
}

@media (max-width: 350px) {
  #preloader .logo-container {
    padding: 0 8px;
  }
  
  #preloader .tagline {
    padding: 0 8px;
    margin-top: 1rem;
  }
  
  #preloader .tagline p {
    font-size: 10px;
  }
  
  #preloader .tagline .loading-text {
    font-size: 9px;
  }
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

  // Ekran genişliğine göre maksimum font size belirle
  function getMaxFontSize() {
    const w = window.innerWidth;
    if (w <= 320) return 24;
    if (w <= 375) return 28;
    if (w <= 414) return 32;
    if (w <= 480) return 38;
    if (w <= 600) return 44;
    if (w <= 768) return 54;
    return 72;
  }

  // Ekran genişliğine göre minimum font size belirle
  function getMinFontSize() {
    const w = window.innerWidth;
    if (w <= 320) return 14;
    if (w <= 375) return 16;
    if (w <= 480) return 18;
    return 20;
  }

  function getCharWidth(char, size) {
    const test = document.createElement('span');
    test.style.cssText =
      `font-size:${size}px;font-weight:700;font-family:system-ui,-apple-system,sans-serif;` +
      `position:absolute;visibility:hidden;white-space:pre;letter-spacing:-0.025em;`;
    test.textContent = char === ' ' ? '\u00A0' : char;
    document.body.appendChild(test);
    const width = test.offsetWidth;
    document.body.removeChild(test);
    return width;
  }

  function calculatePositions(text, size) {
    const widths = [...text].map(c => getCharWidth(c, size));
    const totalWidth = widths.reduce((a, b) => a + b, 0);
    const positions = [];
    let x = -totalWidth / 2;
    for (let i = 0; i < text.length; i++) {
      positions.push(x);
      x += widths[i];
    }
    return { positions, totalWidth, widths };
  }

  // Binary search ile font size'ı container'a sığdır
  function fitFontSizeToWidth(text, maxWidth) {
    const minSize = getMinFontSize();
    const maxSize = getMaxFontSize();
    
    let lo = minSize;
    let hi = maxSize;
    let best = minSize;

    while (lo <= hi) {
      const mid = Math.floor((lo + hi) / 2);
      const { totalWidth } = calculatePositions(text, mid);
      
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
    const keepCursor = cursor && cursor.parentNode === logo ? cursor : null;
    logo.innerHTML = '';
    if (keepCursor) logo.appendChild(keepCursor);
    letters.length = 0;
    comLetters.length = 0;
  }

  function init() {
    clearLogo();

    // Container genişliğini al, yoksa window genişliğini kullan
    const containerWidth = container.clientWidth || window.innerWidth;
    
    // Mobilde daha fazla padding bırak
    const paddingAmount = window.innerWidth <= 480 ? 48 : 32;
    const safeWidth = Math.max(100, containerWidth - paddingAmount);

    // Font size'ı hesapla
    fontSize = fitFontSizeToWidth(initialText, safeWidth);

    // Logo yüksekliğini ayarla
    logo.style.height = (fontSize * 1.4) + 'px';
    logo.style.fontSize = fontSize + 'px';

    // Cursor boyutunu ayarla
    if (cursor) {
      cursor.style.height = (fontSize * 0.8) + 'px';
      cursor.style.width = Math.max(2, Math.floor(fontSize * 0.06)) + 'px';
    }

    const { positions: initialPositions, totalWidth } = calculatePositions(initialText, fontSize);
    const displayWidth = Math.min(totalWidth, safeWidth);

    // Loading bar genişliğini ayarla
    if (loadingBar) {
      loadingBar.style.width = displayWidth + 'px';
    }

    // Harfleri oluştur
    [...initialText].forEach((char, i) => {
      const span = document.createElement('span');
      span.className = 'letter';
      span.textContent = char === ' ' ? '\u00A0' : char;
      span.style.left = `calc(50% + ${initialPositions[i]}px)`;
      span.style.fontSize = fontSize + 'px';
      logo.appendChild(span);
      letters.push(span);
    });

    // .com harflerini oluştur
    const comText = ".com";
    [...comText].forEach((char) => {
      const span = document.createElement('span');
      span.className = 'letter com';
      span.textContent = char;
      span.style.fontSize = fontSize + 'px';
      logo.appendChild(span);
      comLetters.push(span);
    });

    // Cursor pozisyonunu ayarla
    if (cursor) {
      cursor.style.right = 'auto';
      cursor.style.left = `calc(50% + ${displayWidth / 2 + 8}px)`;
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
    const { positions: finalPositions, totalWidth: finalWidthRaw } = calculatePositions(finalText, fontSize);
    
    const containerWidth = container.clientWidth || window.innerWidth;
    const paddingAmount = window.innerWidth <= 480 ? 48 : 32;
    const safeWidth = Math.max(100, containerWidth - paddingAmount);
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
    const { positions, totalWidth: totalWidthRaw } = calculatePositions(fullText, fontSize);
    
    const containerWidth = container.clientWidth || window.innerWidth;
    const paddingAmount = window.innerWidth <= 480 ? 48 : 32;
    const safeWidth = Math.max(100, containerWidth - paddingAmount);
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

  // Resize handler
  let resizeTimer = null;
  function onResize() {
    if (!preloader || preloader.classList.contains('fade-out')) return;
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      const lettersWereVisible = letters.some(l => l.classList.contains('visible'));
      const taglineShown = tagline && tagline.classList.contains('show');

      init();

      if (lettersWereVisible) {
        letters.forEach(l => l.classList.add('visible'));
      }
      if (taglineShown && tagline) tagline.classList.add('show');
    }, 100);
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