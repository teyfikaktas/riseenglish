<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Choice extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'choice_letter',
        'choice_text',
        'is_correct',
        'explanation',
        'order_number'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'order_number' => 'integer'
    ];

    // Soru ilişkisi
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    // Scope'lar
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_number');
    }
}