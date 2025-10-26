<?php
namespace App\Models;

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
        'fee',
        'payment_status',
        'paid_amount',
        'payment_date',
        'status',
        'notes',
        'teacher_notes',
        'group_id', 
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
     * 🔥 Grup dersindeki diğer öğrencilerin session'larını getir
     */
    public function groupSessions(): HasMany
    {
        return $this->hasMany(PrivateLessonSession::class, 'group_id', 'group_id')
            ->where('start_date', $this->start_date)
            ->where('start_time', $this->start_time);
    }

    /**
     * Get the occurrences for this session
     */
    public function occurrences(): HasMany
    {
        return $this->hasMany(PrivateLessonOccurrence::class, 'session_id');
    }

    /**
     * Get the topics covered in this session
     */
    public function sessionTopics(): HasMany
    {
        return $this->hasMany(SessionTopic::class, 'session_id');
    }

    /**
     * Get the materials for this session
     */
    public function materials(): HasMany
    {
        return $this->hasMany(PrivateLessonMaterial::class, 'session_id');
    }

    /**
     * Get the homeworks for this session
     */
    public function homeworks(): HasMany
    {
        return $this->hasMany(PrivateLessonHomework::class, 'session_id');
    }

    /**
     * Get the notifications for this session
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(PrivateLessonNotification::class, 'session_id');
    }

    /**
     * Check if the session is paid
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if the session is partially paid
     */
    public function isPartiallyPaid(): bool
    {
        return $this->payment_status === 'partially_paid';
    }

    /**
     * Check if the session is pending payment
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Get remaining amount to be paid
     */
    public function getRemainingAmount(): float
    {
        return $this->fee - $this->paid_amount;
    }
}