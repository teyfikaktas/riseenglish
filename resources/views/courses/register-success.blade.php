<!-- resources/views/courses/register-success.blade.php -->
@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-8">
                <div class="text-center mb-8">
                    <!-- Başarı İkonu -->
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-600 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-[#1a2e5a] mb-2">Kayıt İşleminiz Başarıyla Tamamlandı!</h1>
                    <p class="text-gray-600">Rise English ailesine hoş geldiniz.</p>
                </div>
                
                <!-- Bilgilendirme Metni -->
                <div class="bg-blue-50 p-6 rounded-lg mb-8 text-blue-800">
                    <p class="mb-4">
                        Kurs kaydınız başarılı bir şekilde oluşturulmuştur. Kayıt bilgileriniz ve kurs erişim detayları e-posta adresinize gönderilmiştir.
                    </p>
                    <p>
                        Lütfen e-posta kutunuzu ve spam klasörünüzü kontrol ediniz. Herhangi bir sorunuz olursa bizimle iletişime geçebilirsiniz.
                    </p>
                </div>
                
                <!-- Kayıt Bilgileri Özeti -->
                <div class="border border-gray-200 rounded-lg p-6 mb-8">
                    <h2 class="text-xl font-semibold text-[#1a2e5a] mb-4">Kayıt Bilgileriniz</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Kurs</h3>
                            <p class="font-medium">{{ $course->name ?? 'İngilizce Konuşma Kursu' }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Başlangıç Tarihi</h3>
                            <p class="font-medium">{{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') : '15.04.2023' }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Eğitmen</h3>
                            <p class="font-medium">{{ $course->teacher->name ?? 'John Smith' }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Ödenen Tutar</h3>
                            <p class="font-medium">{{ $course->discount_price ? number_format($course->discount_price, 2) : '1.299,99' }} ₺</p>
                        </div>
                    </div>
                </div>
                
                <!-- Yapılacaklar -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-[#1a2e5a] mb-4">Sıradaki Adımlar</h2>
                    
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-[#1a2e5a] text-white flex items-center justify-center mr-3 mt-0.5">
                                1
                            </div>
                            <div>
                                <h3 class="font-medium">E-postanızı Kontrol Edin</h3>
                                <p class="text-gray-600 text-sm">Size gönderilen e-postadaki kurs erişim bilgilerini gözden geçirin.</p>
                            </div>
                        </li>
                        
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-[#1a2e5a] text-white flex items-center justify-center mr-3 mt-0.5">
                                2
                            </div>
                            <div>
                                <h3 class="font-medium">Profilinizi Tamamlayın</h3>
                                <p class="text-gray-600 text-sm">Profil bilgilerinizi güncelleyerek kurs deneyiminizi kişiselleştirin.</p>
                            </div>
                        </li>
                        
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-[#1a2e5a] text-white flex items-center justify-center mr-3 mt-0.5">
                                3
                            </div>
                            <div>
                                <h3 class="font-medium">Kurs Başlangıcına Hazırlanın</h3>
                                <p class="text-gray-600 text-sm">İlk dersten önce gerekli hazırlıkları yapın ve platformumuzu keşfedin.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                
                <!-- Action Butonları -->
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('home') }}" class="bg-[#1a2e5a] text-white font-medium py-3 px-6 rounded-lg hover:bg-[#15243f] transition-colors text-center">
                        Hesabıma Git
                    </a>
                    <a href="{{ route('courses.index') }}" class="border border-[#1a2e5a] text-[#1a2e5a] font-medium py-3 px-6 rounded-lg hover:bg-gray-50 transition-colors text-center">
                        Diğer Kursları Keşfet
                    </a>
                </div>
            </div>
        </div>
        
        <!-- İletişim Bilgisi -->
        <div class="max-w-2xl mx-auto mt-8 text-center text-gray-500 text-sm">
            <p>Herhangi bir sorunuz olursa, lütfen bizimle iletişime geçin:</p>
            <div class="mt-2">
                <a href="mailto:info@riseenglish.com" class="text-[#1a2e5a] hover:underline">info@riseenglish.com</a> | 
                <a href="tel:+902121234567" class="text-[#1a2e5a] hover:underline">+90 212 123 45 67</a>
            </div>
        </div>
    </div>
</div>
@endsection