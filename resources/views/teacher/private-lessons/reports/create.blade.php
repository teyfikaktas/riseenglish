@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <!-- Başlık ve Geri Butonu -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Ders Raporu Oluştur</h1>
            <a href="{{ route('ogretmen.private-lessons.session.show', $session->id) }}" class="flex items-center text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Ders Detaylarına Dön
            </a>
        </div>

        <!-- Ders Bilgileri -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Ders Bilgileri</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Ders:</p>
                    <p class="font-medium">{{ $session->privateLesson->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Tarih:</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Saat:</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Öğrenci:</p>
                    <p class="font-medium">{{ $session->student->name }}</p>
                </div>
            </div>
        </div>

        <!-- Rapor Formu -->
        <form action="{{ route('ogretmen.private-lessons.session.storeReport', $session->id) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
            @csrf

            <!-- Çözülen Sorular -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Çözülen Sorular</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="questions_solved" class="block text-sm font-medium text-gray-700 mb-1">Çözülen Soru Sayısı</label>
                        <input type="number" name="questions_solved" id="questions_solved" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('questions_solved', 0) }}" required>
                    </div>
                    <div>
                        <label for="questions_correct" class="block text-sm font-medium text-gray-700 mb-1">Doğru</label>
                        <input type="number" name="questions_correct" id="questions_correct" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('questions_correct', 0) }}" required>
                    </div>
                    <div>
                        <label for="questions_wrong" class="block text-sm font-medium text-gray-700 mb-1">Yanlış</label>
                        <input type="number" name="questions_wrong" id="questions_wrong" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('questions_wrong', 0) }}" required>
                    </div>
                    <div>
                        <label for="questions_unanswered" class="block text-sm font-medium text-gray-700 mb-1">Boş</label>
                        <input type="number" name="questions_unanswered" id="questions_unanswered" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('questions_unanswered', 0) }}" required>
                    </div>
                </div>
            </div>

            <!-- Çözülen Denemeler -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Çözülen Denemeler</h3>
                <div class="bg-gray-50 p-4 rounded-md mb-4">
                    <div id="exam-results-container">
                        <div class="exam-result-row mb-4 p-4 border border-gray-200 rounded-md bg-white">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ders Seçiniz</label>
                                    <select name="exam_subjects[]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Ders Seçiniz</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Doğru</label>
                                    <input type="number" name="exam_correct[]" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Yanlış</label>
                                    <input type="number" name="exam_wrong[]" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Boş</label>
                                    <input type="number" name="exam_unanswered[]" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="0">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" class="remove-exam-btn px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200 transition-colors" onclick="removeExamRow(this)">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-exam-btn" class="mt-2 px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200 transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Yeni Deneme Ekle
                    </button>
                </div>
            </div>

            <!-- Değerlendirme -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ders Değerlendirmesi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="pros" class="block text-sm font-medium text-gray-700 mb-1">Artıları</label>
                        <textarea name="pros" id="pros" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('pros') }}</textarea>
                    </div>
                    <div>
                        <label for="cons" class="block text-sm font-medium text-gray-700 mb-1">Eksileri</label>
                        <textarea name="cons" id="cons" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('cons') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Derse Katılım -->
            <div class="mb-6">
                <label for="participation" class="block text-sm font-medium text-gray-700 mb-1">Derse Katılım</label>
                <textarea name="participation" id="participation" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('participation') }}</textarea>
            </div>

            <!-- Öğretmen Notları -->
            <div class="mb-6">
                <label for="teacher_notes" class="block text-sm font-medium text-gray-700 mb-1">Öğretmen Notları</label>
                <textarea name="teacher_notes" id="teacher_notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('teacher_notes') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Bu notlar sadece öğretmen tarafından görülebilir.</p>
            </div>

            <!-- Butonlar -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('ogretmen.private-lessons.session.show', $session->id) }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    İptal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 border border-transparent rounded-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Raporu Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Maximum number of exam rows allowed
    const MAX_EXAM_ROWS = 5;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Add Exam Row button functionality
        const addExamBtn = document.getElementById('add-exam-btn');
        addExamBtn.addEventListener('click', function() {
            const container = document.getElementById('exam-results-container');
            const examRows = container.querySelectorAll('.exam-result-row');
            
            // Check if we've reached the maximum number of rows
            if (examRows.length >= MAX_EXAM_ROWS) {
                alert('En fazla 5 deneme ekleyebilirsiniz.');
                return;
            }
            
            // Clone the first row
            const firstRow = examRows[0];
            const newRow = firstRow.cloneNode(true);
            
            // Reset values in the new row
            newRow.querySelectorAll('input[type="number"]').forEach(input => {
                input.value = 0;
            });
            newRow.querySelector('select').selectedIndex = 0;
            
            // Add the new row
            container.appendChild(newRow);
            
            // Update UI if we've reached the limit
            if (examRows.length + 1 >= MAX_EXAM_ROWS) {
                addExamBtn.disabled = true;
                addExamBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        });
    });
    
    // Function to remove exam row
    function removeExamRow(button) {
        const container = document.getElementById('exam-results-container');
        const examRows = container.querySelectorAll('.exam-result-row');
        
        // Make sure we always have at least one row
        if (examRows.length <= 1) {
            alert('En az bir deneme satırı gereklidir.');
            return;
        }
        
        // Remove the row
        const row = button.closest('.exam-result-row');
        row.remove();
        
        // Re-enable the add button if we're under the limit
        const addExamBtn = document.getElementById('add-exam-btn');
        if (container.querySelectorAll('.exam-result-row').length < MAX_EXAM_ROWS) {
            addExamBtn.disabled = false;
            addExamBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
    
    // Auto-calculate unanswered questions
    document.addEventListener('input', function(e) {
        if (e.target.id === 'questions_solved' || e.target.id === 'questions_correct' || e.target.id === 'questions_wrong') {
            const solved = parseInt(document.getElementById('questions_solved').value) || 0;
            const correct = parseInt(document.getElementById('questions_correct').value) || 0;
            const wrong = parseInt(document.getElementById('questions_wrong').value) || 0;
            
            // Calculate unanswered and update the field
            const unanswered = Math.max(0, solved - correct - wrong);
            document.getElementById('questions_unanswered').value = unanswered;
        }
    });
</script>
@endsection