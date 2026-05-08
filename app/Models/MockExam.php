<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MockExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'teacher_id',
        'word_set_id',
        'name',
        'description',
        'start_time',
        'time_per_question',
        'is_active',
    ];

    protected $casts = [
        'start_time'        => 'datetime',
        'is_active'         => 'boolean',
        'time_per_question' => 'integer',
    ];

    /**
     * Model boot - otomatik kod üretimi
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($mockExam) {
            if (empty($mockExam->code)) {
                $mockExam->code = self::generateUniqueCode();
            }
        });
    }

    /**
     * Benzersiz RS-XXXXXXXX kodu üret
     */
    public static function generateUniqueCode(): string
    {
        do {
            // 8 haneli rastgele rakam
            $code = 'RS-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (self::where('code', $code)->exists());

        return $code;
    }

    // ============================================================
    // İlişkiler
    // ============================================================

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Tek word set (Exam'dan farkı: belongsTo, belongsToMany değil)
     */
    public function wordSet()
    {
        return $this->belongsTo(WordSet::class, 'word_set_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'mock_exam_student', 'mock_exam_id', 'student_id')
            ->withPivot('started_at', 'completed_at', 'score', 'answers')
            ->withTimestamps();
    }

    public function results()
    {
        return $this->hasMany(MockExamResult::class);
    }

    // ============================================================
    // Yardımcı Metodlar (Exam ile aynı)
    // ============================================================

    /**
     * Sınavdaki tüm kelimeleri al (tek setten)
     */
    public function getAllWords()
    {
        if (!$this->wordSet) {
            return collect();
        }

        return $this->wordSet->words->shuffle();
    }

    /**
     * Soruları üret (set'teki tüm kelimeler, karışık)
     */
    public function generateQuestions($questionCount = null)
    {
        $allWords = $this->getAllWords();

        if ($allWords->isEmpty()) {
            return collect();
        }

        $count = $questionCount ?? $allWords->count();
        $count = min($count, $allWords->count());

        return $allWords->random($count);
    }

    /**
     * Toplam kelime sayısı
     */
    public function getTotalWordCount()
    {
        return $this->getAllWords()->count();
    }

    /**
     * Toplam sınav süresi (saniye)
     */
    public function getTotalDuration()
    {
        $questionCount = $this->getTotalWordCount();
        return $questionCount * $this->time_per_question;
    }
}