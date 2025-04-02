<?php
namespace App\Models\PrivateLesson;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class PrivateLessonSession extends Model
{
    protected $fillable = [
        'private_lesson_id',
        'teacher_id',
        'student_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_recurring',
        'start_date',
        'end_date',
        'location',
        'status',
        'notes',
    ];

    /**
     * Get the private lesson for this session
     */
    public function privateLesson(): BelongsTo
    {
        return $this->belongsTo(PrivateLesson::class);
    }

    /**
     * Get the teacher for this session
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the student for this session
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the occurrences for this session
     */
    public function occurrences(): HasMany
    {
        return $this->hasMany(PrivateLessonOccurrence::class, 'session_id');
    }
}