@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Başlık ve Geri Dön Butonu -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Ders Takvimim</h1>
            
            <a
                href="{{ route('ogretmen.private-lessons.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg shadow
                       hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Derslerime Dön
            </a>
        </div>
    </div>
    
    <!-- Takvim - Tam genişlik -->
    <div class="w-full bg-white py-6">
        @livewire('private-lesson-calendar')
    </div>
@endsection