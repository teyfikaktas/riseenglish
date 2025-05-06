{{-- resources/views/teacher/private-lessons/delete-session.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('ogretmen.private-lessons.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Derslerime Dön
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Dersi Sil</h3>
            </div>
        </div>

        <div class="px-4 py-5 sm:p-6">
            <!-- Uyarı Mesajı -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <p class="text-red-600 font-medium mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Bu işlem geri alınamaz!
                </p>
                <p class="text-gray-700">Silinen dersler ve ilgili tüm bilgiler kalıcı olarak silinecektir.</p>
            </div>

            <!-- Ders Bilgileri -->
            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 mb-6">
                <h4 class="text-lg font-bold text-gray-800 mb-2">{{ $session->privateLesson->name ?? 'Ders' }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Öğrenci</p>
                        <p class="font-medium">{{ $session->student->name ?? 'Öğrenci Atanmamış' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Öğretmen</p>
                        <p class="font-medium">{{ $session->teacher->name ?? 'Öğretmen Atanmamış' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tarih</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Saat</p>
                        <p class="font-medium">{{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Konum</p>
                        <p class="font-medium">{{ $session->location }}</p>
                    </div>
                </div>
            </div>

            @php
                $lessonDate = \Carbon\Carbon::parse($session->start_date);
                $currentDate = \Carbon\Carbon::now('Europe/Istanbul')->startOfDay();
                $isPastLesson = $lessonDate->lt($currentDate);
            @endphp

            @if($isPastLesson)
                <!-- Geçmiş Ders Uyarısı -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <p class="text-yellow-700 font-medium flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Geçmiş dersler silinemez!
                    </p>
                    <p class="text-gray-600 mt-1">Bu ders geçmiş tarihli olduğu için silinemiyor.</p>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('ogretmen.private-lessons.index') }}" 
                        class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Derslerime Dön
                    </a>
                </div>
            @else
                <!-- Silme Formu -->
                <form action="{{ route('ogretmen.private-lessons.session.destroy', $session->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="space-y-4 mb-6">
                        <p class="text-gray-700 font-medium">Silme kapsamını seçin:</p>
                        
                        <div class="flex items-start space-x-2 bg-white p-4 rounded-lg border border-gray-200 transition-all duration-200 hover:bg-gray-50">
                            <input type="radio" id="this_only" name="delete_scope" value="this_only" class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 mt-0.5" checked>
                            <div class="flex-1">
                                <label for="this_only" class="text-gray-800 font-medium block mb-1 cursor-pointer">Sadece bu dersi sil</label>
                                <p class="text-gray-600 text-sm">Bu seçenek yalnızca şu an seçili olan dersi silecektir.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-2 bg-white p-4 rounded-lg border border-gray-200 transition-all duration-200 hover:bg-gray-50">
                            <input type="radio" id="all_future" name="delete_scope" value="all_future" class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 mt-0.5">
                            <div class="flex-1">
                                <label for="all_future" class="text-gray-800 font-medium block mb-1 cursor-pointer">Bu ve gelecekteki dersleri sil</label>
                                <p class="text-gray-600 text-sm">Bu seçenek şu an seçili olan ders ve bu tarihten sonraki aynı saat ve dakikadaki gelecek dersleri silecektir.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('ogretmen.private-lessons.index') }}" 
                            class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            İptal
                        </a>
                        <button type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Dersi Sil
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the form element
        const deleteForm = document.querySelector('form[action*="destroy"]');
        
        if (deleteForm) {
            // Add submit event listener
            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get the selected scope
                const scope = document.querySelector('input[name="delete_scope"]:checked').value;
                
                // Confirmation message based on scope
                let message = '';
                if (scope === 'this_only') {
                    message = 'Bu dersi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.';
                } else if (scope === 'all_future') {
                    message = 'Bu ve gelecekteki aynı saatteki tüm dersleri silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.';
                }
                
                // Show confirmation
                if (confirm(message)) {
                    // If confirmed, submit the form
                    this.submit();
                }
            });
            
            // Add event listeners to the radio options for better UX
            const radioInputs = document.querySelectorAll('input[name="delete_scope"]');
            radioInputs.forEach(function(radio) {
                // Add click event to parent div
                radio.closest('div.flex.items-start').addEventListener('click', function() {
                    radio.checked = true;
                });
            });
        }
    });
</script>
@endsection