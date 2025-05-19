<!-- resources/views/zinciri-kirma.blade.php -->
@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-r from-[#1a2e5a] to-[#283b6a] min-h-screen">
    <!-- Livewire Bileşenini Dahil Et -->
@livewire('chain-breaker', [], key('chain-breaker-'.auth()->id()))
</div>
@endsection