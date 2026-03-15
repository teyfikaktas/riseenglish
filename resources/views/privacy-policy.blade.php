@extends('layouts.app')

@section('content')
<!-- Hero -->
<div class="bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] py-12">
    <div class="container mx-auto px-6">
        <h1 class="text-3xl md:text-4xl font-bold text-white text-center mb-6">Gizlilik Politikası</h1>
        <div class="w-20 h-1 bg-[#e63946] mx-auto mb-8"></div>
        <p class="text-xl text-center text-white max-w-3xl mx-auto">
            Kişisel verileriniz bizim için önemlidir. Bu politika, verilerinizi nasıl topladığımızı, kullandığımızı ve koruduğumuzu açıklar.
        </p>
    </div>
</div>

<!-- Content -->
<div class="container mx-auto px-4 py-16 max-w-4xl">

    <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
        <p class="text-gray-500 text-sm mb-4">Son güncelleme: {{ date('d.m.Y') }}</p>
        <p class="text-gray-700 leading-relaxed">
            Bu Gizlilik Politikası, <strong>Rise English</strong> mobil uygulaması ve web platformunun ("Uygulama") kullanıcılarına ait kişisel verilerin nasıl toplandığını, işlendiğini, saklandığını ve korunduğunu açıklamaktadır. Uygulamayı kullanarak bu politikayı kabul etmiş sayılırsınız.
        </p>
    </div>

    <!-- 1 -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
        <h2 class="text-2xl font-bold text-[#1a2e5a] mb-4 flex items-center gap-3">
            <span class="bg-[#e63946] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">1</span>
            Toplanan Veriler
        </h2>
        <p class="text-gray-700 mb-4">Uygulama aşağıdaki kişisel verileri toplayabilir:</p>
        <ul class="space-y-3 text-gray-700">
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span><strong>Hesap Bilgileri:</strong> Ad, soyad, e-posta adresi, telefon numarası ve şifre (şifreli olarak saklanır).</span></li>
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span><strong>Kullanım Verileri:</strong> Sınav sonuçları, kelime çalışma geçmişi, uygulama içi aktivite ve oturum süreleri.</span></li>
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span><strong>Cihaz Bilgileri:</strong> Cihaz türü, işletim sistemi sürümü, uygulama sürümü ve benzersiz cihaz tanımlayıcıları.</span></li>
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span><strong>Bildirim Tokeni:</strong> Push bildirimi gönderilebilmesi amacıyla FCM (Firebase Cloud Messaging) tokeni.</span></li>
        </ul>
    </div>

    <!-- 2 -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
        <h2 class="text-2xl font-bold text-[#1a2e5a] mb-4 flex items-center gap-3">
            <span class="bg-[#e63946] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">2</span>
            Verilerin Kullanım Amacı
        </h2>
        <ul class="space-y-3 text-gray-700">
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span>Kullanıcı hesabının oluşturulması ve yönetimi</span></li>
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span>Sınav ve kelime çalışma hizmetlerinin sunulması</span></li>
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span>Kişisel ilerleme takibi ve raporlama</span></li>
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span>Günlük hatırlatma ve bildirim gönderimi</span></li>
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span>Uygulama güvenliği ve hata tespiti</span></li>
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span>Yasal yükümlülüklerin yerine getirilmesi</span></li>
        </ul>
    </div>

    <!-- 3 -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
        <h2 class="text-2xl font-bold text-[#1a2e5a] mb-4 flex items-center gap-3">
            <span class="bg-[#e63946] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">3</span>
            Üçüncü Taraf Hizmetler
        </h2>
        <p class="text-gray-700 mb-4">Uygulama, aşağıdaki üçüncü taraf hizmetleri kullanmaktadır. Bu hizmetler kendi gizlilik politikaları çerçevesinde veri işleyebilir:</p>
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-[#1a2e5a] mb-1">Firebase (Google LLC)</h3>
                <p class="text-gray-600 text-sm">Push bildirimleri (FCM) ve uygulama analitikleri için kullanılmaktadır.</p>
                <a href="https://policies.google.com/privacy" target="_blank" class="text-[#e63946] text-sm hover:underline">→ Google Gizlilik Politikası</a>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-[#1a2e5a] mb-1">Apple (Sign in with Apple / App Store)</h3>
                <p class="text-gray-600 text-sm">iOS uygulaması Apple platformu üzerinden dağıtılmaktadır. Apple'ın uygulama izleme politikaları geçerlidir.</p>
                <a href="https://www.apple.com/legal/privacy/tr/" target="_blank" class="text-[#e63946] text-sm hover:underline">→ Apple Gizlilik Politikası</a>
            </div>
        </div>
    </div>

    <!-- 4 -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
        <h2 class="text-2xl font-bold text-[#1a2e5a] mb-4 flex items-center gap-3">
            <span class="bg-[#e63946] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">4</span>
            Veri Güvenliği
        </h2>
        <p class="text-gray-700 leading-relaxed">
            Kişisel verileriniz, yetkisiz erişime, değiştirmeye veya ifşa edilmeye karşı korumak amacıyla endüstri standardı güvenlik önlemleriyle korunmaktadır. Şifreler bcrypt algoritmasıyla şifrelenerek saklanır. Sunucu ile uygulama arasındaki tüm iletişim HTTPS/TLS üzerinden gerçekleştirilir. Bununla birlikte, internet üzerinden hiçbir veri aktarımı yönteminin %100 güvenli olmadığını belirtmek isteriz.
        </p>
    </div>

    <!-- 5 -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
        <h2 class="text-2xl font-bold text-[#1a2e5a] mb-4 flex items-center gap-3">
            <span class="bg-[#e63946] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">5</span>
            Veri Saklama Süresi
        </h2>
        <p class="text-gray-700 leading-relaxed">
            Kişisel verileriniz, hesabınız aktif olduğu sürece veya hizmet sunumu için gerekli olan süre boyunca saklanır. Hesabınızı silmeniz durumunda, yasal yükümlülükler kapsamında saklanması gereken veriler hariç olmak üzere tüm kişisel verileriniz 30 gün içinde sistemlerimizden silinir.
        </p>
    </div>

    <!-- 6 -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
        <h2 class="text-2xl font-bold text-[#1a2e5a] mb-4 flex items-center gap-3">
            <span class="bg-[#e63946] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">6</span>
            Kullanıcı Hakları (KVKK)
        </h2>
        <p class="text-gray-700 mb-4">6698 sayılı Kişisel Verilerin Korunması Kanunu kapsamında aşağıdaki haklara sahipsiniz:</p>
        <ul class="space-y-3 text-gray-700">
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span>Kişisel verilerinizin işlenip işlenmediğini öğrenme</span></li>
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span>İşlenen verileriniz hakkında bilgi talep etme</span></li>
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span>Verilerin eksik veya yanlış işlenmesi halinde düzeltilmesini isteme</span></li>
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span>Kişisel verilerinizin silinmesini talep etme</span></li>
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span>İşlemenin kısıtlanmasını isteme</span></li>
            <li class="flex items-start gap-2"><span class="text-[#e63946] mt-1">•</span><span>Veri taşınabilirliği hakkı</span></li>
        </ul>
        <p class="text-gray-700 mt-4">Bu haklarınızı kullanmak için <a href="mailto:info@riseenglish.com" class="text-[#e63946] hover:underline font-medium">info@riseenglish.com</a> adresine e-posta gönderebilirsiniz.</p>
    </div>

    <!-- 7 -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
        <h2 class="text-2xl font-bold text-[#1a2e5a] mb-4 flex items-center gap-3">
            <span class="bg-[#e63946] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">7</span>
            Çocukların Gizliliği
        </h2>
        <p class="text-gray-700 leading-relaxed">
            Uygulamamız 13 yaşın altındaki çocuklara yönelik değildir ve bilerek bu yaş grubundan kişisel veri toplamayız. 13 yaşın altındaki bir çocuğun verilerinin sistemimize girdiğini fark ederseniz lütfen bizimle iletişime geçin, söz konusu verileri derhal sileceğiz.
        </p>
    </div>

    <!-- 8 -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
        <h2 class="text-2xl font-bold text-[#1a2e5a] mb-4 flex items-center gap-3">
            <span class="bg-[#e63946] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">8</span>
            Hesap Silme
        </h2>
        <p class="text-gray-700 leading-relaxed">
            Hesabınızı ve tüm verilerinizi silmek için uygulama içindeki <strong>Ayarlar → Hesabımı Sil</strong> seçeneğini kullanabilir ya da <a href="mailto:info@riseenglish.com" class="text-[#e63946] hover:underline font-medium">info@riseenglish.com</a> adresine "Hesap Silme Talebi" konusuyla e-posta gönderebilirsiniz. Talepler en geç 30 gün içinde işleme alınır.
        </p>
    </div>

    <!-- 9 -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
        <h2 class="text-2xl font-bold text-[#1a2e5a] mb-4 flex items-center gap-3">
            <span class="bg-[#e63946] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">9</span>
            Politika Değişiklikleri
        </h2>
        <p class="text-gray-700 leading-relaxed">
            Bu gizlilik politikasını zaman zaman güncelleyebiliriz. Önemli değişiklikler olması durumunda uygulama içi bildirim veya e-posta yoluyla sizi bilgilendireceğiz. Güncel politikayı her zaman bu sayfada bulabilirsiniz.
        </p>
    </div>

    <!-- Contact -->
    <div class="bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] rounded-xl p-8 text-white">
        <h2 class="text-2xl font-bold mb-4">İletişim</h2>
        <p class="mb-4 text-blue-100">Gizlilik politikamıza ilişkin sorularınız için bizimle iletişime geçebilirsiniz:</p>
        <div class="space-y-2 text-blue-100">
            <p><strong class="text-white">Rise English</strong></p>
            <p>Hacı Mütahir Mah. Rasim Erel Cad., Ereğli İş Merkezi Kat 2, Ereğli/Konya</p>
            <p>📧 <a href="mailto:info@riseenglish.com" class="text-white hover:underline">info@riseenglish.com</a></p>
            <p>📞 <a href="tel:+905457624498" class="text-white hover:underline">0545 762 44 98</a></p>
        </div>
    </div>

</div>
@endsection