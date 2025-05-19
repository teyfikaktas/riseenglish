@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Bildirim -->
    @if(session('success'))
    <div id="success-alert" class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg mb-6 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Üst Bilgi ve Geriye Dön Butonu -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                {{ $student->name }} {{ $student->surname }}
            </h1>
            <p class="text-gray-600">Zinciri Kırma Detayları</p>
        </div>
        <a href="{{ route('ogretmen.chain-breaker-dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Listeye Dön
        </a>
    </div>

    <!-- Ana İçerik -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sol Bölüm: İstatistikler ve Gün Ayarlama -->
        <div class="lg:col-span-1 space-y-6">
            <!-- İstatistikler -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-[#1a2e5a] to-[#e63946] p-4">
                    <h2 class="text-xl font-bold text-white">İstatistikler</h2>
                </div>
                <div class="p-4 space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white">
                            <div class="text-sm opacity-80">Toplam Gün</div>
                            <div class="text-3xl font-bold mt-2">
                                {{ $student->chainProgress->days_completed ?? 0 }}
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white">
                            <div class="text-sm opacity-80">Seviye</div>
                            <div class="text-2xl font-bold mt-2">
                                {{ $student->chainProgress->getCurrentLevel() ?? 'Başlangıç' }}
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white">
                            <div class="text-sm opacity-80">Toplam Çalışma</div>
                            <div class="text-3xl font-bold mt-2">
                                {{ $student->chainActivities->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Son 30 Günlük Çalışma Takvimi -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-[#1a2e5a] to-[#e63946] p-4">
                    <h2 class="text-xl font-bold text-white">Son 30 Gün Takibi</h2>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-7 gap-2">
                        @foreach($last30Days as $dateStr => $day)
                            <div class="aspect-square flex flex-col items-center justify-center rounded-lg p-1 text-center
                                {{ $day['is_today'] ? 'ring-2 ring-blue-500' : '' }}
                                {{ isset($day['is_future']) && $day['is_future'] ? 'bg-gray-200 text-gray-400' : 
                                   ($day['has_activity'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                <span class="text-xs font-medium">{{ $day['day_name'] }}</span>
                                <span class="text-lg font-bold">{{ $day['day'] }}</span>
                                <div class="mt-1">
                                    @if(isset($day['is_future']) && $day['is_future'])
                                        <i class="fas fa-clock text-gray-400"></i>
                                    @elseif($day['has_activity'])
                                        <i class="fas fa-check-circle text-green-600"></i>
                                    @else
                                        <i class="fas fa-times-circle text-red-600"></i>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 flex justify-between text-sm">
                        <div class="flex items-center">
                            <span class="inline-block w-3 h-3 bg-green-100 mr-1 rounded"></span>
                            <span>Çalışma Yapıldı</span>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-block w-3 h-3 bg-red-100 mr-1 rounded"></span>
                            <span>Çalışma Yapılmadı</span>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-block w-3 h-3 bg-gray-200 mr-1 rounded"></span>
                            <span>Gelecek Gün</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gün Ayarlama -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-[#1a2e5a] to-[#e63946] p-4">
                    <h2 class="text-xl font-bold text-white">Gün Sayısı Ayarla</h2>
                </div>
                <div class="p-4">
                    <form action="{{ route('ogretmen.student.chain-update', $student->id) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ekle/Çıkar (Gün)
                                </label>
                                <input 
                                    type="number" 
                                    name="adjustDays"
                                    class="w-full rounded-lg border-gray-300"
                                    placeholder="+5 veya -3">
                                @error('adjustDays') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Sebep
                                </label>
                                <textarea 
                                    name="adjustReason"
                                    class="w-full rounded-lg border-gray-300"
                                    rows="3"
                                    placeholder="Neden gün ekliyorsunuz veya çıkarıyorsunuz?"></textarea>
                                @error('adjustReason') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>
                            <div>
                                <button 
                                    type="submit"
                                    class="bg-[#e63946] hover:bg-[#d62836] text-white font-bold py-2 px-4 rounded-lg transition-colors w-full">
                                    <i class="fas fa-save mr-2"></i>Güncelle
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sağ Bölüm: Aktivite Geçmişi -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-[#1a2e5a] to-[#e63946] p-4">
                    <h2 class="text-xl font-bold text-white">Aktivite Geçmişi</h2>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        @forelse($activitiesByDate as $date => $activities)
                            <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                <div class="bg-gray-50 p-4 cursor-pointer {{ isset($last30Days[$date]) && $last30Days[$date]['has_activity'] ? 'bg-green-50' : '' }}" 
                                     onclick="toggleActivities('activities-{{ \Illuminate\Support\Str::slug($date) }}')">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-bold {{ isset($last30Days[$date]) && $last30Days[$date]['has_activity'] ? 'text-green-800' : 'text-gray-800' }}">
                                            {{ \Carbon\Carbon::parse($date)->locale('tr')->isoFormat('DD MMMM YYYY, dddd') }}
                                            @if(isset($last30Days[$date]) && $last30Days[$date]['has_activity'])
                                                <i class="fas fa-check-circle text-green-600 ml-2"></i>
                                            @endif
                                        </h3>
                                        <div class="flex items-center">
                                            <span class="mr-2 text-sm {{ isset($last30Days[$date]) && $last30Days[$date]['has_activity'] ? 'text-green-600' : 'text-gray-600' }}">
                                                {{ $activities->count() }} kayıt
                                            </span>
                                            <i class="fas fa-chevron-down {{ isset($last30Days[$date]) && $last30Days[$date]['has_activity'] ? 'text-green-500' : 'text-gray-500' }} toggle-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <div id="activities-{{ \Illuminate\Support\Str::slug($date) }}" class="p-0 hidden">
                                    <div class="divide-y">
                                        @foreach($activities as $activity)
                                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0">
                                                        @if($activity->is_adjustment)
                                                            <i class="fas fa-edit text-yellow-500 text-xl"></i>
                                                        @else
                                                            <i class="fas fa-book text-blue-500 text-xl"></i>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1">
                                                        @if($activity->content)
                                                            <p class="text-gray-700">{{ $activity->content }}</p>
                                                        @endif
                                                        @if($activity->file_name)
                                                            <a href="{{ Storage::url($activity->file_path) }}" target="_blank" 
                                                                class="text-blue-600 hover:text-blue-800 text-sm mt-1 inline-flex items-center">
                                                                <i class="fas fa-file mr-1"></i> {{ $activity->file_name }}
                                                            </a>
                                                        @endif
                                                        <div class="mt-2 text-xs text-gray-500">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            {{ $activity->created_at->format('H:i') }}
                                                            @if($activity->is_adjustment)
                                                                <span class="ml-2 bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">
                                                                    Öğretmen Düzenlemesi
                                                                </span>
                                                            @endif
                                                            @if($activity->teacher)
                                                                <span class="ml-2 text-gray-500">
                                                                    {{ $activity->teacher->name }} {{ $activity->teacher->surname }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-3"></i>
                                <p>Henüz hiç aktivite kaydı bulunmuyor.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Aktivite detaylarını açıp kapatma
    function toggleActivities(id) {
        const element = document.getElementById(id);
        const iconElement = event.currentTarget.querySelector('.toggle-icon');
        
        if (element.classList.contains('hidden')) {
            element.classList.remove('hidden');
            iconElement.classList.remove('fa-chevron-down');
            iconElement.classList.add('fa-chevron-up');
        } else {
            element.classList.add('hidden');
            iconElement.classList.remove('fa-chevron-up');
            iconElement.classList.add('fa-chevron-down');
        }
    }
    
    // Başarı mesajını 3 saniye sonra gizle
    if (document.getElementById('success-alert')) {
        setTimeout(() => {
            const alert = document.getElementById('success-alert');
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    }
</script>
@endpush
@endsection