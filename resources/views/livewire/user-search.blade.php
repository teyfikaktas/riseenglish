<div>
 {{-- Başarılı işlem mesajı --}}
 @if (session()->has('message'))
 <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
     <p>{{ session('message') }}</p>
 </div>
@endif

{{-- Hata mesajı --}}
@if (session()->has('error'))
 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
     <p>{{ session('error') }}</p>
 </div>
@endif

    <div class="bg-gray-50 rounded-md p-4 border border-gray-200">
        <h4 class="text-md font-semibold mb-4 flex items-center text-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Bireysel SMS Gönder
        </h4>
        
        <form wire:submit.prevent="sendSms" class="space-y-4">
            <div>
                <label for="user_search" class="block text-sm font-medium text-gray-700 mb-1">Kullanıcı Ara ve Seç</label>
                <div class="relative">
                    <input 
                        type="text" 
                        id="user_search" 
                        wire:model.live.debounce.300ms="search"
                        class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="Kullanıcı adı veya telefon yazın (en az 3 karakter)..."
                    >
                    
                    @if (!empty($searchResults) && !$selectedUser)
                        <div class="absolute z-10 w-full mt-1 bg-white shadow-lg rounded-md max-h-60 overflow-auto">
                            @foreach ($searchResults as $user)
                                <div 
                                    wire:click="selectUser({{ $user['id'] }})"
                                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                                >
                                    <div class="font-medium">{{ $user['name'] }}</div>
                                    <div class="text-sm text-gray-600">{{ $user['phone'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                
                @if ($selectedUser)
                    <div class="mt-2 flex items-center justify-between bg-blue-50 p-2 rounded-md">
                        <div>
                            <p class="text-sm font-medium">{{ $selectedUser->name }}</p>
                            <p class="text-xs text-gray-600">{{ $selectedUser->phone }}</p>
                        </div>
                        <button type="button" wire:click="clearSelection" class="text-red-600 hover:text-red-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @else
                    <p class="mt-1 text-xs text-gray-500">Henüz bir kullanıcı seçilmedi</p>
                @endif
                
                @error('selectedUserId') 
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Mesaj İçeriği</label>
<!-- Textarea'yı güncelle -->
<textarea 
    id="message" 
    wire:model.live="message"
    rows="4" 
    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" 
    placeholder="SMS içeriğini buraya yazın..."
></textarea>
                
                <p class="mt-1 text-xs {{ $charCount <= 160 ? 'text-gray-500' : 'text-red-500' }}">
                    <span>{{ $charCount }}</span>/160 karakter
                </p>
                
                @error('message') 
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <button 
                    type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    @class(['opacity-50 cursor-not-allowed' => !$selectedUserId || !$message || $charCount > 160])
                    @if(!$selectedUserId || !$message || $charCount > 160) disabled @endif
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Gönder
                </button>
            </div>
        </form>
    </div>
</div>