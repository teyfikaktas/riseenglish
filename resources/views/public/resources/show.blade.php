{{-- resources/views/public/resources/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-blue-50 to-white min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transition duration-300 hover:shadow-2xl">
            <div class="md:flex">
                <div class="md:flex-shrink-0 relative">
                    <img class="h-full w-full object-cover md:w-64" src="{{asset('storage/' . $resource->image_path) }}"  alt="{{ $resource->title }}">
                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-[#2c3e7f]/30 to-transparent"></div>
                </div>
                <div class="p-8 w-full">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                        <div>
                            <div class="uppercase tracking-wide text-sm text-[#2c3e7f] font-bold flex items-center">
                                <span class="w-2 h-2 bg-[#e43546] rounded-full mr-2"></span>
                                {{ $resource->category->name }} - {{ $resource->type->name }}
                            </div>
                            <h1 class="mt-2 text-3xl font-bold text-gray-900">{{ $resource->title }}</h1>
                        </div>
                        <div class="flex flex-col items-end mt-4 md:mt-0">
                            <span class="bg-[#e43546]/10 text-[#e43546] text-xs font-medium px-3 py-1.5 rounded-full flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $resource->is_free ? 'Ücretsiz' : 'Ücretli' }}
                            </span>
                            <span class="text-gray-600 text-sm mt-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#2c3e7f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                </svg>
                                {{ $resource->download_count }} indirme
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-8 p-6 bg-gray-50 rounded-xl border border-gray-100">
                        <h3 class="text-lg font-bold text-[#2c3e7f] flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Açıklama
                        </h3>
                        <p class="mt-3 text-gray-700 leading-relaxed">{{ $resource->description }}</p>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-bold text-[#2c3e7f] flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Etiketler
                        </h3>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($resource->tags as $tag)
                                <span class="bg-[#2c3e7f]/10 text-[#2c3e7f] text-xs font-medium px-3 py-1.5 rounded-full">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mt-10 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('public.resources.index') }}" class="flex items-center text-[#2c3e7f] hover:text-[#1e3370] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kaynaklara dön
                        </a>
                        <button 
                            data-download 
                            data-title="{{ $resource->title }}" 
                            data-file="{{ asset('storage/' . $resource->file_path) }}"
                                                        class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-[#2c3e7f] to-[#264285] text-white font-medium rounded-xl shadow-md hover:from-[#264285] hover:to-[#1e3370] transition duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Kaynağı İndir
                        </button>
                    </div>
                </div>
            </div>
            
            {{-- İlgili Kaynaklar --}}
            @if(isset($relatedResources) && count($relatedResources) > 0)
            <div class="border-t border-gray-200 p-8">
                <h3 class="text-xl font-bold text-[#2c3e7f] mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    İlgili Kaynaklar
                </h3>
                <div class="flex overflow-x-auto pb-4 hide-scrollbar gap-4">
                    @foreach($relatedResources as $relatedResource)
                        <div class="flex-shrink-0 w-64 bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                            <img src="{{ asset($relatedResource->image_path) }}" class="h-36 w-full object-cover" alt="{{ $relatedResource->title }}">
                            <div class="p-4">
                                <h4 class="font-semibold text-gray-900 mb-2">{{ $relatedResource->title }}</h4>
                                <a href="{{ route('public.resources.show', $relatedResource->slug) }}" 
                                   class="w-full block text-center py-2 px-3 bg-[#2c3e7f] text-white text-sm rounded-lg hover:bg-[#1e3370] transition">
                                    Görüntüle
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- İndirme Modalı -->
<div id="downloadModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <!-- Arka plan overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" id="modal-backdrop"></div>
        
        <!-- Modal içeriği -->
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
            <!-- Modal başlık -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-[#2c3e7f]" id="modal-resource-title">YÖKDİL Sağlık Bilimleri Kelime Listesi</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" id="close-modal">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Form alanı -->
            <div class="p-6">
                <p class="text-gray-600 mb-4">İndirmek için lütfen bilgilerinizi giriniz:</p>
                
                <form id="download-form">
                    <div class="space-y-4">
                        <div>
                            <label for="fullname" class="block text-sm font-medium text-gray-700 mb-1">Ad Soyad</label>
                            <input type="text" name="fullname" id="fullname" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-[#2c3e7f] focus:border-[#2c3e7f]" required>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-[#2c3e7f] focus:border-[#2c3e7f]" required>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="newsletter" name="newsletter" type="checkbox" class="h-4 w-4 text-[#2c3e7f] border-gray-300 rounded focus:ring-[#2c3e7f]">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="newsletter" class="font-medium text-gray-700">Önemli kampanyalardan haberdar olmak için elektronik ileti almak istiyorum.</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-[#2c3e7f] to-[#264285] text-white font-medium rounded-xl shadow-md hover:from-[#264285] hover:to-[#1e3370] transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2c3e7f]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            KAYNAĞI İNDİR
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Güvenlik notu -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl">
                <p class="text-xs text-gray-500 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#2c3e7f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Bilgileriniz gizli tutulmaktadır. Hiçbir üçüncü tarafla paylaşılmaz.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM elementlerini seçme
    const downloadButtons = document.querySelectorAll('[data-download]');
    const modal = document.getElementById('downloadModal');
    const modalBackdrop = document.getElementById('modal-backdrop');
    const closeModalBtn = document.getElementById('close-modal');
    const downloadForm = document.getElementById('download-form');
    const modalResourceTitle = document.getElementById('modal-resource-title');
    
    // Aktif indirme bağlantısını saklamak için değişken
    let activeDownloadLink = '';
    
    // İndirme butonlarına event listener ekleme
    downloadButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Aktif indirme bağlantısını sakla
            activeDownloadLink = button.dataset.file || '';
            
            // Kaynak başlığını modalda güncelleme
            if (button.dataset.title) {
                modalResourceTitle.textContent = button.dataset.title;
            }
            
            // Modalı gösterme
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden'); // Scroll'u engelleme
        });
    });
    
    // Modalı kapatma fonksiyonu
    function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden'); // Scroll'u tekrar etkinleştirme
        downloadForm.reset(); // Formu sıfırlama
    }
    
    // Kapatma butonuna ve arka plana tıklama event listener'ları
    closeModalBtn.addEventListener('click', closeModal);
    modalBackdrop.addEventListener('click', closeModal);
    
    // ESC tuşuna basılınca modalı kapatma
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
    
    // Form submit işlemi
    downloadForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Form verilerini alma
        const formData = new FormData(downloadForm);
        const userData = {
            fullname: formData.get('fullname'),
            email: formData.get('email'),
            newsletter: formData.get('newsletter') ? true : false
        };
        
        // Burada form verilerini kaydetme işlemi yapılabilir (Ajax isteği vs.)
        console.log('Kullanıcı bilgileri:', userData);
        
        // Modalı kapatma
        closeModal();
        
        // İndirme işlemini başlatma
        if (activeDownloadLink) {
            window.location.href = activeDownloadLink;
        }
    });
});
</script>
@endsection