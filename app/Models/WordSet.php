<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'color',
        'is_active',
        'word_count'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // words tablosu ile ilişki
    public function words()
    {
        return $this->hasMany(Word::class, 'category', 'id');
    }

    // user_words tablosu ile ilişki - YENİ!
    public function userWords()
    {
        return $this->hasMany(UserWord::class, 'word_set_id');
    }

    // 50'li gruplar halinde getir
    public function getWordsInChunks($chunkSize = 50)
    {
        return $this->words()
            ->orderBy('id')
            ->get()
            ->chunk($chunkSize);
    }

    // Belirli bir sayfayı getir
    public function getChunkByPage($page = 1, $perPage = 50)
    {
        return $this->words()
            ->orderBy('id')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
    }

    // Toplam sayfa sayısı
    public function getTotalChunks($perPage = 50)
    {
        $totalWords = $this->words()->count();
        return $totalWords > 0 ? ceil($totalWords / $perPage) : 0;
    }
}