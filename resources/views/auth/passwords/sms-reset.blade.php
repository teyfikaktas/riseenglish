@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-4 bg-[#1a2e5a] text-center relative">
            <h2 class="text-2xl font-bold text-white">Şifre Sıfırlama</h2>
            <div class="absolute -right-2 top-2 transform rotate-12">
                <div class="bg-[#e63946] text-white text-xs font-bold py-1 px-3 rounded-full shadow-lg">
                    Yeni Şifre Oluştur
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="mb-6 text-gray-600">
                {{ __('Lütfen yeni şifrenizi belirleyin.') }}
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.sms.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <!-- Telefon numarası artık controller'dan alınacak ve gizli alan olarak gönderilmeyecek -->

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('Yeni Şifre') }}
                    </label>
                    <input id="password" type="password" class="appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:border-[#1a2e5a] @error('password') border-red-500 @enderror" 
                           name="password" required autocomplete="new-password">
                    
                    @error('password')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password-confirm" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('Şifreyi Onayla') }}
                    </label>
                    <input id="password-confirm" type="password" class="appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:border-[#1a2e5a]" 
                           name="password_confirmation" required autocomplete="new-password">
                </div>

                <div class="flex items-center justify-center">
                    <button type="submit" class="w-full bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                        {{ __('Şifremi Sıfırla') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection