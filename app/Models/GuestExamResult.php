<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestExamResult extends Model
{
    protected $fillable = [
        'mock_exam_id',
        'name',
        'phone',
        'email',
        'score',
        'total_questions',
        'success_rate',
        'answers',
        'violation',
        'violation_reason',
        'completed_at',
    ];

    protected $casts = [
        'answers'      => 'array',
        'completed_at' => 'datetime',
        'success_rate' => 'decimal:2',
        'violation'    => 'boolean',
    ];

    public function mockExam()
    {
        return $this->belongsTo(MockExam::class);
    }

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
        if ($this->total_questions == 0) return 0;
        return round(($this->getCorrectAnswersCount() / $this->total_questions) * 100);
    }

    public static function getRankedResults($mockExamId)
    {
        $results = self::where('mock_exam_id', $mockExamId)
            ->whereNotNull('completed_at')
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

    public function getRankBadge($rank)
    {
        $badges = [
            1 => ['class' => 'rank-1', 'text' => 'GÜNÜN BİRİNCİSİ'],
            2 => ['class' => 'rank-2', 'text' => 'GÜNÜN İKİNCİSİ'],
            3 => ['class' => 'rank-3', 'text' => 'GÜNÜN ÜÇÜNCÜSÜ'],
        ];

        return $badges[$rank] ?? ['class' => '', 'text' => ''];
    }
}