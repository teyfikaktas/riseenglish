<?php

namespace App\Http\Controllers;

use App\Models\ChainActivity;
use App\Models\ChainProgress;
use App\Models\WordSet;
use App\Models\WordSetQuizLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WordSetController extends Controller
{
    public function submitQuiz(Request $request, $id)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0',
            'total_questions' => 'required|integer|min:1',
            'time_spent' => 'nullable|integer|min:0',
            'answers' => 'nullable|array',
        ]);

        $wordSet = WordSet::findOrFail($id);
        $user = $request->user();
        $successRate = round(($validated['score'] / $validated['total_questions']) * 100, 2);

        $log = DB::transaction(function () use ($validated, $wordSet, $user, $successRate) {
            $log = WordSetQuizLog::create([
                'user_id' => $user->id,
                'word_set_id' => $wordSet->id,
                'score' => $validated['score'],
                'total_questions' => $validated['total_questions'],
                'success_rate' => $successRate,
                'time_spent' => $validated['time_spent'] ?? null,
                'answers' => $validated['answers'] ?? null,
            ]);

            $progress = ChainProgress::firstOrCreate(
                ['user_id' => $user->id],
                ['days_completed' => 0, 'current_streak' => 0, 'longest_streak' => 0]
            );

            ChainActivity::create([
                'user_id' => $user->id,
                'chain_progress_id' => $progress->id,
                'content' => 'Word set quiz completed: ' . $wordSet->name,
                'activity_date' => now()->toDateString(),
            ]);

            $today = now()->startOfDay();
            $lastCompleted = $progress->last_completed_at?->copy()->startOfDay();

            if (!$lastCompleted || !$lastCompleted->equalTo($today)) {
                $yesterday = $today->copy()->subDay();
                $progress->days_completed++;
                $progress->current_streak = $lastCompleted && $lastCompleted->equalTo($yesterday)
                    ? $progress->current_streak + 1
                    : 1;
                $progress->longest_streak = max($progress->longest_streak, $progress->current_streak);
                $progress->last_completed_at = now();
                $progress->save();
            }

            return $log;
        });

        return response()->json([
            'success' => true,
            'message' => 'Quiz sonucu başarıyla kaydedildi.',
            'log_id' => $log->id,
            'score' => $log->score,
            'total_questions' => $log->total_questions,
            'success_rate' => (float) $log->success_rate,
        ], 201);
    }
}