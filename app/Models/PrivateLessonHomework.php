<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrivateLessonHomework extends Model
{
    protected $fillable = [
        'session_id',
        'title',
        'description',
        'due_date',
    ];

    /**
     * Get the session for this homework
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(PrivateLessonSession::class, 'session_id');
    }

    /**
     * Get the submissions for this homework
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(PrivateLessonHomeworkSubmission::class, 'homework_id');
    }
}