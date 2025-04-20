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

        <!-- Bilgilendirme Mesajları -->
        @if(strtotime($session->start_date . ' ' . $session->start_time) < strtotime('now'))
        <div class="bg-blue-50 rounded-lg shadow-md border border-blue-200 mb-6">
            <div class="px-6 py-4">
                <h1 class="text-lg font-bold text-blue-800">Bilgi</h1>
                <p class="text-blue-600 text-sm mt-1">Bu ders geçmiş tarihli olduğu için sadece durum ve ödeme bilgilerini güncelleyebilirsiniz. Tarih ve saat değişiklikleri yapılamaz.</p>
            </div>
        </div>
        @endif

        <!-- Hata Mesajları Özeti -->
        @if ($errors->any())
        <div class="bg-red-50 rounded-lg shadow-md border border-red-200 mb-6">
            <div class="px-6 py-4">
                <h1 class="text-xl font-bold text-red-800">Validasyon Hataları</h1>
                <p class="text-red-600 text-sm mt-1">Aşağıdaki hatalar form gönderiminde tespit edildi:</p>
                <div class="mt-3 p-3 bg-red-100 rounded">
                    <ul class="list-disc pl-5 text-red-800">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Genel Hata Mesajı -->
        @if(session('error'))
        <div class="bg-red-50 rounded-lg shadow-md border border-red-200 mb-6">
            <div class="px-6 py-4">
                <h1 class="text-xl font-bold text-red-800">Hata Oluştu</h1>
                <p class="text-red-600 text-sm mt-1">Özel ders güncellenirken bir hata meydana geldi:</p>
                <div class="mt-3 p-3 bg-red-100 rounded">
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Tekrarlı Dersler İçin Uyarı -->
        @if($session->privateLesson->has_recurring_sessions && !$isPastSession)
        <div class="bg-yellow-50 rounded-lg shadow-md border border-yellow-200 mb-6" id="recurring-warning">
            <div class="px-6 py-4">
                <h1 class="text-xl font-bold text-yellow-800">Tekrarlanan Ders</h1>
                <p class="text-yellow-600 text-sm mt-1">Bu ders, tekrarlanan bir dersin parçasıdır. Yapacağınız değişiklikleri nasıl uygulamak istersiniz?</p>
                <div class="mt-4 flex space-x-4">
                    <button type="button" id="edit-single" class="bg-white border border-yellow-300 text-yellow-700 px-4 py-2 rounded-md hover:bg-yellow-50">
                        Sadece Bu Dersi Düzenle
                    </button>
                    <button type="button" id="edit-all" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                        Bugünden Sonraki Tüm Dersleri Düzenle
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Başlığı -->
        <div class="bg-white rounded-t-lg shadow-md border-b">
            <div class="px-6 py-4">
                <h1 class="text-xl font-bold text-gray-800">Özel Ders Düzenle</h1>
                <p class="text-gray-600 text-sm mt-1">
                    "{{ $session->privateLesson->name }}" dersinin {{ date('d.m.Y', strtotime($session->start_date)) }} tarihli ve {{ date('H:i', strtotime($session->start_time)) }} saatli seansını düzenliyorsunuz.
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-b-lg shadow-md">
            <form action="{{ route('ogretmen.private-lessons.update', $session->id) }}" method="POST" class="p-6 space-y-6" id="edit-form">
                @csrf
                @method('PUT')
                
                <!-- Çakışma Uyarısı (Kontrolör tarafında tespit edilirse gösterilir) -->
                @if(session('conflict_detected'))
                <div class="bg-red-50 rounded-lg shadow-md border border-red-200 mb-6">
                    <div class="px-6 py-4">
                        <h1 class="text-xl font-bold text-red-800">Ders Çakışması</h1>
                        <p class="text-red-600 text-sm mt-1">Seçtiğiniz gün ve saatte başka bir özel dersiniz bulunmaktadır. Yine de devam etmek istiyorsanız "Güncelle" butonuna tekrar basın.</p>
                    </div>
                </div>
                <input type="hidden" name="conflict_confirmed" value="1">
                @endif
                
                <!-- Tüm dersleri düzenlemek için gizli alan -->
                <input type="hidden" name="update_all_sessions" id="update_all_sessions_input" value="0">
                
                <!-- Ders Bilgileri -->
                <div class="border-b pb-6">
                    <h2 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-700 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">1</span>
                        Ders Bilgileri
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Öğrenci Seçimi -->
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Öğrenci <span class="text-red-500">*</span>
                            </label>
                            <select id="student_id" name="student_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Seçiniz</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ $session->student_id == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Ücret -->
                        <div>
                            <label for="fee" class="block text-sm font-medium text-gray-700 mb-1">
                                Ders Ücreti (₺) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="fee" name="fee" step="0.01" min="0" value="{{ $session->fee }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="0.00" required>
                            @error('fee')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Ödeme Bilgileri -->
                <div class="border-b pb-6">
                    <h2 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-700 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">2</span>
                        Ödeme Bilgileri
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Ödeme Durumu (Basitleştirilmiş) -->
                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">
                                Ödeme Durumu <span class="text-red-500">*</span>
                            </label>
                            <select id="payment_status" name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="pending" {{ $session->payment_status == 'pending' ? 'selected' : '' }}>Ödeme Alınmadı</option>
                                <option value="paid" {{ $session->payment_status == 'paid' ? 'selected' : '' }}>Ödeme Alındı</option>
                            </select>
                            @error('payment_status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konum -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                                Konum
                            </label>
                            <input type="text" id="location" name="location" value="{{ $session->location }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Örn: Sınıf A, Online, vb.">
                            @error('location')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Ders Zamanlaması (Geçmiş dersler için düzenlenemez) -->
                <div class="border-b pb-6">
                    <h2 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-700 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">3</span>
                        Ders Zamanlaması
                        @if($isPastSession)
                        <span class="ml-2 text-xs text-red-600 font-normal">(Geçmiş ders - değiştirilemez)</span>
                        @endif
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Haftanın Günü -->
                        <div>
                            <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-1">
                                Haftanın Günü <span class="text-red-500">*</span>
                            </label>
                            <select id="day_of_week" name="day_of_week" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required {{ $isPastSession ? 'disabled' : '' }}>
                                <option value="1" {{ $session->day_of_week == 1 ? 'selected' : '' }}>Pazartesi</option>
                                <option value="2" {{ $session->day_of_week == 2 ? 'selected' : '' }}>Salı</option>
                                <option value="3" {{ $session->day_of_week == 3 ? 'selected' : '' }}>Çarşamba</option>
                                <option value="4" {{ $session->day_of_week == 4 ? 'selected' : '' }}>Perşembe</option>
                                <option value="5" {{ $session->day_of_week == 5 ? 'selected' : '' }}>Cuma</option>
                                <option value="6" {{ $session->day_of_week == 6 ? 'selected' : '' }}>Cumartesi</option>
                                <option value="0" {{ $session->day_of_week == 0 ? 'selected' : '' }}>Pazar</option>
                            </select>
                            @error('day_of_week')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Tarih -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Ders Tarihi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="start_date" name="start_date" value="{{ date('Y-m-d', strtotime($session->start_date)) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required {{ $isPastSession ? 'disabled' : '' }}>
                            @error('start_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Başlangıç ve Bitiş Saatleri -->
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                                Başlangıç Saati <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="start_time" name="start_time" value="{{ $session->start_time }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required {{ $isPastSession ? 'disabled' : '' }}>
                            @error('start_time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Bitiş Saati (Görünür Input) -->
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                                Bitiş Saati <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="end_time" name="end_time" value="{{ $session->end_time }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required {{ $isPastSession ? 'disabled' : '' }}>
                            @error('end_time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Ek Bilgiler -->
                <div>
                    <h2 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-700 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">4</span>
                        Ders Durumu
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Durum -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Durum <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="approved" {{ $session->status == 'approved' ? 'selected' : '' }}>Aktif</option>
                                <option value="cancelled" {{ $session->status == 'cancelled' ? 'selected' : '' }}>İptal Edildi</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <div class="mt-2">
                                <button type="button" id="cancel-lesson-btn" class="inline-flex items-center text-sm text-red-600 hover:text-red-800 {{ $session->status == 'cancelled' ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $session->status == 'cancelled' ? 'disabled' : '' }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Dersi İptal Et
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notlar -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                            Notlar
                        </label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Ders ile ilgili ekstra notlar...">{{ $session->notes }}</textarea>
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
                        Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Geçmiş ders kontrolü için hidden alan ekle
        const isPastSession = {{ $isPastSession ? 'true' : 'false' }};
        
        // Tekrarlı dersler için butonlara tıklama olayları
        const editSingleButton = document.getElementById('edit-single');
        const editAllButton = document.getElementById('edit-all');
        const updateAllInput = document.getElementById('update_all_sessions_input');
        const recurringWarning = document.getElementById('recurring-warning');
        
        if (editSingleButton && editAllButton) {
            editSingleButton.addEventListener('click', function() {
                updateAllInput.value = "0";
                recurringWarning.style.display = 'none';
            });
            
            editAllButton.addEventListener('click', function() {
                updateAllInput.value = "1";
                recurringWarning.style.display = 'none';
            });
        }
        
        // Dersi iptal et butonu
        const cancelLessonBtn = document.getElementById('cancel-lesson-btn');
        const statusSelect = document.getElementById('status');
        
        if (cancelLessonBtn) {
            cancelLessonBtn.addEventListener('click', function() {
                if (confirm('Bu dersi iptal etmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')) {
                    statusSelect.value = 'cancelled';
                    // Sadece bu dersi güncellemek için
                    if (updateAllInput) {
                        updateAllInput.value = "0";
                    }
                    // Kaydetme formunu otomatik gönder
                    document.getElementById('edit-form').submit();
                }
            });
        }
        
        // Başlangıç saati değiştiğinde bitiş saatini otomatik ayarla
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        
        if (startTimeInput && endTimeInput && !isPastSession) {
            startTimeInput.addEventListener('change', function() {
                if (this.value && (!endTimeInput.value || confirm('Bitiş saatini başlangıç saatine göre güncellemek ister misiniz?'))) {
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
                }
            });
        }
        
        // Geçmiş derse tarih/saat değişikliği yapılabilir mi kontrol
        const dayOfWeekSelect = document.getElementById('day_of_week');
        const startDateInput = document.getElementById('start_date');
        const editForm = document.getElementById('edit-form');
        
        if (editForm && isPastSession) {
            editForm.addEventListener('submit', function(e) {
                // Geçmiş derse tarih/saat değişikliği yapılıyor mu kontrol et
                const originalDate = "{{ date('Y-m-d', strtotime($session->start_date)) }}";
                const originalTime = "{{ $session->start_time }}";
                const originalEndTime = "{{ $session->end_time }}";
                const originalDayOfWeek = "{{ $session->day_of_week }}";
                
                if (startDateInput.value !== originalDate || 
                    startTimeInput.value !== originalTime || 
                    endTimeInput.value !== originalEndTime ||
                    dayOfWeekSelect.value !== originalDayOfWeek) {
                    
                    e.preventDefault();
                    alert('Geçmiş tarihli derslerin tarih ve saat bilgileri değiştirilemez.');
                    
                    // Değerleri orijinallerine geri döndür
                    startDateInput.value = originalDate;
                    startTimeInput.value = originalTime;
                    endTimeInput.value = originalEndTime;
                    dayOfWeekSelect.value = originalDayOfWeek;
                    
                    return false;
                }
                
                return true;
            });
        }
        
        // Bitiş saati başlangıç saatinden önce olmasın
        if (endTimeInput && !isPastSession) {
            endTimeInput.addEventListener('change', function() {
                if (startTimeInput.value && this.value) {
                    const startTime = startTimeInput.value;
                    const endTime = this.value;
                    
                    if (endTime <= startTime) {
                        alert('Bitiş saati, başlangıç saatinden sonra olmalıdır.');
                        
                        // Başlangıç saatinden 1 saat sonrasını hesapla
                        const [hours, minutes] = startTime.split(':').map(Number);
                        let endHours = hours + 1;
                        if (endHours >= 24) {
                            endHours = 23;
                        }
                        this.value = `${String(endHours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
                    }
                }
            });
        }
    });
</script>
@endsection