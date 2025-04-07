@extends('layouts.app')

@section('content')
@if(session('error'))
    <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-md">
        {{ session('success') }}
    </div>
@endif

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ $lesson->name }} - Düzenle</h1>
        <a href="{{ route('ogretmen.private-lessons.showLesson', $lesson->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Geri</a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('ogretmen.private-lessons.updateLesson', $lesson->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Ders Adı -->
                <div>
                    <label for="lesson_name" class="block text-sm font-medium text-gray-700 mb-1">Ders Adı</label>
                    <input type="text" name="lesson_name" id="lesson_name" value="{{ old('lesson_name', $lesson->name) }}" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                </div>

                <!-- Öğrenci -->
                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Öğrenci</label>
                    <select name="student_id" id="student_id" class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                        <option value="">Öğrenci Seçin</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id', $session->student_id) == $student->id ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Ücret -->
                <div>
                    <label for="fee" class="block text-sm font-medium text-gray-700 mb-1">Ders Ücreti</label>
                    <input type="number" name="fee" id="fee" value="{{ old('fee', $lesson->price) }}" step="0.01" min="0" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                </div>

                <!-- Lokasyon -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Ders Lokasyonu</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $session->location) }}" class="form-input rounded-md shadow-sm mt-1 block w-full">
                </div>

                <!-- Durum -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Durum</label>
                    <select name="status" id="status" class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                        <option value="approved" {{ old('status', $session->status) == 'approved' ? 'selected' : '' }}>Onaylandı</option>
                        <option value="cancelled" {{ old('status', $session->status) == 'cancelled' ? 'selected' : '' }}>İptal Edildi</option>
                    </select>
                </div>

                <!-- Ders Saati Güncelleme -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ders Saati Güncelleme</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                        <select name="day_of_week" id="day_of_week" class="form-select rounded-md shadow-sm mt-1 block w-full">
                            <option value="" {{ old('day_of_week') === null ? 'selected' : '' }}>Gün Değiştirme</option>
                            @foreach(['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'] as $index => $day)
                                <option value="{{ $index }}" {{ old('day_of_week') == $index ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>

                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" class="form-input rounded-md shadow-sm mt-1 block w-full">

                        <button type="button" id="current_time_button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-2 py-2 w-full rounded text-sm">
                            Mevcut Saati Kullan
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Gün veya saati değiştirmek istemiyorsanız boş bırakın.</p>
                </div>

                <!-- Güncelleme Seçenekleri -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Güncelleme Seçenekleri</label>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="hidden" name="skip_past_sessions" value="0">
                            <input type="checkbox" name="skip_past_sessions" id="skip_past_sessions" value="1" class="form-checkbox h-5 w-5 text-blue-600" {{ old('skip_past_sessions', 1) ? 'checked' : '' }}>
                            <label for="skip_past_sessions" class="ml-2 text-sm text-gray-700">
                                Geçmiş dersleri güncelleme (sadece gelecek seansları güncelle)
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="update_all_times" value="0">
                            <input type="checkbox" name="update_all_times" id="update_all_times" value="1" class="form-checkbox h-5 w-5 text-blue-600" {{ old('update_all_times') ? 'checked' : '' }}>
                            <label for="update_all_times" class="ml-2 text-sm text-gray-700">
                                Tüm gelecek seansların gün ve saatlerini güncelle
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Notlar -->
                <div class="col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notlar</label>
                    <textarea name="notes" id="notes" rows="3" class="form-textarea rounded-md shadow-sm w-full">{{ old('notes', $session->notes) }}</textarea>
                </div>

                <!-- Güncelleme Butonu -->
                <div class="col-span-2 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                        Dersi Güncelle
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('current_time_button').addEventListener('click', function(){
        document.getElementById('start_time').value = '{{ $session->start_time ?? '09:00' }}';
    });
</script>
@endsection
