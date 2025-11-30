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
                'start_time' => $exam->start_time,
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
            'wordSets.words' => function($query) {
                $query->select('words.id', 'words.word', 'words.definition', 'words.word_set_id') // ✅ english/turkish değil word/definition
                      ->inRandomOrder();
            }
        ])
        ->findOrFail($examId);
    
    // Tüm setlerden kelimeleri topla
    $questions = $exam->wordSets->flatMap(function($set) {
        return $set->words;
    })->shuffle()->values();
    
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
                'total_questions' => $questions->count(),
            ],
            'questions' => $questions->map(function($word, $index) {
                return [
                    'question_number' => $index + 1,
                    'word_id' => $word->id,
                    'english' => $word->word,        // ✅ word kolonunu english olarak dön
                    'turkish' => $word->definition,  // ✅ definition kolonunu turkish olarak dön
                ];
            })
        ]
    ]);
}
}