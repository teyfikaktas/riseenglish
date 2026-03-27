@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#1a2e5a]">Grup Günlük Raporu</h1>
            <p class="text-gray-600 mt-2">Grup bazlı günlük sınav raporlarını görüntüleyin</p>
        </div>

        @if(isset($groups) && count($groups) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($groups as $group)
                    <div class="bg-white rounded-xl shadow-lg p-5 border-2 border-gray-200 hover:border-[#1a2e5a] transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-bold text-lg text-[#1a2e5a]">{{ $group->name }}</span>
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{{ $group->students_count }} öğrenci</span>
                        </div>

                        @if($group->teacher)
                            <p class="text-sm text-gray-500 mb-1">Öğretmen: {{ $group->teacher->name }}</p>
                        @endif

                        @if($group->description)
                            <p class="text-sm text-gray-400 mb-4">{{ Str::limit($group->description, 80) }}</p>
                        @else
                            <div class="mb-4"></div>
                        @endif

                        <a href="#" 
                           class="block w-full text-center bg-[#e63946] hover:bg-red-600 text-white px-4 py-2.5 rounded-lg font-semibold transition-colors">
                            📊 Rapor Al
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-8 text-center text-gray-500">
                <p>Aktif grup bulunamadı.</p>
            </div>
        @endif
    </div>
</div>
@endsection