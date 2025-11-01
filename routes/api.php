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
        
        \Log::info('Categories API Called', [
            'lang' => $lang,
            'userId' => $userId,
            'is_authenticated' => auth()->check()
        ]);
        
        $categories = WordSet::where('is_active', 1)
            ->where(function($query) use ($userId) {
                $query->where('user_id', 1)
                      ->orWhere('user_id', 36)
                      ->orWhere('user_id', $userId);
            })
            ->whereHas('words', function($query) use ($lang) {
                $query->where('lang', $lang);
            })
            ->select('id', 'name', 'description', 'color', 'word_count', 'user_id') // ← user_id ekledim
            ->get()
            ->map(function($category) use ($lang) {
                $wordCount = $category->words()->where('lang', $lang)->count();
                $totalChunks = $wordCount > 0 ? ceil($wordCount / 50) : 0;
                
                \Log::info('Category Processed', [
                    'category_id' => $category->id,
                    'category_name' => $category->name,
                    'user_id' => $category->user_id,
                    'actual_word_count' => $wordCount,
                    'total_sets' => $totalChunks
                ]);
                
                $category->total_sets = $totalChunks;
                return $category;
            });
        
        \Log::info('Categories Response', [
            'total_categories' => $categories->count()
        ]);
        
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