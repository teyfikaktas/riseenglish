<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordSetQuizLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'word_set_id',
        'score',
        'total_questions',
        'success_rate',
        'time_spent',
        'answers',
    ];

    protected $casts = [
        'success_rate' => 'float',
        'answers' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wordSet()
    {
        return $this->belongsTo(WordSet::class);
    }
}