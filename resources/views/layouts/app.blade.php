<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Favicons -->
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <title>{{ config('app.name', 'RiseEnlish') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@700;800;900&family=Playfair+Display:ital@0;1&family=Poppins:wght@600;800;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>
<body class="font-sans bg-gray-50 min-h-screen">
    <!-- Kullanıcı rolüne göre topbar gösterimi -->
    @auth
        @if(auth()->user()->hasRole('yonetici'))
            @include('layouts.admin_topbar')
        @else
            @include('layouts.topbar')
        @endif
    @else
        @include('layouts.topbar')
    @endauth

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <p class="text-center">&copy; {{ date('Y') }} Rise English. Tüm hakları saklıdır.</p>
        </div>
    </footer>

    <div id="chat-widget" class="fixed right-6 bottom-6 z-50">
        <!-- Kapalı durumdaki buton -->
        @guest
        <button id="chat-toggle" class="flex items-center justify-center w-16 h-16 bg-green-500 rounded-full shadow-lg hover:bg-green-600 transition-all duration-300">
            <i class="fab fa-whatsapp text-white text-3xl"></i>
        </button>
    @endguest
      
        <!-- Açık durumdaki sohbet kutusu -->
        <div id="chat-box" class="hidden bg-white rounded-lg shadow-xl w-80 mb-4 overflow-hidden">
          <!-- Kategori Başlıkları ve Öğretmen Seçimi -->
          <div class="flex">
            <button id="tab-hakan" class="flex-1 px-4 py-2 bg-green-500 text-white font-medium">
              <div class="text-sm font-bold">İngilizce Kursları</div>
              <div class="text-xs">Hakan Hoca</div>
            </button>
            <button id="tab-rumeysa" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 font-medium">
              <div class="text-sm font-bold">Eğitim Danışmanı & Öğrenci Koçu</div>
              <div class="text-xs">Rümeysa Hoca</div>
            </button>
          </div>
      
          <!-- Başlık -->
          <div id="chat-header" class="bg-green-500 px-4 py-3 flex items-center justify-between">
            <div class="flex items-center">
              <div class="w-10 h-10 rounded-full bg-white overflow-hidden flex items-center justify-center mr-3">
                <!-- İlgili öğretmen resmi eklenebilir -->
                <!--<img id="teacher-img" src="images/hakan-hoca.jpg" alt="Öğretmen" class="w-full h-full object-cover" onerror="this.src='images/default-avatar.png'">-->
              </div>
              <div class="text-white">
                <p id="teacher-name" class="font-medium">Hakan Hoca</p>
                <p id="teacher-role" class="text-xs">İngilizce Kursları</p>
              </div>
            </div>
            <button id="close-chat" class="text-white hover:text-gray-200">
              <i class="fas fa-times"></i>
            </button>
          </div>
      
          <!-- Mesaj içeriği -->
          <div class="p-4 bg-gray-50 max-h-80 overflow-y-auto">
            <div class="flex justify-center mb-4">
              <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded-full">Bugün</span>
            </div>
            
            <!-- Karşı taraftan gelen mesaj -->
            <div class="flex mb-4">
              <div class="bg-white rounded-lg p-3 shadow-sm max-w-xs">
                <p class="text-gray-700">Merhaba, size nasıl yardımcı olabilirim?</p>
                <p class="text-xs text-gray-500 text-right mt-1">{{ date('H:i') }}</p>
              </div>
            </div>
          </div>
      
          <!-- Mesaj gönderme alanı -->
          <div class="p-3 bg-white border-t">
            <form id="message-form" class="flex items-center">
              <input type="text" id="message-input" placeholder="Mesajınızı yazın..." class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
              <button type="submit" class="ml-2 w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white">
                <i class="fas fa-paper-plane"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
      
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          const chatToggle = document.getElementById('chat-toggle');
          const chatBox = document.getElementById('chat-box');
          const closeChat = document.getElementById('close-chat');
          const messageForm = document.getElementById('message-form');
          const messageInput = document.getElementById('message-input');
          
          // Sekme butonları
          const tabHakan = document.getElementById('tab-hakan');
          const tabRumeysa = document.getElementById('tab-rumeysa');
          const teacherName = document.getElementById('teacher-name');
          const teacherRole = document.getElementById('teacher-role');
          // Eğer resim değiştirmek isterseniz:
          // const teacherImg = document.getElementById('teacher-img');
      
          // Varsayılan öğretmen: Hakan Hoca
          let currentTeacher = 'hakan'; // "hakan" veya "rumeysa"
      
          function activateTab(teacher) {
            if(teacher === 'hakan'){
              currentTeacher = 'hakan';
              teacherName.textContent = 'Hakan Hoca';
              teacherRole.textContent = 'İngilizce Kursları';
              tabHakan.classList.replace('bg-gray-200', 'bg-green-500');
              tabHakan.classList.replace('text-gray-700', 'text-white');
              tabRumeysa.classList.replace('bg-green-500', 'bg-gray-200');
              tabRumeysa.classList.replace('text-white', 'text-gray-700');
              // teacherImg.src = 'images/hakan-hoca.jpg';
            } else if(teacher === 'rumeysa'){
              currentTeacher = 'rumeysa';
              teacherName.textContent = 'Rümeysa Hoca';
              teacherRole.textContent = 'Eğitim Danışmanı & Öğrenci Koçu';
              tabRumeysa.classList.replace('bg-gray-200', 'bg-green-500');
              tabRumeysa.classList.replace('text-gray-700', 'text-white');
              tabHakan.classList.replace('bg-green-500', 'bg-gray-200');
              tabHakan.classList.replace('text-white', 'text-gray-700');
              // teacherImg.src = 'images/rumeysa-hoca.jpg';
            }
          }
          
          // Sekme tıklamalarını dinle
          tabHakan.addEventListener('click', function() {
            activateTab('hakan');
          });
          
          tabRumeysa.addEventListener('click', function() {
            activateTab('rumeysa');
          });
          
          // Sohbeti aç/kapat
          chatToggle.addEventListener('click', function() {
            chatBox.classList.toggle('hidden');
          });
          
          // Sohbeti kapat
          closeChat.addEventListener('click', function() {
            chatBox.classList.add('hidden');
          });
          
          // Mesaj gönderme
          messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Mesaj metnini sabit olarak seçime göre belirle
            let messageText = '';
            if(currentTeacher === 'hakan'){
              messageText = "İngilizce Kursları hakkında bilgi almak istiyorum!";
            } else if(currentTeacher === 'rumeysa'){
              messageText = "rehberlik hakkında bilgi almak istiyorum!";
            }
            
            // Mesaj gönderildikten sonra WhatsApp’a yönlendir
            const phoneNumber = '905457624498';
            const encodedMessage = encodeURIComponent(messageText);
            const whatsappURL = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;
            window.open(whatsappURL, '_blank');
            
            // Input'u temizle (isteğe bağlı, sabit mesaj gönderildiği için input değeri kullanılmayabilir)
            messageInput.value = '';
          });
        });
      </script>
          @livewireScripts

</body>
</html>