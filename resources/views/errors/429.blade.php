<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Çok Fazla İstek - Rise English</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] py-20 overflow-hidden flex items-center justify-center px-6">
    <!-- Dekoratif arka plan desenleri -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <defs>
                <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect width="100" height="100" fill="url(#grid)" />
        </svg>
    </div>

    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-auto relative z-10 overflow-hidden">
        <!-- Header with logo -->
        <div class="bg-[#1a2e5a] p-6 text-center relative">
            <img src="{{ asset('images/logo.png') }}" alt="Rise English Logo" class="h-12 mx-auto">
            
            <div class="absolute -right-2 top-2 transform rotate-12">
                <div class="bg-[#e63946] text-white text-xs font-bold py-1 px-3 rounded-full shadow-lg">
                    429 Error
                </div>
            </div>
        </div>
        
        <!-- Error content -->
        <div class="p-8">
            <div class="mb-8 text-center">
                <div class="inline-block p-3 rounded-full bg-orange-100 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-[#1a2e5a] mb-2">Çok Fazla İstek</h1>
                <p class="text-gray-600 mb-6">Çok fazla istek gönderdiniz. Sistem aşırı yüklenmeyi önlemek için geçici olarak isteklerinizi sınırlandırmıştır.</p>
                
                <div class="bg-gray-50 rounded-lg p-6 mb-6 border-l-4 border-orange-500">
                    <p class="text-gray-700 mb-4">Lütfen şu kadar bekleyin:</p>
                    <div class="flex justify-center items-center gap-2">
                        <span id="countdown" class="text-3xl font-bold text-[#e63946] animate-pulse">{{ $retry_after ?? 60 }}</span>
                        <span class="text-gray-700">saniye</span>
                    </div>
                    
                    <!-- Progress bar -->
                    <div class="mt-4 w-full bg-gray-200 rounded-full h-2.5">
                        <div id="progress-bar" class="bg-[#e63946] h-2.5 rounded-full w-full transition-all duration-1000 ease-linear"></div>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ url()->previous() }}" class="bg-[#1a2e5a] hover:bg-[#132447] text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 text-center">
                    <i class="fas fa-arrow-left mr-2"></i>Geri Dön
                </a>
                <a href="{{ url('/') }}" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 text-center">
                    <i class="fas fa-home mr-2"></i>Ana Sayfaya Git
                </a>
            </div>
        </div>
        
        <!-- Alt Banner -->
        <div class="py-3 px-4 bg-gray-100 text-center border-t border-gray-200">
            <span class="inline-block text-[#1a2e5a] font-medium">
                <i class="fas fa-server mr-2"></i>Rise English Sistem Güvenliği
            </span>
        </div>
    </div>
    
    <!-- Animasyonlu vurgu kutusu -->
    <div class="absolute bottom-4 right-4 bg-white rounded-lg p-4 shadow-lg hidden sm:block">
        <div class="flex items-center">
            <div class="bg-[#e63946] rounded-full h-4 w-4 mr-2 animate-pulse"></div>
            <span class="font-bold text-[#1a2e5a]">Kısa bir süre sonra tekrar deneyin</span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const countdownDisplay = document.getElementById('countdown');
            const progressBar = document.getElementById('progress-bar');
            let seconds = parseInt(countdownDisplay.textContent);
            const totalSeconds = seconds;
            
            // Progress bar'ı ayarla
            progressBar.style.width = '100%';
            
            const timer = setInterval(() => {
                seconds--;
                countdownDisplay.textContent = seconds;
                
                // Progress bar'ı güncelle
                const percentLeft = (seconds / totalSeconds) * 100;
                progressBar.style.width = percentLeft + '%';
                
                if (seconds <= 0) {
                    clearInterval(timer);
                    location.reload();
                }
            }, 1000);
        });
    </script>
</body>
</html>