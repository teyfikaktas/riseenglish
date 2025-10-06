<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWord extends Model
{
    use HasFactory;

    protected $fillable = [
        'word_set_id',
        'english_word',
        'turkish_meaning',
        'word_type'
    ];

    // WordSet ilişkisi
    public function wordSet()
    {
        return $this->belongsTo(WordSet::class);
    }
}