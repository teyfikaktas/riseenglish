<!-- resources/views/admin/sms/index.blade.php -->
@extends('layouts.app')

@section('title', 'SMS Yönetimi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">SMS Yönetimi</h1>
        <p class="text-gray-600">Kullanıcılara ve kurs öğrencilerine SMS gönderimi</p>
    </div>

    <!-- Başarı Mesajı -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex justify-between">
            <span>{{ session('success') }}</span>
            <button type="button" class="text-green-700" onclick="this.parentElement.style.display='none'">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    <!-- Hata Mesajı -->
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 flex justify-between">
            <span>{{ session('error') }}</span>
            <button type="button" class="text-red-700" onclick="this.parentElement.style.display='none'">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- SMS Özeti Kartı -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="rounded-full bg-blue-100 p-3 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Gönderilen SMS</p>
                        <h2 class="text-3xl font-bold text-gray-800">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kullanıcı Sayısı Kartı -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="rounded-full bg-purple-100 p-3 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Toplam Kullanıcı</p>
                        <h2 class="text-3xl font-bold text-gray-800">{{ $totalUsers ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aktif Kurslar Kartı -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="rounded-full bg-green-100 p-3 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Aktif Kurslar</p>
                        <h2 class="text-3xl font-bold text-gray-800">{{ $activeCourses ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SMS Gönderme Seçenekleri -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">SMS Gönderme</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bireysel SMS Gönder -->
                <div class="bg-gray-50 rounded-md p-4 border border-gray-200">
                    <h4 class="text-md font-semibold mb-4 flex items-center text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Bireysel SMS Gönder
                    </h4>
                    <form action="{{ route('admin.sms.send-individual') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="user_search" class="block text-sm font-medium text-gray-700 mb-1">Kullanıcı Ara ve Seç</label>
                            <div class="relative">
                                <input type="text" id="user_search" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Kullanıcı adı veya telefon yazın (en az 3 karakter)...">
                                <input type="hidden" id="selected_user_id" name="user_id" value="">
                                
                                <div id="user_search_results" class="absolute z-10 w-full mt-1 bg-white shadow-lg rounded-md max-h-60 overflow-auto hidden">
                                    <!-- JavaScript ile doldurulacak -->
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500" id="selected_user_info">Henüz bir kullanıcı seçilmedi</p>
                        </div>
                        <div>
                            <label for="individual_message" class="block text-sm font-medium text-gray-700 mb-1">Mesaj İçeriği</label>
                            <textarea id="individual_message" name="message" rows="4" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="SMS içeriğini buraya yazın..."></textarea>
                            <p class="mt-1 text-xs text-gray-500">
                                <span id="individual_char_count">0</span>/160 karakter
                            </p>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" id="send_individual_btn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 opacity-50 cursor-not-allowed" disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Gönder
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Toplu SMS Gönder -->
                <div class="bg-gray-50 rounded-md p-4 border border-gray-200">
                    <h4 class="text-md font-semibold mb-4 flex items-center text-green-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Toplu SMS Gönder
                    </h4>
                    <form action="{{ route('admin.sms.send-bulk') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="target_group" class="block text-sm font-medium text-gray-700 mb-1">Hedef Grup</label>
                            <select id="target_group" name="target_group" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="all_users">Tüm Kullanıcılar</option>
                                <option value="all_students">Tüm Öğrenciler</option>
                                <option value="course_students">Belirli Kurs Öğrencileri</option>
                            </select>
                        </div>
                        <div id="course_select_div" class="hidden">
                            <label for="course_search" class="block text-sm font-medium text-gray-700 mb-1">Kurs Ara ve Seç</label>
                            <div class="relative">
                                <input type="text" id="course_search" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Kurs adı yazın (en az 3 karakter)...">
                                <input type="hidden" id="selected_course_id" name="course_id" value="">
                                
                                <div id="course_search_results" class="absolute z-10 w-full mt-1 bg-white shadow-lg rounded-md max-h-60 overflow-auto hidden">
                                    <!-- JavaScript ile doldurulacak -->
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500" id="selected_course_info">Henüz bir kurs seçilmedi</p>
                        </div>
                        <div>
                            <label for="bulk_message" class="block text-sm font-medium text-gray-700 mb-1">Mesaj İçeriği</label>
                            <textarea id="bulk_message" name="message" rows="4" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="SMS içeriğini buraya yazın..."></textarea>
                            <p class="mt-1 text-xs text-gray-500">
                                <span id="bulk_char_count">0</span>/160 karakter
                            </p>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" id="send_bulk_btn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Toplu Gönder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Son Gönderilen SMS'ler -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Son Gönderilen SMS'ler</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gönderen</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alıcı</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesaj</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Örnek veri - normalde veritabanından çekilecek -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" colspan="5">
                                Henüz gönderilmiş SMS bulunmamaktadır.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// SMS Yönetimi - AJAX Temelli Kullanıcı ve Kurs Arama

document.addEventListener('DOMContentLoaded', function() {
    // DOM elementleri
    const userSearchInput = document.getElementById('user_search');
    const userSearchResults = document.getElementById('user_search_results');
    const selectedUserIdInput = document.getElementById('selected_user_id');
    const selectedUserInfo = document.getElementById('selected_user_info');
    const sendIndividualBtn = document.getElementById('send_individual_btn');
    
    const courseSearchInput = document.getElementById('course_search');
    const courseSearchResults = document.getElementById('course_search_results');
    const selectedCourseIdInput = document.getElementById('selected_course_id');
    const selectedCourseInfo = document.getElementById('selected_course_info');
    
    // CSRF token değerini meta tag'den al
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Minimum karakter sayısı
    const MIN_CHARS = 3;
    
    // Gecikme süresi (ms) - art arda aramalar için
    const DEBOUNCE_TIME = 300;
    let userSearchTimeout = null;
    let courseSearchTimeout = null;
    
    // Kullanıcı arama işlevi
// Kullanıcı arama işlevi
userSearchInput.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim();
    
    // Önceki zamanlayıcıyı temizle
    if (userSearchTimeout) {
        clearTimeout(userSearchTimeout);
    }
    
    // 3 karakterden az ise sonuçları gösterme
    if (searchTerm.length < MIN_CHARS) {
        userSearchResults.innerHTML = '';
        userSearchResults.classList.add('hidden');
        return;
    }
    
    // Debug için log
    console.log("Kullanıcı araması:", searchTerm);
    
    // Aramayı geciktir
    userSearchTimeout = setTimeout(function() {
        // Konsola yazdırarak URL'yi görüntüleme
        console.log("API URL:", `/admin/sms/search-users?query=${encodeURIComponent(searchTerm)}`);
        
        // AJAX isteği gönder - tam yol kullanarak
        fetch(`/admin/sms/search-users?query=${encodeURIComponent(searchTerm)}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
        })
        .then(response => {
            console.log("API Yanıtı Status:", response.status);
            return response.json();
        })
        .then(data => {
            // Debug için log
            console.log("Gelen veri:", data);
            
            // Sonuçları temizle
            userSearchResults.innerHTML = '';
            
            const users = data.users || [];
            
            if (users.length === 0) {
                const noResultItem = document.createElement('div');
                noResultItem.className = 'px-4 py-2 text-sm text-gray-700';
                noResultItem.textContent = 'Sonuç bulunamadı';
                userSearchResults.appendChild(noResultItem);
            } else {
                users.forEach(user => {
                    const resultItem = document.createElement('div');
                    resultItem.className = 'px-4 py-2 text-sm text-gray-700 hover:bg-blue-100 cursor-pointer';
                    resultItem.textContent = `${user.name} (${user.phone || 'Telefon yok'})`;
                    
                    resultItem.addEventListener('click', function() {
                        selectedUserIdInput.value = user.id;
                        userSearchInput.value = user.name;
                        selectedUserInfo.textContent = `Seçilen: ${user.name} (${user.phone || 'Telefon yok'})`;
                        userSearchResults.classList.add('hidden');
                        
                        // Gönder butonunu aktifleştir
                        if (sendIndividualBtn) {
                            sendIndividualBtn.disabled = false;
                            sendIndividualBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    });
                    
                    userSearchResults.appendChild(resultItem);
                });
            }
            
            userSearchResults.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Kullanıcı arama hatası:', error);
            userSearchResults.innerHTML = '<div class="px-4 py-2 text-sm text-red-700">Arama sırasında bir hata oluştu</div>';
            userSearchResults.classList.remove('hidden');
        });
    }, DEBOUNCE_TIME);
});
    
    // Kurs arama işlevi - benzer şekilde güncellendi
    courseSearchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        // Önceki zamanlayıcıyı temizle
        if (courseSearchTimeout) {
            clearTimeout(courseSearchTimeout);
        }
        
        // 3 karakterden az ise sonuçları gösterme
        if (searchTerm.length < MIN_CHARS) {
            courseSearchResults.innerHTML = '';
            courseSearchResults.classList.add('hidden');
            return;
        }
        
        // Aramayı geciktir
        courseSearchTimeout = setTimeout(function() {
            // AJAX isteği gönder
            fetch(`/admin/sms/search-courses?query=${encodeURIComponent(searchTerm)}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
            })
                .then(response => response.json())
                .then(data => {
                    // Sonuçları temizle
                    courseSearchResults.innerHTML = '';
                    
                    const courses = data.courses || [];
                    
                    if (courses.length === 0) {
                        const noResultItem = document.createElement('div');
                        noResultItem.className = 'px-4 py-2 text-sm text-gray-700';
                        noResultItem.textContent = 'Sonuç bulunamadı';
                        courseSearchResults.appendChild(noResultItem);
                    } else {
                        courses.forEach(course => {
                            const resultItem = document.createElement('div');
                            resultItem.className = 'px-4 py-2 text-sm text-gray-700 hover:bg-blue-100 cursor-pointer';
                            resultItem.textContent = course.name;
                            
                            resultItem.addEventListener('click', function() {
                                selectedCourseIdInput.value = course.id;
                                courseSearchInput.value = course.name;
                                selectedCourseInfo.textContent = `Seçilen: ${course.name}`;
                                courseSearchResults.classList.add('hidden');
                            });
                            
                            courseSearchResults.appendChild(resultItem);
                        });
                    }
                    
                    courseSearchResults.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Kurs arama hatası:', error);
                    courseSearchResults.innerHTML = '<div class="px-4 py-2 text-sm text-red-700">Arama sırasında bir hata oluştu</div>';
                    courseSearchResults.classList.remove('hidden');
                });
        }, DEBOUNCE_TIME);
    });
    
    // Dışarı tıklandığında sonuç listelerini kapat
    document.addEventListener('click', function(e) {
        if (userSearchInput && userSearchResults && !userSearchInput.contains(e.target) && !userSearchResults.contains(e.target)) {
            userSearchResults.classList.add('hidden');
        }
        
        if (courseSearchInput && courseSearchResults && !courseSearchInput.contains(e.target) && !courseSearchResults.contains(e.target)) {
            courseSearchResults.classList.add('hidden');
        }
    });
    
    // SMS karakter sayacı
    const individualMessage = document.getElementById('individual_message');
    const bulkMessage = document.getElementById('bulk_message');
    const individualCharCount = document.getElementById('individual_char_count');
    const bulkCharCount = document.getElementById('bulk_char_count');
    
    if (individualMessage && individualCharCount) {
        individualMessage.addEventListener('input', function() {
            individualCharCount.textContent = this.value.length;
        });
    }
    
    if (bulkMessage && bulkCharCount) {
        bulkMessage.addEventListener('input', function() {
            bulkCharCount.textContent = this.value.length;
        });
    }
    
    // Kurs seçim alanını göster/gizle
    const targetGroup = document.getElementById('target_group');
    const courseSelectDiv = document.getElementById('course_select_div');
    
    if (targetGroup && courseSelectDiv) {
        targetGroup.addEventListener('change', function() {
            if (this.value === 'course_students') {
                courseSelectDiv.classList.remove('hidden');
            } else {
                courseSelectDiv.classList.add('hidden');
            }
        });
    }
});
</script>
@endsection

@endsection