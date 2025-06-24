<!-- resources/views/livewire/chain-leaderboard.blade.php -->
<div class="bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#1a2e5a] to-[#e63946] px-4 py-6">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-white flex items-center justify-center mb-2">
                <i class="fas fa-trophy mr-2 text-yellow-400"></i>
                Zinciri Kırmayanlar
            </h2>
            <p class="text-white/80 text-sm">En disiplinli öğrencilerimiz</p>
        </div>
        
        <!-- Filtre Butonu -->
        <div class="flex justify-center mt-4">
            <div class="bg-white/10 backdrop-blur-sm rounded-full p-1">
                <button class="px-6 py-2 bg-white text-[#1a2e5a] rounded-full text-sm font-medium shadow-lg">
                    <i class="fas fa-calendar-check mr-2"></i>Toplam Gün
                </button>
            </div>
        </div>
    </div>

    <!-- Leaderboard Cards -->
    <div class="px-4 py-4 space-y-3 pb-4">
        @forelse($showAll ? $leaderboardData : $leaderboardData->take(10) as $data)
            @if($data['rank'] <= 3)
                <!-- Top 3 - Büyük Kartlar -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 
                    {{ $data['rank'] == 1 ? 'border-yellow-400 bg-gradient-to-r from-yellow-50 to-orange-50' : 
                       ($data['rank'] == 2 ? 'border-gray-300 bg-gradient-to-r from-gray-50 to-blue-50' : 
                        'border-amber-600 bg-gradient-to-r from-amber-50 to-yellow-50') }}">
                    
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <!-- Sol Taraf - Rank ve Avatar -->
                            <div class="flex items-center space-x-3">
                                <!-- Rank Badge -->
                                <div class="relative">
                                    @if($data['rank'] == 1)
                                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center shadow-lg">
                                            <i class="fas fa-crown text-white text-lg"></i>
                                        </div>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-yellow-300 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-bold text-yellow-800">1</span>
                                        </div>
                                    @elseif($data['rank'] == 2)
                                        <div class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center shadow-lg">
                                            <i class="fas fa-medal text-white text-lg"></i>
                                        </div>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-bold text-gray-800">2</span>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 bg-gradient-to-br from-amber-600 to-amber-800 rounded-full flex items-center justify-center shadow-lg">
                                            <i class="fas fa-medal text-white text-lg"></i>
                                        </div>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-amber-400 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-bold text-amber-900">3</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Avatar -->
                                <div class="relative">
                                    <div class="w-14 h-14 rounded-full border-3 overflow-hidden shadow-md
                                        @if($data['level'] == 'Bronz') border-amber-600 
                                        @elseif($data['level'] == 'Demir') border-gray-600
                                        @elseif($data['level'] == 'Gümüş') border-gray-300
                                        @elseif($data['level'] == 'Altın') border-yellow-400
                                        @elseif($data['level'] == 'Platin') border-gray-200
                                        @elseif($data['level'] == 'Zümrüt') border-emerald-500
                                        @elseif($data['level'] == 'Elmas') border-blue-500
                                        @elseif($data['level'] == 'MASTER') border-purple-600
                                        @else border-amber-600
                                        @endif">
                                        @php
                                            $levelIconMap = [
                                                'Bronz' => $data['icon_gender'] == 'kadin' ? 'bronzkadin.jpg' : 'bronzerkek.jpg',
                                                'Demir' => $data['icon_gender'] == 'kadin' ? 'demirkadin.jpg' : 'demirerkek.jpg',
                                                'Gümüş' => $data['icon_gender'] == 'kadin' ? 'gumuskadin.jpg' : 'gumuserkek.jpg',
                                                'Altın' => $data['icon_gender'] == 'kadin' ? 'altinkadin.jpg' : 'altinerkek.jpg',
                                                'Platin' => $data['icon_gender'] == 'kadin' ? 'platinkadin.jpg' : 'platinerkek.jpg',
                                                'Zümrüt' => $data['icon_gender'] == 'kadin' ? 'yakutkadin.jpg' : 'yakuterkek.jpg',
                                                'Elmas' => $data['icon_gender'] == 'kadin' ? 'elmaskadin.jpg' : 'elmaserkek.jpg',
                                                'MASTER' => $data['icon_gender'] == 'kadin' ? 'masterkadin.jpg' : 'mastererkek.jpg',
                                            ];
                                            $iconFile = $levelIconMap[$data['level']] ?? ($data['icon_gender'] == 'kadin' ? 'bronzkadin.jpg' : 'bronzerkek.jpg');
                                        @endphp
                                        <img class="w-full h-full object-cover" 
                                            src="{{ asset('images/icons/' . $iconFile) }}" 
                                            alt="{{ $data['level'] }} Seviye">
                                    </div>
                                </div>

                                <!-- İsim ve Seviye -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900 truncate">
                                        {{ $data['user']->name }} {{ $data['user']->surname }}
                                    </h3>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full text-white shadow-sm"
                                              style="background-color: {{ $data['level_color'] }}">
                                            {{ $data['level'] }}
                                        </span>
                                        @if($data['rank'] == 1)
                                            <span class="text-xs text-yellow-600 font-medium">👑 Lider</span>
                                        @elseif($data['rank'] == 2)
                                            <span class="text-xs text-gray-600 font-medium">🥈 2. Sıra</span>
                                        @else
                                            <span class="text-xs text-amber-600 font-medium">🥉 3. Sıra</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Sağ Taraf - Puan -->
                            <div class="text-center">
                                <div class="text-2xl font-bold" style="color: {{ $data['level_color'] }}">
                                    {{ $data['days_completed'] }}
                                </div>
                                <div class="text-xs text-gray-500 font-medium">GÜN</div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Diğer Sıralar - Kompakt Kartlar -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                    <div class="p-3">
                        <div class="flex items-center justify-between">
                            <!-- Sol Taraf -->
                            <div class="flex items-center space-x-3">
                                <!-- Rank Number -->
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-bold text-gray-600">{{ $data['rank'] }}</span>
                                </div>

                                <!-- Avatar -->
                                <div class="w-10 h-10 rounded-full border-2 overflow-hidden shadow-sm
                                    @if($data['level'] == 'Bronz') border-amber-600 
                                    @elseif($data['level'] == 'Demir') border-gray-600
                                    @elseif($data['level'] == 'Gümüş') border-gray-300
                                    @elseif($data['level'] == 'Altın') border-yellow-400
                                    @elseif($data['level'] == 'Platin') border-gray-200
                                    @elseif($data['level'] == 'Zümrüt') border-emerald-500
                                    @elseif($data['level'] == 'Elmas') border-blue-500
                                    @elseif($data['level'] == 'MASTER') border-purple-600
                                    @else border-amber-600
                                    @endif">
                                    @php
                                        $levelIconMap = [
                                            'Bronz' => $data['icon_gender'] == 'kadin' ? 'bronzkadin.jpg' : 'bronzerkek.jpg',
                                            'Demir' => $data['icon_gender'] == 'kadin' ? 'demirkadin.jpg' : 'demirerkek.jpg',
                                            'Gümüş' => $data['icon_gender'] == 'kadin' ? 'gumuskadin.jpg' : 'gumuserkek.jpg',
                                            'Altın' => $data['icon_gender'] == 'kadin' ? 'altinkadin.jpg' : 'altinerkek.jpg',
                                            'Platin' => $data['icon_gender'] == 'kadin' ? 'platinkadin.jpg' : 'platinerkek.jpg',
                                            'Zümrüt' => $data['icon_gender'] == 'kadin' ? 'yakutkadin.jpg' : 'yakuterkek.jpg',
                                            'Elmas' => $data['icon_gender'] == 'kadin' ? 'elmaskadin.jpg' : 'elmaserkek.jpg',
                                            'MASTER' => $data['icon_gender'] == 'kadin' ? 'masterkadin.jpg' : 'mastererkek.jpg',
                                        ];
                                        $iconFile = $levelIconMap[$data['level']] ?? ($data['icon_gender'] == 'kadin' ? 'bronzkadin.jpg' : 'bronzerkek.jpg');
                                    @endphp
                                    <img class="w-full h-full object-cover" 
                                        src="{{ asset('images/icons/' . $iconFile) }}" 
                                        alt="{{ $data['level'] }} Seviye">
                                </div>

                                <!-- İsim ve Seviye -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-medium text-gray-900 text-sm truncate">
                                        {{ $data['user']->name }} {{ $data['user']->surname }}
                                    </h3>
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full text-white"
                                          style="background-color: {{ $data['level_color'] }}">
                                        {{ $data['level'] }}
                                    </span>
                                </div>
                            </div>

                            <!-- Sağ Taraf - Puan -->
                            <div class="text-center">
                                <div class="text-lg font-bold" style="color: {{ $data['level_color'] }}">
                                    {{ $data['days_completed'] }}
                                </div>
                                <div class="text-xs text-gray-500">gün</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <div class="text-gray-400 mb-3">
                    <i class="fas fa-trophy text-4xl"></i>
                </div>
                <p class="text-gray-500">Henüz veri bulunmuyor.</p>
            </div>
        @endforelse
    </div>

    <!-- Daha Fazla Göster -->
    @if(count($leaderboardData) > 10 && !$showAll)
        <div class="px-4 pb-4">
            <button 
                wire:click="toggleShowAll"
                class="w-full bg-white rounded-xl shadow-md p-4 text-[#1a2e5a] hover:bg-gray-50 font-medium transition-colors">
                <i class="fas fa-chevron-down mr-2"></i>Daha Fazla Göster ({{ count($leaderboardData) - 10 }} kişi)
            </button>
        </div>
    @elseif($showAll)
        <div class="px-4 pb-4">
            <button 
                wire:click="toggleShowAll"
                class="w-full bg-white rounded-xl shadow-md p-4 text-[#1a2e5a] hover:bg-gray-50 font-medium transition-colors">
                <i class="fas fa-chevron-up mr-2"></i>Daha Az Göster
            </button>
        </div>
    @endif
</div>

<style>
@keyframes shine {
    0% { left: -100%; }
    100% { left: 100%; }
}

.animate-shine {
    overflow: hidden;
    position: relative;
}

.animate-shine::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: linear-gradient(to right, transparent, rgba(255,255,255,0.5), transparent);
    animation: shine 3s infinite;
    transform: skewX(-15deg);
}

/* Mobil optimizasyonlar */
@media (max-width: 640px) {
    .truncate {
        max-width: 120px;
    }
}
</style>