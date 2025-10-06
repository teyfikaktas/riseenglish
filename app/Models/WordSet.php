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

    // Kullanıcı ilişkisi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ESKI: words() yerine userWords() kullanıyoruz
    public function userWords()
    {
        return $this->hasMany(UserWord::class);
    }

    // VEYA geriye uyumluluk için words() metodunu userWords'e yönlendirebilirsiniz
    public function words()
    {
        return $this->hasMany(UserWord::class);
    }
}