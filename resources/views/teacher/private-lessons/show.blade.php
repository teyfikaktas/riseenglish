@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Geri Dön Butonu -->
        <div class="mb-6">
            <a href="{{ route('ogretmen.private-lessons.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Panele Geri Dön
            </a>
        </div>

        <!-- Başarı Mesajı -->
        @if(session('success'))
        <div class="bg-green-50 rounded-lg shadow-md border border-green-200 mb-6">
            <div class="px-6 py-4">
                <h1 class="text-xl font-bold text-green-800">İşlem Başarılı</h1>
                <div class="mt-3 p-3 bg-green-100 rounded">
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Hata Mesajı -->
        @if(session('error'))
        <div class="bg-red-50 rounded-lg shadow-md border border-red-200 mb-6">
            <div class="px-6 py-4">
                <h1 class="text-xl font-bold text-red-800">Hata Oluştu</h1>
                <div class="mt-3 p-3 bg-red-100 rounded">
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Ders Detay Kartı -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
                <div class="flex justify-between items-center">
                    <h1 class="text-xl font-bold text-gray-800">Özel Ders Detayları</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('ogretmen.private-lessons.edit', $session->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Düzenle
                        </a>
                        <button onclick="document.getElementById('delete-form').submit();" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Sil
                        </button>
                        <form id="delete-form" action="{{ route('ogretmen.private-lessons.destroy', $session->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mt-1">Özel ders bilgilerini görüntülemek ve yönetmek için bu sayfayı kullanabilirsiniz.</p>
            </div>

            <div class="p-6">
                <!-- Genel Bilgiler -->
                <div class="border-b pb-6 mb-6">
                    <h2 class="font-semibold text-gray-800 mb-4">Genel Bilgiler</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Ders Tipi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Ders Türü</label>
                            <p class="text-gray-800 font-medium">{{ $session->privateLesson->name ?? 'Bilinmiyor' }}</p>
                        </div>
                        
                        <!-- Öğrenci -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Öğrenci</label>
                            <p class="text-gray-800 font-medium">{{ $session->student->name ?? 'Bilinmiyor' }}</p>
                        </div>
                        
                        <!-- Durum -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Ders Durumu</label>
                            @php
                                switch ($session->status) {
                                    case 'pending':
                                        $statusText = 'Bekliyor';
                                        $statusColor = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                                        break;
                                    case 'active':
                                        $statusText = 'Aktif';
                                        $statusColor = 'bg-green-100 text-green-800 border-green-300';
                                        break;
                                    case 'paused':
                                        $statusText = 'Duraklatıldı';
                                        $statusColor = 'bg-blue-100 text-blue-800 border-blue-300';
                                        break;
                                    case 'expired':
                                        $statusText = 'Süresi Doldu';
                                        $statusColor = 'bg-gray-100 text-gray-800 border-gray-300';
                                        break;
                                    case 'cancelled':
                                        $statusText = 'İptal Edildi';
                                        $statusColor = 'bg-red-100 text-red-800 border-red-300';
                                        break;
                                    default:
                                        $statusText = ucfirst($session->status);
                                        $statusColor = 'bg-gray-100 text-gray-800 border-gray-300';
                                }
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $statusColor }}">
                                {{ $statusText }}
                            </span>
                        </div>
                        
                        <!-- Tekrarlı -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tekrarlı Ders</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $session->is_recurring ? 'bg-purple-100 text-purple-800 border-purple-300' : 'bg-gray-100 text-gray-800 border-gray-300' }}">
                                {{ $session->is_recurring ? 'Evet' : 'Hayır' }}
                            </span>
                        </div>
                        
                        <!-- Ders ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Ders ID</label>
                            <p class="text-gray-800 font-medium">#{{ $session->id }}</p>
                        </div>
                        
                        <!-- Konum -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Konum</label>
                            <p class="text-gray-800 font-medium">{{ $session->location ?: 'Belirtilmemiş' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Zamanlama Bilgileri -->
                <div class="border-b pb-6 mb-6">
                    <h2 class="font-semibold text-gray-800 mb-4">Zamanlama Bilgileri</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Gün -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Haftanın Günü</label>
                            @php
                                $days = ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'];
                                $dayText = isset($days[$session->day_of_week]) ? $days[$session->day_of_week] : 'Bilinmiyor';
                            @endphp
                            <p class="text-gray-800 font-medium">{{ $dayText }}</p>
                        </div>
                        
                        <!-- Başlangıç Tarihi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Başlangıç Tarihi</label>
                            <p class="text-gray-800 font-medium">
                                {{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}
                            </p>
                        </div>
                        
                        <!-- Bitiş Tarihi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Bitiş Tarihi</label>
                            <p class="text-gray-800 font-medium">
                                {{ $session->end_date ? \Carbon\Carbon::parse($session->end_date)->format('d.m.Y') : 'Belirtilmemiş' }}
                            </p>
                        </div>
                        
                        <!-- Başlangıç Saati -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Başlangıç Saati</label>
                            <p class="text-gray-800 font-medium">
                                {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}
                            </p>
                        </div>
                        
                        <!-- Bitiş Saati -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Bitiş Saati</label>
                            <p class="text-gray-800 font-medium">
                                {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                            </p>
                        </div>
                        
                        <!-- Süre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Ders Süresi</label>
                            @php
                                $startTime = \Carbon\Carbon::parse($session->start_time);
                                $endTime = \Carbon\Carbon::parse($session->end_time);
                                $durationMinutes = $startTime->diffInMinutes($endTime);
                                $durationHours = floor($durationMinutes / 60);
                                $remainingMinutes = $durationMinutes % 60;
                                
                                if ($durationHours > 0) {
                                    $durationText = $durationHours . ' saat';
                                    if ($remainingMinutes > 0) {
                                        $durationText .= ' ' . $remainingMinutes . ' dakika';
                                    }
                                } else {
                                    $durationText = $durationMinutes . ' dakika';
                                }
                            @endphp
                            <p class="text-gray-800 font-medium">{{ $durationText }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Ödeme Bilgileri -->
                <div class="border-b pb-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-semibold text-gray-800">Ödeme Bilgileri</h2>
                        
                        <!-- Ödeme İşlemleri Butonu -->
                        <button type="button" onclick="togglePaymentModal()" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            Ödeme Al
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Ders Ücreti -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Toplam Ders Ücreti</label>
                            <p class="text-gray-800 font-medium">{{ number_format($session->fee, 2) }} ₺</p>
                        </div>
                        
                        <!-- Ödeme Durumu -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Ödeme Durumu</label>
                            @php
                                switch ($session->payment_status) {
                                    case 'pending':
                                        $paymentStatusText = 'Bekliyor';
                                        $paymentStatusColor = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                                        break;
                                    case 'partially_paid':
                                        $paymentStatusText = 'Kısmi Ödeme Alındı';
                                        $paymentStatusColor = 'bg-blue-100 text-blue-800 border-blue-300';
                                        break;
                                    case 'paid':
                                        $paymentStatusText = 'Tam Ödeme Alındı';
                                        $paymentStatusColor = 'bg-green-100 text-green-800 border-green-300';
                                        break;
                                    case 'refunded':
                                        $paymentStatusText = 'İade Edildi';
                                        $paymentStatusColor = 'bg-purple-100 text-purple-800 border-purple-300';
                                        break;
                                    case 'cancelled':
                                        $paymentStatusText = 'İptal Edildi';
                                        $paymentStatusColor = 'bg-red-100 text-red-800 border-red-300';
                                        break;
                                    default:
                                        $paymentStatusText = ucfirst($session->payment_status);
                                        $paymentStatusColor = 'bg-gray-100 text-gray-800 border-gray-300';
                                }
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $paymentStatusColor }}">
                                {{ $paymentStatusText }}
                            </span>
                        </div>
                        
                        <!-- Ödenen Miktar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Ödenen Miktar</label>
                            <p class="text-gray-800 font-medium">{{ number_format($session->paid_amount, 2) }} ₺</p>
                        </div>
                        
                        <!-- Kalan Miktar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Kalan Miktar</label>
                            <p class="text-gray-800 font-medium">{{ number_format($session->fee - $session->paid_amount, 2) }} ₺</p>
                        </div>
                        
                        <!-- Ödeme Tarihi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Son Ödeme Tarihi</label>
                            <p class="text-gray-800 font-medium">{{ $session->payment_date ? \Carbon\Carbon::parse($session->payment_date)->format('d.m.Y') : 'Belirtilmemiş' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Notlar -->
                <div>
                    <h2 class="font-semibold text-gray-800 mb-4">Notlar</h2>
                    
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="text-gray-700 whitespace-pre-line">{{ $session->notes ?: 'Henüz not eklenmemiş.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ödeme Alma Modal -->
<div id="payment-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="border-b px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-800">Ödeme Al</h3>
        </div>
        
        <form action="{{ route('ogretmen.private-lessons.takePayment', $session->id) }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-4">
                <!-- Ödeme Miktarı -->
                <div>
                    <label for="payment_amount" class="block text-sm font-medium text-gray-700 mb-1">
                        Ödeme Miktarı (₺) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="payment_amount" name="payment_amount" step="0.01" min="0.01" max="{{ $session->fee - $session->paid_amount }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <p class="text-xs text-gray-500 mt-1">Maksimum kalan miktar: {{ number_format($session->fee - $session->paid_amount, 2) }} ₺</p>
                </div>
                
                <!-- Ödeme Tarihi -->
                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Ödeme Tarihi <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                
                <!-- Ödeme Notu -->
                <div>
                    <label for="payment_notes" class="block text-sm font-medium text-gray-700 mb-1">
                        Ödeme Notu
                    </label>
                    <textarea id="payment_notes" name="payment_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Ödeme ile ilgili ekstra notlar..."></textarea>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="togglePaymentModal()" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50">
                    İptal
                </button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Ödeme Al
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePaymentModal() {
        const modal = document.getElementById('payment-modal');
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Sayfanın kaydırılmasını engelle
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Sayfanın kaydırılmasını etkinleştir
        }
    }

    // Modal dışına tıklandığında kapatma
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('payment-modal');
        const modalContent = modal.querySelector('.bg-white');
        
        if (!modal.classList.contains('hidden') && !modalContent.contains(event.target) && !event.target.closest('button')) {
            togglePaymentModal();
        }
    });

    // ESC tuşu ile kapatma
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('payment-modal');
            if (!modal.classList.contains('hidden')) {
                togglePaymentModal();
            }
        }
    });
</script>
@endsection