<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('message') }}</p>
        </div>
    @endif

    <div class="bg-gray-50 rounded-md p-4 border border-gray-200">
        <h4 class="text-md font-semibold mb-4 flex items-center text-green-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Toplu SMS Gönder
        </h4>
        
        <form wire:submit.prevent="sendBulkSms" class="space-y-4">
            <div>
                <label for="target_group" class="block text-sm font-medium text-gray-700 mb-1">Hedef Grup</label>
                <select 
                    id="target_group" 
                    wire:model="targetGroup"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                >
                    <option value="all_users">Tüm Kullanıcılar</option>
                    <option value="all_students">Tüm Öğrenciler</option>
                    <option value="course_students">Belirli Kurs Öğrencileri</option>
                </select>
            </div>
            
            @if ($targetGroup === 'course_students')
                <div>
                    <label for="course_search" class="block text-sm font-medium text-gray-700 mb-1">Kurs Ara ve Seç</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="course_search" 
                            wire:model.debounce.300ms="courseSearch"
                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="Kurs adı yazın (en az 3 karakter)..."
                        >
                        
                        @if (!empty($courseResults) && !$selectedCourse)
                            <div class="absolute z-10 w-full mt-1 bg-white shadow-lg rounded-md max-h-60 overflow-auto">
                                @foreach ($courseResults as $course)
                                    <div 
                                        wire:click="selectCourse({{ $course['id'] }})"
                                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                                    >
                                        <div class="font-medium">{{ $course['name'] }}</div>
                                        <div class="text-sm text-gray-600">{{ isset($course['students_count']) ? $course['students_count'] . ' öğrenci' : '' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                    @if ($selectedCourse)
                        <div class="mt-2 flex items-center justify-between bg-green-50 p-2 rounded-md">
                            <div>
                                <p class="text-sm font-medium">{{ $selectedCourse->name }}</p>
                                <p class="text-xs text-gray-600">{{ $selectedCourse->students_count ?? '?' }} öğrenci</p>
                            </div>
                            <button type="button" wire:click="clearCourseSelection" class="text-red-600 hover:text-red-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @else
                        <p class="mt-1 text-xs text-gray-500">Henüz bir kurs seçilmedi</p>
                    @endif
                    
                    @error('selectedCourseId') 
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            @endif
            
            <div>
                <label for="bulk_message" class="block text-sm font-medium text-gray-700 mb-1">Mesaj İçeriği</label>
                <textarea 
                    id="bulk_message" 
                    wire:model="message"
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
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    @class(['opacity-50 cursor-not-allowed' => !$message || $charCount > 160 || ($targetGroup === 'course_students' && !$selectedCourseId)])
                    @if(!$message || $charCount > 160 || ($targetGroup === 'course_students' && !$selectedCourseId)) disabled @endif
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Toplu Gönder
                </button>
            </div>
        </form>
    </div>
</div>