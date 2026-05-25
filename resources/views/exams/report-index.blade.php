@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#1a2e5a]">Hakan Hoca - Günlük Raporlar</h1>
            <p class="text-gray-600 mt-2">Günlük sınav raporlarını görüntüleyin</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Bugün --}}
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-gray-200 hover:border-[#1a2e5a] transition-all">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-2xl">📅</div>
                    <div>
                        <h2 class="font-bold text-lg text-[#1a2e5a]">Bugünün Raporu</h2>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::today()->format('d.m.Y') }}</p>
                    </div>
                </div>
                <a href="{{ route('public.today-report') }}"
                   target="_blank"
                   class="block w-full text-center bg-[#1a2e5a] hover:bg-blue-900 text-white px-4 py-2.5 rounded-lg font-semibold transition-colors">
                    📊 Raporu Görüntüle
                </a>
            </div>

            {{-- Dün --}}
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-gray-200 hover:border-[#1a2e5a] transition-all">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-2xl">📋</div>
                    <div>
                        <h2 class="font-bold text-lg text-[#1a2e5a]">Dünün Raporu</h2>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::yesterday()->format('d.m.Y') }}</p>
                    </div>
                </div>
                <a href="{{ route('public.daily-report', \Carbon\Carbon::yesterday()->format('Y-m-d')) }}"
                   target="_blank"
                   class="block w-full text-center bg-[#e63946] hover:bg-red-600 text-white px-4 py-2.5 rounded-lg font-semibold transition-colors">
                    📊 Raporu Görüntüle
                </a>
            </div>

        </div>
    </div>
</div>
@endsection