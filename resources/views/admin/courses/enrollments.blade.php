@extends('layouts.app')

@section('title', $course->name . ' - Kayıt Yönetimi')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Başlık ve Geri Butonu -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('admin.courses.index') }}" class="text-gray-600 hover:text-gray-900 mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Kurslara Dön
            </a>
            <h1 class="text-2xl font-bold text-gray-800 inline-block">{{ $course->name }} - Kayıt Yönetimi</h1>
        </div>
    </div>
    
    <!-- Bilgi Kartı -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <div class="flex flex-wrap">
            <div class="w-full md:w-1/2 lg:w-1/3 p-2">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Kurs Tipi</div>
                        <div class="text-md font-semibold text-gray-800">{{ $course->courseType?->name ?? 'Belirtilmemiş' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="w-full md:w-1/2 lg:w-1/3 p-2">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Toplam Öğrenci</div>
                        <div class="text-md font-semibold text-gray-800">{{ $enrollments->total() }}</div>
                    </div>
                </div>
            </div>
            
            <div class="w-full md:w-1/2 lg:w-1/3 p-2">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Onay Bekleyen</div>
                        <div class="text-md font-semibold text-gray-800">
                            {{ $enrollments->where('pivot.approval_status', 0)->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Kayıt Listesi -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Öğrenci
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kayıt Tarihi
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Durum
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Onay Durumu
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ödeme
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        İşlemler
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($enrollments as $enrollment)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-800 font-semibold">{{ substr($enrollment->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $enrollment->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $enrollment->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ optional($enrollment->pivot->enrollment_date)->format('d.m.Y') ?? 'Belirtilmemiş' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusId = $enrollment->pivot->status_id ?? null;
                                $statusObj = \App\Models\EnrollmentStatus::find($statusId);
                                $statusName = $statusObj ? $statusObj->name : 'Belirtilmemiş';
                                $statusDesc = $statusObj ? $statusObj->description : 'Belirtilmemiş';
                            @endphp
                            
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($statusDesc == 'Aktif')
                                    bg-green-100 text-green-800
                                @elseif($statusDesc == 'Tamamlandı')
                                    bg-blue-100 text-blue-800
                                @elseif($statusDesc == 'Bıraktı')
                                    bg-red-100 text-red-800
                                @elseif($statusDesc == 'Beklemede')
                                    bg-yellow-100 text-yellow-800
                                @else
                                    bg-gray-100 text-gray-800
                                @endif
                            ">
                                {{ $statusDesc }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $enrollment->pivot->approval_status ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $enrollment->pivot->approval_status ? 'Onaylandı' : 'Onay Bekliyor' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($enrollment->pivot->paid_amount ?? 0, 2) }} ₺</div>
                            <div class="text-xs text-gray-500">
                                {{ $enrollment->pivot->payment_completed ? 'Tamamlandı' : 'Bekliyor' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2 justify-end">
                                <!-- Öğrenciye Git İkonu -->
                                <a href="#" class="text-blue-600 hover:text-blue-900 transition-all duration-200" title="Öğrenciye Git">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                
                                <!-- Düzenle İkonu -->
                                <button type="button" onclick="openEditModal('{{ $enrollment->id }}')" class="text-indigo-600 hover:text-indigo-900 transition-all duration-200" title="Düzenle">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 whitespace-nowrap text-sm text-gray-500 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600 text-lg font-medium">Henüz kurs kaydı bulunmuyor.</span>
                                <p class="text-gray-500 mt-1">Bu kursa henüz öğrenci kaydı yapılmamış.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Sayfalama -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-700">
                    Toplam <span class="font-medium">{{ $enrollments->total() }}</span> kayıt
                </div>
                <div>
                    {{ $enrollments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Düzenleme Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Kayıt Durumunu Düzenle</h3>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6">
                <div class="mb-4">
                    <label for="status_id" class="block text-sm font-medium text-gray-700 mb-1">Durum</label>
                    <select id="status_id" name="status_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="approval_status" class="block text-sm font-medium text-gray-700 mb-1">Onay Durumu</label>
                    <select id="approval_status" name="approval_status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="0">Onay Bekliyor</option>
                        <option value="1">Onaylandı</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notlar</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 text-right rounded-b-lg">
                <button type="button" onclick="closeEditModal()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2">
                    İptal
                </button>
                <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(userId) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        
        // Form action URL'sini ayarla
        form.action = `/admin/courses/{{ $course->id }}/enrollments/${userId}`;
        
        // Mevcut kayıt bilgilerini getir ve formu doldur
        fetch(`/admin/courses/{{ $course->id }}/enrollments/${userId}/data`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('status_id').value = data.status_id;
                document.getElementById('approval_status').value = data.approval_status ? "1" : "0";
                document.getElementById('notes').value = data.notes || '';
                
                // Modal'ı göster
                modal.classList.remove('hidden');
            })
            .catch(error => console.error('Kayıt bilgileri alınamadı:', error));
    }
    
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
    
    // Modal dışında bir yere tıklanınca modal'ı kapat
    document.getElementById('editModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeEditModal();
        }
    });
</script>
@endsection
