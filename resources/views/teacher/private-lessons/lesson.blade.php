@extends('layouts.app')

@section('content')
@if(session('error'))
    <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-md">
        {{ session('success') }}
    </div>
@endif

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ $lesson->name }}</h1>
        <div class="space-x-2">
            <a href="{{ route('ogretmen.private-lessons.editLesson', $lesson->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Dersi Düzenle</a>
            <a href="{{ route('ogretmen.private-lessons.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Geri</a>
        </div>
    </div>

    <!-- Ders Bilgileri -->
    <div class="bg-white rounded-lg shadow-md mb-6 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-semibold mb-4">Ders Bilgileri</h2>
                <div class="space-y-2">
                    <p><span class="font-medium">Öğrenci:</span> {{ $student ? $student->name : 'Öğrenci Atanmamış' }}</p>
                    <p><span class="font-medium">Ücret:</span> {{ number_format($lesson->price, 2) }} TL</p>
                    <p><span class="font-medium">Aktif:</span> {{ $lesson->is_active ? 'Evet' : 'Hayır' }}</p>
                    <p><span class="font-medium">Oluşturulma:</span> {{ $lesson->created_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>
            <div>
                <h2 class="text-xl font-semibold mb-4">Özet Bilgiler</h2>
                <div class="space-y-2">
                    <p><span class="font-medium">Toplam Seans:</span> {{ $sessions->count() }}</p>
                    <p><span class="font-medium">Tamamlanan Seans:</span> {{ $sessions->where('status', 'completed')->count() }}</p>
                    <p><span class="font-medium">Kalan Seans:</span> {{ $sessions->whereIn('status', ['approved', 'active', 'scheduled'])->count() }}</p>
                    <p><span class="font-medium">İptal Edilen:</span> {{ $sessions->where('status', 'cancelled')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ders Seansları -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Seanslar</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saat</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ödeme</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($sessions as $session)
                    @php
                        // Status renklerini belirle
                        switch($session->status) {
                            case 'pending':
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $statusText = 'Beklemede';
                                break;
                            case 'approved':
                                $statusClass = 'bg-blue-100 text-blue-800';
                                $statusText = 'Onaylandı';
                                break;
                            case 'active':
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusText = 'Aktif';
                                break;
                            case 'completed':
                                $statusClass = 'bg-purple-100 text-purple-800';
                                $statusText = 'Tamamlandı';
                                break;
                            case 'cancelled':
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusText = 'İptal Edildi';
                                break;
                            default:
                                $statusClass = 'bg-gray-100 text-gray-800';
                                $statusText = 'Bilinmiyor';
                        }
                        
                        // Ödeme durumu
                        $paymentClass = $session->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                        $paymentText = $session->payment_status == 'paid' ? 'Ödenmiş' : 'Ödenmemiş';
                    @endphp
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($session->start_date)->format('d.m.Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paymentClass }}">
                                {{ $paymentText }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('ogretmen.private-lessons.session.show', $session->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Detay</a>
                            <a href="{{ route('ogretmen.private-lessons.edit', $session->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Düzenle</a>
                            

                            

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection