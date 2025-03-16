<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeworkSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'homework_id',
        'user_id',
        'file_path',
        'comment',
        'submitted_at',
        'score',
        'feedback',
        'graded_at',
        'status',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'score' => 'integer',
    ];

    // Ödevin kendisi
    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }

    // Ödevi gönderen öğrenci
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}