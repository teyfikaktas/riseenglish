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

        <!-- Hata Mesajları -->
        @if(session('error'))
        <div class="bg-red-50 rounded-lg shadow-md border border-red-200 mb-6">
            <div class="px-6 py-4">
                <h1 class="text-xl font-bold text-red-800">Hata Oluştu</h1>
                <p class="text-red-600 text-sm mt-1">Özel ders oluşturulurken bir hata meydana geldi:</p>
                <div class="mt-3 p-3 bg-red-100 rounded">
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Başlığı -->
        <div class="bg-white rounded-t-lg shadow-md border-b">
            <div class="px-6 py-4">
                <h1 class="text-xl font-bold text-gray-800">Yeni Özel Ders Oluştur</h1>
                <p class="text-gray-600 text-sm mt-1">Yeni bir özel ders planı oluşturmak için aşağıdaki formu doldurun.</p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-b-lg shadow-md">
            <form action="{{ route('ogretmen.private-lessons.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <!-- Ders Bilgileri -->
                <div class="border-b pb-6">
                    <h2 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-700 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">1</span>
                        Ders Bilgileri
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Özel Ders Adı -->
                        <div>
                            <label for="lesson_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Özel Ders Adı <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="lesson_name" name="lesson_name" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Örn: İngilizce Konuşma Dersi, Matematik 8. Sınıf" required>
                            @error('lesson_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Öğrenci Seçimi -->
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Öğrenci <span class="text-red-500">*</span>
                            </label>
                            <select id="student_id" name="student_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Seçiniz</option>
                                @foreach($students ?? [] as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Ücret -->
                        <div>
                            <label for="fee" class="block text-sm font-medium text-gray-700 mb-1">
                                Seans Ücreti (₺) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="fee" name="fee" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="0.00" required>
                            @error('fee')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konum -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                                Konum
                            </label>
                            <input type="text" id="location" name="location" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Örn: Sınıf A, Online, vb.">
                            @error('location')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Ders Zamanlaması -->
                <div class="border-b pb-6">
                    <h2 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-700 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">2</span>
                        Ders Zamanlaması
                    </h2>
                    
                    <!-- Tarih Aralığı -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Başlangıç Tarihi -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Başlangıç Tarihi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="start_date" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            @error('start_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Bitiş Tarihi -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Bitiş Tarihi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="end_date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            @error('end_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Ders Günleri Paneli -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4">
                        <h3 class="font-medium text-gray-700 mb-2">Ders Günleri ve Saatleri</h3>
                        <p class="text-sm text-gray-500 mb-3">Her hafta hangi gün ve saatlerde ders yapılacağını seçin:</p>
                        
                        <div id="lesson-days-container">
                            <div class="lesson-day-row bg-white p-3 rounded border border-gray-200 mb-2">
                                <div class="grid grid-cols-3 gap-4 items-center">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Gün <span class="text-red-500">*</span>
                                        </label>
                                        <select name="days[]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                            <option value="1">Pazartesi</option>
                                            <option value="2">Salı</option>
                                            <option value="3">Çarşamba</option>
                                            <option value="4">Perşembe</option>
                                            <option value="5">Cuma</option>
                                            <option value="6">Cumartesi</option>
                                            <option value="0">Pazar</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Başlangıç Saati <span class="text-red-500">*</span>
                                        </label>
                                        <input type="time" name="start_times[]" class="start-time-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    </div>
                                    <div class="hidden">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Bitiş Saati <span class="text-red-500">*</span>
                                        </label>
                                        <input type="time" name="end_times[]" class="end-time-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <button type="button" id="add-lesson-day" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Başka Gün Ekle
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Ek Bilgiler -->
                <div>
                    <h2 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-700 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">3</span>
                        Ek Bilgiler
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Durum -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Durum <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="pending">Bekliyor</option>
                                <option value="approved" selected>Onaylandı</option>
                                <option value="rejected">Reddedildi</option>
                                <option value="cancelled">İptal Edildi</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Ödeme Durumu -->
                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">
                                Ödeme Durumu <span class="text-red-500">*</span>
                            </label>
                            <select id="payment_status" name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="pending" selected>Bekliyor</option>
                                <option value="partially_paid">Kısmen Ödendi</option>
                                <option value="paid">Ödendi</option>
                                <option value="refunded">İade Edildi</option>
                                <option value="cancelled">İptal Edildi</option>
                            </select>
                            @error('payment_status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Notlar -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                            Notlar
                        </label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Ders ile ilgili ekstra notlar..."></textarea>
                        @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Form Gönderme Butonları -->
                <div class="flex items-center justify-end pt-4 border-t">
                    <button type="button" onclick="window.history.back()" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md mr-2 hover:bg-gray-50">
                        İptal
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        Dersi Oluştur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ders Günleri Ekleme İşlevi
        const addLessonDayBtn = document.getElementById('add-lesson-day');
        const lessonDaysContainer = document.getElementById('lesson-days-container');
        
        // Başlangıç saati değiştiğinde bitiş saatini otomatik ayarla
        function setupTimeInputListeners() {
            document.querySelectorAll('.start-time-input').forEach(input => {
                if (!input.hasAttribute('data-has-listener')) {
                    input.setAttribute('data-has-listener', 'true');
                    input.addEventListener('change', function() {
                        const endTimeInput = this.closest('.lesson-day-row').querySelector('.end-time-input');
                        if (this.value) {
                            // Başlangıç saatini ayrıştır
                            const [hours, minutes] = this.value.split(':').map(Number);
                            
                            // Bir saat sonrasını hesapla
                            let endHours = hours + 1;
                            if (endHours >= 24) {
                                endHours = 23;
                                endTimeInput.value = `${String(endHours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
                            } else {
                                endTimeInput.value = `${String(endHours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
                            }
                        } else {
                            endTimeInput.value = '';
                        }
                    });
                }
            });
        }
        
        // İlk satır için dinleyiciyi ekle
        setupTimeInputListeners();
        
        // Yeni ders günü ekle
        addLessonDayBtn.addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className = 'lesson-day-row bg-white p-3 rounded border border-gray-200 mb-2';
            newRow.innerHTML = `
                <div class="grid grid-cols-3 gap-4 items-center">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Gün <span class="text-red-500">*</span>
                        </label>
                        <select name="days[]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="1">Pazartesi</option>
                            <option value="2">Salı</option>
                            <option value="3">Çarşamba</option>
                            <option value="4">Perşembe</option>
                            <option value="5">Cuma</option>
                            <option value="6">Cumartesi</option>
                            <option value="0">Pazar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Başlangıç Saati <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="start_times[]" class="start-time-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Bitiş Saati <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="end_times[]" class="end-time-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" readonly>
                    </div>
                </div>
                <div class="mt-2 text-right">
                    <button type="button" class="remove-row text-sm text-red-600 hover:text-red-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Kaldır
                    </button>
                </div>
            `;
            
            lessonDaysContainer.appendChild(newRow);
            
            // Yeni eklenen satır için dinleyiciyi ekle
            setupTimeInputListeners();
            
            // Satır silme düğmesi için dinleyici ekle
            newRow.querySelector('.remove-row').addEventListener('click', function() {
                newRow.remove();
            });
        });
        
        // Tarih kontrolü
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        
        function validateDates() {
            if (startDateInput.value && endDateInput.value) {
                if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
                    endDateInput.setCustomValidity('Bitiş tarihi başlangıç tarihinden önce olamaz');
                } else {
                    endDateInput.setCustomValidity('');
                }
            }
        }
        
        startDateInput.addEventListener('change', validateDates);
        endDateInput.addEventListener('change', validateDates);
    });
</script>
@endsection