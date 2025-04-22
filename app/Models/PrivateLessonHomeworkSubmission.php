<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class PrivateLessonHomeworkSubmission extends Model
{
    protected $table = 'private_lesson_homework_submissions';

    protected $fillable = [
        'homework_id',
        'student_id',
        'submission_content',
        'teacher_feedback',
        'score',
        'is_latest',
        
    ];

    /**
     * Get the homework for this submission
     */
    public function homework(): BelongsTo
    {
        return $this->belongsTo(PrivateLessonHomework::class, 'homework_id');
    }

    /**
     * Get the student for this submission
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get all files for this submission
     */
    public function files(): HasMany
    {
        return $this->hasMany(PrivateLessonHomeworkSubmissionFile::class, 'submission_id');
    }
}