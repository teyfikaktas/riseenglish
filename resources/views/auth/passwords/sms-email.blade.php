@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-4 bg-[#1a2e5a] text-center">
            <h2 class="text-2xl font-bold text-white">Şifremi Unuttum</h2>
            <div class="absolute -right-2 top-2 transform rotate-12">
                <div class="bg-[#e63946] text-white text-xs font-bold py-1 px-3 rounded-full shadow-lg">
                    SMS ile Sıfırla
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="mb-6 text-gray-600">
                {{ __('Şifrenizi mi unuttunuz? Telefon numaranızı girin ve size şifre sıfırlama linki gönderelim.') }}
            </div>

            @if (session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.sms.email') }}">
                @csrf

                <div class="mb-6">
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('Telefon Numarası') }}
                    </label>
                    <input id="phone" type="text" class="appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:border-[#1a2e5a] @error('phone') border-red-500 @enderror" 
                           name="phone" value="{{ old('phone') }}" placeholder="Telefon numaranız (5XX XXX XX XX)" required>
                    
                    @error('phone')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                        {{ __('Şifre Sıfırlama Linki Gönder') }}
                    </button>
                    
                    <a href="{{ route('login') }}" class="text-sm text-[#1a2e5a] hover:text-[#e63946]">
                        {{ __('Giriş sayfasına dön') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection