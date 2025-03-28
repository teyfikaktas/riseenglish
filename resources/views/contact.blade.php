<!-- resources/views/contact.blade.php -->
@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] py-12">
    <div class="container mx-auto px-6">
        <h1 class="text-3xl md:text-4xl font-bold text-white text-center mb-6">Bize Ulaşın</h1>
        <div class="w-20 h-1 bg-[#e63946] mx-auto mb-8"></div>
        <p class="text-xl text-center text-white max-w-3xl mx-auto">
            Herhangi bir sorunuz veya öneriniz mi var? Bizimle iletişime geçin, size en kısa sürede yanıt vereceğiz.
        </p>
    </div>
</div>

<div class="container mx-auto px-4 py-16">
    <div class="flex flex-col lg:flex-row gap-10">
        <!-- Sol Taraf - İletişim Bilgileri -->
        <div class="w-full lg:w-1/3">
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-[#1a2e5a] mb-6">İletişim Bilgilerimiz</h2>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="bg-[#1a2e5a] rounded-full p-3 text-white mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-[#1a2e5a] text-lg mb-1">Adres</h3>
                            <p class="text-gray-700">
                                Hacı Mütahir Mah. Rasim Erel Cad.<br>
                                Şehit Kamil Okulu Yanı<br>
                                Ereğli İş Merkezi Kat 2<br>
                                Ereğli/Konya
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-[#1a2e5a] rounded-full p-3 text-white mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-[#1a2e5a] text-lg mb-1">Telefon</h3>
                            <p class="text-gray-700">0545 762 44 98</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-[#1a2e5a] rounded-full p-3 text-white mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-[#1a2e5a] text-lg mb-1">E-posta</h3>
                            <p class="text-gray-700">info@riseenglish.com</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-[#1a2e5a] rounded-full p-3 text-white mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-[#1a2e5a] text-lg mb-1">Çalışma Saatleri</h3>
                            <p class="text-gray-700">
                                Pazartesi - Cuma: 09:00 - 18:00<br>
                                Cumartesi: 09:00 - 13:00<br>
                                Pazar: Kapalı
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sosyal Medya Linkleri -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-[#1a2e5a] mb-6">Sosyal Medya</h2>
                <div class="flex space-x-4">
                    <a href="#" class="bg-[#1a2e5a] hover:bg-[#e63946] text-white p-3 rounded-full transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="#" class="bg-[#1a2e5a] hover:bg-[#e63946] text-white p-3 rounded-full transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                        </svg>
                    </a>
                    <a href="#" class="bg-[#1a2e5a] hover:bg-[#e63946] text-white p-3 rounded-full transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                        </svg>
                    </a>
                    <a href="#" class="bg-[#1a2e5a] hover:bg-[#e63946] text-white p-3 rounded-full transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="bg-[#1a2e5a] hover:bg-[#e63946] text-white p-3 rounded-full transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Sağ Taraf - İletişim Formu ve Harita -->
        <div class="w-full lg:w-2/3">
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-[#1a2e5a] mb-6">Bize Mesaj Gönderin</h2>
                
                <!-- İletişim Formu -->
                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    
                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-gray-700 font-medium mb-2">Adınız Soyadınız *</label>
                            <input type="text" name="name" id="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#1a2e5a] focus:border-[#1a2e5a]" required>
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">E-posta Adresiniz *</label>
                            <input type="email" name="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#1a2e5a] focus:border-[#1a2e5a]" required>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="phone" class="block text-gray-700 font-medium mb-2">Telefon Numaranız</label>
                        <input type="tel" name="phone" id="phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#1a2e5a] focus:border-[#1a2e5a]">
                    </div>
                    
                    <div class="mb-6">
                        <label for="subject" class="block text-gray-700 font-medium mb-2">Konu *</label>
                        <input type="text" name="subject" id="subject" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#1a2e5a] focus:border-[#1a2e5a]" required>
                    </div>
                    
                    <div class="mb-6">
                        <label for="message" class="block text-gray-700 font-medium mb-2">Mesajınız *</label>
                        <textarea name="message" id="message" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-[#1a2e5a] focus:border-[#1a2e5a]" required></textarea>
                    </div>
                    
                    <button type="submit" class="bg-[#e63946] hover:bg-[#d32836] text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                        <i class="fas fa-paper-plane mr-2"></i>Mesajı Gönder
                    </button>
                </form>
            </div>
            
            <!-- Google Harita -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-[#1a2e5a] mb-6">Konum</h2>
                <div class="rounded-lg overflow-hidden h-96 border border-gray-200">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3177.7929391006295!2d34.04579791489651!3d37.51124197980736!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14d797043e6b2edd%3A0x91e98e696e46f2a7!2zSGFjxLEgTcO8dGFoaXIsIEVyZcSfbGkvS29ueWE!5e0!3m2!1str!2str!4v1679307526736!5m2!1str!2str" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SSS Bölümü -->
<div class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-[#1a2e5a] mb-8">Sıkça Sorulan Sorular</h2>
        <div class="w-20 h-1 bg-[#e63946] mx-auto mb-12"></div>
        
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Sık Sorulan Soru 1 -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <button class="faq-toggle w-full flex justify-between items-center p-5 text-left focus:outline-none">
                    <span class="text-lg font-semibold text-[#1a2e5a]">Kurslara nasıl kayıt olabilirim?</span>
                    <svg class="h-6 w-6 text-[#1a2e5a] transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="faq-content hidden px-5 pb-5">
                    <p class="text-gray-700">Kurslara kayıt olmak için websitemizdeki "Kurslar" sayfasından ilgilendiğiniz kursu seçebilir ve "Hemen Başla" butonuna tıklayabilirsiniz. Alternatif olarak ofisimizi ziyaret edebilir veya telefon numaramızdan bize ulaşabilirsiniz.</p>
                </div>
            </div>
            
            <!-- Sık Sorulan Soru 2 -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <button class="faq-toggle w-full flex justify-between items-center p-5 text-left focus:outline-none">
                    <span class="text-lg font-semibold text-[#1a2e5a]">Eğitimlerin süresi ne kadardır?</span>
                    <svg class="h-6 w-6 text-[#1a2e5a] transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="faq-content hidden px-5 pb-5">
                    <p class="text-gray-700">Eğitimlerimizin süresi kursun içeriğine ve seviyesine göre değişmektedir. Genel İngilizce kurslarımız genellikle 8-12 hafta sürerken, özel amaçlı dil kursları 4-8 hafta arası sürebilmektedir. Her kursun detaylı süresi ilgili kurs sayfasında belirtilmiştir.</p>
                </div>
            </div>
            
            <!-- Sık Sorulan Soru 3 -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <button class="faq-toggle w-full flex justify-between items-center p-5 text-left focus:outline-none">
                    <span class="text-lg font-semibold text-[#1a2e5a]">Ödeme seçenekleri nelerdir?</span>
                    <svg class="h-6 w-6 text-[#1a2e5a] transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="faq-content hidden px-5 pb-5">
                    <p class="text-gray-700">Kredi kartı, banka havalesi ve nakit ödeme kabul ediyoruz. Ayrıca belirli kurslarda taksit imkanlarımız da bulunmaktadır. Ödeme detayları için lütfen bizimle iletişime geçiniz.</p>
                </div>
            </div>
            
            <!-- Sık Sorulan Soru 4 -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <button class="faq-toggle w-full flex justify-between items-center p-5 text-left focus:outline-none">
                    <span class="text-lg font-semibold text-[#1a2e5a]">Eğitimler online mı yoksa yüz yüze mi?</span>
                    <svg class="h-6 w-6 text-[#1a2e5a] transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="faq-content hidden px-5 pb-5">
                    <p class="text-gray-700">Rise English olarak hem yüz yüze hem de online eğitim seçenekleri sunuyoruz. İhtiyaçlarınıza ve durumunuza en uygun formatı seçebilirsiniz. Bazı kurslarımız karma eğitim modeliyle hem online hem de yüz yüze içerik sunmaktadır.</p>
                </div>
            </div>
            
            <!-- Sık Sorulan Soru 5 -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <button class="faq-toggle w-full flex justify-between items-center p-5 text-left focus:outline-none">
                    <span class="text-lg font-semibold text-[#1a2e5a]">Kurs sonunda sertifika alabilir miyim?</span>
                    <svg class="h-6 w-6 text-[#1a2e5a] transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="faq-content hidden px-5 pb-5">
                    <p class="text-gray-700">Evet, tüm kurslarımızı başarıyla tamamlayan öğrencilerimize sertifika veriyoruz. Sertifikalarımız, katıldığınız eğitimin adını, süresini ve seviyesini belirtir ve profesyonel gelişiminiz için referans olarak kullanılabilir.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // SSS Bölümü için JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const faqToggles = document.querySelectorAll('.faq-toggle');
        
        faqToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const icon = this.querySelector('svg');
                
                // Toggle content visibility
                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                } else {
                    content.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            });
        });
    });
</script>
@endsection