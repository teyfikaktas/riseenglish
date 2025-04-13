<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrivateLessonExamResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'report_id',
        'subject_id',
        'subject_name',
        'questions_correct',
        'questions_wrong',
        'questions_unanswered',
    ];

    /**
     * Get the report this exam result belongs to
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(PrivateLessonReport::class, 'report_id');
    }
    
    /**
     * Get the subject this exam result is for
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}