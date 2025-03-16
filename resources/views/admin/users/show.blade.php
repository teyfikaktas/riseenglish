<!-- resources/views/admin/users/show.blade.php -->
@extends('layouts.app')

@section('title', 'Kullanıcı Detayı')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Kullanıcı Detayları</h1>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Düzenle
                </a>
                <a href="{{ route('admin.users.manageCourses', $user) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Kursları Yönet
                </a>
            </div>
        </div>
    </div>

    <!-- Kullanıcı Profil Bilgileri -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-800">Profil Bilgileri</h2>
        </div>
        <div class="p-6">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/3 flex justify-center mb-6 md:mb-0">
                    <div class="h-40 w-40 rounded-full bg-blue-500 flex items-center justify-center text-white text-4xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
                <div class="md:w-2/3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Ad Soyad</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Email</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Telefon</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $user->phone ?? 'Belirtilmemiş' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Kayıt Tarihi</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $user->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                        <div>
    <h3 class="text-sm font-medium text-gray-500">Roller</h3>
    <div class="mt-1">
        @foreach($user->getRoleNames() as $roleName)
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                {{ ucfirst($roleName) }}
            </span>
        @endforeach
    </div>
</div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Son Güncelleme</h3>
                            <p class="mt-1 text-lg text-gray-900">{{ $user->updated_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kayıtlı Kurslar -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Kayıtlı Kurslar</h2>
            <a href="{{ route('admin.users.manageCourses', $user) }}" class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Kurs Ekle
            </a>
        </div>
        <div class="p-6">
            @if($enrolledCourses->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kurs</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eğitmen</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($enrolledCourses as $course)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($course->thumbnail)
                                                <img class="h-10 w-10 rounded object-cover" src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded bg-green-500 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($course->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $course->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $course->level->name ?? 'Belirtilmemiş' }} · {{ $course->type->name ?? 'Belirtilmemiş' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $course->teacher->name ?? 'Belirtilmemiş' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <div class="text-xs text-gray-500">
                                            <span class="block">Kayıt: {{ $course->pivot->enrollment_date ? date('d.m.Y', strtotime($course->pivot->enrollment_date)) : '-' }}</span>
                                            @if($course->pivot->completion_date)
                                                <span class="block">Bitiş: {{ date('d.m.Y', strtotime($course->pivot->completion_date)) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            1 => 'bg-yellow-100 text-yellow-800', // Beklemede
                                            2 => 'bg-blue-100 text-blue-800',    // Devam Ediyor
                                            3 => 'bg-green-100 text-green-800',  // Tamamlandı
                                            4 => 'bg-red-100 text-red-800'       // İptal Edildi
                                        ];
                                        $statusName = [
                                            1 => 'Beklemede',
                                            2 => 'Devam Ediyor',
                                            3 => 'Tamamlandı',
                                            4 => 'İptal Edildi'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$course->pivot->status_id] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusName[$course->pivot->status_id] ?? 'Belirsiz' }}
                                    </span>
                                    
                                    <div class="mt-1">
                                        @if($course->pivot->payment_completed)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Ödeme Tamam
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Ödeme Eksik
                                            </span>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $enrolledCourses->links() }}
                </div>
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">Bu kullanıcı henüz hiçbir kursa kayıtlı değil.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Eğer Öğretmen ise Verdiği Kurslar -->
    @if($user->hasRole('ogretmen'))
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-800">Verdiği Kurslar</h2>
        </div>
        <div class="p-6">
            @if(isset($taughtCourses) && $taughtCourses->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kurs</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detaylar</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kayıt/Kapasite</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($taughtCourses as $course)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($course->thumbnail)
                                                <img class="h-10 w-10 rounded object-cover" src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded bg-green-500 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($course->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $course->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $course->level->name ?? 'Belirtilmemiş' }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $course->type->name ?? 'Belirtilmemiş' }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if($course->frequency)
                                        <span>{{ $course->frequency->name }}</span>
                                        @endif
                                        @if($course->total_hours)
                                        <span> · {{ $course->total_hours }} saat</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    <div>Başlangıç: {{ $course->start_date ? date('d.m.Y', strtotime($course->start_date)) : '-' }}</div>
                                    <div>Bitiş: {{ $course->end_date ? date('d.m.Y', strtotime($course->end_date)) : '-' }}</div>
                                    <div class="mt-1">
                                        {{ $course->start_time ? date('H:i', strtotime($course->start_time)) : '-' }} - 
                                        {{ $course->end_time ? date('H:i', strtotime($course->end_time)) : '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                    <span class="font-medium">{{ $course->students_count ?? 0 }}</span> / 
                                    <span>{{ $course->max_students ?? '∞' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($course->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Pasif
                                        </span>
                                    @endif
                                    
                                    <div class="mt-1">
                                        @if($course->is_featured)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Öne Çıkan
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.courses.show', $course) }}" class="text-blue-600 hover:text-blue-900" title="Detay">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(isset($taughtCourses) && method_exists($taughtCourses, 'links'))
                <div class="mt-4">
                    {{ $taughtCourses->links() }}
                </div>
                @endif
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">Bu öğretmen henüz kurs vermemiş.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif


</div>
@endsection

@section('scripts')
<script>

    
    function closeCourseModal() {
        document.getElementById('courseModal').classList.add('hidden');
    }
    
    function confirmUnenroll(courseId, courseName) {
        if (confirm(`"${courseName}" kursundan kullanıcı kaydını silmek istediğinizden emin misiniz?`)) {
            document.getElementById(`unenroll-form-${courseId}`).submit();
        }
    }
    
    // ESC tuşu ile modal'ı kapat
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeCourseModal();
        }
    });
</script>
@endsection