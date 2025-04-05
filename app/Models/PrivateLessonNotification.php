<?php

namespace App\Models\PrivateLesson;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class PrivateLessonNotification extends Model
{
    protected $fillable = [
        'teacher_id',
        'student_id',
        'session_id', // occurrence_id yerine değiştirildi
        'message',
        'is_sms',
        'is_read',
    ];

    /**
     * Get the teacher for this notification
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the student for this notification
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the session for this notification
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(PrivateLessonSession::class, 'session_id');
    }
}