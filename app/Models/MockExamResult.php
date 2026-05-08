<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'mock_exam_id',
        'student_id',
        'score',
        'total_questions',
        'time_spent',
        'success_rate',
        'answers',
        'entered_at',
        'completed_at',
        'sms_sent',
        'violation',
        'violation_reason',
    ];

    protected $casts = [
        'answers'      => 'array',
        'entered_at'   => 'datetime',
        'completed_at' => 'datetime',
        'success_rate' => 'decimal:2',
        'sms_sent'     => 'boolean',
        'violation'    => 'boolean',
    ];

    // ============================================================
    // İlişkiler
    // ============================================================

    public function mockExam()
    {
        return $this->belongsTo(MockExam::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // ============================================================
    // Yardımcı Metodlar (ExamResult ile birebir aynı)
    // ============================================================

    public function isPassed()
    {
        return $this->success_rate >= 60;
    }

    public function getCorrectAnswersCount()
    {
        return collect($this->answers)->where('is_correct', true)->count();
    }

    public function getWrongAnswersCount()
    {
        return collect($this->answers)->where('is_correct', false)->count();
    }

    public function calculateSuccessRate()
    {
        if ($this->total_questions == 0) {
            return 0;
        }
        return round(($this->getCorrectAnswersCount() / $this->total_questions) * 100);
    }

    /**
     * Sıralanmış sonuçları getir
     */
    public static function getRankedResults($mockExamId)
    {
        $results = self::where('mock_exam_id', $mockExamId)
            ->whereNotNull('completed_at')
            ->with('student')
            ->get();

        $rankedResults = $results->map(function ($result) {
            return [
                'result'       => $result,
                'correctCount' => $result->getCorrectAnswersCount(),
                'wrongCount'   => $result->getWrongAnswersCount(),
                'successRate'  => $result->calculateSuccessRate(),
            ];
        });

        $sorted = $rankedResults->sortByDesc(function ($item) {
            return [$item['successRate'], $item['correctCount']];
        })->values();

        $currentRank     = 0;
        $previousRate    = null;
        $previousCorrect = null;

        return $sorted->map(function ($item, $index) use (&$currentRank, &$previousRate, &$previousCorrect) {
            if ($previousRate !== $item['successRate'] || $previousCorrect !== $item['correctCount']) {
                $currentRank = $index + 1;
            }

            $previousRate    = $item['successRate'];
            $previousCorrect = $item['correctCount'];

            return array_merge($item, ['rank' => $currentRank]);
        });
    }

    /**
     * Sıra badge'i
     */
    public function getRankBadge($rank)
    {
        $badges = [
            1 => ['class' => 'rank-1', 'text' => 'BİRİNCİ'],
            2 => ['class' => 'rank-2', 'text' => 'İKİNCİ'],
            3 => ['class' => 'rank-3', 'text' => 'ÜÇÜNCÜ'],
        ];

        return $badges[$rank] ?? ['class' => '', 'text' => ''];
    }
}