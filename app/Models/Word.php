<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'word',
        'definition',
        'lang',
        'category',
        'difficulty',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Dile göre kelimeleri getir
    public static function getByLanguage($lang)
    {
        return self::where('lang', $lang)
                  ->where('is_active', true)
                  ->get();
    }

    // Zorluk seviyesine göre kelimeleri getir
    public static function getByDifficulty($difficulty, $lang = null)
    {
        $query = self::where('difficulty', $difficulty)
                    ->where('is_active', true);
        
        if ($lang) {
            $query->where('lang', $lang);
        }
        
        return $query->get();
    }

    // Rastgele quiz soruları getir
    public static function getQuizWords($lang, $difficulty = null, $count = 20)
    {
        $query = self::where('lang', $lang)
                    ->where('is_active', true);
        
        if ($difficulty) {
            $query->where('difficulty', $difficulty);
        }
        
        return $query->inRandomOrder()
                    ->limit($count)
                    ->get();
    }

    // Mevcut zorluk seviyelerini getir
    public static function getDifficultyLevels($lang = null)
    {
        $query = self::where('is_active', true)
                    ->distinct();
        
        if ($lang) {
            $query->where('lang', $lang);
        }
        
        return $query->pluck('difficulty')
                    ->filter()
                    ->sort()
                    ->values();
    }

    // Mevcut dilleri getir
    public static function getAvailableLanguages()
    {
        return self::where('is_active', true)
                  ->distinct()
                  ->pluck('lang')
                  ->sort()
                  ->values();
    }

    // Scope'lar
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLanguage($query, $lang)
    {
        return $query->where('lang', $lang);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    // Accessor'lar
    public function getLanguageNameAttribute()
    {
        $languages = [
            'en' => 'İngilizce',
            'de' => 'Almanca',
            'tr' => 'Türkçe'
        ];
        
        return $languages[$this->lang] ?? $this->lang;
    }

    public function getDifficultyNameAttribute()
    {
        $difficulties = [
            'beginner' => 'Başlangıç',
            'intermediate' => 'Orta',
            'advanced' => 'İleri'
        ];
        
        return $difficulties[$this->difficulty] ?? $this->difficulty;
    }
}