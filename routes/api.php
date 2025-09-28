<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;
use App\Models\Word;
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

Route::get('/difficulties/{lang}', function($lang) {
    try {
        $difficulties = Word::getDifficultyLevels($lang);
        return response()->json($difficulties);
    } catch (\Exception $e) {
        \Log::error('Difficulties API Error: ' . $e->getMessage());
        return response()->json(['beginner', 'intermediate'], 200); // advanced'i kaldırdım
    }
});

Route::get('/words/{lang}/{difficulty?}', function($lang, $difficulty = null) {
    try {
        $words = Word::getQuizWords($lang, $difficulty, 50);
        
        $gameWords = $words->map(function($word) {
            return [
                'english' => $word->word,
                'turkish' => $word->definition,
                'difficulty' => $word->difficulty,
                'id' => $word->id
            ];
        });
        
        return response()->json($gameWords);
    } catch (\Exception $e) {
        \Log::error('Words API Error: ' . $e->getMessage());
        return response()->json([
            ['english' => 'Apple', 'turkish' => 'Elma', 'difficulty' => 'beginner', 'id' => 1],
            ['english' => 'Book', 'turkish' => 'Kitap', 'difficulty' => 'beginner', 'id' => 2],
        ]);
    }
});