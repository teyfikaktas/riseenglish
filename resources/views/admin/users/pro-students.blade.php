@extends('layouts.app')

@section('title', 'Pro Öğrenciler')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Pro Öğrenciler</h1>
            <p class="text-gray-600">Grup bazlı Pro öğrenci raporu alın</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            ← Geri Dön
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Grup Seç</h2>

        <div class="space-y-3 mb-6">
            @forelse($groups as $group)
                <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition">
                    <input type="radio" name="group_id" value="{{ $group->id }}" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                    <div class="ml-3 flex-1">
                        <div class="font-semibold text-gray-800">{{ $group->name }}</div>
                        <div class="text-sm text-gray-500">{{ $group->students->count() }} öğrenci</div>
                        @if($group->teacher)
                            <div class="text-xs text-gray-400">Öğretmen: {{ $group->teacher->name }}</div>
                        @endif
                    </div>
                    @php
                        $proCount = $group->students->filter(fn($s) => $s->activeMembership)->count();
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                        {{ $proCount }} Pro
                    </span>
                </label>
            @empty
                <div class="text-center text-gray-500 py-8">
                    Aktif grup bulunamadı.
                </div>
            @endforelse
        </div>

        @if($groups->isNotEmpty())
            <button type="button" id="reportBtn" onclick="getReport()" class="inline-flex items-center px-6 py-3 bg-purple-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-purple-700 disabled:opacity-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                PDF İndir
            </button>
        @endif
    </div>
</div>

<script>
function getReport() {
    const selected = document.querySelector('input[name="group_id"]:checked');
    if (!selected) {
        alert('Lütfen bir grup seçin.');
        return;
    }
    window.location.href = '{{ url("admin/users/pro-students") }}/' + selected.value + '/pdf';
}
</script>
@endsection