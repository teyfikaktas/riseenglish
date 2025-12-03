<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;

class StudentExamController extends Controller
{
    // Öğrenciye atanan sınavları listele
// StudentExamController.php - index metodu
public function index(Request $request)
{
    $studentId = auth()->id();
    
    $exams = Exam::whereHas('students', function($query) use ($studentId) {
            $query->where('users.id', $studentId);
        })
        ->with(['teacher:id,name', 'wordSets:id,name'])
        ->withCount('wordSets')
        ->select('id', 'teacher_id', 'name', 'description', 'start_time', 'time_per_question', 'is_active')
        ->orderBy('start_time', 'desc')
        ->get()
        ->map(function($exam) {
            return [
                'id' => $exam->id,
                'name' => $exam->name,
                'description' => $exam->description,
                'teacher_name' => $exam->teacher->name,
                'start_time' => $exam->start_time->toIso8601String(), // ← BURAYA SADECE ->toIso8601String() EKLE
                'time_per_question' => $exam->time_per_question,
                'is_active' => $exam->is_active,
                'word_set_count' => $exam->word_sets_count,
                'word_sets' => $exam->wordSets->map(function($set) {  // ✅ sadece name yerine obje
                    return [
                        'id' => $set->id,
                        'name' => $set->name,
                    ];
                }),
            ];
        });
    
    return response()->json([
        'success' => true,
        'data' => $exams
    ]);
}
    
// StudentExamController.php - show metodu
public function show($examId)
{
    $studentId = auth()->id();
    
    $exam = Exam::whereHas('students', function($query) use ($studentId) {
            $query->where('users.id', $studentId);
        })
        ->with([
            'teacher:id,name',
            'wordSets' // ✅ eager load'u kaldır, manuel çekeceğiz
        ])
        ->findOrFail($examId);
    
    // Word set ID'lerini al
    $wordSetIds = $exam->wordSets->pluck('id')->toArray();
    
    // Kelimeleri çek - category kullan, word_set_id yok
    $words = \App\Models\Word::whereIn('category', $wordSetIds)
        ->select('id', 'word', 'definition', 'category')
        ->inRandomOrder()
        ->get();
    
    return response()->json([
        'success' => true,
        'data' => [
            'exam' => [
                'id' => $exam->id,
                'name' => $exam->name,
                'description' => $exam->description,
                'teacher_name' => $exam->teacher->name,
                'start_time' => $exam->start_time,
                'time_per_question' => $exam->time_per_question,
                'total_questions' => $words->count(),
            ],
            'questions' => $words->map(function($word, $index) {
                return [
                    'question_number' => $index + 1,
                    'word_id' => $word->id,
                    'english' => $word->word,
                    'turkish' => $word->definition,
                ];
            })->values()
        ]
    ]);
}
}