@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Geri Butonu ve Başlık -->
    <div class="flex items-center mb-6">
        <a href="{{ route('ogrenci.private-lessons.index') }}" class="mr-4 text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Ders Materyalleri</h1>
            <p class="text-gray-600">Öğretmenlerinizin paylaştığı tüm ders materyalleri</p>
        </div>
    </div>

    <!-- Filtre ve Arama -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="{{ route('ogrenci.private-lessons.materials') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Arama</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Materyal adı ara..." class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="w-full md:w-1/4">
                <label for="lesson_id" class="block text-sm font-medium text-gray-700 mb-1">Ders</label>
                <select name="lesson_id" id="lesson_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tüm Dersler</option>
                    @foreach($lessonList ?? [] as $id => $name)
                        <option value="{{ $id }}" {{ request('lesson_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Filtrele
                </button>
            </div>
        </form>
    </div>

    <!-- Materyal Listesi -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="border-b px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-800">Tüm Materyaller</h2>
        </div>
        <div class="p-6">
            @if($materials->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($materials as $material)
                        <div class="border rounded-lg overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b">
                                <h3 class="font-semibold text-gray-800">{{ $material->title }}</h3>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($material->created_at)->format('d.m.Y') }}</p>
                            </div>
                            <div class="p-4">
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-1">Ders</p>
                                    <p class="font-medium text-gray-800">{{ $material->session->privateLesson->name ?? 'Belirtilmemiş' }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-1">Öğretmen</p>
                                    <p class="font-medium text-gray-800">{{ $material->session->teacher->name ?? 'Belirtilmemiş' }}</p>
                                </div>
                                @if($material->description)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-1">Açıklama</p>
                                    <p class="text-sm text-gray-800">{{ \Illuminate\Support\Str::limit($material->description, 100) }}</p>
                                </div>
                                @endif
                                <div class="flex justify-end">
                                    <a href="{{ route('ogrenci.private-lessons.material.download', $material->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        İndir
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $materials->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <p class="text-gray-600 text-lg font-medium">Ders materyali bulunamadı.</p>
                    <p class="text-gray-500 mt-2">Filtreleri değiştirerek tekrar aramayı deneyebilirsiniz.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection