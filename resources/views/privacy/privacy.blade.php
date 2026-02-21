{{-- resources/views/privacy.blade.php --}}
@extends('layouts.app')

@section('content')
{{-- Hero --}}
<div class="bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] py-12">
    <div class="container mx-auto px-6">
        <h1 class="text-3xl md:text-4xl font-bold text-white text-center mb-6">Gizlilik Politikası</h1>
        <div class="w-20 h-1 bg-[#e63946] mx-auto mb-8"></div>
        <p class="text-xl text-center text-white max-w-3xl mx-auto">
            Kişisel verilerinizin güvenliği bizim için büyük önem taşımaktadır.
        </p>
    </div>
</div>

{{-- Content --}}
<div class="container mx-auto px-4 py-16">
    <div class="flex flex-col lg:flex-row gap-10">

        {{-- Sidebar - İçindekiler --}}
        <div class="w-full lg:w-1/4">
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                <h2 class="text-lg font-bold text-[#1a2e5a] mb-4 border-b border-gray-200 pb-3">İçindekiler</h2>
                <nav class="space-y-2">
                    @foreach([
                        ['#veri-sorumlusu',       'Veri Sorumlusu'],
                        ['#toplanan-veriler',      'Toplanan Veriler'],
                        ['#veri-isleme-amaci',     'Veri İşleme Amacı'],
                        ['#veri-aktarimi',         'Veri Aktarımı'],
                        ['#cerezler',              'Çerezler'],
                        ['#veri-guvenligi',        'Veri Güvenliği'],
                        ['#haklariniz',            'Haklarınız'],
                        ['#iletisim',              'İletişim'],
                    ] as [$href, $label])
                    <a href="{{ $href }}"
                       class="flex items-center text-sm text-gray-600 hover:text-[#e63946] transition-colors duration-200 py-1">
                        <span class="w-2 h-2 bg-[#e63946] rounded-full mr-3 flex-shrink-0"></span>
                        {{ $label }}
                    </a>
                    @endforeach
                </nav>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="w-full lg:w-3/4 space-y-8">

            {{-- Güncelleme Tarihi --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg px-6 py-4 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#1a2e5a] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-gray-700">
                    Bu Gizlilik Politikası en son <strong>{{ date('d.m.Y') }}</strong> tarihinde güncellenmiştir.
                    Rise English hizmetlerini kullanarak bu politikayı kabul etmiş sayılırsınız.
                </p>
            </div>

            {{-- Giriş --}}
            <div class="bg-white rounded-xl shadow-lg p-8">
                <p class="text-gray-700 leading-relaxed">
                    Rise English olarak, kullanıcılarımızın kişisel verilerinin gizliliğine ve güvenliğine son derece önem veriyoruz.
                    Bu Gizlilik Politikası; web sitemizi, mobil uygulamamızı ve diğer hizmetlerimizi kullanırken hangi verilerin
                    toplandığını, bu verilerin nasıl işlendiğini ve korunduğunu açıklamaktadır.
                    Politikamız, 6698 sayılı <strong>Kişisel Verilerin Korunması Kanunu (KVKK)</strong> kapsamında hazırlanmıştır.
                </p>
            </div>

            {{-- 1. Veri Sorumlusu --}}
            <div id="veri-sorumlusu" class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-5">
                    <div class="bg-[#1a2e5a] rounded-full p-3 text-white mr-4 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-[#1a2e5a]">1. Veri Sorumlusu</h2>
                </div>
                <p class="text-gray-700 leading-relaxed mb-4">
                    KVKK kapsamında veri sorumlusu sıfatını taşıyan kuruluş Rise English'tir.
                </p>
                <div class="bg-gray-50 rounded-lg p-5 space-y-2 text-sm text-gray-700">
                    <p><strong>Unvan:</strong> Rise English Dil Okulu</p>
                    <p><strong>Adres:</strong> Hacı Mütahir Mah. Rasim Erel Cad., Şehit Kamil Okulu Yanı, Ereğli İş Merkezi Kat 2, Ereğli / Konya</p>
                    <p><strong>Telefon:</strong> 0545 762 44 98</p>
                    <p><strong>E-posta:</strong> info@riseenglish.com</p>
                </div>
            </div>

            {{-- 2. Toplanan Veriler --}}
            <div id="toplanan-veriler" class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-5">
                    <div class="bg-[#1a2e5a] rounded-full p-3 text-white mr-4 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-[#1a2e5a]">2. Toplanan Kişisel Veriler</h2>
                </div>
                <p class="text-gray-700 leading-relaxed mb-6">
                    Hizmetlerimizi sunabilmek amacıyla aşağıdaki kişisel verileri toplayabiliriz:
                </p>

                <div class="space-y-4">
                    @foreach([
                        [
                            'title' => 'Kimlik Bilgileri',
                            'icon'  => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                            'items' => ['Ad, soyad', 'Doğum tarihi', 'T.C. kimlik numarası (zorunlu durumlarda)'],
                        ],
                        [
                            'title' => 'İletişim Bilgileri',
                            'icon'  => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                            'items' => ['E-posta adresi', 'Telefon numarası', 'Adres bilgileri'],
                        ],
                        [
                            'title' => 'Eğitim & Kullanım Bilgileri',
                            'icon'  => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                            'items' => ['Kayıt olduğunuz kurslar', 'Sınav ve ödev sonuçları', 'Uygulama içi ilerleme verileri'],
                        ],
                        [
                            'title' => 'Teknik & Log Verileri',
                            'icon'  => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                            'items' => ['IP adresi', 'Tarayıcı ve cihaz bilgisi', 'Ziyaret edilen sayfalar ve işlem geçmişi'],
                        ],
                    ] as $category)
                    <div class="border border-gray-200 rounded-lg p-5">
                        <div class="flex items-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#e63946] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $category['icon'] }}"/>
                            </svg>
                            <h3 class="font-semibold text-[#1a2e5a]">{{ $category['title'] }}</h3>
                        </div>
                        <ul class="space-y-1">
                            @foreach($category['items'] as $item)
                            <li class="flex items-center text-sm text-gray-600">
                                <span class="w-1.5 h-1.5 bg-[#e63946] rounded-full mr-2"></span>
                                {{ $item }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 3. Veri İşleme Amacı --}}
            <div id="veri-isleme-amaci" class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-5">
                    <div class="bg-[#1a2e5a] rounded-full p-3 text-white mr-4 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-[#1a2e5a]">3. Veri İşleme Amaçları</h2>
                </div>
                <p class="text-gray-700 leading-relaxed mb-5">
                    Kişisel verileriniz aşağıdaki amaçlarla işlenmektedir:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach([
                        'Kayıt ve üyelik işlemlerinin gerçekleştirilmesi',
                        'Eğitim hizmetlerinin sunulması ve takibi',
                        'Sınav, ödev ve değerlendirme süreçlerinin yönetilmesi',
                        'Faturalama ve ödeme işlemlerinin yapılması',
                        'Müşteri destek ve iletişim hizmetleri',
                        'Yasal yükümlülüklerin yerine getirilmesi',
                        'Hizmet kalitesinin iyileştirilmesi ve analiz',
                        'Kampanya ve duyuruların iletilmesi (onay verilmesi halinde)',
                    ] as $item)
                    <div class="flex items-start bg-gray-50 rounded-lg p-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#e63946] mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-sm text-gray-700">{{ $item }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 4. Veri Aktarımı --}}
            <div id="veri-aktarimi" class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-5">
                    <div class="bg-[#1a2e5a] rounded-full p-3 text-white mr-4 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-[#1a2e5a]">4. Kişisel Verilerin Aktarımı</h2>
                </div>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Kişisel verileriniz, Rise English tarafından üçüncü taraflara satılmamaktadır.
                    Aşağıdaki durumlarda sınırlı ölçüde aktarım gerçekleştirilebilir:
                </p>
                <div class="space-y-3">
                    @foreach([
                        ['Yasal Zorunluluk'      => 'Mahkeme kararı, yasal düzenleme veya resmi kurum talebi doğrultusunda ilgili otoritelerle.'],
                        ['Ödeme Altyapısı'       => 'Güvenli ödeme işlemleri için yetkili ödeme kuruluşları ve bankalarla.'],
                        ['Teknik Hizmet Sağlayıcılar' => 'Altyapı, hosting, e-posta ve SMS hizmetleri gibi teknik destek sağlayıcılarıyla.'],
                    ] as $row)
                    @foreach($row as $title => $desc)
                    <div class="border-l-4 border-[#1a2e5a] pl-4 py-2">
                        <p class="font-semibold text-[#1a2e5a] text-sm">{{ $title }}</p>
                        <p class="text-gray-600 text-sm mt-1">{{ $desc }}</p>
                    </div>
                    @endforeach
                    @endforeach
                </div>
            </div>

            {{-- 5. Çerezler --}}
            <div id="cerezler" class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-5">
                    <div class="bg-[#1a2e5a] rounded-full p-3 text-white mr-4 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 6.343l-.707-.707m12.728 12.728l-.707-.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-[#1a2e5a]">5. Çerezler (Cookies)</h2>
                </div>
                <p class="text-gray-700 leading-relaxed mb-5">
                    Web sitemiz ve uygulamamız, kullanıcı deneyimini iyileştirmek amacıyla çerezler kullanmaktadır.
                </p>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-[#1a2e5a] text-white">
                                <th class="text-left p-3 rounded-tl-lg">Çerez Türü</th>
                                <th class="text-left p-3">Amaç</th>
                                <th class="text-left p-3 rounded-tr-lg">Süre</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach([
                                ['Zorunlu Çerezler',   'Oturum yönetimi, güvenlik, temel site işlevselliği',    'Oturum süresi'],
                                ['Analitik Çerezler',  'Ziyaretçi istatistikleri ve site performansı ölçümü',   '1-2 yıl'],
                                ['İşlevsel Çerezler',  'Dil tercihi ve kişiselleştirme ayarları',               '1 yıl'],
                                ['Pazarlama Çerezleri','İlgi alanına göre içerik ve reklam kişiselleştirme',    '6 ay'],
                            ] as $i => [$type, $purpose, $duration])
                            <tr class="{{ $i % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                                <td class="p-3 font-medium text-[#1a2e5a]">{{ $type }}</td>
                                <td class="p-3 text-gray-600">{{ $purpose }}</td>
                                <td class="p-3 text-gray-600">{{ $duration }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="text-sm text-gray-500 mt-4">
                    Tarayıcı ayarlarınızdan çerezleri devre dışı bırakabilirsiniz. Ancak bu durum bazı site özelliklerinin çalışmamasına yol açabilir.
                </p>
            </div>

            {{-- 6. Veri Güvenliği --}}
            <div id="veri-guvenligi" class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-5">
                    <div class="bg-[#1a2e5a] rounded-full p-3 text-white mr-4 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-[#1a2e5a]">6. Veri Güvenliği</h2>
                </div>
                <p class="text-gray-700 leading-relaxed mb-5">
                    Kişisel verilerinizi korumak için teknik ve idari güvenlik tedbirleri uygulamaktayız:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach([
                        ['SSL / TLS Şifreleme',        'Tüm veri iletimi HTTPS protokolü ile şifrelenmektedir.'],
                        ['Erişim Kontrolü',             'Verilerinize yalnızca yetkili personel erişebilmektedir.'],
                        ['Güvenlik Duvarı',             'Sunucularımız firewall ve IDS sistemleriyle korunmaktadır.'],
                        ['Düzenli Yedekleme',           'Veriler düzenli aralıklarla yedeklenmektedir.'],
                        ['Şifreli Parola Saklama',      'Kullanıcı parolaları hash algoritmaları ile saklanmaktadır.'],
                        ['Güvenlik Denetimleri',        'Sistemlerimiz periyodik güvenlik testlerine tabi tutulmaktadır.'],
                    ] as [$title, $desc])
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="font-semibold text-[#1a2e5a] text-sm mb-1">🔒 {{ $title }}</p>
                        <p class="text-gray-600 text-sm">{{ $desc }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 7. Haklarınız --}}
            <div id="haklariniz" class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-5">
                    <div class="bg-[#1a2e5a] rounded-full p-3 text-white mr-4 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-[#1a2e5a]">7. KVKK Kapsamında Haklarınız</h2>
                </div>
                <p class="text-gray-700 leading-relaxed mb-5">
                    6698 sayılı KVKK'nın 11. maddesi uyarınca kişisel verilerinize ilişkin aşağıdaki haklara sahipsiniz:
                </p>
                <div class="space-y-3">
                    @foreach([
                        ['Bilgi Edinme Hakkı',            'Kişisel verilerinizin işlenip işlenmediğini öğrenme'],
                        ['Erişim Hakkı',                  'İşlenen kişisel verilerinize ilişkin bilgi talep etme'],
                        ['Düzeltme Hakkı',                'Eksik veya yanlış verilerin düzeltilmesini isteme'],
                        ['Silme / Yok Etme Hakkı',        'Yasal koşullar çerçevesinde verilerinizin silinmesini talep etme'],
                        ['İtiraz Hakkı',                  'Verilerinizin işlenmesine itiraz etme'],
                        ['Zararın Giderilmesi Hakkı',     'Hukuka aykırı işleme nedeniyle uğradığınız zararın tazminini isteme'],
                        ['Aktarımın Kısıtlanması Hakkı',  'Verilerinizin üçüncü taraflara aktarılmasını kısıtlama'],
                    ] as [$right, $desc])
                    <div class="flex items-start border border-gray-200 rounded-lg p-4 hover:border-[#1a2e5a] transition-colors duration-200">
                        <div class="bg-[#e63946] text-white rounded-full w-7 h-7 flex items-center justify-center mr-3 flex-shrink-0 text-xs font-bold">✓</div>
                        <div>
                            <p class="font-semibold text-[#1a2e5a] text-sm">{{ $right }}</p>
                            <p class="text-gray-600 text-sm mt-0.5">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-5">
                    <p class="text-sm text-yellow-800">
                        <strong>Başvuru:</strong> Haklarınızı kullanmak için kimliğinizi doğrulayan belgelerle birlikte
                        <a href="mailto:info@riseenglish.com" class="text-[#1a2e5a] font-semibold underline">info@riseenglish.com</a>
                        adresine veya yukarıda belirtilen adresimize yazılı olarak başvurabilirsiniz.
                        Başvurularınız en geç <strong>30 gün</strong> içinde yanıtlanacaktır.
                    </p>
                </div>
            </div>

            {{-- 8. İletişim --}}
            <div id="iletisim" class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-5">
                    <div class="bg-[#1a2e5a] rounded-full p-3 text-white mr-4 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-[#1a2e5a]">8. İletişim</h2>
                </div>
                <p class="text-gray-700 leading-relaxed mb-5">
                    Gizlilik politikamıza ilişkin sorularınız veya kişisel verilerinizle ilgili talepleriniz için aşağıdaki kanallardan bize ulaşabilirsiniz:
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="mailto:info@riseenglish.com"
                       class="flex items-center justify-center gap-2 bg-[#1a2e5a] hover:bg-[#283b6a] text-white font-semibold py-3 px-6 rounded-lg transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        info@riseenglish.com
                    </a>
                    <a href="{{ route('contact') }}"
                       class="flex items-center justify-center gap-2 bg-[#e63946] hover:bg-[#d32836] text-white font-semibold py-3 px-6 rounded-lg transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        İletişim Formu
                    </a>
                </div>
            </div>

            {{-- Güncelleme notu --}}
            <p class="text-sm text-gray-400 text-center pb-4">
                Rise English, bu Gizlilik Politikası'nı önceden bildirimde bulunmaksızın güncelleme hakkını saklı tutar.
                Güncel politikayı takip etmek için bu sayfayı periyodik olarak ziyaret etmenizi öneririz.
            </p>

        </div>{{-- /main content --}}
    </div>
</div>
@endsection