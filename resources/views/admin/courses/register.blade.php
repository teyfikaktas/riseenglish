<!-- resources/views/courses/register.blade.php -->
@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <!-- Başarı ve hata mesajları -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sol: Kurs bilgisi özet -->
            <div class="md:w-1/3">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden sticky top-6">
                    <div class="h-48 relative">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        @endif
                        
                        @if($course->discount_price)
                            <div class="absolute top-2 right-2 bg-[#e63946] text-white px-3 py-1 rounded-full font-bold text-sm">
                                %{{ number_format((($course->price - $course->discount_price) / $course->price) * 100) }} İNDİRİM
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-[#1a2e5a] mb-3">{{ $course->name }}</h2>
                        
                        <!-- Kurs detayları özet -->
                        <div class="space-y-4 mb-4">
                            <div class="flex items-center text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>{{ $course->teacher->name ?? 'Eğitmen belirtilmemiş' }}</span>
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $course->total_hours ?? 'Belirtilmemiş' }} Saat</span>
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') : 'Belirtilmemiş' }}</span>
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>{{ $course->max_students ?? 'Sınırsız' }} Kişilik Kontenjan</span>
                            </div>
                            
                            @if($course->has_certificate)
                                <div class="flex items-center text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span>Sertifikalı Eğitim</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Fiyat bilgisi -->
                        <div class="border-t border-gray-200 pt-4 mb-4">
                            <div class="flex flex-col">
                                <span class="text-gray-500 text-sm">Eğitim Ücreti</span>
                                <div>
                                    @if($course->discount_price)
                                        <span class="text-gray-500 line-through text-base">{{ number_format($course->price, 2) }} ₺</span>
                                        <span class="text-[#e63946] font-bold text-xl ml-2">{{ number_format($course->discount_price, 2) }} ₺</span>
                                    @else
                                        <span class="text-[#1a2e5a] font-bold text-xl">{{ number_format($course->price, 2) }} ₺</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Açıklama ve detaylar linki -->
                        <div class="text-center mt-4">
                            <a href="{{ url('/egitimler/' . $course->slug) }}" class="text-[#1a2e5a] hover:underline font-medium">
                                Kurs Detaylarına Dön
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sağ: Kayıt formu -->
            <div class="md:w-2/3">
                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                    <h1 class="text-2xl font-bold text-[#1a2e5a] mb-6">Kurs Kaydı</h1>
                    
                    <!-- Üst bilgilendirme alanı -->
                    <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-200">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-blue-800 font-medium mb-1">Kayıt İşlemi Hakkında</h3>
                                <p class="text-blue-700 text-sm">
                                    Aşağıdaki formu doldurarak kursa kayıt olabilirsiniz. Ödeme işleminden sonra kaydınız tamamlanacak ve kurs erişim bilgileri e-posta adresinize gönderilecektir.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kayıt formu -->
                    <form method="POST" action="{{ route('course.register.submit', $course->id) }}" class="space-y-6">
                        @csrf
                        
                        <!-- Kişisel bilgiler -->
                        <div>
                            <h2 class="text-lg font-semibold mb-4">Kişisel Bilgiler</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Ad Soyad -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Ad Soyad *</label>
                                    <input type="text" name="name" id="name" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1a2e5a] focus:border-[#1a2e5a]" value="{{ auth()->user()->name ?? old('name') }}" required>
                                </div>
                                
                                <!-- E-posta -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-posta Adresi *</label>
                                    <input type="email" name="email" id="email" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1a2e5a] focus:border-[#1a2e5a]" value="{{ auth()->user()->email ?? old('email') }}" required>
                                </div>
                                
                                <!-- Telefon -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefon Numarası *</label>
                                    <input type="tel" name="phone" id="phone" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1a2e5a] focus:border-[#1a2e5a]" value="{{ auth()->user()->phone ?? old('phone') }}" required>
                                </div>
                                
                                <!-- Doğum Tarihi -->
                                <div>
                                    <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-1">Doğum Tarihi</label>
                                    <input type="date" name="birthdate" id="birthdate" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1a2e5a] focus:border-[#1a2e5a]" value="{{ old('birthdate') }}">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Adres Bilgileri -->
                        <div>
                            <h2 class="text-lg font-semibold mb-4">Adres Bilgileri</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Ülke -->
                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Ülke</label>
                                    <select name="country" id="country" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1a2e5a] focus:border-[#1a2e5a]">
                                        <option value="Türkiye" selected>Türkiye</option>
                                        <option value="Diğer">Diğer</option>
                                    </select>
                                </div>
                                
                                <!-- Şehir -->
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Şehir</label>
                                    <input type="text" name="city" id="city" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1a2e5a] focus:border-[#1a2e5a]" value="{{ old('city') }}">
                                </div>
                                
                                <!-- Adres -->
                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adres</label>
                                    <textarea name="address" id="address" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1a2e5a] focus:border-[#1a2e5a]">{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Ödeme Bilgileri -->
                        <div>
                            <h2 class="text-lg font-semibold mb-4">Ödeme Bilgileri</h2>
                            
                            <!-- Ödeme yöntemi seçimi -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ödeme Yöntemi</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="relative">
                                        <input type="radio" name="payment_method" id="credit_card" value="credit_card" class="hidden peer" checked>
                                        <label for="credit_card" class="block p-4 border border-gray-300 rounded-md cursor-pointer peer-checked:border-[#1a2e5a] peer-checked:bg-blue-50 hover:border-gray-400">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                                <span class="font-medium">Kredi Kartı</span>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <div class="relative">
                                        <input type="radio" name="payment_method" id="bank_transfer" value="bank_transfer" class="hidden peer">
                                        <label for="bank_transfer" class="block p-4 border border-gray-300 rounded-md cursor-pointer peer-checked:border-[#1a2e5a] peer-checked:bg-blue-50 hover:border-gray-400">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                                </svg>
                                                <span class="font-medium">Havale / EFT</span>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <div class="relative">
                                        <input type="radio" name="payment_method" id="online_payment" value="online_payment" class="hidden peer">
                                        <label for="online_payment" class="block p-4 border border-gray-300 rounded-md cursor-pointer peer-checked:border-[#1a2e5a] peer-checked:bg-blue-50 hover:border-gray-400">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                </svg>
                                                <span class="font-medium">Online Ödeme</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Kredi kartı bilgileri (varsayılan görünür) -->
                            <div id="credit_card_form" class="p-4 border border-gray-200 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Kart üzerindeki isim -->
                                    <div class="md:col-span-2">
                                        <label for="card_name" class="block text-sm font-medium text-gray-700 mb-1">Kart Üzerindeki İsim</label>
                                        <input type="text" name="card_name" id="card_name" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1a2e5a] focus:border-[#1a2e5a]" placeholder="Kart sahibinin adı soyadı">
                                    </div>
                                    
                                    <!-- Kart numarası -->
                                    <div class="md:col-span-2">
                                        <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">Kart Numarası</label>
                                        <div class="relative">
                                            <input type="text" name="card_number" id="card_number" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1a2e5a] focus:border-[#1a2e5a] pr-10" placeholder="0000 0000 0000 0000">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Son kullanma tarihi -->
                                    <div>
                                        <label for="card_expiry" class="block text-sm font-medium text-gray-700 mb-1">Son Kullanma Tarihi</label>
                                        <input type="text" name="card_expiry" id="card_expiry" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1a2e5a] focus:border-[#1a2e5a]" placeholder="AA/YY">
                                    </div>
                                    
                                    <!-- CVV/CVC -->
                                    <div>
                                        <label for="card_cvc" class="block text-sm font-medium text-gray-700 mb-1">CVV/CVC</label>
                                        <input type="text" name="card_cvc" id="card_cvc" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1a2e5a] focus:border-[#1a2e5a]" placeholder="000">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Taksit seçenekleri -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Taksit Seçenekleri</label>
                                <select name="installment" id="installment" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1a2e5a] focus:border-[#1a2e5a]">
                                    <option value="1">Tek Çekim</option>
                                    <option value="3">3 Taksit</option>
                                    <option value="6">6 Taksit</option>
                                    <option value="9">9 Taksit</option>
                                </select>
                            </div>
                        </div>
                        
<!-- Şartlar ve koşullar --> 
<div class="pt-4 border-t border-gray-200"> 
    <div class="flex items-start"> 
        <div class="flex items-center h-5"> 
            <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-[#1a2e5a] border-gray-300 rounded focus:ring-[#1a2e5a]" required> 
        </div> 
        <div class="ml-3 text-sm"> 
            <label for="terms" class="font-medium text-gray-700"> 
                <a href="#" class="text-[#1a2e5a] hover:underline">Şartlar ve koşulları</a> okudum ve kabul ediyorum.
            </label>
            <p class="text-gray-500 mt-1">
                Kişisel verileriniz, eğitim hizmetlerinin sunulması amacıyla işlenmektedir. Daha fazla bilgi için <a href="#" class="text-[#1a2e5a] hover:underline">Gizlilik Politikamızı</a> inceleyebilirsiniz.
            </p>
        </div>
    </div>
    
    <div class="flex items-start mt-4">
        <div class="flex items-center h-5">
            <input id="newsletter" name="newsletter" type="checkbox" class="h-4 w-4 text-[#1a2e5a] border-gray-300 rounded focus:ring-[#1a2e5a]">
        </div>
        <div class="ml-3 text-sm">
            <label for="newsletter" class="font-medium text-gray-700">
                Özel kampanyalar, indirimler ve yeni eğitimlerden haberdar olmak istiyorum.
            </label>
        </div>
    </div>
</div>

<!-- Kayıt butonu ve ödeme özeti -->
<div class="mt-8 flex flex-col md:flex-row md:justify-between md:items-center">
    <div class="order-2 md:order-1 mt-6 md:mt-0">
        <button type="submit" class="w-full md:w-auto bg-[#e63946] hover:bg-[#d32836] text-white font-bold py-3 px-8 rounded-lg transition-colors duration-300 shadow-md hover:shadow-lg">
            Şimdi Kayıt Ol
        </button>
    </div>
    
    <div class="order-1 md:order-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
        <div class="text-right">
            <div class="text-gray-500 text-sm">Ödenecek Tutar:</div>
            <div class="text-[#1a2e5a] font-bold text-2xl">
                @if($course->discount_price)
                    {{ number_format($course->discount_price, 2) }} ₺
                @else
                    {{ number_format($course->price, 2) }} ₺
                @endif
            </div>
            <div class="text-gray-500 text-xs mt-1">KDV Dahil</div>
        </div>
    </div>
</div>
</form>

<!-- Güvenli ödeme bildirimi -->
<div class="mt-8 flex items-center justify-center text-sm text-gray-500">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
    </svg>
    Tüm ödeme işlemleri 256-bit SSL sertifikası ile şifrelenerek gerçekleştirilmektedir.
</div>

</div>
</div>
</div>
</div>

<!-- Benzer/İlgili Kurslar Bölümü -->
<div class="bg-gray-50 py-12">
<div class="container mx-auto px-4">
<h2 class="text-2xl font-bold text-[#1a2e5a] mb-8">Beğenebileceğiniz Diğer Kurslar</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($similarCourses as $similarCourse)
    <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-2 group">
        <div class="h-48 bg-gray-200 relative overflow-hidden">
            @if($similarCourse->thumbnail)
                <img src="{{ asset('storage/' . $similarCourse->thumbnail) }}" alt="{{ $similarCourse->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @else
                <div class="flex items-center justify-center h-full bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            @endif
            @if($similarCourse->discount_price)
                <div class="absolute top-2 right-2 bg-[#e63946] text-white px-3 py-1 rounded-full font-bold text-sm">
                    %{{ number_format((($similarCourse->price - $similarCourse->discount_price) / $similarCourse->price) * 100) }} İNDİRİM
                </div>
            @endif
        </div>
        
        <div class="p-4">
            <h3 class="text-lg font-semibold mb-2 text-[#1a2e5a]">{{ $similarCourse->name }}</h3>
            <p class="text-gray-600 mb-4 text-sm h-12 overflow-hidden">{{ Str::limit($similarCourse->description, 80) }}</p>
            
            <div class="flex items-center text-sm text-gray-500 mb-4">
                @if($similarCourse->teacher)
                    <div class="flex items-center mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ $similarCourse->teacher->name }}
                    </div>
                @endif
                
                @if($similarCourse->total_hours)
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#1a2e5a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $similarCourse->total_hours }} Saat
                    </div>
                @endif
            </div>
            
            <div class="flex justify-between items-center">
                <div>
                    @if($similarCourse->discount_price)
                        <span class="text-gray-500 line-through text-sm">{{ number_format($similarCourse->price, 2) }} ₺</span>
                        <span class="text-[#e63946] font-bold ml-2">{{ number_format($similarCourse->discount_price, 2) }} ₺</span>
                    @else
                        <span class="text-[#1a2e5a] font-bold">{{ number_format($similarCourse->price, 2) }} ₺</span>
                    @endif
                </div>
                <a href="{{ url('/egitimler/' . $similarCourse->slug) }}" class="bg-[#1a2e5a] hover:bg-[#15243f] text-white px-3 py-1 rounded-lg transition-colors duration-300 text-sm">İncele</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
</div>
</div>

@endsection

@section('scripts')
<script>
// Ödeme yöntemi değiştiğinde ilgili form alanını göster/gizle
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const creditCardForm = document.getElementById('credit_card_form');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'credit_card') {
                creditCardForm.classList.remove('hidden');
            } else {
                creditCardForm.classList.add('hidden');
            }
        });
    });
    
    // Kredi kartı formatlaması
    const cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            let formatted = '';
            
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formatted += ' ';
                }
                formatted += value[i];
            }
            
            e.target.value = formatted.substring(0, 19); // 16 rakam + 3 boşluk
        });
    }
    
    // Son kullanma tarihi formatlaması
    const cardExpiryInput = document.getElementById('card_expiry');
    if (cardExpiryInput) {
        cardExpiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            
            e.target.value = value;
        });
    }
});
</script>
@endsection