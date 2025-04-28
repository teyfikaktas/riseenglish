@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Verdiğim Ödevler</h1>
    
    <!-- Filtreleme ve Görünüm Kontrolü -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <!-- Sol taraf: Filtreleme seçenekleri -->
            <div class="flex flex-wrap items-center gap-4">
                <div>
                    <label for="filter-date" class="block text-sm font-medium text-gray-700 mb-1">Tarihe Göre</label>
                    <select id="filter-date" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="all">Tüm Tarihler</option>
                        <option value="recent">Son 7 Gün</option>
                        <option value="month">Bu Ay</option>
                        <option value="overdue">Süresi Geçenler</option>
                    </select>
                </div>
                
                <div>
                    <label for="filter-student" class="block text-sm font-medium text-gray-700 mb-1">Öğrenciye Göre</label>
                    <select id="filter-student" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="all">Tüm Öğrenciler</option>
                        @php
                            $students = $homeworks->pluck('session.student.name')->unique()->sort()->values();
                        @endphp
                        @foreach($students as $student)
                            <option value="{{ $student }}">{{ $student }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Sağ taraf: Görünüm Kontrolü -->
            <div>
                <button id="toggle-graded" class="flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <span class="relative inline-block w-10 mr-2 align-middle select-none">
                        <input type="checkbox" id="show-graded" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
                        <label for="show-graded" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                    </span>
                    <span>Değerlendirdiğim Ödevleri Göster</span>
                </button>
            </div>
        </div>
    </div>

    @if($homeworks->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md text-yellow-800">
            Henüz eklenmiş ödev yok.
        </div>
    @else
        <!-- Tarih Bazlı Gruplama -->
        @php
            $groupedHomeworks = $homeworks->groupBy(function($homework) {
                return \Carbon\Carbon::parse($homework->due_date)->format('Y-m');
            })->sortKeysDesc();
        @endphp
        
        <div id="homeworks-container" class="space-y-8">
            @foreach($groupedHomeworks as $yearMonth => $homeworkGroup)
                <div class="homework-month-group">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $yearMonth)->format('F Y') }}
                    </h2>
                    
                    <!-- Öğrenci Bazlı Alt Gruplama -->
                    @php
                        $studentGrouped = $homeworkGroup->groupBy(function($homework) {
                            return $homework->session->student->name;
                        })->sortKeys();
                    @endphp
                    
                    <div class="space-y-6">
                        @foreach($studentGrouped as $studentName => $studentHomeworks)
                            <div class="homework-student-group">
                                <h3 class="text-lg font-medium text-gray-600 mb-3 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $studentName }}
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($studentHomeworks as $hw)
                                        @php
                                            $lessonName = $hw->session->privateLesson->name;
                                            $dueDate = \Carbon\Carbon::parse($hw->due_date)->format('d.m.Y');
                                            $submissionCount = $hw->submissions->count();
                                            $hasGradedSubmissions = $hw->submissions->contains(function($submission) {
                                                return !is_null($submission->score);
                                            });
                                            $isOverdue = \Carbon\Carbon::parse($hw->due_date)->isPast() && $submissionCount === 0;
                                        @endphp
                                        
                                        <div class="homework-card bg-white rounded-lg shadow-md overflow-hidden border-t-4 
                                                    hover:shadow-lg transition-shadow {{ $hasGradedSubmissions ? 'graded-homework' : '' }}"
                                             data-student="{{ $studentName }}"
                                             data-date="{{ $hw->due_date }}"
                                             data-graded="{{ $hasGradedSubmissions ? 'true' : 'false' }}">
                                            
                                            <!-- Renk kodlaması -->
                                            <div class="border-t-4 -mt-1 {{ $isOverdue ? 'border-red-500' : ($hasGradedSubmissions ? 'border-green-500' : 'border-blue-500') }}"></div>
                                            
                                            <!-- Başlık ve durum -->
                                            <div class="px-5 py-4 border-b flex justify-between items-center">
                                                <h4 class="text-lg font-semibold text-gray-800 truncate">{{ $hw->title }}</h4>
                                                
                                                @if($hasGradedSubmissions)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Değerlendirildi
                                                    </span>
                                                @elseif($submissionCount > 0)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Değerlendirilecek
                                                    </span>
                                                @elseif($isOverdue)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Süresi Geçti
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Bekliyor
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <!-- İçerik -->
                                            <div class="p-5 space-y-3">
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">Ders:</span> {{ $lessonName }}
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">Son Teslim:</span> {{ $dueDate }}
                                                </p>
                                                <div class="flex justify-between items-center pt-2">
                                                    <div class="text-sm 
                                                        {{ $submissionCount > 0 ? 'text-green-600' : 'text-gray-500' }}">
                                                        <span class="font-medium">Teslimler:</span> {{ $submissionCount }}
                                                    </div>
                                                    
                                                    @if($hw->file_path)
                                                    <a href="{{ route('ogretmen.private-lessons.homework.download', $hw->id) }}" 
                                                       class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                        Ek Dosya
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Aksiyon Butonları -->
                                            <div class="px-5 py-4 bg-gray-50 flex justify-between">
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('ogretmen.private-lessons.session.show', $hw->session_id) }}" 
                                                       class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Ders Detayı
                                                    </a>
                                                    
                                                    <button 
                                                        onclick="confirmDelete({{ $hw->id }})" 
                                                        class="text-sm text-red-600 hover:text-red-800 flex items-center ml-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Sil
                                                    </button>
                                                </div>
                                                
                                                <a href="{{ route('ogretmen.private-lessons.homework.submissions', $hw->id) }}"
                                                   class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm font-medium
                                                          rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Teslimleri Gör
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteHomeworkModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Ödevi Silmek İstediğinize Emin Misiniz?</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Bu işlem geri alınamaz. Tüm ödev verileri ve bu ödeve ait tüm teslimler de silinecektir.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteHomeworkForm" method="POST" class="mt-3 text-center space-x-4">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="homework_id" id="homework_id_to_delete">
                    <button id="cancelDeleteBtn" type="button" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        İptal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Sil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.toggle-checkbox:checked {
    right: 0;
    border-color: #3B82F6;
}
.toggle-checkbox:checked + .toggle-label {
    background-color: #3B82F6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // İlk yüklemede değerlendirilmiş ödevleri gizle
    const showGradedCheckbox = document.getElementById('show-graded');
    const filterDate = document.getElementById('filter-date');
    const filterStudent = document.getElementById('filter-student');
    const homeworkCards = document.querySelectorAll('.homework-card');
    
    // Sayfa ilk yüklendiğinde değerlendirilmiş ödevleri gizle
    showGradedCheckbox.checked = false;
    updateVisibility();
    
    // Değerlendirilmiş ödevleri göster/gizle
    showGradedCheckbox.addEventListener('change', updateVisibility);
    
    // Filtreleme değişikliklerini izle
    filterDate.addEventListener('change', updateVisibility);
    filterStudent.addEventListener('change', updateVisibility);
    
    // Ödev silme modalı için fonksiyon
    window.confirmDelete = function(homeworkId) {
        // Set the homework ID in the form
        document.getElementById('homework_id_to_delete').value = homeworkId;
        
        // Show the modal
        document.getElementById('deleteHomeworkModal').classList.remove('hidden');
        
        // Set the form action dynamically
        document.getElementById('deleteHomeworkForm').action = `/ogretmen/ozel-ders-odev/${homeworkId}`;
    };
    
    // Close the modal when the cancel button is clicked
    document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
        document.getElementById('deleteHomeworkModal').classList.add('hidden');
    });
    
    // Also close the modal if the user clicks outside of it
    window.addEventListener('click', function(event) {
        var modal = document.getElementById('deleteHomeworkModal');
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
    
    function updateVisibility() {
        const showGraded = showGradedCheckbox.checked;
        const dateFilter = filterDate.value;
        const studentFilter = filterStudent.value;
        
        homeworkCards.forEach(card => {
            let shouldShow = true;
            
            // Değerlendirme durumu filtrelemesi
            if (!showGraded && card.getAttribute('data-graded') === 'true') {
                shouldShow = false;
            }
            
            // Öğrenci filtrelemesi
            if (studentFilter !== 'all' && card.getAttribute('data-student') !== studentFilter) {
                shouldShow = false;
            }
            
            // Tarih filtrelemesi
            if (dateFilter !== 'all') {
                const dueDate = new Date(card.getAttribute('data-date'));
                const today = new Date();
                
                if (dateFilter === 'recent') {
                    // Son 7 gün
                    const weekAgo = new Date();
                    weekAgo.setDate(today.getDate() - 7);
                    if (dueDate < weekAgo) shouldShow = false;
                } 
                else if (dateFilter === 'month') {
                    // Bu ay
                    if (dueDate.getMonth() !== today.getMonth() || 
                        dueDate.getFullYear() !== today.getFullYear()) {
                        shouldShow = false;
                    }
                }
                else if (dateFilter === 'overdue') {
                    // Süresi geçenler
                    if (dueDate > today || card.querySelector('.text-green-600')?.textContent.includes('Teslimler: 0') === false) {
                        shouldShow = false;
                    }
                }
            }
            
            // Görünürlüğü ayarla
            card.closest('.homework-card').style.display = shouldShow ? 'block' : 'none';
            
            // Öğrenci grubu içinde görünür ödev kalmadıysa grubu da gizle
            const studentGroups = document.querySelectorAll('.homework-student-group');
            studentGroups.forEach(group => {
                const visibleCards = group.querySelectorAll('.homework-card[style="display: block;"]');
                group.style.display = visibleCards.length > 0 ? 'block' : 'none';
            });
            
            // Ay grubu içinde görünür öğrenci grubu kalmadıysa ay grubunu da gizle
            const monthGroups = document.querySelectorAll('.homework-month-group');
            monthGroups.forEach(group => {
                const visibleStudentGroups = group.querySelectorAll('.homework-student-group[style="display: block;"]');
                group.style.display = visibleStudentGroups.length > 0 ? 'block' : 'none';
            });
        });
        
        // Hiç görünür kart yoksa "Sonuç bulunamadı" mesajı göster
        const visibleCards = document.querySelectorAll('.homework-card[style="display: block;"]');
        const container = document.getElementById('homeworks-container');
        
        if (visibleCards.length === 0 && document.querySelectorAll('.homework-card').length > 0) {
            if (!document.getElementById('no-results-message')) {
                const noResults = document.createElement('div');
                noResults.id = 'no-results-message';
                noResults.className = 'bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md text-yellow-800 mt-4';
                noResults.textContent = 'Seçilen kriterlere uygun ödev bulunamadı.';
                container.appendChild(noResults);
            }
        } else {
            const noResults = document.getElementById('no-results-message');
            if (noResults) noResults.remove();
        }
    }
});
</script>
@endsection