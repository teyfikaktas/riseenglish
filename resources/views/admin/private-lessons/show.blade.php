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
                <a href="{{ route('admin.private-lessons.teachers') }}" class="font-medium text-gray-600 hover:text-blue-600">
                    <i class="fa fa-users mr-1"></i> Öğretmenler
                </a>
                <a href="{{ route('admin.private-lessons.students') }}" class="font-medium text-gray-600 hover:text-blue-600">
                    <i class="fa fa-graduation-cap mr-1"></i> Öğrenciler
                </a>
                <a href="{{ route('admin.private-lessons.reports') }}" class="font-medium text-gray-600 hover:text-blue-600">
                    <i class="fa fa-chart-bar mr-1"></i> Raporlar
                </a>
            </div>
        </div>

        <div class="p-4">
            @livewire('private-lesson-calendar')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Ek JavaScript ihtiyacı olursa buraya eklenebilir
</script>
@endpush