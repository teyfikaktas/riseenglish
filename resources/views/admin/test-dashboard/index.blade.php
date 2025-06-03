@extends('layouts.app')

@section('title', 'Test Yönetimi Dashboard')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Başlık ve Hızlı Aksiyonlar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">📝 Test Yönetimi Dashboard</h1>
            <p class="text-gray-600">Test kategorileri, testler ve soruları yönetin</p>
        </div>
        
        <!-- Hızlı Aksiyonlar -->
        <div class="flex flex-wrap gap-3 mt-4 lg:mt-0">
            <a href="{{ route('admin.test-categories.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Kategori Ekle
            </a>
            <a href="{{ route('admin.tests.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Test Ekle
            </a>
            <a href="{{ route('admin.questions.create') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Soru Ekle
            </a>
        </div>
    </div>

    <!-- İstatistik Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Kategoriler -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="bg-blue-500 rounded-full p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-blue-600">Kategoriler</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $stats['total_categories'] }}</p>
                    <p class="text-xs text-gray-500">{{ $stats['active_categories'] }} aktif</p>
                </div>
            </div>
        </div>

        <!-- Testler -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="bg-green-500 rounded-full p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-green-600">Testler</p>
                    <p class="text-2xl font-bold text-green-900">{{ $stats['total_tests'] }}</p>
                    <p class="text-xs text-gray-500">{{ $stats['active_tests'] }} aktif</p>
                </div>
            </div>
        </div>

        <!-- Sorular -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="bg-purple-500 rounded-full p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-purple-600">Sorular</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $stats['total_questions'] }}</p>
                    <p class="text-xs text-gray-500">{{ $stats['active_questions'] }} aktif</p>
                </div>
            </div>
        </div>

        <!-- Test Çözümleri -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="bg-orange-500 rounded-full p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-orange-600">Test Çözümleri</p>
                    <p class="text-2xl font-bold text-orange-900">{{ $stats['total_attempts'] }}</p>
                    <p class="text-xs text-gray-500">{{ $stats['completed_attempts'] }} tamamlandı</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ana İçerik Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sol Kolon - Yönetim Menüleri -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Kategori Yönetimi -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">📂 Kategori Yönetimi</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $stats['total_categories'] }}</span>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.test-categories.index') }}" 
                       class="block w-full text-left p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition duration-200">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-700">Tüm Kategoriler</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Kategorileri görüntüle ve düzenle</p>
                    </a>
                    
                    <a href="{{ route('admin.test-categories.create') }}" 
                       class="block w-full text-left p-3 rounded-lg border border-blue-200 bg-blue-50 hover:bg-blue-100 transition duration-200">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-blue-700">Yeni Kategori</span>
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-blue-600 mt-1">Yeni kategori oluştur</p>
                    </a>
                </div>
            </div>

            <!-- Test Yönetimi -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">📝 Test Yönetimi</h3>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $stats['total_tests'] }}</span>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.tests.index') }}" 
                       class="block w-full text-left p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition duration-200">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-700">Tüm Testler</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Testleri görüntüle ve düzenle</p>
                    </a>
                    
                    <a href="{{ route('admin.tests.create') }}" 
                       class="block w-full text-left p-3 rounded-lg border border-green-200 bg-green-50 hover:bg-green-100 transition duration-200">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-green-700">Yeni Test</span>
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-green-600 mt-1">Yeni test oluştur</p>
                    </a>
                </div>
            </div>

            <!-- Soru Yönetimi -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">❓ Soru Yönetimi</h3>
                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $stats['total_questions'] }}</span>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.questions.index') }}" 
                       class="block w-full text-left p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition duration-200">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-700">Tüm Sorular</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Soruları görüntüle ve düzenle</p>
                    </a>
                    
                    <a href="{{ route('admin.questions.create') }}" 
                       class="block w-full text-left p-3 rounded-lg border border-purple-200 bg-purple-50 hover:bg-purple-100 transition duration-200">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-purple-700">Yeni Soru</span>
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-purple-600 mt-1">Yeni soru oluştur</p>
                    </a>
                    
                    <a href="{{ route('admin.questions.bulk-create') }}" 
                       class="block w-full text-left p-3 rounded-lg border border-purple-200 bg-purple-50 hover:bg-purple-100 transition duration-200">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-purple-700">Toplu Soru Ekle</span>
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-purple-600 mt-1">Birden fazla soru ekle</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sağ Kolon - Listeler ve İstatistikler -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Son Kategoriler -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">📂 Son Eklenen Kategoriler</h3>
                    <a href="{{ route('admin.test-categories.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Tümünü Gör</a>
                </div>
                
                @if($recentCategories->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentCategories as $category)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-{{ $category->is_active ? 'green' : 'red' }}-400 rounded-full mr-3"></span>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $category->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $category->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                        {{ $category->tests_count ?? 0 }} test
                                    </span>
                                    <a href="{{ route('admin.test-categories.edit', $category) }}" 
                                       class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-4xl mb-2">📂</div>
                        <p class="text-gray-500">Henüz kategori eklenmemiş</p>
                        <a href="{{ route('admin.test-categories.create') }}" 
                           class="inline-block mt-2 text-blue-600 hover:text-blue-800 font-medium">İlk kategoriyi oluştur</a>
                    </div>
                @endif
            </div>

            <!-- Son Testler -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">📝 Son Eklenen Testler</h3>
                    <a href="{{ route('admin.tests.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">Tümünü Gör</a>
                </div>
                
                @if($recentTests->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentTests as $test)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-{{ $test->is_active ? 'green' : 'red' }}-400 rounded-full mr-3"></span>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $test->title }}</h4>
                                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                                            <span>{{ $test->created_at->diffForHumans() }}</span>
                                            @if($test->categories->isNotEmpty())
                                                <span>•</span>
                                                <span>{{ $test->categories->first()->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">
                                        {{ $test->question_count ?? 0 }} soru
                                    </span>
                                    <a href="{{ route('admin.tests.edit', $test) }}" 
                                       class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-4xl mb-2">📝</div>
                        <p class="text-gray-500">Henüz test eklenmemiş</p>
                        <a href="{{ route('admin.tests.create') }}" 
                           class="inline-block mt-2 text-green-600 hover:text-green-800 font-medium">İlk testi oluştur</a>
                    </div>
                @endif
            </div>

            <!-- Son Test Sonuçları -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">📊 Son Test Sonuçları</h3>
                    <span class="text-sm text-gray-500">Son 10 sonuç</span>
                </div>
                
                @if($recentResults->count() > 0)
                    <div class="space-y-2">
                        @foreach($recentResults as $result)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-{{ $result->percentage >= 80 ? 'green' : ($result->percentage >= 60 ? 'yellow' : 'red') }}-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-{{ $result->percentage >= 80 ? 'green' : ($result->percentage >= 60 ? 'yellow' : 'red') }}-800 text-xs font-bold">
                                            {{ number_format($result->percentage) }}%
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ $result->user->name }}</h5>
                                        <p class="text-sm text-gray-500">{{ $result->test->title }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ $result->correct_answers }}/{{ $result->total_questions }}</p>
                                    <p class="text-xs text-gray-500">{{ $result->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-4xl mb-2">📊</div>
                        <p class="text-gray-500">Henüz test sonucu yok</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection