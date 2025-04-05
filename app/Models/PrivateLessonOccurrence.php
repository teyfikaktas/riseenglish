<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrivateLessonOccurrence extends Model
{
    protected $fillable = [
        'session_id',
        'lesson_date',
        'start_time',
        'end_time',
        'status',
        'teacher_notes',
    ];

    /**
     * Get the session for this occurrence
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(PrivateLessonSession::class, 'session_id');
    }

    /**
     * Get the materials for this occurrence
     */
    public function materials(): HasMany
    {
        return $this->hasMany(PrivateLessonMaterial::class, 'occurrence_id');
    }

    /**
     * Get the homeworks for this occurrence
     */
    public function homeworks(): HasMany
    {
        return $this->hasMany(PrivateLessonHomework::class, 'occurrence_id');
    }

    /**
     * Get the notifications for this occurrence
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(PrivateLessonNotification::class, 'occurrence_id');
    }
}