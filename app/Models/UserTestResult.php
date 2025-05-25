<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserTestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'score',
        'correct_answers',
        'wrong_answers',
        'empty_answers',
        'total_questions',
        'percentage',
        'duration_seconds',
        'started_at',
        'completed_at',
        'status',
        'answers'
    ];

    protected $casts = [
        'score' => 'integer',
        'correct_answers' => 'integer',
        'wrong_answers' => 'integer',
        'empty_answers' => 'integer',
        'total_questions' => 'integer',
        'percentage' => 'decimal:2',
        'duration_seconds' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'answers' => 'array'
    ];

    // İlişkiler
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function userTestAnswers(): HasMany
    {
        return $this->hasMany(UserTestAnswer::class);
    }

    // Scope'lar
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByTest($query, $testId)
    {
        return $query->where('test_id', $testId);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeStarted($query)
    {
        return $query->where('status', 'started');
    }

    public function scopeInProgress($query)
    {
        return $query->whereIn('status', ['started', 'in_progress']);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('completed_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('completed_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('completed_at', now()->month)
                    ->whereYear('completed_at', now()->year);
    }

    // Accessor'lar
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_seconds) {
            return '0:00';
        }

        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        $seconds = $this->duration_seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    public function getSuccessLevelAttribute(): string
    {
        if ($this->percentage >= 80) {
            return 'excellent';
        } elseif ($this->percentage >= 60) {
            return 'good';
        } elseif ($this->percentage >= 40) {
            return 'average';
        } else {
            return 'poor';
        }
    }

    public function getSuccessColorAttribute(): string
    {
        return match($this->success_level) {
            'excellent' => 'green',
            'good' => 'blue',
            'average' => 'yellow',
            'poor' => 'red',
            default => 'gray'
        };
    }

    public function getSuccessMessageAttribute(): string
    {
        return match($this->success_level) {
            'excellent' => 'Mükemmel! Harika bir performans sergileddin!',
            'good' => 'İyi! Başarılı bir sonuç aldın!',
            'average' => 'Fena değil! Biraz daha çalışarak geliştirebilirsin!',
            'poor' => 'Bu konuları tekrar çalışman faydalı olacak!',
            default => 'Test tamamlandı!'
        };
    }

    // Yardımcı metodlar
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isStarted(): bool
    {
        return $this->status === 'started';
    }

    public function isInProgress(): bool
    {
        return in_array($this->status, ['started', 'in_progress']);
    }

    public function hasPassedTest($passingScore = 60): bool
    {
        return $this->percentage >= $passingScore;
    }

    public function getGradeLevel(): string
    {
        if ($this->percentage >= 90) return 'A+';
        if ($this->percentage >= 85) return 'A';
        if ($this->percentage >= 80) return 'A-';
        if ($this->percentage >= 75) return 'B+';
        if ($this->percentage >= 70) return 'B';
        if ($this->percentage >= 65) return 'B-';
        if ($this->percentage >= 60) return 'C+';
        if ($this->percentage >= 55) return 'C';
        if ($this->percentage >= 50) return 'C-';
        if ($this->percentage >= 45) return 'D+';
        if ($this->percentage >= 40) return 'D';
        return 'F';
    }

    public function getAnswerByQuestionId($questionId)
    {
        return $this->userTestAnswers()->where('question_id', $questionId)->first();
    }

    public function getUserAnswer($questionId)
    {
        return $this->answers[$questionId] ?? null;
    }

    public function getTotalPossibleScore(): int
    {
        return $this->test->questions->sum('points') ?: $this->total_questions;
    }

    public function getEfficiencyPercentage(): float
    {
        if (!$this->duration_seconds || !$this->test->duration_minutes) {
            return 0;
        }

        $totalTimeInSeconds = $this->test->duration_minutes * 60;
        return (($totalTimeInSeconds - $this->duration_seconds) / $totalTimeInSeconds) * 100;
    }

    public function getAverageTimePerQuestion(): float
    {
        if (!$this->duration_seconds || !$this->total_questions) {
            return 0;
        }

        return $this->duration_seconds / $this->total_questions;
    }

    // Statik metodlar
    public static function getStatusOptions(): array
    {
        return [
            'started' => 'Başlatıldı',
            'in_progress' => 'Devam Ediyor',
            'completed' => 'Tamamlandı',
            'abandoned' => 'Terk Edildi',
            'expired' => 'Süresi Doldu'
        ];
    }

    public static function getUserStats($userId): array
    {
        $results = self::byUser($userId)->completed();

        return [
            'total_tests' => $results->count(),
            'average_score' => round($results->avg('percentage') ?? 0, 2),
            'best_score' => $results->max('percentage') ?? 0,
            'worst_score' => $results->min('percentage') ?? 0,
            'total_time' => $results->sum('duration_seconds') ?? 0,
            'total_questions' => $results->sum('total_questions') ?? 0,
            'total_correct' => $results->sum('correct_answers') ?? 0,
            'success_rate' => $results->count() > 0 ? 
                round(($results->where('percentage', '>=', 60)->count() / $results->count()) * 100, 2) : 0
        ];
    }

    // Boot metodu
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($result) {
            if (!$result->started_at) {
                $result->started_at = now();
            }
        });

        static::updating(function ($result) {
            if ($result->isDirty('status') && $result->status === 'completed' && !$result->completed_at) {
                $result->completed_at = now();
            }
        });

        static::deleting(function ($result) {
            // Test sonucu silinirken ilgili cevapları da sil
            $result->userTestAnswers()->delete();
        });
    }
}