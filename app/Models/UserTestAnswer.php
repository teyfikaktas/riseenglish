<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTestAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_test_result_id',
        'question_id',
        'selected_choice_id',
        'is_correct',
        'points_earned'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'points_earned' => 'integer'
    ];

    // İlişkiler
    public function userTestResult(): BelongsTo
    {
        return $this->belongsTo(UserTestResult::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedChoice(): BelongsTo
    {
        return $this->belongsTo(Choice::class, 'selected_choice_id');
    }

    // Scope'lar
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    public function scopeWrong($query)
    {
        return $query->where('is_correct', false);
    }

    public function scopeByResult($query, $resultId)
    {
        return $query->where('user_test_result_id', $resultId);
    }

    public function scopeByQuestion($query, $questionId)
    {
        return $query->where('question_id', $questionId);
    }

    // Yardımcı metodlar
    public function getAnswerLetter(): string
    {
        return $this->selectedChoice->choice_letter ?? '';
    }

    public function getAnswerText(): string
    {
        return $this->selectedChoice->choice_text ?? '';
    }

    public function getQuestionText(): string
    {
        return $this->question->question_text ?? '';
    }

    public function getCorrectChoice()
    {
        return $this->question->choices()->where('is_correct', true)->first();
    }

    public function getCorrectAnswerLetter(): string
    {
        return $this->getCorrectChoice()->choice_letter ?? '';
    }

    public function getCorrectAnswerText(): string
    {
        return $this->getCorrectChoice()->choice_text ?? '';
    }
}