<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class PrivateLessonHomeworkSubmission extends Model
{
    protected $fillable = [
        'homework_id',
        'student_id',
        'submission_content',
        'file_path',
        'teacher_feedback',
        'score',
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
}