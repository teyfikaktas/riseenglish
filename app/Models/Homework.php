<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Homework extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'homeworks';

    protected $fillable = [
        'title',
        'description',
        'course_id',
        'due_date',
        'published_at',
        'max_score',
        'is_active',
        'file_path',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'published_at' => 'datetime',
        'is_active' => 'boolean',
        'max_score' => 'integer',
    ];

    // Ödev hangi kursa ait
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Öğrencilerin gönderdiği ödev yanıtları
    public function submissions()
    {
        return $this->hasMany(HomeworkSubmission::class);
    }
}