@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Ana Başlık -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
              {{ request('show_all') ? 'Tüm Özel Derslerim' : 'Aktif Özel Derslerim' }}
            </h1>
          
            <div class="flex space-x-3">
              <!-- Öğrenciler Butonu -->
              <button
                onclick="scrollToStudents()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow
                       hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Öğrencilerim
              </button>
              
              <!-- Toggle Aktif/Pasif -->
              <a
                href="{{ request('show_all')
                          ? route('ogretmen.private-lessons.index')
                          : route('ogretmen.private-lessons.index', ['show_all' => 1]) }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg shadow
                       hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
              >
                @if(request('show_all'))
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                       viewBox="0 0 24 24" stroke="currentColor">
                    <!-- eye-off icon -->
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10
                             0-1.04.159-2.042.459-3.005M3 3l18 18"/>
                  </svg>
                  Sadece Aktifleri Göster
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                       viewBox="0 0 24 24" stroke="currentColor">
                    <!-- eye icon -->
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943
                             9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z"/>
                  </svg>
                  İptal Edilenleri de Göster
                @endif
              </a>
          
              <!-- Verdiğim Ödevler -->
              <a
                href="{{ route('ogretmen.private-lessons.homeworks') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-indigo-600
                       text-indigo-600 rounded-lg shadow hover:bg-indigo-50 focus:outline-none
                       focus:ring-2 focus:ring-indigo-500 transition"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5l7 7-7 7" />
                </svg>
                Verdiğim Ödevler
              </a>
            </div>
          </div>
          
          
        @livewire('private-lesson-calendar')

        <!-- Özel Derslerim -->
        <div id="lessons-section" class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Özel Derslerim</h1>
            
            @livewire('private-lessons-list')
        </div>
    </div>
    
    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 flex flex-col items-end"></div>
    
    <!-- Modal for lesson deactivation confirmation -->
    <div id="toggleModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4" id="modalTitle"></h3>
            <p class="text-gray-600 mb-6" id="modalMessage"></p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    İptal
                </button>
                <button id="confirmButton" onclick="confirmToggle()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Onaylıyorum
                </button>
            </div>
        </div>
    </div>
    

    <script>
        let currentLessonId = null;
        let currentForm = null;
        
        function scrollToStudents() {
            const lessonsSection = document.getElementById('lessons-section');
            if (lessonsSection) {
                lessonsSection.scrollIntoView({ behavior: 'smooth' });
                
                // Aynı zamanda öğrenci filtresi açılsın (eğer Livewire component varsa)
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('open-student-filter');
                }
            }
        }
        
        function toggleLessonActive(lessonId, action) {
            currentLessonId = lessonId;
            currentForm = document.getElementById('toggle-form-' + lessonId);
            
            document.getElementById('modalTitle').textContent = 'Dersi ' + action + ' Yap';
            
            if (action === 'Pasif') {
                document.getElementById('modalMessage').textContent = 
                    'Bu işlem dersi pasif duruma getirecek ve öğrencinin dersi görüntülemesini engelleyecektir. ' +
                    'Bu işlem oluşturulmuş seansları iptal etmez. İlerleyen dersleri iptal etmek için takvimi kullanınız.';
                document.getElementById('confirmButton').className = 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700';
            } else {
                document.getElementById('modalMessage').textContent = 
                    'Bu işlem dersi aktif duruma getirecek ve öğrencinin tekrar görüntülemesini sağlayacaktır.';
                document.getElementById('confirmButton').className = 'px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700';
            }
            
            document.getElementById('toggleModal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('toggleModal').classList.add('hidden');
            currentLessonId = null;
            currentForm = null;
        }
        
        function confirmToggle() {
            if (currentForm) {
                currentForm.submit();
            }
            closeModal();
        }
    
        document.addEventListener('livewire:initialized', function () {
            Livewire.on('lessonCompleted', message => {
                showNotification(message || 'Ders başarıyla tamamlandı!', 'success');
            });
    
            Livewire.on('lessonError', message => {
                showNotification(message || 'İşlem sırasında bir hata oluştu.', 'error');
            });
        });
        
        function showNotification(message, type = 'success') {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = 'flex items-center p-4 mb-3 rounded-lg shadow-lg transform transition-all duration-300 opacity-0 translate-x-full max-w-md';

            notification.classList.add(type === 'success' ? 'bg-green-600' : 'bg-red-600', 'text-white');
            notification.innerHTML = `
                <div class="flex-shrink-0 mr-3">
                    ${type === 'success' ? 
                        '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' :
                        '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'}
                </div>
                <div class="flex-1">
                    <p class="font-medium">${message}</p>
                </div>
                <div class="flex-shrink-0 ml-3">
                    <button class="text-white" onclick="this.parentElement.parentElement.remove()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                    </button>
                </div>
            `;

            container.appendChild(notification);

            setTimeout(() => notification.classList.replace('opacity-0', 'opacity-100'), 10);
            setTimeout(() => notification.classList.add('translate-x-0'), 50);
            setTimeout(() => notification.remove(), 5000);
        }
    
        // Bu fonksiyon MUTLAKA EN ALTTA VE GLOBAL OLMALI
        window.checkModalAndRedirect = function(lessonId) {
            setTimeout(() => {
                const modal = document.querySelector('.fixed.inset-0.bg-black');
                if (!modal) {
                    console.error('Modal açılamadı, lessonId:', lessonId);
                    if (typeof Livewire !== 'undefined') {
                        Livewire.dispatch('lessonError', 'Ders detayları yüklenemedi. Lütfen tekrar deneyin veya yöneticiye bilgi verin.');
                    } else {
                        showNotification('Ders detayları yüklenemedi. Lütfen tekrar deneyin veya yöneticiye bilgi verin.', 'error');
                    }
                }
            }, 1000);
        };
    </script>

@endsection