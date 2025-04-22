<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrivateLessonHomeworkSubmissionFile extends Model
{
    protected $table = 'private_lesson_homework_submission_files';

    protected $fillable = [
        'submission_id',
        'file_path',
        'original_filename',
        'submission_date',
    ];

    /**
     * Get the submission this file belongs to
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(PrivateLessonHomeworkSubmission::class, 'submission_id');
    }
}