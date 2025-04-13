<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrivateLessonReport extends Model
{
    protected $fillable = [
        'session_id',
        'questions_solved',
        'questions_correct',
        'questions_wrong',
        'questions_unanswered',
        'pros',
        'cons',
        'participation',
        'teacher_notes',
    ];

    /**
     * Get the session this report belongs to
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(PrivateLessonSession::class, 'session_id');
    }

    /**
     * Get all exam results for this report
     */
    public function examResults(): HasMany
    {
        return $this->hasMany(PrivateLessonExamResult::class, 'report_id');
    }
}