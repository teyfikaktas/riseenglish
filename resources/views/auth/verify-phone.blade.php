@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden mx-auto">
        <div class="bg-[#1a2e5a] px-6 py-4">
            <h2 class="text-center text-2xl font-bold text-white">
                Telefon Numarası Doğrulama
            </h2>
        </div>
        
        <div class="px-6 py-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="text-center mb-6">
                <p class="text-gray-700">
                    Kayıt olduğunuz <span class="font-medium">{{ auth()->user()->phone }}</span> numaralı telefona bir doğrulama kodu gönderdik.
                </p>
            </div>
            
            <form method="POST" action="{{ route('verification.phone.verify') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-700">
                        Doğrulama Kodu
                    </label>
                    <div class="mt-1">
                        <input id="otp" name="otp" type="text" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#1a2e5a] focus:border-[#1a2e5a] sm:text-sm">
                    </div>
                    @error('otp')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        <button type="button" id="resendOtpBtn" class="font-medium text-[#1a2e5a] hover:text-[#283b6a]" onclick="resendOtp()">
                            Kodu Tekrar Gönder
                        </button>
                    </div>
                    <div id="countdown" class="text-sm text-gray-500"></div>
                </div>
                
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#e63946] hover:bg-[#d62836] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#e63946]">
                        Doğrula
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Sayaç için değişkenler
    let countdownInterval;
    let countdownTime = 60;
    
    // Sayfa yüklendiğinde sayacı başlat
    document.addEventListener('DOMContentLoaded', function() {
        startCountdown();
    });
    
    // Sayaç başlatma fonksiyonu
    function startCountdown() {
        // Başlangıçta butonu devre dışı bırak
        document.getElementById('resendOtpBtn').disabled = true;
        document.getElementById('resendOtpBtn').classList.add('opacity-50', 'cursor-not-allowed');
        
        // Sayaç metnini göster
        updateCountdownText();
        
        // Her saniye sayacı güncelle
        countdownInterval = setInterval(function() {
            countdownTime--;
            updateCountdownText();
            
            // Sayaç sıfırlandığında
            if (countdownTime <= 0) {
                clearInterval(countdownInterval);
                document.getElementById('resendOtpBtn').disabled = false;
                document.getElementById('resendOtpBtn').classList.remove('opacity-50', 'cursor-not-allowed');
                document.getElementById('countdown').textContent = '';
            }
        }, 1000);
    }
    
    // Sayaç metnini güncelleme
    function updateCountdownText() {
        document.getElementById('countdown').textContent = countdownTime + ' saniye sonra tekrar gönderebilirsiniz';
    }
    
    // OTP'yi yeniden gönderme
    function resendOtp() {
        // CSRF token'ı al
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // AJAX isteği gönder
        fetch('{{ route("verification.phone.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Başarılı olduğunda bildirim göster
                alert('Doğrulama kodu tekrar gönderildi.');
                // Sayacı yeniden başlat
                countdownTime = 60;
                startCountdown();
            } else {
                alert('Kod gönderilirken bir hata oluştu: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        });
    }
</script>
@endsection