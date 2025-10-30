<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;
use App\Models\Word;
use App\Models\WordSet;

// API route for OTP SMS without middleware
Route::post('/send-otp', [OtpController::class, 'sendOtp']);

Route::get('/languages', function() {
    try {
        $languages = Word::getAvailableLanguages();
        return response()->json($languages);
    } catch (\Exception $e) {
        \Log::error('Languages API Error: ' . $e->getMessage());
        return response()->json(['en'], 200);
    }
});
Route::get('/categories/{lang}', function($lang) {
    try {
        $userId = auth()->check() ? auth()->id() : 1;
        
        $categories = WordSet::where('is_active', 1)
            ->where(function($query) use ($userId) {
                $query->where('user_id', 1)
                      ->orWhere('user_id', $userId);
            })
            ->whereHas('words', function($query) use ($lang) {
                $query->where('lang', $lang);
            })
            ->select('id', 'name', 'description', 'color', 'word_count')
            ->get()
            ->map(function($category) use ($lang) {
                // Sadece bu dildeki kelime sayısına göre set sayısını hesapla
                $wordCount = $category->words()->where('lang', $lang)->count();
                $totalChunks = $wordCount > 0 ? ceil($wordCount / 50) : 0;
                $category->total_sets = $totalChunks;
                return $category;
            });
        
        return response()->json($categories);
    } catch (\Exception $e) {
        \Log::error('Categories API Error: ' . $e->getMessage());
        return response()->json([]);
    }
});
Route::get('/words/{categoryId}/{page}/{lang?}', function($categoryId, $page = 1, $lang = null) {
    try {
        $category = WordSet::findOrFail($categoryId);
        
        // Eğer lang gönderilmediyse, ilk kelimeden al
        if (!$lang) {
            $firstWord = $category->words()->first();
            $lang = $firstWord ? $firstWord->lang : 'en';
        }
        
        // Sadece o dildeki kelimeleri getir
        $words = $category->words()
            ->where('lang', $lang)
            ->orderBy('id')
            ->skip(($page - 1) * 50)
            ->take(50)
            ->get();
        
        $gameWords = $words->map(function($word) {
            return [
                'english' => $word->word,
                'turkish' => $word->definition,
                'id' => $word->id
            ];
        });
        
        $totalWords = $category->words()->where('lang', $lang)->count();
        
        return response()->json([
            'words' => $gameWords,
            'current_page' => $page,
            'total_pages' => $totalWords > 0 ? ceil($totalWords / 50) : 0,
            'category_name' => $category->name,
            'lang' => $lang // Dil bilgisini de gönder
        ]);
    } catch (\Exception $e) {
        \Log::error('Words API Error: ' . $e->getMessage());
        return response()->json([
            'words' => [
                ['english' => 'Apple', 'turkish' => 'Elma', 'id' => 1],
                ['english' => 'Book', 'turkish' => 'Kitap', 'id' => 2],
            ],
            'current_page' => 1,
            'total_pages' => 1,
            'category_name' => 'Error',
            'lang' => 'en'
        ]);
    }
});