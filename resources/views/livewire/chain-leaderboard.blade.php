<div>
    <div class="bg-white rounded-xl shadow-xl overflow-hidden">
        <!-- Başlık ve Filtreler -->
        <div class="bg-gradient-to-r from-[#1a2e5a] to-[#e63946] p-6">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-white flex items-center">
                        <i class="fas fa-trophy mr-3 text-yellow-400"></i>
                        Zinciri Kırmayanlar
                    </h2>
                    <p class="text-white/80 mt-1">En disiplinli öğrencilerimiz</p>
                </div>
                
                <!-- Filtre Butonları -->
                <div class="flex flex-wrap gap-2">
                    <button 
                        wire:click="changeFilter('total')"
                        class="px-4 py-2 rounded-lg font-medium transition-all
                            {{ true
                                ? 'bg-white text-[#1a2e5a] shadow-lg transform scale-105' 
                                : 'bg-white/20 text-white hover:bg-white/30' }}">
                        <i class="fas fa-calendar-check mr-2"></i>Aktif Seri
                    </button>
                </div>
            </div>
        </div>

        <!-- Liderlik Tablosu -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sıra</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Öğrenci</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Seviye</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @if($filterType === 'current')
                                Aktif Seri
                            @elseif($filterType === 'longest')
                                En Uzun Seri
                            @else
                                Toplam Gün
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($leaderboardData as $data)
                    <tr class="hover:bg-gray-50 transition-colors duration-200 {{ $data['rank'] <= 3 ? 'bg-yellow-50/30' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($data['rank'] == 1)
                                    <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas fa-trophy text-white"></i>
                                    </div>
                                @elseif($data['rank'] == 2)
                                    <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas fa-medal text-white"></i>
                                    </div>
                                @elseif($data['rank'] == 3)
                                    <div class="w-8 h-8 bg-amber-600 rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas fa-medal text-white"></i>
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        <span class="text-gray-600 font-bold">{{ $data['rank'] }}</span>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($data['avatar'])
                                        <img class="h-10 w-10 rounded-full" src="{{ $data['avatar'] }}" alt="">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($data['user']->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $data['user']->name }} {{ $data['user']->surname }}
                                    </div>
                                    @if($data['rank'] <= 3)
                                        <div class="text-xs text-gray-500">
                                            @if($data['rank'] == 1)
                                                🏆 Lider
                                            @elseif($data['rank'] == 2)
                                                🥈 2. Sıra
                                            @else
                                                🥉 3. Sıra
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full text-white shadow-sm"
                                  style="background-color: {{ $data['level_color'] }}">
                                {{ $data['level'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-2xl font-bold" style="color: {{ $data['level_color'] }}">
                                        {{ $data['days_completed'] }}
                                </span>
                                <span class="text-xs text-gray-500">gün</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Henüz veri bulunmuyor.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Daha Fazla Göster -->
        @if(count($leaderboardData) >= $topLimit && !$showAll)
        <div class="text-center p-4 bg-gray-50">
            <button 
                wire:click="toggleShowAll"
                class="text-[#1a2e5a] hover:text-[#e63946] font-medium transition-colors">
                <i class="fas fa-chevron-down mr-2"></i>Daha Fazla Göster
            </button>
        </div>
        @elseif($showAll)
        <div class="text-center p-4 bg-gray-50">
            <button 
                wire:click="toggleShowAll"
                class="text-[#1a2e5a] hover:text-[#e63946] font-medium transition-colors">
                <i class="fas fa-chevron-up mr-2"></i>Daha Az Göster
            </button>
        </div>
        @endif
    </div>
</div>