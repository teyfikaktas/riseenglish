<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'score',
        'total_questions',
        'time_spent',
        'success_rate',
        'answers',
        'completed_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'completed_at' => 'datetime',
        'success_rate' => 'decimal:2',
    ];

    // İlişkiler
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Başarı durumu
    public function isPassed()
    {
        return $this->success_rate >= 60; // %60 baraj
    }

    // Doğru cevap sayısı
    public function getCorrectAnswersCount()
    {
        return collect($this->answers)->where('is_correct', true)->count();
    }

    // Yanlış cevap sayısı
    public function getWrongAnswersCount()
    {
        return collect($this->answers)->where('is_correct', false)->count();
    }
}