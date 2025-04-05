<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrivateLessonHomework extends Model
{
    protected $fillable = [
        'occurrence_id',
        'title',
        'description',
        'due_date',
    ];

    /**
     * Get the occurrence for this homework
     */
    public function occurrence(): BelongsTo
    {
        return $this->belongsTo(PrivateLessonOccurrence::class, 'occurrence_id');
    }

    /**
     * Get the submissions for this homework
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(PrivateLessonHomeworkSubmission::class, 'homework_id');
    }
}