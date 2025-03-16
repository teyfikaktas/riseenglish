@extends('layouts.app')

@section('title', $course->name . ' - Kayıt Yönetimi')

@section('content')
<!-- RISE ENGLISH Prize Wheel -->
<div class="fixed inset-0 bg-gray-900 bg-opacity-90 flex items-center justify-center z-50 hidden" id="prizeWheelModal">
    <div class="bg-gray-900 rounded-lg shadow-xl max-w-md w-full p-6 text-center">
        <!-- Close Button -->
        <button type="button" class="absolute top-3 right-3 text-white hover:text-gray-300" onclick="closePrizeWheel()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <!-- Wheel Container -->
        <div class="relative w-64 h-64 mx-auto my-6">
            <!-- Wheel -->
            <svg id="prizeWheel" class="w-full h-full" viewBox="0 0 100 100">
                <!-- Segments will be created by JS -->
                
                <!-- Center circle -->
                <circle cx="50" cy="50" r="10" fill="white" stroke="#1a56db" stroke-width="1"></circle>
                <circle cx="50" cy="50" r="9" fill="#1a56db"></circle>
                <text x="50" y="53" font-size="7" fill="white" text-anchor="middle" font-weight="bold">RS</text>
            </svg>
            
            <!-- Marker (Triangle pointer) -->
            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20">
                <div class="w-4 h-4 bg-yellow-400 rounded-full"></div>
            </div>
        </div>
        
        <!-- Title -->
        <h2 class="text-3xl font-bold mb-2">
            <span class="text-pink-500">Ramazan Çarkı</span> <span class="text-white">Aktif!</span>
        </h2>
        
        <!-- Subtitle -->
        <p class="text-xl font-bold text-blue-100 mb-6">
            Ekstra indirim için <span class="text-yellow-400">👇</span>
        </p>
        
        <!-- Timer -->
        <div class="flex justify-center space-x-3 mb-6">
            <div class="bg-white p-3 rounded-md w-20">
                <div id="hours" class="text-4xl font-bold text-blue-900">00</div>
                <div class="text-sm text-blue-900">Saat</div>
            </div>
            <div class="bg-white p-3 rounded-md w-20">
                <div id="minutes" class="text-4xl font-bold text-blue-900">29</div>
                <div class="text-sm text-blue-900">Dakika</div>
            </div>
            <div class="bg-white p-3 rounded-md w-20">
                <div id="seconds" class="text-4xl font-bold text-blue-900">36</div>
                <div class="text-sm text-blue-900">Saniye</div>
            </div>
        </div>
        
        <!-- Consent -->
        <div class="flex items-center justify-center mb-4">
            <input type="checkbox" id="consent" class="h-5 w-5 text-blue-600 border-gray-300 rounded">
            <label for="consent" class="ml-2 text-sm text-white">
                Kişisel verilerimin Rıza Metni kapsamında işlenmesini onaylıyorum.
            </label>
        </div>
        
        <!-- Email input -->
        <input type="email" id="wheelEmail" placeholder="Email.." class="w-full p-3 mb-4 border border-gray-300 rounded-md bg-gray-800 text-white">
        
        <!-- Spin button -->
        <button id="spinButton" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-4 px-4 rounded-md transition-colors">
            Ücretsiz Çevir
        </button>
        
        <!-- Note -->
        <p class="text-sm text-white mt-3">
            Not: Çark indiriminden sadece bir kez faydalanabilirsiniz.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prize wheel configuration
    const segments = [
        { text: '%10', color: '#3a86ff' }, // Blue
        { text: 'Tüh!', color: '#1a2f69' }, // Navy blue
        { text: '%20', color: '#e63946' }, // Red
        { text: '%10', color: '#3a86ff' }, // Blue
        { text: 'Tüh!', color: '#1a2f69' }, // Navy blue
        { text: '%15', color: '#e63946' }, // Red
        { text: '%12', color: '#3a86ff' }, // Blue
        { text: 'Tüh!', color: '#1a2f69' } // Navy blue
    ];
    
    const wheel = document.getElementById('prizeWheel');
    const spinButton = document.getElementById('spinButton');
    let spinning = false;
    let currentRotation = 0;
    
    // Create wheel segments
    createWheel();
    
    // Start countdown
    startCountdown();
    
    // Spin button event
    spinButton.addEventListener('click', spinWheel);
    
    // Functions
    function createWheel() {
        const segmentAngle = 360 / segments.length;
        const svgNS = "http://www.w3.org/2000/svg";
        
        segments.forEach((segment, index) => {
            // Calculate the coordinates for the segment path
            const startAngle = index * segmentAngle;
            const endAngle = (index + 1) * segmentAngle;
            
            const startRad = (startAngle - 90) * Math.PI / 180;
            const endRad = (endAngle - 90) * Math.PI / 180;
            
            const x1 = 50 + 40 * Math.cos(startRad);
            const y1 = 50 + 40 * Math.sin(startRad);
            const x2 = 50 + 40 * Math.cos(endRad);
            const y2 = 50 + 40 * Math.sin(endRad);
            
            // Create the path for the segment
            const path = document.createElementNS(svgNS, "path");
            
            const d = [
                "M", 50, 50,
                "L", x1, y1,
                "A", 40, 40, 0, 0, 1, x2, y2,
                "Z"
            ].join(" ");
            
            path.setAttribute("d", d);
            path.setAttribute("fill", segment.color);
            path.setAttribute("stroke", "white");
            path.setAttribute("stroke-width", "0.5");
            
            wheel.appendChild(path);
            
            // Add text to the segment
            const textAngle = startAngle + segmentAngle / 2;
            const textRad = (textAngle - 90) * Math.PI / 180;
            const textX = 50 + 25 * Math.cos(textRad);
            const textY = 50 + 25 * Math.sin(textRad);
            
            const text = document.createElementNS(svgNS, "text");
            text.setAttribute("x", textX);
            text.setAttribute("y", textY);
            text.setAttribute("fill", "white");
            text.setAttribute("font-size", "5");
            text.setAttribute("font-weight", "bold");
            text.setAttribute("text-anchor", "middle");
            text.setAttribute("dominant-baseline", "middle");
            text.textContent = segment.text;
            
            // Rotate the text to be more readable
            text.setAttribute("transform", `rotate(${textAngle}, ${textX}, ${textY})`);
            
            wheel.appendChild(text);
        });
    }
    
    function spinWheel() {
        if (spinning) return;
        
        const email = document.getElementById('wheelEmail').value;
        const consent = document.getElementById('consent').checked;
        
        if (!email || !consent) {
            alert('Lütfen e-posta adresinizi girin ve onay kutusunu işaretleyin.');
            return;
        }
        
        spinning = true;
        spinButton.disabled = true;
        spinButton.classList.add('opacity-50');
        
        // Random rotation (5-10 full rotations + random segment)
        const spinRotation = 1800 + Math.floor(Math.random() * 1800);
        const finalRotation = currentRotation + spinRotation;
        
        // Apply rotation to the wheel with CSS transition
        wheel.style.transition = 'transform 5s cubic-bezier(0.17, 0.67, 0.83, 0.67)';
        wheel.style.transform = `rotate(${finalRotation}deg)`;
        
        currentRotation = finalRotation;
        
        // Determine winner after spin
        setTimeout(() => {
            spinning = false;
            spinButton.disabled = false;
            spinButton.classList.remove('opacity-50');
            
            // Calculate which segment landed on the marker
            const normalizedRotation = finalRotation % 360;
            const segmentAngle = 360 / segments.length;
            const winningIndex = Math.floor((360 - normalizedRotation) / segmentAngle) % segments.length;
            const prize = segments[winningIndex].text;
            
            if (prize !== 'Tüh!') {
                alert(`Tebrikler! ${prize} indirim kazandınız!`);
                
                // Here you can make an AJAX request to save the prize and user email
                // fetch('/api/save-prize', {
                //     method: 'POST',
                //     headers: { 'Content-Type': 'application/json' },
                //     body: JSON.stringify({ email, prize })
                // });
            } else {
                alert('Üzgünüz, bu kez şanslı değildiniz. Tekrar deneyebilirsiniz!');
            }
        }, 5000);
    }
    
    function startCountdown() {
        let duration = 30 * 60; // 30 minutes in seconds
        
        const timer = setInterval(() => {
            const hours = Math.floor(duration / 3600);
            const minutes = Math.floor((duration % 3600) / 60);
            const seconds = duration % 60;
            
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
            
            if (--duration < 0) {
                clearInterval(timer);
                closePrizeWheel();
            }
        }, 1000);
    }
});

// Function to open the prize wheel
function openPrizeWheel() {
    const modal = document.getElementById('prizeWheelModal');
    modal.classList.remove('hidden');
}

// Function to close the prize wheel
function closePrizeWheel() {
    const modal = document.getElementById('prizeWheelModal');
    modal.classList.add('hidden');
}
</script>

<!-- Button to open the prize wheel -->
<button onclick="openPrizeWheel()" class="fixed bottom-4 right-4 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full shadow-lg z-40">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947z" clip-rule="evenodd" />
        <path d="M10 13a3 3 0 100-6 3 3 0 000 6z" />
    </svg>
    Çarkı Çevir
</button>
@endsection