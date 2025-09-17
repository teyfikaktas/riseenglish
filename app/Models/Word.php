<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'word_set_id',
        'english_word',
        'turkish_meaning',
        'word_type'
    ];

    // Kelime seti ilişkisi
    public function wordSet()
    {
        return $this->belongsTo(WordSet::class);
    }
}