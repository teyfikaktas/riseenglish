@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Geri Dön Butonu -->
        <div class="mb-6">
            <a href="{{ route('ogretmen.private-lessons.showLesson', $lesson->id) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Derse Geri Dön
            </a>
        </div>

        <!-- Hata Mesajları -->
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

        <!-- Form Başlığı -->
        <div class="bg-white rounded-t-lg shadow-md border-b">
            <div class="px-6 py-4">
                <h1 class="text-xl font-bold text-gray-800">{{ $lesson->name }} - Yeni Seans Ekle</h1>
                <p class="text-gray-600 text-sm mt-1">{{ $student->name }} için yeni seans veya seanslar ekleyin.</p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-b-lg shadow-md">
            <form action="{{ route('ogretmen.private-lessons.storeNewSession', $lesson->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <!-- Seans Bilgileri -->
                <div class="border-b pb-6">
                    <h2 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-700 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">1</span>
                        Seans Bilgileri
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Gün -->
                        <div>
                            <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-1">
                                Gün <span class="text-red-500">*</span>
                            </label>
                            <select id="day_of_week" name="day_of_week" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Seçiniz</option>
                                <option value="1">Pazartesi</option>
                                <option value="2">Salı</option>
                                <option value="3">Çarşamba</option>
                                <option value="4">Perşembe</option>
                                <option value="5">Cuma</option>
                                <option value="6">Cumartesi</option>
                                <option value="0">Pazar</option>
                            </select>
                            @error('day_of_week')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- İlk Seans Tarihi -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                                İlk Seans Tarihi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="start_date" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            @error('start_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Saat Bilgileri -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <!-- Başlangıç Saati -->
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                                Başlangıç Saati <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="start_time" name="start_time" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            @error('start_time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Bitiş Saati -->
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                                Bitiş Saati <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="end_time" name="end_time" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            @error('end_time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Konum -->
                    <div class="mt-4">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                            Konum
                        </label>
                        <input type="text" id="location" name="location" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Örn: Sınıf A, Online, vb.">
                        @error('location')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Tekrar Seçenekleri -->
                <div class="border-b pb-6">
                    <h2 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-700 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">2</span>
                        Birden Fazla Seans Planla
                    </h2>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Tekrarlama Onayı -->
                        <div class="flex items-center">
                            <input type="checkbox" id="is_multi_session" name="is_multi_session" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_multi_session" class="ml-2 block text-sm text-gray-700">Birden fazla seans planla</label>
                        </div>
                        
                        <!-- Son Seans Tarihi -->
                        <div id="end_date_container" class="hidden">
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Son Seans Tarihi <span class="text-red-500">*</span>
                                @if(isset($lastSessionDate))
                                <span class="text-sm text-gray-500 ml-1">
                                    (Son dersinizin tarihi: {{ \Carbon\Carbon::parse($lastSessionDate)->format('d.m.Y') }})
                                </span>
                                @endif
                            </label>
                            <div class="flex">
                                <input type="date" id="end_date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @if(isset($lastSessionDate))
                                <button type="button" id="use_last_date" class="ml-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Kullan
                                </button>
                                @endif
                            </div>
                            @error('end_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Sonuç Özeti -->
                        <div id="session_summary" class="hidden mt-2 p-3 bg-yellow-50 rounded border border-yellow-200">
                            <p class="text-sm text-yellow-800">
                                <span id="summary_text">Oluşturulacak toplam ders sayısı hesaplanıyor...</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Notlar -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                        Notlar
                    </label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Seansla ilgili ekstra notlar..."></textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Form Gönderme Butonları -->
                <div class="flex items-center justify-end pt-4 border-t">
                    <button type="button" onclick="window.history.back()" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md mr-2 hover:bg-gray-50">
                        İptal
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        Seansı Ekle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Başlangıç saati değiştiğinde bitiş saatini tavsiye olarak ayarla
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        
        startTimeInput.addEventListener('change', function() {
    if (this.value && !endTimeInput.value) {
        // Başlangıç saatini ayrıştır
        const [hours, minutes] = this.value.split(':').map(Number);

        // Toplam dakikayı hesapla: saat→dakika + 45
        let totalMinutes = hours * 60 + minutes + 45;

        // Yeni saati ve dakikayı bul
        const endHours = Math.floor(totalMinutes / 60) % 24;
        const endMinutes = totalMinutes % 60;

        // İki haneli formatla ve input'a ata
        endTimeInput.value = 
          `${String(endHours).padStart(2, '0')}:${String(endMinutes).padStart(2, '0')}`;
    }
});

        // Çoklu seans onay kutusu değiştiğinde bitiş tarihi alanını göster/gizle
        const isMultiSessionCheckbox = document.getElementById('is_multi_session');
        const endDateContainer = document.getElementById('end_date_container');
        const sessionSummary = document.getElementById('session_summary');
        
        isMultiSessionCheckbox.addEventListener('change', function() {
            if (this.checked) {
                endDateContainer.classList.remove('hidden');
                updateSessionSummary();
            } else {
                endDateContainer.classList.add('hidden');
                sessionSummary.classList.add('hidden');
                document.getElementById('end_date').value = '';
            }
        });
        
        // Son ders tarihini kullan butonu
        const useLastDateButton = document.getElementById('use_last_date');
        if (useLastDateButton) {
            useLastDateButton.addEventListener('click', function() {
                const lastSessionDate = '{{ isset($lastSessionDate) ? $lastSessionDate : "" }}';
                if (lastSessionDate) {
                    document.getElementById('end_date').value = lastSessionDate;
                    updateSessionSummary();
                }
            });
        }
        
        // Tarih seçiminde uygunluk kontrolü
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const dayOfWeekSelect = document.getElementById('day_of_week');
        
        // Gün değiştiğinde tarihleri kontrol et
        dayOfWeekSelect.addEventListener('change', function() {
            if (startDateInput.value) {
                adjustStartDateToMatchDay();
                updateSessionSummary();
            }
        });
        
        // Başlangıç tarihi değiştiğinde günle uyumluluğu kontrol et
        startDateInput.addEventListener('change', function() {
            if (dayOfWeekSelect.value) {
                adjustStartDateToMatchDay();
            }
            
            // Bitiş tarihinin başlangıç tarihinden önce olmamasını sağla
            if (endDateInput.value && new Date(endDateInput.value) < new Date(this.value)) {
                endDateInput.value = this.value;
            }
            
            // Minimum bitiş tarihini ayarla
            endDateInput.min = this.value;
            
            updateSessionSummary();
        });
        
        // Bitiş tarihi değiştiğinde ders özetini güncelle
        endDateInput.addEventListener('change', function() {
            updateSessionSummary();
        });
        
        // İlk seans tarihini seçilen güne göre ayarla
        function adjustStartDateToMatchDay() {
            const selectedDay = parseInt(dayOfWeekSelect.value);
            const startDate = new Date(startDateInput.value);
            const currentDay = startDate.getDay(); // 0: Pazar, 1: Pazartesi, ...
            
            if (currentDay !== selectedDay) {
                // Seçilen gün ve tarih arasındaki farkı hesapla
                const dayDiff = (selectedDay - currentDay + 7) % 7;
                
                // Tarihi seçilen güne ayarla
                startDate.setDate(startDate.getDate() + dayDiff);
                
                // Input değerini güncelle
                startDateInput.value = startDate.toISOString().split('T')[0];
            }
        }
        
        // Ders özet bilgisini güncelle
        function updateSessionSummary() {
            if (!isMultiSessionCheckbox.checked || !startDateInput.value || !endDateInput.value || !dayOfWeekSelect.value) {
                sessionSummary.classList.add('hidden');
                return;
            }
            
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            
            if (endDate < startDate) {
                document.getElementById('summary_text').textContent = "Hata: Bitiş tarihi başlangıç tarihinden önce olamaz!";
                sessionSummary.classList.remove('hidden');
                return;
            }
            
            // Seçilen gün
            const selectedDay = parseInt(dayOfWeekSelect.value);
            
            // Başlangıç tarihini seçilen güne ayarla (eğer uygun değilse)
            let currentDate = new Date(startDate);
            const startDayOfWeek = currentDate.getDay();
            
            if (startDayOfWeek !== selectedDay) {
                const dayDiff = (selectedDay - startDayOfWeek + 7) % 7;
                currentDate.setDate(currentDate.getDate() + dayDiff);
            }
            
            // Seçilen gün için tüm ders sayısını hesapla
            let sessionCount = 0;
            
            while (currentDate <= endDate) {
                sessionCount++;
                // Bir sonraki haftaya aynı güne git
                currentDate.setDate(currentDate.getDate() + 7);
            }
            
            document.getElementById('summary_text').textContent = `Oluşturulacak toplam ders sayısı: ${sessionCount}`;
            sessionSummary.classList.remove('hidden');
        }
        
        // Form gönderilmeden önce kontrol et
        document.querySelector('form').addEventListener('submit', function(e) {
    const isMultiSession = document.getElementById('is_multi_session').checked;
    
    if (isMultiSession && !document.getElementById('end_date').value) {
        e.preventDefault();
        alert('Lütfen son seans tarihini belirtin.');
        return false;
    }
    
    // Form gönderiminde repeat_until alanına bitiş tarihini ata
    if (isMultiSession) {
        const endDateValue = document.getElementById('end_date').value;
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'repeat_until';
        hiddenInput.value = endDateValue;
        this.appendChild(hiddenInput);
    } else {
        // Tek seans için başlangıç tarihi aynı zamanda bitiş tarihi olarak gönderilsin
        const startDateValue = document.getElementById('start_date').value;
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'repeat_until';
        hiddenInput.value = startDateValue;
        this.appendChild(hiddenInput);
    }
});
    });
</script>
@endsection