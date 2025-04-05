@extends('layouts.admin')

@section('title', 'Özel Ders Yönetimi')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Özel Ders Yönetimi</h1>
        <div>
            <a href="{{ route('admin.private-lessons.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                <i class="fa fa-plus mr-1"></i> Yeni Özel Ders Ekle
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 bg-gray-50 border-b">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.private-lessons.index') }}" class="font-medium text-blue-600 hover:underline">
                    <i class="fa fa-calendar mr-1"></i> Takvim
                </a>
                <a href="{{ route('admin.private-lessons.lessons') }}" class="font-medium text-gray-600 hover:text-blue-600">
                    <i class="fa fa-book mr-1"></i> Dersler
                </a>
                <a href="{{ route('admin.private-lessons.reports') }}" class="font-medium text-gray-600 hover:text-blue-600">
                    <i class="fa fa-chart-bar mr-1"></i> Raporlar
                </a>
            </div>
        </div> Raporlar
                </a>
            </div>
        </div>

        <!-- Dashboard İstatistikleri -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-400 bg-opacity-30">
                        <i class="fa fa-book text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium">Toplam Ders</h2>
                        <p class="text-2xl font-bold">{{ $totalLessons ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-400 bg-opacity-30">
                        <i class="fa fa-users text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium">Toplam Öğretmen</h2>
                        <p class="text-2xl font-bold">{{ $totalTeachers ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-400 bg-opacity-30">
                        <i class="fa fa-graduation-cap text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium">Toplam Öğrenci</h2>
                        <p class="text-2xl font-bold">{{ $totalStudents ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-400 bg-opacity-30">
                        <i class="fa fa-clock text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium">Bu Haftaki Dersler</h2>
                        <p class="text-2xl font-bold">{{ $weeklyLessons ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Yaklaşan Dersler -->
        <div class="p-6 border-t">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Yaklaşan Dersler</h2>
            
            @if(isset($upcomingLessons) && count($upcomingLessons) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ders</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Öğretmen</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Öğrenci</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saat</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($upcomingLessons as $lesson)
                                <tr>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $lesson->privateLesson->name ?? 'Belirtilmemiş' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $lesson->teacher->name ?? 'Belirtilmemiş' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $lesson->student->name ?? 'Belirtilmemiş' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $lesson->lesson_date ? date('d.m.Y', strtotime($lesson->lesson_date)) : 'Belirtilmemiş' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">{{ $lesson->start_time ? date('H:i', strtotime($lesson->start_time)) : '--' }} - {{ $lesson->end_time ? date('H:i', strtotime($lesson->end_time)) : '--' }}</td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        @if($lesson->status == 'scheduled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Planlandı</span>
                                        @elseif($lesson->status == 'cancelled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">İptal Edildi</span>
                                        @elseif($lesson->status == 'completed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Tamamlandı</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $lesson->status }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.private-lessons.occurrence.edit', $lesson->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Düzenle</a>
                                        <a href="{{ route('admin.private-lessons.occurrence.show', $lesson->id) }}" class="text-green-600 hover:text-green-900">Detay</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-gray-50 p-4 rounded-md text-center">
                    <p class="text-gray-600">Yaklaşan ders bulunmamaktadır.</p>
                </div>
            @endif

            <div class="mt-4 text-right">
                <a href="{{ route('admin.private-lessons.occurrences') }}" class="text-blue-600 hover:underline">Tüm dersleri görüntüle &rarr;</a>
            </div>
        </div>

        <!-- Son Öğretmen Aktiviteleri -->
        <div class="p-6 border-t">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Son Öğretmen Aktiviteleri</h2>
            
            @if(isset($recentTeacherActivities) && count($recentTeacherActivities) > 0)
                <div class="space-y-4">
                    @foreach($recentTeacherActivities as $activity)
                        <div class="bg-gray-50 p-4 rounded-md">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($activity->teacher->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity->teacher->name ?? 'Belirtilmemiş' }}</p>
                                    <p class="text-sm text-gray-700">{{ $activity->description ?? 'Aktivite açıklaması bulunmamaktadır.' }}</p>
                                    <p class="mt-1 text-xs text-gray-500">{{ $activity->created_at ? $activity->created_at->diffForHumans() : '--' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 p-4 rounded-md text-center">
                    <p class="text-gray-600">Henüz öğretmen aktivitesi bulunmamaktadır.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Gerekli JavaScript kodları buraya eklenebilir
</script>
@endpush