@extends('layouts.app')

@section('title', 'Kurs Düzenle')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kurs Düzenle: {{ $course->name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.courses.show', $course) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
                Görüntüle
            </a>
            <a href="{{ route('admin.courses.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kurslara Dön
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                <div class="font-bold">Hata!</div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Temel Bilgiler -->
                <div class="col-span-2 border-b pb-4 mb-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Temel Bilgiler</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Kurs Adı -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Kurs Adı <span class="text-red-600">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $course->name) }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        
                        <!-- Kurs Kodu -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Kurs Kodu <span class="text-red-600">*</span></label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $course->slug) }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Örn: ENG101, MATH202 vb.</p>
                        </div>
                        
                        <!-- Kurs Tipi -->
                        <div>
                            <label for="type_id" class="block text-sm font-medium text-gray-700 mb-1">Kurs Tipi <span class="text-red-600">*</span></label>
                            <select name="type_id" id="type_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seçiniz</option>
                                @foreach($courseTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('type_id', $course->type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Kategori Seçiniz</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- İndirimli Fiyat -->
                        <div class="mb-4">
                            <label for="discount_price" class="block text-sm font-medium text-gray-700">İndirimli Fiyat (TL)</label>
                            <input type="number" step="0.01" name="discount_price" id="discount_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('discount_price', $course->discount_price) }}">
                        </div>
                        
                        <!-- Görüntülenme Sırası -->
                        <div class="mb-4">
                            <label for="display_order" class="block text-sm font-medium text-gray-700">Görüntülenme Sırası</label>
                            <input type="number" name="display_order" id="display_order" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('display_order', $course->display_order) }}">
                            <p class="mt-1 text-sm text-gray-500">Sıralama değeri küçük olan kurslar önce gösterilir.</p>
                        </div>
                        
                        <!-- Öne Çıkarma -->
                        <div class="mb-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="is_featured" name="is_featured" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('is_featured', $course->is_featured) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_featured" class="font-medium text-gray-700">Ana Sayfada Göster</label>
                                    <p class="text-gray-500">İşaretlenirse kurs ana sayfada gösterilecektir.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Kurs Seviyesi -->
                        <div>
                            <label for="level_id" class="block text-sm font-medium text-gray-700 mb-1">Kurs Seviyesi <span class="text-red-600">*</span></label>
                            <select name="level_id" id="level_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seçiniz</option>
                                @foreach($courseLevels as $level)
                                    <option value="{{ $level->id }}" {{ old('level_id', $course->level_id) == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Öğretmen -->
                        <div>
                            <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-1">Öğretmen <span class="text-red-600">*</span></label>
                            <select name="teacher_id" id="teacher_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seçiniz</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $course->teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Fiyat -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Fiyat (₺)</label>
                            <input type="number" name="price" id="price" value="{{ old('price', $course->price) }}" step="0.01" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <!-- Kurs Detayları -->
                <div class="col-span-2 border-b pb-4 mb-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Kurs Detayları</h2>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Açıklama -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                            <textarea name="description" id="description" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('description', $course->description) }}</textarea>
                        </div>
                        
                        <!-- Hedefler -->
                        <div>
                            <label for="objectives" class="block text-sm font-medium text-gray-700 mb-1">Kurs Hedefleri</label>
                            <textarea name="objectives" id="objectives" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('objectives', $course->objectives) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Zaman Bilgileri -->
                <div class="col-span-2 border-b pb-4 mb-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Zaman Bilgileri</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Kurs Sıklığı -->
                        <div>
                            <label for="frequency_id" class="block text-sm font-medium text-gray-700 mb-1">Kurs Sıklığı</label>
                            <select name="frequency_id" id="frequency_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seçiniz</option>
                                @foreach($courseFrequencies as $frequency)
                                    <option value="{{ $frequency->id }}" {{ old('frequency_id', $course->frequency_id) == $frequency->id ? 'selected' : '' }}>{{ $frequency->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Toplam Saat -->
                        <div>
                            <label for="total_hours" class="block text-sm font-medium text-gray-700 mb-1">Toplam Saat</label>
                            <input type="number" name="total_hours" id="total_hours" value="{{ old('total_hours', $course->total_hours) }}" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        
                        <!-- Max Öğrenci -->
                        <div>
                            <label for="max_students" class="block text-sm font-medium text-gray-700 mb-1">Maksimum Öğrenci</label>
                            <input type="number" name="max_students" id="max_students" value="{{ old('max_students', $course->max_students) }}" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        
                        <!-- Başlangıç Tarihi -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Başlangıç Tarihi</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $course->start_date ? $course->start_date->format('Y-m-d') : '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        
                        <!-- Bitiş Tarihi -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Bitiş Tarihi</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $course->end_date ? $course->end_date->format('Y-m-d') : '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        
                        <!-- Başlangıç Saati -->
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Başlangıç Saati</label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $course->start_time ? $course->start_time->format('H:i') : '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        
                        <!-- Bitiş Saati -->
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Bitiş Saati</label>
                            <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $course->end_time ? $course->end_time->format('H:i') : '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <!-- Lokasyon Bilgileri -->
                <div class="col-span-2 border-b pb-4 mb-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Lokasyon Bilgileri</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Lokasyon -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Fiziksel Lokasyon</label>
                            <input type="text" name="location" id="location" value="{{ old('location', $course->location) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Sınıf numarası, bina adı vb.</p>
                        </div>
                        
                        <!-- Online Meeting Linki -->
                        <div>
                            <label for="meeting_link" class="block text-sm font-medium text-gray-700 mb-1">Online Toplantı Linki</label>
                            <input type="url" name="meeting_link" id="meeting_link" value="{{ old('meeting_link', $course->meeting_link) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        
                        <!-- Meeting Şifresi -->
                        <div>
                            <label for="meeting_password" class="block text-sm font-medium text-gray-700 mb-1">Toplantı Şifresi</label>
                            <input type="text" name="meeting_password" id="meeting_password" value="{{ old('meeting_password', $course->meeting_password) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <!-- Diğer Ayarlar -->
                <div class="col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Diğer Ayarlar</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Aktif/Pasif -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">Kurs Aktif</label>
                        </div>
                        
                        <!-- Sertifika -->
                        <div class="flex items-center">
                            <input type="checkbox" name="has_certificate" id="has_certificate" value="1" {{ old('has_certificate', $course->has_certificate) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="has_certificate" class="ml-2 block text-sm text-gray-700">Sertifika Verilecek</label>
                        </div>
                        
                        <!-- Küçük Resim -->
                        <div class="col-span-2">
                            <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Kurs Görseli</label>
                            
                            @if($course->thumbnail)
                                <div class="mb-2 flex items-center">
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}" class="h-20 w-20 object-cover rounded-md mr-4">
                                    <span class="text-sm text-gray-500">Mevcut görsel</span>
                                </div>
                            @endif
                            
                            <input type="file" name="thumbnail" id="thumbnail" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Önerilen boyut: 800x450px, maksimum dosya boyutu: 2MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="window.location='{{ route('admin.courses.index') }}'" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                    İptal
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                    Kursu Güncelle
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tarih kontrolü
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        
        endDateInput.addEventListener('change', function() {
            if (startDateInput.value) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                
                if (endDate < startDate) {
                    alert('Bitiş tarihi başlangıç tarihinden önce olamaz!');
                    endDateInput.value = '';
                }
            }
        });
        
        // Saat kontrolü
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        
        endTimeInput.addEventListener('change', function() {
            if (startTimeInput.value && endTimeInput.value) {
                const startTime = startTimeInput.value;
                const endTime = endTimeInput.value;
                
                if (endTime <= startTime) {
                    alert('Bitiş saati başlangıç saatinden sonra olmalıdır!');
                    endTimeInput.value = '';
                }
            }
        });
    });
</script>
@endsection