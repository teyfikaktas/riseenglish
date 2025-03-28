@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">İletişim Mesajı Detayı</h1>
        <div>
            <a href="{{ route('admin.contacts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Geri Dön</a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="col-span-1">
                <h3 class="text-lg font-semibold mb-2">Mesaj Bilgileri</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <span class="text-gray-600 block text-sm">Durum:</span>
                        @if ($contact->is_read)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Okundu
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Yeni
                            </span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <span class="text-gray-600 block text-sm">Tarih:</span>
                        <span class="text-gray-900">{{ $contact->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="col-span-2">
                <h3 class="text-lg font-semibold mb-2">Gönderen Bilgileri</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <span class="text-gray-600 block text-sm">İsim:</span>
                        <span class="text-gray-900">{{ $contact->name }}</span>
                    </div>
                    <div class="mb-3">
                        <span class="text-gray-600 block text-sm">E-posta:</span>
                        <span class="text-gray-900">{{ $contact->email }}</span>
                    </div>
                    <div class="mb-3">
                        <span class="text-gray-600 block text-sm">Telefon:</span>
                        <span class="text-gray-900">{{ $contact->phone ?: 'Belirtilmemiş' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Konu</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-900">{{ $contact->subject }}</p>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Mesaj</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-900 whitespace-pre-line">{{ $contact->message }}</p>
            </div>
        </div>

        <div class="flex space-x-3 mt-6">
            @if (!$contact->is_read)
                <form action="{{ route('admin.contacts.mark-as-read', $contact) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">Okundu İşaretle</button>
                </form>
            @else
                <form action="{{ route('admin.contacts.mark-as-unread', $contact) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Okunmadı İşaretle</button>
                </form>
            @endif

            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Bu mesajı silmek istediğinizden emin misiniz?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Sil</button>
            </form>
        </div>
    </div>
</div>
@endsection