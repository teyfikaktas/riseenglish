<!-- resources/views/welcome.blade.php -->
@extends('layouts.app')
@include('partials.preloader')

@section('content')

    @if (!auth()->check())
        <div id="fortuneWheelModal"
            class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
            style="display: none;">
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
                @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700;800&display=swap');

                /* Modal arka plan - Turuncu gradient */
                #fortuneWheelModal {
                    background: linear-gradient(135deg, rgba(255, 107, 53, 0.95), rgba(247, 147, 30, 0.95)) !important;
                }

                #demoModal {
                    z-index: 9999 !important;
                }

                #demoModal .fortune-wheel-container {
                    position: relative;
                    z-index: 10000 !important;
                }

                /* Dekoratif elementlerin z-index'ini düşür */
                .fortune-wheel-container::before,
                .fortune-wheel-container::after {
                    z-index: -1 !important;
                }

                .fortune-wheel-container {
                    font-family: 'Roboto', 'Inter', sans-serif;
                    background: rgba(255, 255, 255, 0.98);
                    backdrop-filter: blur(15px);
                    border: 4px solid rgba(255, 255, 255, 0.4);
                    box-shadow:
                        0 30px 80px rgba(0, 0, 0, 0.25),
                        0 0 120px rgba(255, 107, 53, 0.3),
                        inset 0 1px 0 rgba(255, 255, 255, 0.8);
                }

                /* Çark konteyneri - KÜÇÜLTÜLMÜŞ */
                .wheel-container {
                    position: relative;
                    width: 450px !important;
                    height: 450px !important;
                    margin: 0 auto;
                }

                /* Mobil responsive */
                @media (max-width: 768px) {
                    .wheel-container {
                        width: 350px !important;
                        height: 350px !important;
                    }
                }

                @media (max-width: 480px) {
                    .wheel-container {
                        width: 280px !important;
                        height: 280px !important;
                    }
                }

                .wheel {
                    width: 100%;
                    height: 100%;
                    border-radius: 50%;
                    position: relative;
                    overflow: hidden;
                    box-shadow:
                        0 0 60px rgba(255, 107, 53, 0.4),
                        0 0 120px rgba(247, 147, 30, 0.2),
                        inset 0 0 40px rgba(255, 255, 255, 0.1);
                        transition: transform 6s cubic-bezier(0, 0, 0.2, 1); /* ⬅️ DEĞİŞTİRİLDİ: İlk 5 sn hızlı, son 1 sn yavaş */
                    border: 10px solid rgba(255, 255, 255, 0.9);
                }

                .wheel-segment {
                    position: absolute;
                    width: 50%;
                    height: 50%;
                    transform-origin: 100% 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 700;
                    font-size: 12px !important;
                    color: white;
                    text-align: center;
                    padding: 12px;
                    box-sizing: border-box;
                    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
                    letter-spacing: 1px;
                }

                /* Turuncu tonlarında segmentler */
                .wheel-segment:nth-child(1) {
                    background: linear-gradient(45deg, #ff6b35, #e55039);
                }

                .wheel-segment:nth-child(2) {
                    background: linear-gradient(45deg, #f7931e, #f39c12);
                }

                .wheel-segment:nth-child(3) {
                    background: linear-gradient(45deg, #ff8c42, #ff7675);
                }

                .wheel-segment:nth-child(4) {
                    background: linear-gradient(45deg, #fd9644, #fdcb6e);
                }

                .wheel-segment:nth-child(5) {
                    background: linear-gradient(45deg, #e17055, #d63031);
                }

                .wheel-segment:nth-child(6) {
                    background: linear-gradient(45deg, #ff9ff3, #fd79a8);
                }

                .wheel-segment:nth-child(7) {
                    background: linear-gradient(45deg, #00b894, #00cec9);
                }

                .wheel-segment:nth-child(8) {
                    background: linear-gradient(45deg, #6c5ce7, #a29bfe);
                }

                .wheel-pointer {
                    position: absolute;
                    top: -20px;
                    left: 50%;
                    transform: translateX(-50%);
                    width: 0;
                    height: 0;
                    border-left: 25px solid transparent;
                    border-right: 25px solid transparent;
                    border-top: 35px solid #ff6b35;
                    z-index: 10;
                    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.4));
                }

                .wheel-center {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 80px !important;
                    height: 80px !important;
                    background: linear-gradient(45deg, #ff6b35, #f7931e);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 800;
                    color: white;
                    z-index: 5;
                    border: 8px solid white;
                    box-shadow:
                        0 0 40px rgba(0, 0, 0, 0.4),
                        0 0 80px rgba(255, 107, 53, 0.3);
                    font-size: 14px !important;
                    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
                    letter-spacing: 2px;
                }

                /* SVG çark için turuncu tonları */
                .wheel svg {
                    width: 100%;
                    height: 100%;
                }

                /* SVG text düzeltmeleri */
                .wheel svg text {
                    font-family: 'Roboto', 'Arial', sans-serif !important;
                    font-weight: 700 !important;
                    font-size: 14px !important;
                    text-rendering: optimizeLegibility;
                    -webkit-font-feature-settings: "liga", "kern";
                    font-feature-settings: "liga", "kern";
                    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
                    letter-spacing: 1px;
                }

                @media (max-width: 768px) {
                    .wheel svg text {
                        font-size: 12px !important;
                    }

                    .wheel-center {
                        width: 70px !important;
                        height: 70px !important;
                        font-size: 12px !important;
                    }
                }

                @media (max-width: 480px) {
                    .wheel svg text {
                        font-size: 9px !important;
                    }

                    .wheel-center {
                        width: 60px !important;
                        height: 60px !important;
                        font-size: 10px !important;
                    }

                    .wheel-segment {
                        font-size: 10px !important;
                    }
                }

                .spin-button {
                    background: linear-gradient(135deg, #ff6b35, #f7931e) !important;
                    border: none;
                    color: white;
                    padding: 15px 30px !important;
                    font-size: 16px !important;
                    font-weight: 800;
                    border-radius: 50px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    box-shadow:
                        0 8px 25px rgba(255, 107, 53, 0.4),
                        0 0 30px rgba(247, 147, 30, 0.3);
                    margin-top: 25px;
                    text-transform: uppercase;
                    letter-spacing: 2px;
                    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
                }

                .spin-button:hover {
                    transform: translateY(-3px) scale(1.05);
                    box-shadow:
                        0 12px 35px rgba(255, 107, 53, 0.6),
                        0 0 40px rgba(247, 147, 30, 0.5);
                }

                .spin-button:disabled {
                    background: linear-gradient(135deg, #bdc3c7, #95a5a6) !important;
                    cursor: not-allowed;
                    transform: none;
                    box-shadow: none;
                }

                .code-display {
                    background: linear-gradient(135deg, #ff6b35, #f7931e) !important;
                    color: white;
                    padding: 20px;
                    border-radius: 12px;
                    font-family: 'Courier New', monospace;
                    font-size: 20px !important;
                    font-weight: bold;
                    letter-spacing: 3px;
                    text-align: center;
                    margin: 20px 0;
                    border: 3px dashed white;
                    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
                    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
                }

                .confetti {
                    position: fixed;
                    width: 12px;
                    height: 12px;
                    background: #ff6b35;
                    animation: confetti-fall 3s linear infinite;
                    z-index: 9999;
                }

                @keyframes confetti-fall {
                    0% {
                        transform: translateY(-100vh) rotate(0deg);
                        opacity: 1;
                    }

                    100% {
                        transform: translateY(100vh) rotate(360deg);
                        opacity: 0;
                    }
                }

                .pulse-animation {
                    animation: orangePulse 2s infinite;
                }

                @keyframes orangePulse {

                    0%,
                    100% {
                        transform: scale(1);
                        box-shadow:
                            0 8px 25px rgba(255, 107, 53, 0.4),
                            0 0 30px rgba(247, 147, 30, 0.3);
                    }

                    50% {
                        transform: scale(1.08);
                        box-shadow:
                            0 12px 35px rgba(255, 107, 53, 0.7),
                            0 0 50px rgba(247, 147, 30, 0.6);
                    }
                }

                .close-button {
                    position: absolute;
                    top: 15px;
                    right: 15px;
                    background: rgba(255, 255, 255, 0.95);
                    border: none;
                    border-radius: 50%;
                    width: 35px;
                    height: 35px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    z-index: 10;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                }

                .close-button:hover {
                    background: white;
                    transform: rotate(90deg) scale(1.1);
                    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
                }

                /* Başlık stilleri */
                .fortune-wheel-container h2 {
                    font-size: 1.8rem !important;
                    font-weight: 800;
                    background: linear-gradient(135deg, #ff6b35, #d63031);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    margin-bottom: 10px;
                    letter-spacing: 2px;
                }

                .fortune-wheel-container p {
                    font-size: 1rem;
                    color: #666;
                    margin-bottom: 20px;
                }

                /* Dekoratif elementler */
                .fortune-wheel-container::before {
                    content: '';
                    position: absolute;
                    top: -15px;
                    left: -15px;
                    width: 40px;
                    height: 40px;
                    background: linear-gradient(45deg, #ff6b35, #f7931e);
                    border-radius: 50%;
                    box-shadow: 0 0 30px rgba(255, 107, 53, 0.5);
                }

                .fortune-wheel-container::after {
                    content: '';
                    position: absolute;
                    bottom: -15px;
                    right: -15px;
                    width: 35px;
                    height: 35px;
                    background: linear-gradient(45deg, #f7931e, #ff6b35);
                    border-radius: 50%;
                    box-shadow: 0 0 25px rgba(247, 147, 30, 0.5);
                }
            </style>

            <!-- Ana Modal Container -->
            <div
                class="fortune-wheel-container bg-white rounded-2xl shadow-2xl p-8 max-w-4xl w-full text-center relative overflow-hidden">
                <button id="closeFortuneWheel" class="close-button">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <div class="relative z-10 mb-6">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">ŞANS ÇARKI</h2>
                    <p class="text-lg text-gray-600">Şansını dene, büyük hediyeni kazan!</p>
                    <div class="w-24 h-1 bg-gradient-to-r from-orange-500 to-red-500 mx-auto mt-3 rounded-full"></div>
                </div>

                <div class="wheel-container relative z-10">
                    <div class="wheel-pointer"></div>
                    <div id="fortuneWheel" class="wheel">

                        <svg width="100%" height="100%" viewBox="0 0 400 400"
                            class="transform transition-transform duration-[6s] ease-out">
                            <!-- Segment 1: %20 İNDİRİM -->
                            <g>
                                <path d="M 200,200 L 200,0 A 200,200 0 0,1 341.42,58.58 z" fill="#ff6b35" stroke="#fff"
                                    stroke-width="4" />
                                <text x="250" y="65" fill="white" font-size="8" font-weight="800" text-anchor="middle"
                                    transform="rotate(22.5 250 65)">
                                    %20
                                </text>
                            </g>

                            <!-- Segment 2: TEKRAR DENE -->
                            <g>
                                <path d="M 200,200 L 341.42,58.58 A 200,200 0 0,1 400,200 z" fill="#f7931e" stroke="#fff"
                                    stroke-width="4" />
                                <text x="325" y="125" fill="white" font-size="7" font-weight="800" text-anchor="middle"
                                    transform="rotate(67.5 300 125)">
                                    TEKRAR
                                </text>
                            </g>

                            <!-- Segment 3: %30 İNDİRİM -->
                            <g>
                                <path d="M 200,200 L 400,200 A 200,200 0 0,1 341.42,341.42 z" fill="#ff8c42" stroke="#fff"
                                    stroke-width="4" />
                                <text x="275" y="275" fill="white" font-size="10" font-weight="800" text-anchor="middle"
                                    transform="rotate(112.5 300 275)">
                                    %30
                                </text>
                            </g>

                            <!-- Segment 4: 1 ÖZEL DERS -->
                            <g>
                                <path d="M 200,200 L 341.42,341.42 A 200,200 0 0,1 200,400 z" fill="#fd9644" stroke="#fff"
                                    stroke-width="4" />
                                <text x="250" y="335" fill="white" font-size="10" font-weight="800" text-anchor="middle"
                                    transform="rotate(157.5 250 335)">
                                    ÖZEL DERS
                                </text>
                            </g>

                            <!-- Segment 5: %40 İNDİRİM -->
                            <g>
                                <path d="M 200,200 L 200,400 A 200,200 0 0,1 58.58,341.42 z" fill="#e17055" stroke="#fff"
                                    stroke-width="4" />
                                <text x="150" y="335" fill="white" font-size="8" font-weight="800" text-anchor="middle"
                                    transform="rotate(202.5 150 335)">
                                    %40
                                </text>
                            </g>

                            <!-- Segment 6: TEKRAR DENE -->
                            <g>
                                <path d="M 200,200 L 58.58,341.42 A 200,200 0 0,1 0,200 z" fill="#ff9ff3" stroke="#fff"
                                    stroke-width="4" />
                                <text x="125" y="275" fill="white" font-size="6" font-weight="800" text-anchor="middle"
                                    transform="rotate(247.5 100 275)">
                                    TEKRAR
                                </text>
                            </g>

                            <!-- Segment 7: %50 İNDİRİM -->
                            <g>
                                <path d="M 200,200 L 0,200 A 200,200 0 0,1 58.58,58.58 z" fill="#00b894" stroke="#fff"
                                    stroke-width="4" />
                                <text x="75" y="125" fill="white" font-size="8" font-weight="800" text-anchor="middle"
                                    transform="rotate(292.5 100 125)">
                                    %50
                                </text>
                            </g>

                            <!-- Segment 8: 1 ÖZEL DERS -->
                            <g>
                                <path d="M 200,200 L 58.58,58.58 A 200,200 0 0,1 200,0 z" fill="#6c5ce7" stroke="#fff"
                                    stroke-width="4" />
                                <text x="150" y="65" fill="white" font-size="7" font-weight="800" text-anchor="middle"
                                    transform="rotate(337.5 150 65)">
                                    ÖZEL DERS
                                </text>
                            </g>
                        </svg>
                    </div>
                    <div class="wheel-center">ÇEVİR</div>
                </div>

                <button id="fortuneSpinButton" class="spin-button pulse-animation">
                    ÇARKI ÇEVİR
                </button>

                <p class="text-sm text-gray-500 mt-4">
                    Çarkı çevir ve özel fırsatları yakala! Büyük hediyeler seni bekliyor.
                </p>
            </div>
        </div>

        <div id="fortuneResultModal"
            class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
            style="display: none;">
            <div
                class="fortune-wheel-container bg-white rounded-2xl p-8 max-w-lg w-full text-center relative overflow-hidden">
                <!-- Dekoratif arka plan -->
                <div
                    class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-orange-200 to-red-200 rounded-full -translate-y-10 translate-x-10 opacity-70">
                </div>

                <div class="relative z-10">
                    <!-- Kazanma Durumu -->
                    <div id="fortuneWinContent" class="hidden">
                        <div class="text-6xl mb-4">🎉</div>
                        <h3 class="text-2xl font-bold text-orange-600 mb-4">TEBRİKLER!</h3>
                        <div id="fortunePrizeText" class="text-xl font-semibold text-gray-800 mb-4"></div>
                        <div class="code-display">
                            <div class="text-sm text-gray-200 mb-2">Kodunuz:</div>
                            <div id="fortunePrizeCode"></div>
                        </div>
                        <p class="text-sm text-gray-600 mb-6">
                            Bu kodu WhatsApp'tan göndererek hediyenizi talep edebilirsiniz!
                        </p>
                        <button id="fortuneWhatsappButton"
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-6 rounded-lg transition-colors duration-300 mb-3 text-lg">
                            🎁 HEDİYEMİ TALEP ET
                        </button>
                    </div>

                    <!-- Tekrar Deneme Durumu -->
                    <div id="fortuneRetryContent" class="hidden">
                        <div class="text-6xl mb-4">😊</div>
                        <h3 class="text-2xl font-bold text-orange-600 mb-4">Bu Sefer Olmadı!</h3>
                        <p class="text-lg text-gray-600 mb-6">
                            Üzülme! Bir kez daha şansını deneyebilirsin.
                        </p>
                        <button id="fortuneRetryButton"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-6 rounded-lg transition-colors duration-300 mb-3 text-lg">
                            🎯 TEKRAR DENE
                        </button>
                    </div>

                    <button id="closeFortuneResult"
                        class="w-full bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-300">
                        KAPAT
                    </button>
                </div>
            </div>
        </div>
    @endif
    <div id="demoModal" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 p-4"
        style="background: rgba(0, 0, 0, 0.3);">
        <div
            class="bg-white rounded-2xl max-w-md w-full mx-4 overflow-hidden shadow-2xl transform transition-all duration-300 scale-100 relative">
            <button id="closeModal" class="absolute top-3 right-3 w-10 h-10 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full transition-all duration-200 hover:rotate-90 z-20 shadow-md flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="bg-gradient-to-r from-[#e63946] to-red-500 px-6 py-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full opacity-10">
                    <div class="absolute top-4 left-4 w-8 h-8 bg-white rounded-full"></div>
                    <div class="absolute top-8 right-8 w-4 h-4 bg-white rounded-full"></div>
                    <div class="absolute bottom-4 left-8 w-6 h-6 bg-white rounded-full"></div>
                </div>

                <div class="relative z-10">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-white bg-opacity-20 rounded-full mb-4">
                        <span class="text-3xl">🎓</span>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Ücretsiz Demo Ders</h2>
                    <p class="text-white text-opacity-90">Kurucu Hocamızdan</p>
                </div>
            </div>

            <div class="p-6 text-center">
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#1a2e5a] mb-3">Bizimle İletişime Geçebilirsin</h3>
                    <p class="text-gray-600 mb-6">Kurucu Hocamızdan ücretsiz Demo ders randevusu al! Hemen WhatsApp'tan
                        iletişime geç.</p>
                </div>

                <button id="whatsappBtn"
                    class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center space-x-3">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.594z" />
                    </svg>
                    <span>WhatsApp ile İletişime Geç</span>
                </button>

                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">
                        <span class="font-semibold text-[#e63946]">Ücretsiz Demo Ders</span> almak için hemen mesaj atın!
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div id="successMessage"
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded fixed top-4 right-4 shadow-lg z-50 transform transition-transform duration-300 ease-in-out">
            <div class="flex items-center">
                <div class="py-1">
                    <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>{{ session('success') }}</div>
                <button onclick="closeSuccessMessage()"
                    class="ml-4 text-green-700 hover:text-green-900 focus:outline-none">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="successMessageProgress" class="h-1 bg-green-500 mt-2 w-full transform origin-left"></div>
        </div>
    @endif
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)" />
            </svg>
        </div>

<div class="relative py-20 overflow-hidden hero-section">
    <!-- Placeholder gradient - Hemen yüklenir -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#1a2e5a] via-[#2d4373] to-[#1a2e5a]"></div>
    
    <!-- Asıl arka plan resmi - Lazy load -->
    <div class="absolute inset-0 hero-bg opacity-0 transition-opacity duration-700" 
         data-bg="{{ asset('images/free.webp') }}"
         data-bg-fallback="{{ asset('images/free.jpg') }}">
    </div>

    <div class="absolute inset-0 bg-black opacity-30"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col md:flex-row items-center md:space-x-12"> <!-- space-x eklendi -->
            <div class="w-full md:w-1/2 text-center md:text-left mb-12 md:mb-0">
                @if (auth()->check() && auth()->user()->hasRole('ogrenci'))
                    <div class="mb-6"> <!-- mb-4 -> mb-6 artırıldı -->
                        <span
                            class="bg-[#e63946] text-white text-xl px-4 py-2 rounded-lg shadow-lg inline-block transform -rotate-2 hover:rotate-0 transition-transform duration-300 font-bold">
                            <i class="fas fa-graduation-cap mr-2"></i>KURSLARINIZ
                        </span>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                        Öğrenmeye <span class="text-[#e63946]">Devam</span> Edin!
                    </h1>
                    <p class="text-xl text-white mb-8 max-w-lg mx-auto md:mx-0">
                        Eğitim yolculuğunuzda size yardımcı olmak için buradayız. Kurslarınıza hemen erişin.
                    </p>
                @else
                    <!-- GİRİŞ YAPMAYAN KULLANICI İÇİN STANDART MESAJ -->
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                        Rise English ile <span class="text-[#e63946]">Öğrenmeye</span> Başlayın
                    </h1>
                    <p class="text-xl text-white mb-8 max-w-lg mx-auto md:mx-0">
                        Eğitim platformumuzda profesyonel eğitmenlerle yeni beceriler kazanın ve kariyerinizde bir
                        adım
                        öne çıkın.
                    </p>
                @endif

                <div class="relative rounded-xl overflow-hidden shadow-xl mb-8 mt-8"> <!-- mt-8 eklendi -->
                    <div class="relative pb-[56.25%]"> <!-- 16:9 aspect ratio -->
                        <div class="video-thumbnail absolute inset-0" data-video-id="VRqM2zyqJeI">
                            <img src="https://i.ytimg.com/vi/VRqM2zyqJeI/hqdefault.jpg" alt="Rise English Tanıtım"
                                class="w-full h-full object-cover">

                            <div
                                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-20 h-20 rounded-full bg-[#e63946] flex items-center justify-center z-10 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>

                            <div class="absolute inset-0  bg-opacity-30"></div>
                        </div>
                        <div class="video-iframe-container absolute inset-0 hidden"></div>
                    </div>

                    <div class="absolute top-4 right-4">
                        <div class="bg-[#e63946] text-white text-sm font-bold py-1 px-3 rounded-full shadow-lg">
                            <i class="fas fa-play-circle mr-1"></i> Tanıtım Videosu
                        </div>
                    </div>
                </div>

                <!-- Buton Bölümü - En Altta -->
                <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-4 mt-8">
                    <!-- mt-8 eklendi -->
                    @if (auth()->check() && auth()->user()->hasRole('ogrenci'))
                        <!-- Giriş yapmış öğrenci için kurslarım butonu -->
                        <a href="{{ url('/ogrenci/kurslarim') }}"
                            class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-4 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                            <i class="fas fa-book-reader mr-2"></i>Kurslarıma Git
                        </a>
                        <a href="{{ url('/egitimler') }}"
                            class="bg-white hover:bg-gray-100 text-[#1a2e5a] font-bold py-4 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                            <i class="fas fa-book-open mr-2"></i>Yeni Eğitimler
                        </a>
                    @else
                        <!-- Giriş yapmamış kullanıcı için standart butonlar -->
                        <a href="{{ url('/egitimler') }}"
                            class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-4 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                            Eğitimleri Keşfet
                        </a>
                        <a href="{{ url('/kayit-ol') }}"
                            class="bg-white hover:bg-gray-100 text-[#1a2e5a] font-bold py-4 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                            Hemen Başla
                        </a>
                    @endif
                </div>

                <!-- Giriş yapmamış kullanıcılar için indirim banner'ı -->
                @if (!auth()->check() || !auth()->user()->hasRole('ogrenci'))
                    <div
                        class="mt-6 bg-gradient-to-r from-[#e63946] to-[#d62836] rounded-lg p-3 shadow-lg transform -rotate-1 hover:rotate-0 transition-transform duration-300 mx-auto sm:mx-0 max-w-xs">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-white font-bold text-lg">%40 İNDİRİM</div>
                                <div class="text-xs text-white opacity-90">Tüm eğitimlerde geçerli</div>
                            </div>
                            <div class="bg-white text-[#e63946] text-xs font-bold py-1 px-3 rounded-full shadow">
                                RiseEnglish
                            </div>
                        </div>
                        <div class="w-full h-1 bg-white bg-opacity-30 mt-2 rounded-full overflow-hidden">
                            <div class="w-1/2 h-full bg-white rounded-full animate-pulse"></div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="w-full md:w-1/2">
                <div class="relative">
                    <!-- Tüm kullanıcılar için İdiom görsel -->
                    <div class="w-full bg-white rounded-lg shadow-xl overflow-hidden border border-gray-200">
                        <!-- Üst başlık - Genel site tasarımına uygun -->
                        <div class="p-4 bg-[#1a2e5a] text-center relative">
                            <h2 class="text-2xl font-bold text-white">IDIOM OF THE DAY</h2>

                            <div class="absolute -right-2 top-2 transform rotate-12">
                                <div
                                    class="bg-[#e63946] text-white text-xs font-bold py-1 px-3 rounded-full shadow-lg">
                                    RiseEnglish
                                </div>
                            </div>
                        </div>

                        <!-- İdiom Gösterim Alanı -->
                        <div class="p-6 bg-gray-50">
                            @if (isset($dailyIdiom))
                                <!-- İngilizce İdiom -->
                                <div class="bg-white rounded-lg p-4 mb-4 shadow-md border-l-4 border-[#e63946]">
                                    <div class="text-xl font-bold text-[#1a2e5a] mb-1">
                                        "{{ $dailyIdiom->english_phrase }}"</div>
                                    <div class="text-md text-gray-500 italic">
                                        {{ $dailyIdiom->turkish_translation }}
                                    </div>
                                </div>

                                <!-- Örnek Cümleler -->
                                <div class="bg-white rounded-lg p-4 shadow-md border-l-4 border-[#1a2e5a]">
                                    <div class="text-lg font-bold text-[#1a2e5a]">Örnek Cümleler:</div>
                                    <div class="text-md text-gray-600 mt-2">- {{ $dailyIdiom->example_sentence_1 }}
                                    </div>
                                    @if ($dailyIdiom->example_sentence_2)
                                        <div class="text-md text-gray-600">- {{ $dailyIdiom->example_sentence_2 }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Karakter Görseli - Ortalanmış -->
                                <div class="relative mt-6 flex justify-center">
                                    @if ($dailyIdiom->image_path)
                                        <img src="{{ asset('storage/' . $dailyIdiom->image_path) }}"
                                            alt="İdiom Görseli" class="h-80 object-contain z-10">
                                    @else
                                        <img src="{{ asset('images/1.jpg') }}" alt="Varsayılan İdiom Görseli"
                                            class="h-80 object-contain z-10">
                                    @endif
                                    <div class="absolute top-0 right-10 animate-bounce z-20">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#e63946]"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                    </div>
                                </div>
                            @else
                                <!-- Veri yoksa gösterilecek alan -->
                                <div class="bg-white rounded-lg p-4 shadow-md text-center">
                                    <div class="text-lg text-gray-500 italic">Bugün için deyim bulunamadı.</div>
                                </div>
                            @endif
                        </div>

                        <!-- Alt Banner -->
                        <div class="py-3 px-4 bg-gray-100 text-center relative border-t border-gray-200">
                            <span class="inline-block text-[#1a2e5a] font-medium">
                                Günlük İngilizce Deyimi
                            </span>
                        </div>
                    </div>

                    <!-- Kullanıcı türüne göre farklı bilgi kutuları -->
                    @if (auth()->check() && auth()->user()->hasRole('ogrenci'))
                        <!-- Aktif kurs sayısı kutusu - Giriş yapmış öğrenci için -->
                        <div
                            class="absolute -top-4 -left-4 bg-[#1a2e5a] text-white rounded-lg p-3 shadow-lg transform rotate-3 hover:rotate-0 transition-transform duration-300">
                            <div class="flex items-center">
                                <i class="fas fa-book-open mr-2"></i>
                                <div>
                                    <div class="text-lg font-bold">Aktif Kurslar</div>
                                    <div class="text-2xl font-extrabold">
                                        {{ auth()->user()->enrolledCourses()->where('is_active', true)->count() }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kişiselleştirilmiş animasyonlu vurgu kutusu - Giriş yapmış öğrenci için -->
                        <div class="absolute -bottom-4 -right-4 bg-white rounded-lg p-4 shadow-lg">
                            <div class="flex items-center">
                                <div class="bg-[#e63946] rounded-full h-4 w-4 mr-2 animate-pulse"></div>
                                <span class="font-bold text-[#1a2e5a]">Eğitiminize Devam Edin!</span>
                            </div>
                        </div>
                    @else
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
        <div class="relative bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] py-16 overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <defs>
                        <pattern id="video-grid" width="10" height="10" patternUnits="userSpaceOnUse">
                            <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
                        </pattern>
                    </defs>
                    <rect width="100" height="100" fill="url(#video-grid)" />
                </svg>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-white mb-2">Öğrencilerimiz Ne Dedi?</h2>
                    <div class="w-20 h-1 bg-[#e63946] mx-auto"></div>
                    <p class="mt-4 text-blue-100 max-w-2xl mx-auto">Başarı hikayelerini öğrencilerimizden dinleyin.</p>
                </div>

                <div class="student-videos-slider relative">
                    <div class="slider-controls">
                        <button id="prevVideo"
                            class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-5 bg-white p-3 rounded-full shadow-lg z-10 text-[#1a2e5a]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button id="nextVideo"
                            class="absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-5 bg-white p-3 rounded-full shadow-lg z-10 text-[#1a2e5a]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                    <div class="video-slides-container overflow-hidden">
                        <div id="videoSlidesWrapper" class="flex transition-transform duration-500 ease-in-out">

                            <div class="video-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                    <div class="relative pb-[56.25%]"> <!-- 16:9 aspect ratio -->
                                        <div class="video-thumbnail absolute inset-0" data-video-id="Kw0ezq06ruU">
                                            <img src="https://i.ytimg.com/vi/Kw0ezq06ruU/hqdefault.jpg"
                                                alt="Video thumbnail" class="w-full h-full object-cover">

                                            <div
                                                class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-[#e63946] flex items-center justify-center z-10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="video-iframe-container absolute inset-0 hidden"></div>
                                    </div>
                                    <div class="p-4 text-center bg-[#1a2e5a] text-white">
                                        <h3 class="font-semibold">EREĞLİ YÖK DİL BİRİNCİMİZ</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="video-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                    <div class="relative pb-[56.25%]"> <!-- 16:9 aspect ratio -->
                                        <div class="video-thumbnail absolute inset-0" data-video-id="WMfARGd1fkQ">
                                            <img src="https://i.ytimg.com/vi/WMfARGd1fkQ/hqdefault.jpg"
                                                alt="Video thumbnail" class="w-full h-full object-cover">

                                            <div
                                                class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-[#e63946] flex items-center justify-center z-10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="video-iframe-container absolute inset-0 hidden"></div>
                                    </div>
                                    <div class="p-4 text-center bg-[#1a2e5a] text-white">
                                        <h3 class="font-semibold">Öğrencilerimiz</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="video-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                    <div class="relative pb-[56.25%]"> <!-- 16:9 aspect ratio -->
                                        <div class="video-thumbnail absolute inset-0" data-video-id="cVPIqxeLPWI">
                                            <img src="https://i.ytimg.com/vi/cVPIqxeLPWI/hqdefault.jpg"
                                                alt="Video thumbnail" class="w-full h-full object-cover">

                                            <div
                                                class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-[#e63946] flex items-center justify-center z-10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="video-iframe-container absolute inset-0 hidden"></div>
                                    </div>
                                    <div class="p-4 text-center bg-[#1a2e5a] text-white">
                                        <h3 class="font-semibold">Öğrencilerimiz</h3>
                                    </div>
                                </div>
                            </div>


                            <div class="video-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                    <div class="relative pb-[56.25%]"> <!-- 16:9 aspect ratio -->
                                        <div class="video-thumbnail absolute inset-0" data-video-id="GBxGfpVM5E8">
                                            <img src="https://i.ytimg.com/vi/GBxGfpVM5E8/hqdefault.jpg"
                                                alt="Video thumbnail" class="w-full h-full object-cover">

                                            <div
                                                class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-[#e63946] flex items-center justify-center z-10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="video-iframe-container absolute inset-0 hidden"></div>
                                    </div>
                                    <div class="p-4 text-center bg-[#1a2e5a] text-white">
                                        <h3 class="font-semibold">Öğrencilerimiz</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="video-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                                    <div class="relative pb-[56.25%]"> <!-- 16:9 aspect ratio -->
                                        <div class="video-thumbnail absolute inset-0" data-video-id="cVPIqxeLPWI">
                                            <img src="https://i.ytimg.com/vi/cVPIqxeLPWI/hqdefault.jpg"
                                                alt="Video thumbnail" class="w-full h-full object-cover">


                                            <div
                                                class="play-button absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-[#e63946] flex items-center justify-center z-10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="video-iframe-container absolute inset-0 hidden"></div>
                                    </div>
                                    <div class="p-4 text-center bg-[#1a2e5a] text-white">
                                        <h3 class="font-semibold">Öğrencilerimiz</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="flex justify-center mt-6">
                        <div id="videoSliderDots" class="flex space-x-2">
                            <!-- Dots will be added with JS -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="bg-white py-16">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-12">

                <div class="w-full md:w-2/5 flex justify-center mb-8 md:mb-0">
                    <img src="{{ asset('images/teacherwelcome.jpg') }}" alt="English Teacher"
                        class="rounded-lg shadow-lg w-full h-auto object-cover">
                </div>


                <div class="w-full md:w-3/5">
                    <h2 class="text-3xl font-bold text-[#1a2e5a] mb-6">Welcome to Rise English!</h2>
                    <div class="mb-8 text-gray-700">
                        <p class="mb-4">As the founder of Rise English, I am proud to present a platform designed not
                            just to teach English, but to inspire confidence, growth, and real communication skills. Our
                            mission is simple: to help every learner rise to their full potential through quality,
                            personalized, and motivating English education.</p>
                        <p class="mb-4">At Rise English, we believe language learning should be engaging, practical, and
                            goal-oriented. Whether you're preparing for an exam, improving your speaking, or starting from
                            scratch — we are here to guide you every step of the way.</p>
                        <p class="mb-4">This journey started with a passion for education and a belief that with the
                            right support, anyone can master English. I'm excited to see how far we can go — together.</p>
                        <p class="mb-2 font-semibold">Let's rise, learn, and grow</p>
                        <p class="font-bold text-[#e63946]">Hakan Ekinci</p>
                    </div>

                    <div class="pt-6 border-t border-gray-200">
                        <h2 class="text-3xl font-bold text-[#1a2e5a] mb-6">Rise English'e Hoş Geldiniz!</h2>
                        <div class="text-gray-700">
                            <p class="mb-4">Rise English'in kurucusu olarak sizlere sadece bir dil kursu değil, aynı
                                zamanda özgüven kazandıran, gelişimi destekleyen ve gerçek iletişim becerileri kazandıran
                                bir öğrenme ortamı sunmaktan gurur duyuyorum. Amacımız basit: Her öğrencinin kendi
                                potansiyelini keşfetmesine yardımcı olmak ve onu en iyi şekilde ortaya çıkarmak.</p>
                            <p class="mb-4">Rise English'te dil öğrenmenin ilham verici, pratik ve hedef odaklı olması
                                gerektiğine inanıyoruz. İster sınava hazırlanıyor olun, ister konuşma becerilerinizi
                                geliştirmek ya da sıfırdan başlamak istiyor olun — bu yolculukta her adımda yanınızdayız.
                            </p>
                            <p class="mb-4">Bu platform, eğitime duyduğum tutku ve doğru destekle herkesin İngilizceyi
                                öğrenebileceğine olan inancımla doğdu. Şimdi birlikte ne kadar yol kat edebileceğimizi
                                görmek için sabırsızlanıyorum.</p>
                            <p class="font-bold text-[#e63946]">Hakan Ekinci</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto px-4 py-16 bg-gray-50">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-[#1a2e5a] mb-2">Öne Çıkan Eğitimler</h2>
            <div class="w-20 h-1 bg-[#e63946] mx-auto"></div>
            <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Profesyonel eğitmenlerimiz tarafından hazırlanan kaliteli ve
                güncel içeriklerle kariyer hedeflerinize bir adım daha yaklaşın.</p>
        </div>


        <div class="relative">

            <div class="hidden md:block">
                <button id="prevButton"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-5 bg-white p-3 rounded-full shadow-lg z-10 text-[#1a2e5a]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button id="nextButton"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-5 bg-white p-3 rounded-full shadow-lg z-10 text-[#1a2e5a]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>


            <div class="slider-container overflow-hidden">
                <div id="slidesWrapper" class="flex transition-transform duration-500 ease-in-out">
                    @forelse($featuredCourses as $course)
                        <div class="slider-item flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                            <div
                                class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-2 group h-full">
                                <div class="h-48 bg-gray-200 relative overflow-hidden">
                                    @if ($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                            alt="{{ $course->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="flex items-center justify-center h-full bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                    @endif
                                    @if ($course->discount_price)
                                        <div
                                            class="absolute top-2 right-2 bg-[#e63946] text-white px-3 py-1 rounded-full font-bold text-sm">
                                            %{{ number_format((($course->price - $course->discount_price) / $course->price) * 100) }}
                                            İNDİRİM
                                        </div>
                                    @endif


                                    @php
                                        $today = \Carbon\Carbon::today();
                                        $startDate = \Carbon\Carbon::parse($course->start_date);
                                        $endDate = \Carbon\Carbon::parse($course->end_date);
                                        $daysLeft = $today->diffInDays($startDate, false);
                                    @endphp

                                    @if ($startDate->isPast() && $endDate->isFuture())
                                        <div
                                            class="absolute top-2 left-2 bg-[#44bd32] text-white text-xs font-bold px-2 py-1 rounded-full">
                                            DEVAM EDİYOR
                                        </div>
                                    @elseif($startDate->isPast() && $endDate->isPast())
                                        <div
                                            class="absolute top-2 left-2 bg-[#718093] text-white text-xs font-bold px-2 py-1 rounded-full">
                                            TAMAMLANDI
                                        </div>
                                    @elseif($daysLeft <= 7 && $daysLeft > 0)
                                        <div
                                            class="absolute top-2 left-2 bg-[#e1b12c] text-white text-xs font-bold px-2 py-1 rounded-full">
                                            {{ $daysLeft }} GÜN KALDI
                                        </div>
                                    @elseif($daysLeft == 0)
                                        <div
                                            class="absolute top-2 left-2 bg-[#c23616] text-white text-xs font-bold px-2 py-1 rounded-full">
                                            BUGÜN BAŞLIYOR
                                        </div>
                                    @endif


                                    <div class="absolute bottom-2 left-2 flex space-x-2">
                                        @if ($course->courseType)
                                            <span
                                                class="bg-[#1a2e5a] text-white text-xs font-bold px-2 py-1 rounded">{{ $course->courseType->name }}</span>
                                        @endif
                                        @if ($course->courseLevel)
                                            <span
                                                class="bg-[#e63946] text-white text-xs font-bold px-2 py-1 rounded">{{ $course->courseLevel->name }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="p-6">
                                    <h3 class="text-xl font-semibold mb-2 text-[#1a2e5a]">{{ $course->name }}</h3>
                                    <p class="text-gray-600 mb-4 text-sm h-12 overflow-hidden">
                                        {{ Str::limit($course->description, 100) }}</p>

                                    <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex items-center mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="font-medium text-[#1a2e5a]">Eğitim Tarihleri</span>
                                        </div>

                                        @if ($course->start_date && $course->end_date)
                                            <div class="grid grid-cols-2 gap-2 text-sm">
                                                <div>
                                                    <span class="text-gray-500">Başlangıç:</span>
                                                    <span
                                                        class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500">Bitiş:</span>
                                                    <span
                                                        class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($course->end_date)->format('d.m.Y') }}</span>
                                                </div>
                                            </div>

                                            @php
                                                $totalDuration = $startDate->diffInDays($endDate);
                                            @endphp

                                            <div class="mt-2">
                                                @if ($startDate->isPast() && $endDate->isFuture())
                                                    <!-- Kurs devam ediyor -->
                                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                        @php
                                                            $elapsed = $today->diffInDays($startDate);
                                                            $progress = ($elapsed / $totalDuration) * 100;
                                                            $progress = min(100, max(0, $progress));
                                                        @endphp
                                                        <div class="bg-[#44bd32] h-2 rounded-full"
                                                            style="width: {{ $progress }}%"></div>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        <span class="font-medium">Eğitim devam ediyor</span>
                                                    </p>
                                                @elseif($startDate->isFuture())
                                                    <!-- Kurs başlamadı -->
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        @if ($daysLeft == 0)
                                                            <span class="font-medium text-[#e63946]">Bugün başlıyor!</span>
                                                        @elseif($daysLeft == 1)
                                                            <span class="font-medium text-[#e63946]">Yarın başlıyor!</span>
                                                        @else
                                                            <span class="font-medium text-[#1a2e5a]">{{ $daysLeft }}
                                                                gün</span> sonra başlayacak
                                                        @endif
                                                    </p>
                                                @else
                                                    <!-- Kurs tamamlandı -->
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        <span class="font-medium">Eğitim tamamlandı</span>
                                                    </p>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-xs text-gray-500">Tarih bilgisi bulunmamaktadır.</p>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap items-center text-sm text-gray-500 mb-4 gap-3">
                                        <!-- Öğretmen bilgisi -->
                                        @if ($course->teacher)
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                {{ $course->teacher->name }}
                                            </div>
                                        @endif

                                        <!-- Toplam saat -->
                                        @if ($course->total_hours)
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $course->total_hours }} Saat
                                            </div>
                                        @endif

                                        <!-- Kurs sıklığı -->
                                        @if ($course->courseFrequency)
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $course->courseFrequency->name }}
                                            </div>
                                        @endif

                                        <!-- Sertifika bilgisi -->
                                        @if ($course->has_certificate)
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                                Sertifikalı
                                            </div>
                                        @endif

                                        <!-- Kontenjan bilgisi -->
                                        @if ($course->max_students)
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                {{ $course->max_students }} Kişi
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex justify-between items-center">
                                        {{-- <div>
                                  @if ($course->discount_price)
                                      <span class="text-gray-500 line-through text-sm">{{ number_format($course->price, 2) }} ₺</span>
                                      <span class="text-[#e63946] font-bold ml-2">{{ number_format($course->discount_price, 2) }} ₺</span>
                                  @else
                                      <span class="text-[#1a2e5a] font-bold">{{ number_format($course->price, 2) }} ₺</span>
                                  @endif
                              </div> --}}
                                        <a href="{{ url('/egitimler/' . $course->slug) }}"
                                            class="bg-[#e63946] hover:bg-[#d32836] text-white px-4 py-2 rounded-lg transition-colors duration-300 font-medium text-sm">Detayları
                                            Gör</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="w-full text-center py-12 bg-white rounded-lg shadow">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-16 w-16 text-[#1a2e5a] opacity-60 mx-auto mb-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="text-lg text-[#1a2e5a] font-medium">Henüz öne çıkan eğitim bulunmamaktadır.</p>
                            <p class="text-gray-500 mt-2">Lütfen daha sonra tekrar kontrol edin.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="flex justify-center mt-6 md:hidden">
                <div class="flex justify-between items-center w-full max-w-xs mb-3">
                    <button id="mobilePrevButton"
                        class="bg-white p-3 rounded-full shadow-lg text-[#1a2e5a] focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div id="sliderDots" class="flex space-x-2">
                        <!-- Dots will be added with JS -->
                    </div>

                    <button id="mobileNextButton"
                        class="bg-white p-3 rounded-full shadow-lg text-[#1a2e5a] focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-10 text-center">
            <a href="{{ url('/egitimler') }}"
                class="inline-block bg-[#1a2e5a] hover:bg-[#0f1d3a] text-white px-6 py-3 rounded-lg font-medium transition-colors duration-300">
                Tüm Eğitimleri Görüntüle
            </a>
        </div>
    </div>

    @if (auth()->check() && auth()->user()->hasRole('ogrenci'))
        <div class="container mx-auto px-4 py-16 bg-gray-100">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-[#1a2e5a] mb-2">Kurslarınız</h2>
                <div class="w-20 h-1 bg-[#e63946] mx-auto"></div>
                <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Eğitimlerinize hızlıca erişin ve öğrenme yolculuğunuza
                    devam edin.</p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-lg">
                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-[#1a2e5a]">
                        <i class="fas fa-graduation-cap mr-2"></i>Devam Eden Kurslarınız
                    </h3>
                    <a href="{{ url('/ogrenci/kurslarim') }}"
                        class="text-[#e63946] hover:text-[#d32836] font-medium flex items-center">
                        Tümünü Görüntüle
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="space-y-4">
                    @php
                        // Sadece aktif ve onaylanmış kayıtları getir
                        $enrolledCourses = auth()
                            ->user()
                            ->enrolledCourses()
                            ->wherePivot('approval_status', 'approved') // Onaylanmış kayıtlar
                            ->where(function ($query) {
                                $query
                                    ->where('end_date', '>=', now()) // Bitiş tarihi bugünden sonra olanlar
                                    ->orWhereNull('end_date'); // Veya bitiş tarihi belirtilmemiş olanlar
                            })
                            ->where('is_active', true) // Aktif kurslar
                            ->take(3)
                            ->get();
                    @endphp

                    @forelse($enrolledCourses as $course)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-semibold text-[#1a2e5a]">{{ $course->name }}</h4>
                                    <div class="text-sm text-gray-500 mt-1">
                                        @if ($course->start_time && $course->end_time)
                                            {{ \Carbon\Carbon::parse($course->start_time)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($course->end_time)->format('H:i') }}
                                        @endif
                                        @if ($course->courseFrequency)
                                            {{ $course->courseFrequency->name }}
                                        @endif
                                    </div>
                                </div>
                                <div class="flex space-x-3">
                                    <a href="{{ route('ogrenci.kurs-detay', $course->slug) }}"
                                        class="text-[#1a2e5a] hover:text-[#e63946] font-medium text-sm">Detaylar</a>
                                    @if ($course->meeting_link)
                                        <a href="{{ $course->meeting_link }}" target="_blank"
                                            class="bg-[#e63946] hover:bg-[#d32836] text-white px-3 py-1 rounded-lg text-sm font-medium">Derse
                                            Katıl</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-gray-50 p-6 rounded-lg text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <p class="text-gray-600">Henüz bir kursa kayıt olmamışsınız.</p>
                            <a href="{{ url('/egitimler') }}"
                                class="mt-3 inline-block text-[#e63946] font-medium hover:underline">Kursları keşfedin</a>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ url('/ogrenci/kurslarim') }}"
                        class="bg-[#1a2e5a] hover:bg-[#132447] text-white px-6 py-2 rounded-lg inline-flex items-center font-medium transition-colors duration-300">
                        <i class="fas fa-book-open mr-2"></i>Tüm Kurslarımı Görüntüle
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 py-16">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12">Ücretsiz İçerikler</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div
                            class="w-12 h-12 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-graduation-cap text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Uzman Eğitmenler</h3>
                        <p class="text-gray-600">Alanında uzman, deneyimli eğitmenlerden öğrenin.</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div
                            class="w-12 h-12 bg-green-100 text-green-800 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Esnek Öğrenme</h3>
                        <p class="text-gray-600">İstediğiniz zaman, istediğiniz yerden eğitimlerimize erişin.</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div
                            class="w-12 h-12 bg-purple-100 text-purple-800 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-certificate text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Sertifika</h3>
                        <p class="text-gray-600">Eğitimlerinizi tamamlayarak sertifika kazanın.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <div id="floatingSignupPanel"
        class="fixed left-4 bottom-4 md:left-8 md:bottom-8 z-50 w-72 md:w-80 bg-[#1a2e5a] rounded-lg overflow-visible shadow-2xl transform transition-all duration-500 hover:scale-105 group">

        <div class="absolute inset-0 opacity-0 group-hover:opacity-10 transition-opacity duration-500">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid-anim" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid-anim)" />
            </svg>
        </div>

        @if (!auth()->check() || !auth()->user()->hasRole('ogrenci'))
            <div
                class="absolute -top-4 -left-4 z-10 bg-[#e63946] text-white px-3 py-1 rounded-lg transform -rotate-12 shadow-md font-bold text-sm">
                %40 İNDİRİM
            </div>
        @endif


        <div class="p-6 text-white relative">
            <div class="absolute top-2 right-2">
                <button id="closeFloatingPanel" class="text-white opacity-70 hover:opacity-100 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            @if (auth()->check() && auth()->user()->hasRole('ogrenci'))
                <!-- Giriş yapmış öğrenci için basit panel -->
                <h3 class="text-xl font-bold mb-2 flex items-center">
                    <span class="inline-block w-2 h-2 bg-[#e63946] rounded-full mr-2 animate-pulse"></span>
                    Kurslarınız
                </h3>

                <p class="text-blue-100 mb-4 text-sm">Aktif kurslarınızı görüntüleyebilir veya yeni kurslara göz
                    atabilirsiniz.</p>

                <div class="flex flex-col space-y-2">
                    <a href="{{ url('/ogrenci/kurslarim') }}"
                        class="bg-[#e63946] hover:bg-[#d32836] text-white px-4 py-2 rounded-lg transition-colors duration-300 font-medium text-sm text-center">
                        <i class="fas fa-graduation-cap mr-1"></i>Kurslarıma Git
                    </a>
                    <a href="{{ url('/egitimler') }}"
                        class="bg-white text-[#1a2e5a] hover:bg-gray-100 px-4 py-2 rounded-lg transition-colors duration-300 font-medium text-sm text-center">
                        <i class="fas fa-search mr-1"></i>Yeni Kurslar Keşfet
                    </a>
                </div>
            @else
                <!-- Giriş yapmamış kullanıcı için üyelik paneli -->
                <h3 class="text-xl font-bold mb-2 flex items-center">
                    <span class="inline-block w-2 h-2 bg-[#e63946] rounded-full mr-2 animate-pulse"></span>
                    Eğitimlere Katılın
                </h3>

                <p class="text-blue-100 mb-4 text-sm">Yüzlerce eğitime sınırsız erişim için bugün Rise English'a katılın.
                </p>

                <div class="flex flex-col space-y-2">
                    <a href="{{ url('/kayit-ol') }}"
                        class="bg-[#e63946] hover:bg-[#d32836] text-white px-4 py-2 rounded-lg transition-colors duration-300 font-medium text-sm text-center">
                        Hemen Üye Olun
                    </a>
                    <a href="{{ url('/egitimler') }}"
                        class="bg-white text-[#1a2e5a] hover:bg-gray-100 px-4 py-2 rounded-lg transition-colors duration-300 font-medium text-sm text-center">
                        Tüm Kursları Görüntüle
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Düzeltilmiş Video Slider JavaScript - Tam Versiyon
        document.addEventListener('DOMContentLoaded', function() {

                const heroBg = document.querySelector('.hero-bg');
    if (heroBg) {
        const img = new Image();
        img.onload = function() {
            heroBg.style.backgroundImage = `url('${this.src}')`;
            heroBg.style.backgroundSize = 'cover';
            heroBg.style.backgroundPosition = 'center';
            heroBg.style.backgroundBlendMode = 'multiply';
            setTimeout(() => heroBg.style.opacity = '1', 50);
        };
        img.onerror = function() {
            const fallback = heroBg.getAttribute('data-bg-fallback');
            if (fallback && this.src !== fallback) {
                this.src = fallback;
            }
        };
        img.src = heroBg.getAttribute('data-bg');
    }
            // Success message auto-hide functionality
            initSuccessMessage();

            // Floating panel functionality
            initFloatingPanel();

            // Main featured courses slider functionality
            initMainSlider();

            // Student Videos slider functionality - YENİLENMİŞ VERSİYON
            initVideoSlider();

            initMainPromoVideo();

            // MODAL FUNCTIONALİTY - YENİ EKLENEN BÖLÜM
            initModalFunctionality();

            // ===== MODAL FUNCTIONS - YENİ EKLENEN =====
            // Demo modal kapatma işlevselliğini düzelten JavaScript kodu

            // MODAL FUNCTIONALİTY - Düzeltilmiş versiyon
            function initModalFunctionality() {
                const demoModal = document.getElementById('demoModal');
                const closeModalBtn = document.getElementById('closeModal');
                const whatsappBtn = document.getElementById('whatsappBtn');
                const modalContent = demoModal?.querySelector('.bg-white.rounded-2xl');

                console.log('Modal elementleri:', {
                    demoModal,
                    closeModalBtn,
                    whatsappBtn,
                    modalContent
                });

                // Kapama butonu event listener
                if (closeModalBtn) {
                    closeModalBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log('Kapama butonu tıklandı');
                        closeModal();
                    });
                }

                // WhatsApp butonu event listener
                if (whatsappBtn) {
                    whatsappBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log('WhatsApp butonu tıklandı');
                        openWhatsApp();
                    });
                }

                // Modal backdrop (arka plan) tıklama event listener
            if (demoModal) {
                demoModal.addEventListener('click', function(e) {
                    console.log('Modal backdrop tıklandı');
                    // Sadece arka plana basıldığında kapat
                    if (!modalContent.contains(e.target)) {
                        closeModal();
                    }
                });
            }


                // Modal içeriğine tıklamanın modal'ı kapatmasını engelle
                if (modalContent) {
                    modalContent.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                }

                // ESC tuşu ile kapama
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && demoModal && demoModal.style.display !== 'none') {
                        console.log('ESC tuşu ile modal kapatılıyor');
                        closeModal();
                    }
                });
            }

// closeModal fonksiyonunu güncelle
function closeModal() {
    console.log('closeModal fonksiyonu çağrıldı');
    const modal = document.getElementById('demoModal');
    if (modal) {
        modal.style.display = 'none';
        console.log('Modal kapatıldı');

        // Body scroll'unu geri getir
        document.body.style.overflow = '';

        // BANNER'I GÖSTER - 1.5 saniye sonra
        setTimeout(() => {
            showProBanner();
        }, 1500);

        // Sadece giriş yapmayan kullanıcılar için şans çarkını göster
        // Banner'dan sonra
        @if (!auth()->check())
            setTimeout(() => {
                if (!hasSeenFortuneWheel()) {
                    showFortuneWheel();
                    setFortuneWheelSeen();
                }
            }, 8000); // Banner gösterildikten 6.5 saniye sonra (1.5 + 6.5 = 8)
        @endif
    }
}

function showProBanner() {
    const bannerHTML = `
    <div id="proBannerModal" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 p-4"
        style="background:rgba(0,0,0,0.6);opacity:0;transition:opacity 0.35s ease;">
        <div id="proBannerInner" style="max-width:860px;width:100%;margin:0 16px;position:relative;transform:scale(0.85) translateY(30px);opacity:0;transition:transform 0.45s cubic-bezier(0.34,1.56,0.64,1),opacity 0.35s ease;">

            <button id="closeProBanner" class="absolute -top-4 -right-4 w-12 h-12 bg-white hover:bg-gray-100 rounded-full transition-all duration-200 hover:rotate-90 z-20 shadow-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div style="border-radius:20px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,0.4);">

                <!-- BASLIK -->
                <div style="background:linear-gradient(135deg,#1a2e5a 0%,#2a4a7f 60%,#1a2e5a 100%);padding:22px 32px;text-align:center;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:-20px;left:-20px;width:120px;height:120px;background:rgba(255,255,255,0.05);border-radius:50%;"></div>
                    <div style="position:absolute;bottom:-30px;right:40px;width:100px;height:100px;background:rgba(255,255,255,0.05);border-radius:50%;"></div>
                    <div style="color:rgba(255,255,255,0.55);font-size:0.68rem;font-weight:800;letter-spacing:4px;font-family:sans-serif;position:relative;z-index:1;margin-bottom:5px;">RISE ENGLISH</div>
                    <h2 style="color:white;font-size:1.25rem;font-weight:900;margin:0;font-family:sans-serif;position:relative;z-index:1;letter-spacing:0.3px;">Abonelik Paketleri</h2>
                </div>

                <!-- TABLO -->
                <div style="overflow-x:auto;background:white;">
                    <table style="width:100%;border-collapse:collapse;font-family:sans-serif;">
                        <thead>
                            <tr>
                                <th style="background:#f1f5f9;color:#64748b;padding:16px 20px;text-align:left;font-size:0.85rem;font-weight:700;width:190px;">&#214;zellikler</th>
                                <th style="background:#1a2e5a;color:white;padding:16px 14px;text-align:center;font-weight:800;font-size:1.05rem;">Lite</th>
                                <th style="background:#f97316;color:white;padding:16px 14px;text-align:center;font-weight:800;font-size:1.05rem;">Basic</th>
                                <th style="background:#e63946;color:white;padding:16px 14px;text-align:center;font-weight:800;font-size:1.05rem;position:relative;">
                                    <span style="position:absolute;top:0;right:0;background:#9b1c2a;color:white;font-size:0.58rem;font-weight:800;padding:3px 9px;border-radius:0 0 0 10px;letter-spacing:0.5px;">En Avantajl&#305;</span>
                                    Premium
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:13px 20px;font-weight:700;color:#1e293b;font-size:0.88rem;">Eri&#351;im S&#252;resi</td>
                                <td style="padding:13px 14px;text-align:center;background:#eef1f8;font-weight:700;color:#1a2e5a;">3 Ay</td>
                                <td style="padding:13px 14px;text-align:center;background:#fff8f3;font-weight:700;color:#374151;">12 Ay</td>
                                <td style="padding:13px 14px;text-align:center;background:#fff5f5;font-weight:700;color:#374151;">12 Ay</td>
                            </tr>
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:13px 20px;font-weight:700;color:#1e293b;font-size:0.88rem;">Quiz &amp; Kaynaklar</td>
                                <td style="padding:13px 14px;text-align:center;background:#eef1f8;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#dcfce7;color:#16a34a;font-weight:900;">&#10003;</span></td>
                                <td style="padding:13px 14px;text-align:center;background:#fff8f3;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#dcfce7;color:#16a34a;font-weight:900;">&#10003;</span></td>
                                <td style="padding:13px 14px;text-align:center;background:#fff5f5;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#dcfce7;color:#16a34a;font-weight:900;">&#10003;</span></td>
                            </tr>
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:13px 20px;font-weight:700;color:#1e293b;font-size:0.88rem;">Set Olu&#351;turma</td>
                                <td style="padding:13px 14px;text-align:center;background:#eef1f8;font-weight:600;color:#1a2e5a;font-size:0.85rem;">Maks 7 Set</td>
                                <td style="padding:13px 14px;text-align:center;background:#fff8f3;font-weight:700;color:#f97316;font-size:0.85rem;">Maks 12 Set</td>
                                <td style="padding:13px 14px;text-align:center;background:#fff5f5;font-weight:900;color:#e63946;">S&#305;n&#305;rs&#305;z Set</td>
                            </tr>
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:13px 20px;font-weight:700;color:#1e293b;font-size:0.88rem;">Kelime Blast</td>
                                <td style="padding:13px 14px;text-align:center;background:#eef1f8;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#fee2e2;color:#dc2626;font-weight:900;">&#10005;</span></td>
                                <td style="padding:13px 14px;text-align:center;background:#fff8f3;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#dcfce7;color:#16a34a;font-weight:900;">&#10003;</span></td>
                                <td style="padding:13px 14px;text-align:center;background:#fff5f5;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#dcfce7;color:#16a34a;font-weight:900;">&#10003;</span></td>
                            </tr>
                            <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:13px 20px;font-weight:700;color:#1e293b;font-size:0.88rem;">Çevrimdışı Çalışma</td>
                                <td style="padding:13px 14px;text-align:center;background:#eef1f8;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#fee2e2;color:#dc2626;font-weight:900;">&#10005;</span></td>
                                <td style="padding:13px 14px;text-align:center;background:#fff8f3;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#dcfce7;color:#16a34a;font-weight:900;">&#10003;</span></td>
                                <td style="padding:13px 14px;text-align:center;background:#fff5f5;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#dcfce7;color:#16a34a;font-weight:900;">&#10003;</span></td>
                            </tr>
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:13px 20px;font-weight:700;color:#1e293b;font-size:0.88rem;">K&#252;t&#252;phane</td>
                                <td style="padding:13px 14px;text-align:center;background:#eef1f8;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#fee2e2;color:#dc2626;font-weight:900;">&#10005;</span></td>
                                <td style="padding:13px 14px;text-align:center;background:#fff8f3;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#dcfce7;color:#16a34a;font-weight:900;">&#10003;</span><div style="font-size:0.68rem;color:#15803d;font-weight:700;margin-top:2px;">6 Ay Eri&#351;im</div></td>
                                <td style="padding:13px 14px;text-align:center;background:#fff5f5;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#dcfce7;color:#16a34a;font-weight:900;">&#10003;</span><div style="font-size:0.68rem;color:#15803d;font-weight:700;margin-top:2px;">12 Ay Tam Eri&#351;im</div></td>
                            </tr>
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:13px 20px;font-weight:700;color:#1e293b;font-size:0.88rem;">Raporlama</td>
                                <td style="padding:13px 14px;text-align:center;background:#eef1f8;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#fee2e2;color:#dc2626;font-weight:900;">&#10005;</span></td>
                                <td style="padding:13px 14px;text-align:center;background:#fff8f3;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#fee2e2;color:#dc2626;font-weight:900;">&#10005;</span></td>
                                <td style="padding:13px 14px;text-align:center;background:#fff5f5;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#dcfce7;color:#16a34a;font-weight:900;">&#10003;</span></td>
                            </tr>
                            <tr style="border-bottom:2px solid #e2e8f0;">
                                <td style="padding:13px 20px;font-weight:700;color:#1e293b;font-size:0.88rem;">Ses Kayd&#305; Takibi</td>
                                <td style="padding:13px 14px;text-align:center;background:#eef1f8;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#fee2e2;color:#dc2626;font-weight:900;">&#10005;</span></td>
                                <td style="padding:13px 14px;text-align:center;background:#fff8f3;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#fee2e2;color:#dc2626;font-weight:900;">&#10005;</span></td>
                                <td style="padding:13px 14px;text-align:center;background:#fff5f5;"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#dcfce7;color:#16a34a;font-weight:900;">&#10003;</span></td>
                            </tr>
                            <!-- FİYAT -->
                            <tr>
                                <td style="padding:18px 20px;font-weight:800;color:#1e293b;background:#f8fafc;">Fiyat</td>
                                <td style="padding:18px 14px;text-align:center;background:#1a2e5a;">
                                    <div style="position:relative;display:inline-block;margin-bottom:6px;"><span style="color:rgba(255,255,255,0.45);font-size:0.88rem;font-weight:700;">5.000 TL</span><div style="position:absolute;top:50%;left:-2px;right:-2px;height:2px;background:#e63946;transform:rotate(-8deg) translateY(-50%);"></div></div>
                                    <div style="color:white;font-size:1.9rem;font-weight:900;line-height:1;">3.000 TL</div>
                                </td>
                                <td style="padding:18px 14px;text-align:center;background:#f97316;">
                                    <div style="position:relative;display:inline-block;margin-bottom:6px;"><span style="color:rgba(255,255,255,0.45);font-size:0.88rem;font-weight:700;">10.000 TL</span><div style="position:absolute;top:50%;left:-2px;right:-2px;height:2px;background:#7c3500;transform:rotate(-8deg) translateY(-50%);"></div></div>
                                    <div style="color:white;font-size:1.9rem;font-weight:900;line-height:1;">5.000 TL</div>
                                    <div style="color:rgba(255,255,255,0.7);font-size:0.7rem;font-weight:700;margin-top:4px;">2 Taksit</div>
                                </td>
                                <td style="padding:18px 14px;text-align:center;background:#e63946;">
                                    <div style="position:relative;display:inline-block;margin-bottom:6px;"><span style="color:rgba(255,255,255,0.45);font-size:0.88rem;font-weight:700;">15.000 TL</span><div style="position:absolute;top:50%;left:-2px;right:-2px;height:2px;background:#7c1d1d;transform:rotate(-8deg) translateY(-50%);"></div></div>
                                    <div style="color:white;font-size:1.9rem;font-weight:900;line-height:1;">7.500 TL</div>
                                    <div style="color:rgba(255,255,255,0.7);font-size:0.7rem;font-weight:700;margin-top:4px;">3 Taksit</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ALT -->
                <div style="background:#0f1e3d;padding:14px 32px;text-align:center;">
                    <p style="color:rgba(255,255,255,0.6);font-size:0.82rem;font-weight:600;margin:0;font-family:sans-serif;">
                        Detayl&#305; bilgi i&#231;in <span style="color:white;font-weight:800;">Hakan Hocam&#305;zla</span> ileti&#351;ime ge&#231;ebilirsiniz &#128522;
                    </p>
                </div>

            </div>
        </div>
    </div>
    `;

    const bannerDiv = document.createElement('div');
    bannerDiv.innerHTML = bannerHTML;
    document.body.appendChild(bannerDiv);

    const bannerModal = document.getElementById('proBannerModal');
    const bannerInner = document.getElementById('proBannerInner');
    const closeBannerBtn = document.getElementById('closeProBanner');

    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            bannerModal.style.opacity = '1';
            bannerInner.style.opacity = '1';
            bannerInner.style.transform = 'scale(1) translateY(0)';
        });
    });

    if (closeBannerBtn && bannerModal) {
        closeBannerBtn.addEventListener('click', function() {
            bannerModal.style.opacity = '0';
            bannerInner.style.transform = 'scale(0.9) translateY(20px)';
            bannerInner.style.opacity = '0';

            setTimeout(() => {
                bannerModal.remove();

                @if (!auth()->check())
                    setTimeout(() => {
                        if (!hasSeenFortuneWheel()) {
                            showFortuneWheel();
                            setFortuneWheelSeen();
                        }
                    }, 500);
                @endif
            }, 350);
        });

        bannerModal.addEventListener('click', function(e) {
            if (e.target === bannerModal) closeBannerBtn.click();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && bannerModal) closeBannerBtn.click();
        });
    }
}         // Şans çarkının daha önce görülüp görülmediğini kontrol et
            function hasSeenFortuneWheel() {
                return localStorage.getItem('fortuneWheelSeen') === 'true';
            }

            // Şans çarkının görüldüğünü işaretle (24 saat boyunca)
            function setFortuneWheelSeen() {
                const now = new Date().getTime();
                const expirationTime = now + (24 * 60 * 60 * 1000); // 24 saat
                localStorage.setItem('fortuneWheelSeen', 'true');
                localStorage.setItem('fortuneWheelSeenTime', expirationTime.toString());
            }


            // Global WhatsApp fonksiyonu - Düzeltilmiş
            function openWhatsApp() {
                console.log('openWhatsApp fonksiyonu çağrıldı');
                const phoneNumber = '905541383539';
                const message = encodeURIComponent('Merhaba, ücretsiz demo ders hakkında bilgi almak istiyorum.');
                const whatsappUrl = `https://wa.me/${phoneNumber}?text=${message}`;

                // WhatsApp'ı yeni sekmede aç
                window.open(whatsappUrl, '_blank');

                // Modal'ı kapat
                closeModal();
            }

            // Demo modal kapatma - Basit çözüm
            document.addEventListener('DOMContentLoaded', function() {
                const demoModal = document.getElementById('demoModal');
                const closeModalBtn = document.getElementById('closeModal');
                const whatsappBtn = document.getElementById('whatsappBtn');

                // X butonu ile kapat
                if (closeModalBtn) {
                    closeModalBtn.onclick = function() {
                        demoModal.style.display = 'none';
                    };
                }

                // WhatsApp butonu
                if (whatsappBtn) {
                    whatsappBtn.onclick = function() {
                        const phoneNumber = '905541383539';
                        const message = encodeURIComponent(
                            'Merhaba, ücretsiz demo ders hakkında bilgi almak istiyorum.');
                        window.open(`https://wa.me/${phoneNumber}?text=${message}`, '_blank');
                        demoModal.style.display = 'none';
                    };
                }

            });
            // ===== FLOATING PANEL FUNCTIONS =====
            function initFloatingPanel() {
                const closeFloatingPanelButton = document.getElementById('closeFloatingPanel');
                const floatingSignupPanel = document.getElementById('floatingSignupPanel');

                if (closeFloatingPanelButton && floatingSignupPanel) {
                    closeFloatingPanelButton.addEventListener('click', function() {
                        floatingSignupPanel.classList.add('hidden');
                        floatingSignupPanel.style.display = 'none';

                        // Save user preference as cookie
                        document.cookie =
                            "hideFloatingPanel=true; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
                    });

                    // Check if user closed the panel before
                    if (getCookie('hideFloatingPanel') === 'true') {
                        floatingSignupPanel.classList.add('hidden');
                    }
                }

                // Helper function to get cookie value
                function getCookie(name) {
                    const value = `; ${document.cookie}`;
                    const parts = value.split(`; ${name}=`);
                    if (parts.length === 2) return parts.pop().split(';').shift();
                }
            }

            // ===== MAIN COURSES SLIDER FUNCTIONS =====
            function initMainSlider() {
                const slidesWrapper = document.getElementById('slidesWrapper');
                const sliderDots = document.getElementById('sliderDots');
                const nextButton = document.getElementById('nextButton');
                const prevButton = document.getElementById('prevButton');
                const mobileNextButton = document.getElementById('mobileNextButton');
                const mobilePrevButton = document.getElementById('mobilePrevButton');
                const sliderItems = document.querySelectorAll('.slider-item');

                if (!slidesWrapper || sliderItems.length === 0) return;

                let mainSliderIndex = 0;
                let mainSliderWidthPercent = 100;
                let mainVisibleSlides = 1;

                // Configure slider based on screen size
                function updateMainSlidesConfig() {
                    if (window.innerWidth >= 1024) { // lg
                        mainVisibleSlides = 3;
                        mainSliderWidthPercent = 100 / 3;
                    } else if (window.innerWidth >= 768) { // md
                        mainVisibleSlides = 2;
                        mainSliderWidthPercent = 50;
                    } else { // sm and below
                        mainVisibleSlides = 1;
                        mainSliderWidthPercent = 100;
                    }

                    // Set slide widths
                    sliderItems.forEach(item => {
                        item.style.width = `${mainSliderWidthPercent}%`;
                    });

                    // Update active slide
                    updateMainSlide(mainSliderIndex);

                    // Create dots
                    createMainDots();
                }

                // Create dots for navigation
                function createMainDots() {
                    if (!sliderDots) return;

                    sliderDots.innerHTML = '';
                    const totalDots = Math.ceil(sliderItems.length / mainVisibleSlides);

                    for (let i = 0; i < totalDots; i++) {
                        const dot = document.createElement('div');
                        dot.classList.add('w-2', 'h-2', 'rounded-full', 'bg-gray-300', 'cursor-pointer',
                            'transition-colors');

                        if (i === Math.floor(mainSliderIndex / mainVisibleSlides)) {
                            dot.classList.remove('bg-gray-300');
                            dot.classList.add('bg-[#1a2e5a]');
                        }

                        dot.addEventListener('click', () => {
                            goToMainSlide(i * mainVisibleSlides);
                        });

                        sliderDots.appendChild(dot);
                    }
                }

                // Update main slider position
                function updateMainSlide(index) {
                    if (!slidesWrapper) return;

                    mainSliderIndex = index;

                    // Check maximum bounds
                    const maxIndex = Math.max(0, sliderItems.length - mainVisibleSlides);
                    if (mainSliderIndex > maxIndex) {
                        mainSliderIndex = maxIndex;
                    }

                    // Smooth transition with transform
                    slidesWrapper.style.transition = 'transform 0.5s ease';
                    slidesWrapper.style.transform = `translateX(-${mainSliderIndex * mainSliderWidthPercent}%)`;

                    // Update dots
                    updateMainActiveDot();
                }

                // Update active dot
                function updateMainActiveDot() {
                    if (!sliderDots) return;

                    const dots = sliderDots.querySelectorAll('div');
                    const activeDotIndex = Math.floor(mainSliderIndex / mainVisibleSlides);

                    dots.forEach((dot, index) => {
                        if (index === activeDotIndex) {
                            dot.classList.remove('bg-gray-300');
                            dot.classList.add('bg-[#1a2e5a]');
                        } else {
                            dot.classList.remove('bg-[#1a2e5a]');
                            dot.classList.add('bg-gray-300');
                        }
                    });
                }

                // Go to specific slide
                function goToMainSlide(index) {
                    updateMainSlide(index);
                }

                // Go to next slide
                function nextMainSlide() {
                    if (mainSliderIndex < sliderItems.length - mainVisibleSlides) {
                        updateMainSlide(mainSliderIndex + mainVisibleSlides);
                    } else {
                        // Loop to beginning
                        updateMainSlide(0);
                    }
                }

                // Go to previous slide
                function prevMainSlide() {
                    if (mainSliderIndex > 0) {
                        updateMainSlide(mainSliderIndex - mainVisibleSlides);
                    } else {
                        // Loop to end
                        updateMainSlide(Math.max(0, sliderItems.length - mainVisibleSlides));
                    }
                }

                // Single slide movement for mobile
                function nextMainSingleSlide() {
                    if (mainSliderIndex < sliderItems.length - 1) {
                        updateMainSlide(mainSliderIndex + 1);
                    } else {
                        updateMainSlide(0);
                    }
                }

                function prevMainSingleSlide() {
                    if (mainSliderIndex > 0) {
                        updateMainSlide(mainSliderIndex - 1);
                    } else {
                        updateMainSlide(sliderItems.length - 1);
                    }
                }

                // Button event handlers
                if (nextButton) nextButton.addEventListener('click', nextMainSlide);
                if (prevButton) prevButton.addEventListener('click', prevMainSlide);
                if (mobileNextButton) mobileNextButton.addEventListener('click', nextMainSingleSlide);
                if (mobilePrevButton) mobilePrevButton.addEventListener('click', prevMainSingleSlide);

                // Touch events for mobile swipe
                let mainTouchStartX = 0;
                let mainTouchEndX = 0;

                if (slidesWrapper) {
                    slidesWrapper.addEventListener('touchstart', e => {
                        mainTouchStartX = e.changedTouches[0].screenX;
                    });

                    slidesWrapper.addEventListener('touchend', e => {
                        mainTouchEndX = e.changedTouches[0].screenX;
                        handleMainSwipe();
                    });
                }

                function handleMainSwipe() {
                    const swipeThreshold = 30;

                    if (mainTouchEndX < mainTouchStartX - swipeThreshold) {
                        // Swipe left
                        nextMainSingleSlide();
                    } else if (mainTouchEndX > mainTouchStartX + swipeThreshold) {
                        // Swipe right
                        prevMainSingleSlide();
                    }
                }

                // Auto-slide functionality
                let mainAutoSlide;
                const sliderContainer = document.querySelector('.slider-container');

                if (sliderContainer && slidesWrapper) {
                    mainAutoSlide = setInterval(nextMainSlide, 6000);

                    // Pause auto-slide on user interaction
                    sliderContainer.addEventListener('mouseenter', () => {
                        clearInterval(mainAutoSlide);
                    });

                    // Resume auto-slide when user leaves
                    sliderContainer.addEventListener('mouseleave', () => {
                        clearInterval(mainAutoSlide);
                        mainAutoSlide = setInterval(nextMainSlide, 6000);
                    });
                }

                // Initialize with screen size
                updateMainSlidesConfig();

                // Update on window resize
                window.addEventListener('resize', updateMainSlidesConfig);
            }

            function initMainPromoVideo() {
                console.log("Video thumbnail işlemi başlatılıyor...");

                // Tüm video thumbnail'lerini seçin - ana tanıtım ve slayt videoları dahil
                const videoThumbnails = document.querySelectorAll('.video-thumbnail');
                console.log(`Toplam ${videoThumbnails.length} video thumbnail bulundu`);

                videoThumbnails.forEach((thumbnail, index) => {
                    const img = thumbnail.querySelector('img');
                    if (img) {
                        const videoId = thumbnail.getAttribute('data-video-id');
                        console.log(`[${index}] Video işleniyor: ${videoId}, mevcut src: ${img.src}`);

                        // Direkt varsayılan bir değer koyalım, sonra asenkron olarak yükleyelim
                        if (index === 0) {
                            // Ana video için özel yüksek kaliteli placeholder
                            thumbnail.classList.add('thumbnail-loading');
                            img.style.background = '#f1f1f1';
                        }

                        // Tüm olası YouTube thumbnail formatlarını bir dizide tutalım
                        const thumbnailOptions = [
                            `https://i.ytimg.com/vi/${videoId}/maxresdefault.jpg`, // HD
                            `https://i.ytimg.com/vi/${videoId}/hqdefault.jpg`, // High quality
                            `https://i.ytimg.com/vi/${videoId}/mqdefault.jpg`, // Medium quality
                            `https://i.ytimg.com/vi/${videoId}/sddefault.jpg`, // Standard quality
                            `https://i.ytimg.com/vi/${videoId}/0.jpg`, // Alternatif format
                            `https://i.ytimg.com/vi/${videoId}/default.jpg`, // Lowest quality
                            'https://via.placeholder.com/480x360?text=Video+Thumbnail' // Fallback
                        ];

                        // Tüm formatlarda thumbnail'leri asenkron olarak kontrol edelim
                        // ve ilk çalışanı kullanalım
                        checkImageSources(thumbnailOptions, 0, (validSrc) => {
                            console.log(
                                `[${index}] ${videoId} için çalışan kaynak bulundu: ${validSrc}`
                            );
                            img.src = validSrc;
                            img.style.opacity = '1';
                            thumbnail.classList.remove('thumbnail-loading');
                        });

                        // Görünürlük için CSS ekle
                        if (!document.getElementById('thumbnail-styles')) {
                            const style = document.createElement('style');
                            style.id = 'thumbnail-styles';
                            style.textContent = `
                        .thumbnail-loading { position: relative; }
                        .thumbnail-loading::after {
                            content: "Yükleniyor...";
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            color: #666;
                            font-size: 14px;
                            z-index: 1;
                        }
                    `;
                            document.head.appendChild(style);
                        }
                    }

                    // Video tıklama işlevselliği - Ana video için
                    thumbnail.addEventListener('click', function() {
                        const videoId = this.getAttribute('data-video-id');
                        const iframeContainer = this.parentElement.querySelector(
                            '.video-iframe-container');

                        if (iframeContainer) {
                            // Slider slayt geçişini durdur - videoSliderIsPlaying değişkenini true yap
                            if (window.videoSliderIsPlaying !== undefined) {
                                window.videoSliderIsPlaying = true;
                                // Varsa otomatik kaydırmayı durdur
                                if (window.videoSliderInterval) {
                                    clearInterval(window.videoSliderInterval);
                                }
                            }

                            // Video iframe'ini oluştur - video bittiğinde slayt geçişini tekrar başlatmak için event listener ekle
                            iframeContainer.innerHTML = `<iframe class="w-full h-full absolute inset-0" 
                        src="https://www.youtube.com/embed/${videoId}?autoplay=1&enablejsapi=1" 
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen></iframe>`;

                            this.style.display = 'none';
                            iframeContainer.classList.remove('hidden');
                        }
                    });
                });
            }

            // Görüntünün yüklenebilir olup olmadığını kontrol eden yardımcı fonksiyon
            function checkImageSources(sources, index, callback) {
                if (index >= sources.length) {
                    // Tüm kaynaklar denendi, varsayılan son kaynağı kullan
                    callback(sources[sources.length - 1]);
                    return;
                }

                const img = new Image();
                const timestamp = new Date().getTime();
                const source = sources[index].includes('?') ?
                    `${sources[index]}&_=${timestamp}` :
                    `${sources[index]}?_=${timestamp}`;

                img.onload = function() {
                    // Bu kaynak çalıştı, geri çağır
                    callback(source);
                };

                img.onerror = function() {
                    console.log(`${source} yüklenemedi, sıradaki kaynak deneniyor`);
                    // Bu kaynak çalışmadı, sıradakini dene
                    checkImageSources(sources, index + 1, callback);
                };

                img.src = source;
            }

            // ===== STUDENT VIDEOS SLIDER FUNCTIONS - YENİLENMİŞ VERSİYON =====
            function initVideoSlider() {
                // DOM Elemanlarını Seç
                const videoSlidesWrapper = document.getElementById('videoSlidesWrapper');
                const videoSliderDots = document.getElementById('videoSliderDots');
                const nextVideoBtn = document.getElementById('nextVideo');
                const prevVideoBtn = document.getElementById('prevVideo');
                const videoSlides = document.querySelectorAll('.video-slide');

                // Eğer gerekli elemanlar yoksa işlemi sonlandır
                if (!videoSlidesWrapper || videoSlides.length === 0) return;

                // Değişkenler
                let currentIndex = 0;
                let slidesPerView = 1;
                const totalSlides = videoSlides.length;
                let slideWidth = 100; // Yüzde cinsinden
                let autoSlideInterval;

                // Video oynatma durumunu global olarak izle
                window.videoSliderIsPlaying = false;
                window.videoSliderInterval = null;

                // Ekran boyutuna göre görünür slayt sayısını ayarla
                function updateSlidesConfig() {
                    if (window.innerWidth >= 1024) { // lg
                        slidesPerView = 3;
                        slideWidth = 100 / 3;
                    } else if (window.innerWidth >= 768) { // md
                        slidesPerView = 2;
                        slideWidth = 50;
                    } else { // sm ve altı
                        slidesPerView = 1;
                        slideWidth = 100;
                    }

                    // Slayt genişliklerini ayarla
                    videoSlides.forEach(slide => {
                        slide.style.width = `${slideWidth}%`;
                    });

                    // Slaytları güncelle
                    goToSlide(currentIndex);

                    // Dot'ları oluştur
                    createDots();
                }

                // Dot navigasyonu oluştur
                function createDots() {
                    if (!videoSliderDots) return;

                    videoSliderDots.innerHTML = '';
                    const dotsCount = Math.ceil(totalSlides / slidesPerView);

                    for (let i = 0; i < dotsCount; i++) {
                        const dot = document.createElement('div');
                        dot.classList.add('w-2', 'h-2', 'rounded-full', 'bg-white', 'bg-opacity-30',
                            'cursor-pointer', 'transition-all', 'duration-300');

                        if (i === Math.floor(currentIndex / slidesPerView)) {
                            dot.classList.remove('bg-opacity-30');
                            dot.classList.add('bg-opacity-100');
                        }

                        dot.addEventListener('click', () => {
                            goToSlide(i * slidesPerView);
                        });

                        videoSliderDots.appendChild(dot);
                    }
                }

                // Belirli bir slayta git
                function goToSlide(index) {
                    // Video oynatılıyorsa slayt geçişini durdur
                    if (window.videoSliderIsPlaying) return;

                    // Otomatik geçişi durdur
                    clearInterval(autoSlideInterval);

                    // Index'in sınırlar içinde olduğunu kontrol et
                    currentIndex = index;
                    if (currentIndex < 0) {
                        currentIndex = totalSlides - slidesPerView;
                    } else if (currentIndex > totalSlides - slidesPerView) {
                        currentIndex = 0;
                    }

                    // CSS transform ile slaytları kaydır
                    videoSlidesWrapper.style.transition = 'transform 0.5s ease';
                    videoSlidesWrapper.style.transform = `translateX(-${currentIndex * slideWidth}%)`;

                    // Aktif dot'u güncelle
                    updateActiveDot();

                    // Eğer video oynatılmıyorsa otomatik geçişi yeniden başlat
                    if (!window.videoSliderIsPlaying) {
                        startAutoSlide();
                    }
                }

                // Aktif dot'u güncelle
                function updateActiveDot() {
                    if (!videoSliderDots) return;

                    const dots = videoSliderDots.querySelectorAll('div');
                    const activeDotIndex = Math.floor(currentIndex / slidesPerView);

                    dots.forEach((dot, index) => {
                        if (index === activeDotIndex) {
                            dot.classList.remove('bg-opacity-30');
                            dot.classList.add('bg-opacity-100');
                        } else {
                            dot.classList.remove('bg-opacity-100');
                            dot.classList.add('bg-opacity-30');
                        }
                    });
                }

                // Sonraki slayta geç
                function nextSlide() {
                    // Video oynatılıyorsa slayt geçişini durdur
                    if (window.videoSliderIsPlaying) return;
                    goToSlide(currentIndex + slidesPerView);
                }

                // Önceki slayta geç
                function prevSlide() {
                    // Video oynatılıyorsa slayt geçişini durdur
                    if (window.videoSliderIsPlaying) return;
                    goToSlide(currentIndex - slidesPerView);
                }

                // Otomatik geçişi başlat
                function startAutoSlide() {
                    clearInterval(autoSlideInterval);

                    // Eğer video oynatılmıyorsa otomatik geçişi başlat
                    if (!window.videoSliderIsPlaying) {
                        autoSlideInterval = setInterval(() => {
                            // Her kontrol et - eğer video oynatılıyorsa otomatik slayt geçişini durduracak
                            if (!window.videoSliderIsPlaying) {
                                nextSlide();
                            }
                        }, 5000);

                        // Otomatik geçiş aralığını kaydet (video bitiminde tekrar başlatmak için)
                        window.videoSliderInterval = autoSlideInterval;
                    }
                }

                // Otomatik geçişi durdur
                function stopAutoSlide() {
                    clearInterval(autoSlideInterval);
                    window.videoSliderInterval = null;
                }

                // Buton event listener'ları
                if (nextVideoBtn) {
                    nextVideoBtn.addEventListener('click', () => {
                        nextSlide();
                    });
                }

                if (prevVideoBtn) {
                    prevVideoBtn.addEventListener('click', () => {
                        prevSlide();
                    });
                }

                // Video thumbnails tıklama olayları
                const videoThumbnails = document.querySelectorAll('.video-thumbnail');

                videoThumbnails.forEach(thumbnail => {
                    thumbnail.addEventListener('click', function() {
                        const videoId = this.getAttribute('data-video-id');
                        const iframeContainer = this.parentElement.querySelector(
                            '.video-iframe-container');

                        console.log("Video ID:", videoId); // Debugging

                        // Video başlatıldığında otomatik geçişi durdur
                        window.videoSliderIsPlaying = true;
                        stopAutoSlide();

                        // Video iframe'ini oluştur
                        const iframeId = `video-iframe-${videoId}`;
                        iframeContainer.innerHTML = `
                    <iframe id="${iframeId}" class="w-full h-full absolute inset-0" 
                        src="https://www.youtube.com/embed/${videoId}?autoplay=1&enablejsapi=1" 
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen></iframe>`;

                        // Thumbnail'i gizle, iframe'i göster
                        this.style.display = 'none';
                        iframeContainer.classList.remove('hidden');

                        // YouTube iFrame API ile video bitiş olayını dinle
                        window.addEventListener('message', function(event) {
                            // YouTube'dan gelen mesaj mı kontrol et
                            if (event.origin.startsWith('https://www.youtube.com') &&
                                typeof event.data === 'string') {

                                try {
                                    const data = JSON.parse(event.data);
                                    // Video bitiş durumunu kontrol et (0 = bitti)
                                    if (data.event === 'onStateChange' && data.info === 0) {
                                        // Video bittiğinde otomatik kaydırmayı tekrar başlat
                                        window.videoSliderIsPlaying = false;
                                        startAutoSlide();
                                    }
                                } catch (e) {
                                    // JSON değilse veya başka bir hata - yoksay
                                }
                            }
                        });

                        // Ayrıca, sayfadan ayrılma durumunda da otomatik kaydırmayı tekrar başlat
                        document.addEventListener('visibilitychange', function() {
                            if (document.visibilityState === 'hidden') {
                                // Sayfa arkaplanda ise ve video oynatılıyorsa, otomatik kaydırmayı tekrar başlat
                                // Bu, kullanıcı videoyu izlemekten vazgeçtiğinde yardımcı olur
                                setTimeout(() => {
                                    window.videoSliderIsPlaying = false;
                                    startAutoSlide();
                                }, 30000); // 30 saniye sonra tekrar başlat
                            }
                        });
                    });
                });

                // Swipe işlevselliği
                let touchStartX = 0;
                let touchEndX = 0;

                if (videoSlidesWrapper) {
                    videoSlidesWrapper.addEventListener('touchstart', e => {
                        touchStartX = e.changedTouches[0].screenX;
                    });

                    videoSlidesWrapper.addEventListener('touchend', e => {
                        touchEndX = e.changedTouches[0].screenX;
                        handleSwipe();
                    });
                }

                function handleSwipe() {
                    const swipeThreshold = 30;

                    // Video oynatılıyorsa swipe işlemini durdur
                    if (window.videoSliderIsPlaying) return;

                    if (touchEndX < touchStartX - swipeThreshold) {
                        // Sola kaydırma
                        nextSlide();
                    } else if (touchEndX > touchStartX + swipeThreshold) {
                        // Sağa kaydırma
                        prevSlide();
                    }
                }

                // Thumbnail yükleme hatalarını işle
                const thumbnailImages = document.querySelectorAll('.video-thumbnail img');
                thumbnailImages.forEach(img => {
                    img.addEventListener('error', function() {
                        console.error('Thumbnail yükleme hatası:', this.src);

                        // Video ID'sini al
                        const videoId = this.parentElement.getAttribute('data-video-id');

                        // Alternatif thumbnail dene
                        this.src = `https://i.ytimg.com/vi/${videoId}/default.jpg`;

                        // İkinci deneme de başarısız olursa
                        this.addEventListener('error', function() {
                            // Placeholder resim göster
                            this.src =
                                'https://via.placeholder.com/480x360?text=Video+Thumbnail';
                        });
                    });
                });

                // İlk yükleme için konfigürasyonu ayarla
                updateSlidesConfig();

                // Otomatik geçişi başlat
                startAutoSlide();

                // Ekran boyutu değiştiğinde güncelle
                window.addEventListener('resize', updateSlidesConfig);

                // Eğer sayfa yüklendiğinde video oynatılmıyorsa, videoSliderIsPlaying değişkenini kontrol et
                setInterval(() => {
                    // Tüm iframe konteynerlerini kontrol et
                    const videoContainers = document.querySelectorAll('.video-iframe-container');
                    let anyVideoVisible = false;

                    videoContainers.forEach(container => {
                        // Eğer herhangi bir iframe container görünürse (display != 'none' ve hidden değilse)
                        if (container.style.display !== 'none' && !container.classList.contains(
                                'hidden') &&
                            container.querySelector('iframe')) {
                            anyVideoVisible = true;
                        }
                    });

                    // Görünür video yoksa otomatik kaydırmayı tekrar başlat
                    if (!anyVideoVisible && window.videoSliderIsPlaying) {
                        window.videoSliderIsPlaying = false;
                        startAutoSlide();
                    }
                }, 10000); // Her 10 saniyede bir kontrol et
            }
        });

        // GLOBAL FONKSİYONLAR - HTML'den çağrılabilir
        function closeModal() {
            console.log('Global closeModal fonksiyonu çağrıldı');
            const modal = document.getElementById('demoModal');
            if (modal) {
                modal.style.display = 'none';
                console.log('Modal global fonksiyon ile kapatıldı');

                // 1.5 saniye sonra şans çarkını göster
                setTimeout(() => {
                    showFortuneWheel();
                }, 1500);
            }
        }
        // ===== SUCCESS MESSAGE FUNCTIONS =====
        function initSuccessMessage() {
            // DOM'dan başarı mesajı elementlerini seç
            const successMessage = document.getElementById('successMessage');
            const progressBar = document.getElementById('successMessageProgress');

            // Eğer başarı mesajı ve progress bar varsa
            if (successMessage && progressBar) {
                // Progress bar animasyonunu başlat
                progressBar.style.transition = 'width 5s linear'; // 5 saniye doğrusal geçiş
                progressBar.style.width = '0'; // Genişliği 0'a ayarla (animasyon için)

                // 5 saniye sonra mesajı otomatik kaldır
                setTimeout(function() {
                    // Mesajı sağa kaydırarak gizle
                    successMessage.classList.add('translate-x-full');

                    // Kaydırma animasyonu bitince elementi tamamen kaldır
                    setTimeout(function() {
                        successMessage.remove(); // DOM'dan elementi sil
                    }, 300); // 300ms kaydırma animasyonu süresi
                }, 5000); // 5000ms = 5 saniye bekleme
            }

            // Manuel kapama fonksiyonunu global olarak tanımla
            // Bu fonksiyon HTML'den çağrılabilir (onclick="closeSuccessMessage()")
            window.closeSuccessMessage = function() {
                const successMessage = document.getElementById('successMessage');
                if (successMessage) {
                    // Aynı kaydırma animasyonunu uygula
                    successMessage.classList.add('translate-x-full');
                    setTimeout(function() {
                        successMessage.remove();
                    }, 300);
                }
            };
        }

        function openWhatsApp() {
            console.log('Global openWhatsApp fonksiyonu çağrıldı');
            const phoneNumber = '905541383539';
            const message = encodeURIComponent('Merhaba, ücretsiz demo ders hakkında bilgi almak istiyorum.');
            const whatsappUrl = `https://wa.me/${phoneNumber}?text=${message}`;

            window.open(whatsappUrl, '_blank');

            // Modal'ı kapat
            const modal = document.getElementById('demoModal');
            if (modal) {
                modal.style.display = 'none';
                console.log('WhatsApp yönlendirmesi sonrası modal kapatıldı');
            }
        }
        class FortuneWheelPopup {
            constructor() {
                // Sadece giriş yapmayan kullanıcılar için initialize et
                @if (!auth()->check())
                    this.modal = document.getElementById('fortuneWheelModal');
                    this.wheel = document.getElementById('fortuneWheel');
                    this.spinButton = document.getElementById('fortuneSpinButton');
                    this.resultModal = document.getElementById('fortuneResultModal');
                    this.winContent = document.getElementById('fortuneWinContent');
                    this.retryContent = document.getElementById('fortuneRetryContent');
                    this.prizeText = document.getElementById('fortunePrizeText');
                    this.prizeCode = document.getElementById('fortunePrizeCode');
                    this.whatsappButton = document.getElementById('fortuneWhatsappButton');
                    this.retryButton = document.getElementById('fortuneRetryButton');
                    this.closeWheelBtn = document.getElementById('closeFortuneWheel');
                    this.closeResultBtn = document.getElementById('closeFortuneResult');

                    this.isSpinning = false;
                    this.hasSpun = false;
                    this.retryCount = 0;
                    this.maxRetries = 1;

                    // Ödüller tanımlaması
                    this.prizes = [{
                            name: '%20 İNDİRİM',
                            type: 'discount',
                            value: 20,
                            weight: 25
                        },
                        {
                            name: 'TEKRAR DENE',
                            type: 'retry',
                            value: null,
                            weight: 20
                        },
                        {
                            name: '%30 İNDİRİM',
                            type: 'discount',
                            value: 30,
                            weight: 20
                        },
                        {
                            name: '1 ÖZEL DERS',
                            type: 'lesson',
                            value: 1,
                            weight: 15
                        },
                        {
                            name: '%40 İNDİRİM',
                            type: 'discount',
                            value: 40,
                            weight: 10
                        },
                        {
                            name: 'TEKRAR DENE',
                            type: 'retry',
                            value: null,
                            weight: 5
                        },
                        {
                            name: '%50 İNDİRİM',
                            type: 'discount',
                            value: 50,
                            weight: 4
                        },
                        {
                            name: '1 ÖZEL DERS',
                            type: 'lesson',
                            value: 1,
                            weight: 1
                        }
                    ];

                    this.init();
                @endif
            }

            init() {
                @if (!auth()->check())
                    if (!this.modal || !this.spinButton) return;

                    // Event listeners
                    this.spinButton.addEventListener('click', () => this.spin());

                    if (this.retryButton) {
                        this.retryButton.addEventListener('click', () => this.retry());
                    }

                    if (this.closeWheelBtn) {
                        this.closeWheelBtn.addEventListener('click', () => this.close());
                    }

                    if (this.closeResultBtn) {
                        this.closeResultBtn.addEventListener('click', () => this.closeResult());
                    }

                    if (this.whatsappButton) {
                        this.whatsappButton.addEventListener('click', () => this.claimPrize());
                    }

                    // Modal dışı tıklama ile kapama
                    this.modal.addEventListener('click', (e) => {
                        if (e.target === this.modal) this.close();
                    });

                    if (this.resultModal) {
                        this.resultModal.addEventListener('click', (e) => {
                            if (e.target === this.resultModal) this.closeResult();
                        });
                    }

                    // ESC tuşu ile kapama
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape') {
                            if (this.resultModal && this.resultModal.style.display !== 'none') {
                                this.closeResult();
                            } else if (this.modal && this.modal.style.display !== 'none') {
                                this.close();
                            }
                        }
                    });
                @endif
            }

            // Popup'ı aç
            show() {
                @if (!auth()->check())
                    if (this.modal) {
                        this.modal.style.display = 'flex';
                        document.body.style.overflow = 'hidden';
                    }
                @endif
            }

            // Popup'ı kapat ve localStorage güncelle
            close() {
                @if (!auth()->check())
                    if (this.modal) {
                        this.modal.style.display = 'none';
                        document.body.style.overflow = '';

                        // Çark kapatıldığında da görüldüğünü işaretle
                        setFortuneWheelSeen();
                    }
                @endif
            }

            // Sonuç modalını kapat
            closeResult() {
                @if (!auth()->check())
                    if (this.resultModal) {
                        this.resultModal.style.display = 'none';
                    }

                    // Ana çark modalını da kapat
                    this.close();

                    // Buton durumunu güncelle
                    if (this.spinButton && (!this.hasSpun || this.retryCount >= this.maxRetries)) {
                        this.spinButton.textContent = 'ÇEVRİLDİ';
                        this.spinButton.disabled = true;
                        this.spinButton.classList.remove('pulse-animation');
                    }
                @endif
            }

            generatePrizeCode() {
                @if (!auth()->check())
                    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    let code = 'RISE';
                    for (let i = 0; i < 4; i++) {
                        code += chars.charAt(Math.floor(Math.random() * chars.length));
                    }
                    return code;
                @else
                    return 'N/A';
                @endif
            }

            selectPrize() {
                @if (!auth()->check())
                    // Ağırlıklı rastgele seçim
                    const totalWeight = this.prizes.reduce((sum, prize) => sum + prize.weight, 0);
                    let random = Math.random() * totalWeight;

                    for (let i = 0; i < this.prizes.length; i++) {
                        random -= this.prizes[i].weight;
                        if (random <= 0) {
                            return {
                                ...this.prizes[i],
                                index: i
                            };
                        }
                    }

                    // Fallback
                    return {
                        ...this.prizes[1],
                        index: 1
                    }; // Tekrar dene
                @else
                    return null;
                @endif
            }

            spin() {
                @if (!auth()->check())
                    if (this.isSpinning || !this.spinButton) return;

                    this.isSpinning = true;
                    this.spinButton.disabled = true;
                    this.spinButton.textContent = 'ÇEVRİLİYOR...';
                    this.spinButton.classList.remove('pulse-animation');

                    // 🔊 SES EKLE - Son 1 saniyede yavaşlayacak
                    const spinSound = new Audio('/sounds/spinning.mp3');
                    spinSound.volume = 0.5;
                    spinSound.preservesPitch = false;
                    spinSound.play().catch(e => console.log('Ses çalınamadı:', e));

                    // 5 saniye sonra sesi yavaşlat
                    setTimeout(() => {
                        let playbackRate = 1.0;
                        const slowdownInterval = setInterval(() => {
                            playbackRate -= 0.15; // Hızlı yavaşlama
                            if (playbackRate < 0.3) {
                                playbackRate = 0.3;
                                clearInterval(slowdownInterval);
                            }
                            spinSound.playbackRate = playbackRate;
                        }, 100);
                    }, 5000); // ⬅️ 5 saniye sonra başla

                    // Ödül seçimi
                    const selectedPrize = this.selectPrize();
                    if (!selectedPrize) return;

                    // Çark animasyonu hesaplaması
                    const segmentAngle = 360 / 8;
                    const targetAngle = (selectedPrize.index * segmentAngle) + (segmentAngle / 2);
                    const spinRotations = 5;
                    const finalAngle = (spinRotations * 360) + (360 - targetAngle);

                    // Çarkı çevir
                    if (this.wheel) {
                        this.wheel.style.transform = `rotate(${finalAngle}deg)`;
                    }

                    // Konfeti efekti
                    this.createConfetti();

                    // Sonucu göster
                    setTimeout(() => {
                        this.showResult(selectedPrize);
                        this.isSpinning = false;
                        this.hasSpun = true;
                    }, 6000);
                @endif
            }

            retry() {
                @if (!auth()->check())
                    if (this.retryCount >= this.maxRetries || !this.spinButton) return;

                    this.retryCount++;
                    this.closeResult(); // Bu artık ana modalı da kapatacak

                    // Çarkı reset et ve yeniden aç
                    if (this.wheel) {
                        this.wheel.style.transform = 'rotate(0deg)';
                    }

                    this.spinButton.disabled = false;
                    this.spinButton.textContent = 'SON ŞANS!';
                    this.spinButton.classList.add('pulse-animation');
                    this.hasSpun = false;

                    // Ana modalı tekrar aç
                    this.show();
                @endif
            }

            showResult(prize) {
                @if (!auth()->check())
                    if (!prize) return;

                    if (prize.type === 'retry') {
                        if (this.retryCount < this.maxRetries) {
                            this.showRetryModal();
                        } else {
                            // Son şans da tekrar dene gelirse, otomatik olarak en düşük ödülü ver
                            const fallbackPrize = {
                                name: '%20 İNDİRİM',
                                type: 'discount',
                                value: 20
                            };
                            this.showWinModal(fallbackPrize);
                        }
                    } else {
                        this.showWinModal(prize);
                    }
                @endif
            }

            showWinModal(prize) {
                @if (!auth()->check())
                    if (!prize || !this.prizeText || !this.prizeCode) return;

                    const code = this.generatePrizeCode();
                    this.prizeText.textContent = prize.name;
                    this.prizeCode.textContent = code;

                    // WhatsApp mesajını hazırla
                    this.currentPrizeCode = code;
                    this.currentPrizeName = prize.name;

                    if (this.winContent && this.retryContent && this.resultModal) {
                        this.winContent.classList.remove('hidden');
                        this.retryContent.classList.add('hidden');
                        this.resultModal.style.display = 'flex';
                    }
                @endif
            }

            showRetryModal() {
                @if (!auth()->check())
                    if (this.winContent && this.retryContent && this.resultModal) {
                        this.winContent.classList.add('hidden');
                        this.retryContent.classList.remove('hidden');
                        this.resultModal.style.display = 'flex';
                    }
                @endif
            }

            claimPrize() {
                @if (!auth()->check())
                    if (!this.currentPrizeCode || !this.currentPrizeName) return;

                    const phoneNumber = '905541383539';
                    const message = encodeURIComponent(
                        `Merhaba! Rise English şans çarkından "${this.currentPrizeName}" kazandım. Kod: ${this.currentPrizeCode}`
                    );
                    const whatsappUrl = `https://wa.me/${phoneNumber}?text=${message}`;

                    window.open(whatsappUrl, '_blank');
                    this.closeResult();
                    this.close();
                @endif
            }

            createConfetti() {
                @if (!auth()->check())
                    const colors = ['#e63946', '#1a2e5a', '#f39c12', '#27ae60', '#8e44ad'];

                    for (let i = 0; i < 30; i++) {
                        setTimeout(() => {
                            const confetti = document.createElement('div');
                            confetti.className = 'confetti';
                            confetti.style.left = Math.random() * 100 + 'vw';
                            confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                            confetti.style.animationDelay = Math.random() * 3 + 's';
                            confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
                            document.body.appendChild(confetti);

                            setTimeout(() => {
                                confetti.remove();
                            }, 5000);
                        }, i * 50);
                    }
                @endif
            }
        }

        // Global fortune wheel instance
        let fortuneWheelInstance = null;

        function showFortuneWheel() {
            // Sadece giriş yapmayan kullanıcılar için
            @if (!auth()->check())
                // 24 saatlik süre kontrolü
                const seenTime = localStorage.getItem('fortuneWheelSeenTime');
                const now = new Date().getTime();

                if (seenTime && now < parseInt(seenTime)) {
                    // 24 saat henüz dolmamış, gösterme
                    return;
                }

                // Süresi dolmuşsa localStorage'ı temizle
                if (seenTime && now >= parseInt(seenTime)) {
                    localStorage.removeItem('fortuneWheelSeen');
                    localStorage.removeItem('fortuneWheelSeenTime');
                }

                // Şans çarkını göster
                if (!fortuneWheelInstance) {
                    fortuneWheelInstance = new FortuneWheelPopup();
                }
                fortuneWheelInstance.show();
            @endif
        }


        // DOM yüklendiğinde instance'ı hazırla
        document.addEventListener('DOMContentLoaded', function() {
            if (!fortuneWheelInstance) {
                fortuneWheelInstance = new FortuneWheelPopup();
            }
        });
    </script>
@endsection
