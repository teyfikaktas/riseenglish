<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class StudentExamController extends Controller
{
    /**
     * Öğrenciye atanan sınavları listele
     */
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
                    'start_time' => $exam->start_time->toIso8601String(),
                    'time_per_question' => $exam->time_per_question,
                    'is_active' => $exam->is_active,
                    'word_set_count' => $exam->word_sets_count,
                    'word_sets' => $exam->wordSets->map(function($set) {
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

    /**
     * Sınav detayı ve soruları getir
     */
    public function show($examId)
    {
        $studentId = auth()->id();
        $student = auth()->user();

        $exam = Exam::whereHas('students', function($query) use ($studentId) {
                $query->where('users.id', $studentId);
            })
            ->with([
                'teacher:id,name',
                'wordSets'
            ])
            ->findOrFail($examId);

        // ✅ Öğrenci bu sınavı daha önce yapmış mı kontrol et
        $existingResult = ExamResult::where('exam_id', $examId)
            ->where('student_id', $studentId)
            ->first();

        if ($existingResult) {
            return response()->json([
                'success' => false,
                'message' => 'Bu sınavı zaten tamamladınız',
                'data' => [
                    'already_completed' => true,
                    'result' => [
                        'score' => $existingResult->score,
                        'total_questions' => $existingResult->total_questions,
                        'success_rate' => round($existingResult->success_rate, 2),
                        'completed_at' => $existingResult->completed_at->toIso8601String(),
                    ]
                ]
            ], 422);
        }

        // ✅ Sınava girişte veliye SMS gönder (sadece bir kez)
        $this->sendExamStartSms($student, $exam);

        // Word set ID'lerini al
        $wordSetIds = $exam->wordSets->pluck('id')->toArray();

        // Kelimeleri çek - category kullan
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

    /**
     * Sınava başlangıçta SMS gönder
     */
    private function sendExamStartSms($student, $exam)
    {
        try {
            // Cache key - bugün bu öğrenci için bu sınava zaten SMS gönderildi mi?
            $today = now()->format('Y-m-d');
            $cacheKey = "exam_start_sms_{$student->id}_{$exam->id}_{$today}";

            // Zaten gönderildiyse tekrar gönderme
            if (Cache::has($cacheKey)) {
                Log::info('Exam start SMS: Bugün zaten gönderilmiş', [
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                    'cache_key' => $cacheKey
                ]);
                return;
            }

            // Veli telefon numaralarını topla
            $parentPhones = [];
            
            if (!empty($student->parent_phone_number)) {
                $parentPhones[] = [
                    'number' => $student->parent_phone_number,
                    'type' => '1. Veli'
                ];
            }
            
            if (!empty($student->parent_phone_number_2)) {
                $parentPhones[] = [
                    'number' => $student->parent_phone_number_2,
                    'type' => '2. Veli'
                ];
            }

            if (empty($parentPhones)) {
                Log::info('Exam start SMS: Veli telefonu bulunamadı', [
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'exam_id' => $exam->id
                ]);
                return;
            }

            // SMS içeriğini hazırla
            $smsContent = sprintf(
                "Sayın Veli, %s adlı öğrenciniz '%s' sınavına başlamıştır. - Rise English",
                $student->name,
                $exam->name
            );

            $sentCount = 0;

            // Her veli numarasına SMS gönder
            foreach ($parentPhones as $phone) {
                try {
                    $smsResult = \App\Services\SmsService::sendSms($phone['number'], $smsContent);
                    
                    if ($smsResult) {
                        $sentCount++;
                        Log::info('Exam start SMS gönderildi', [
                            'student_id' => $student->id,
                            'student_name' => $student->name,
                            'parent_type' => $phone['type'],
                            'parent_phone' => $phone['number'],
                            'exam_id' => $exam->id,
                            'exam_name' => $exam->name,
                            'teacher_name' => $exam->teacher->name
                        ]);
                    } else {
                        Log::error('Exam start SMS gönderilemedi', [
                            'student_id' => $student->id,
                            'parent_type' => $phone['type'],
                            'parent_phone' => $phone['number']
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Exam start SMS hatası', [
                        'student_id' => $student->id,
                        'parent_type' => $phone['type'],
                        'parent_phone' => $phone['number'],
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // En az bir SMS gönderildiyse cache'e kaydet (24 saat)
            if ($sentCount > 0) {
                Cache::put($cacheKey, true, 86400);
                Log::info('Exam start SMS cache kaydedildi', [
                    'cache_key' => $cacheKey,
                    'sent_count' => $sentCount
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Exam start SMS process error: ' . $e->getMessage());
            // SMS hatası sınav başlamasını etkilemesin
        }
    }

    /**
     * Sınav sonucunu kaydet
     */
    public function submitResult(Request $request, $examId)
    {
        $request->validate([
            'score' => 'required|integer|min:0',
            'total_questions' => 'required|integer|min:1',
            'time_spent' => 'required|integer|min:0',
            'answers' => 'required|array',
            'answers.*.question_number' => 'required|integer',
            'answers.*.word_id' => 'required|integer',
            'answers.*.selected_answer' => 'nullable|string',
            'answers.*.is_correct' => 'required|boolean',
            'answers.*.time_taken' => 'required|integer|min:0',
        ]);

        $studentId = auth()->id();
        $student = auth()->user();

        // Sınavı kontrol et
        $exam = Exam::whereHas('students', function($query) use ($studentId) {
            $query->where('users.id', $studentId);
        })->with('teacher:id,name')->findOrFail($examId);

        // Öğrenci bu sınavı daha önce yapmış mı kontrol et
        $existingResult = ExamResult::where('exam_id', $examId)
            ->where('student_id', $studentId)
            ->first();

        if ($existingResult) {
            return response()->json([
                'success' => false,
                'message' => 'Bu sınavı zaten tamamladınız'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Başarı oranını hesapla
            $successRate = ($request->score / $request->total_questions) * 100;

            // Sonucu kaydet
            $result = ExamResult::create([
                'exam_id' => $examId,
                'student_id' => $studentId,
                'score' => $request->score,
                'total_questions' => $request->total_questions,
                'time_spent' => $request->time_spent,
                'success_rate' => $successRate,
                'answers' => $request->answers,
                'completed_at' => now(),
            ]);

            // ✅ Veliye sonuç SMS'i gönder
            $this->sendExamResultSms($student, $exam, $result);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sınav sonucunuz kaydedildi',
                'data' => [
                    'result_id' => $result->id,
                    'score' => $result->score,
                    'total_questions' => $result->total_questions,
                    'success_rate' => round($result->success_rate, 2),
                    'is_passed' => $result->isPassed(),
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Exam result save error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Sınav sonucu kaydedilemedi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sınav sonucu SMS'i gönder
     */
    private function sendExamResultSms($student, $exam, $result)
    {
        try {
            // Veli telefon numaralarını topla
            $parentPhones = [];
            
            if (!empty($student->parent_phone_number)) {
                $parentPhones[] = [
                    'number' => $student->parent_phone_number,
                    'type' => '1. Veli'
                ];
            }
            
            if (!empty($student->parent_phone_number_2)) {
                $parentPhones[] = [
                    'number' => $student->parent_phone_number_2,
                    'type' => '2. Veli'
                ];
            }

            if (empty($parentPhones)) {
                Log::info('Exam result SMS: Veli telefonu bulunamadı', [
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'exam_id' => $exam->id
                ]);
                return;
            }

            // SMS içeriğini hazırla
            $smsContent = sprintf(
                "Sayın Veli, %s adlı öğrenciniz '%s' sınavını tamamladı. Sonuç: %d/%d (%%%d). - Rise English",
                $student->name,
                $exam->name,
                $result->score,
                $result->total_questions,
                round($result->success_rate)
            );

            // Her veli numarasına SMS gönder
            foreach ($parentPhones as $phone) {
                try {
                    $smsResult = \App\Services\SmsService::sendSms($phone['number'], $smsContent);
                    
                    if ($smsResult) {
                        Log::info('Exam result SMS gönderildi', [
                            'student_id' => $student->id,
                            'student_name' => $student->name,
                            'parent_type' => $phone['type'],
                            'parent_phone' => $phone['number'],
                            'exam_id' => $exam->id,
                            'exam_name' => $exam->name,
                            'teacher_name' => $exam->teacher->name,
                            'score' => $result->score,
                            'total' => $result->total_questions,
                            'success_rate' => round($result->success_rate)
                        ]);
                    } else {
                        Log::error('Exam result SMS gönderilemedi', [
                            'student_id' => $student->id,
                            'parent_type' => $phone['type'],
                            'parent_phone' => $phone['number']
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Exam result SMS hatası', [
                        'student_id' => $student->id,
                        'parent_type' => $phone['type'],
                        'parent_phone' => $phone['number'],
                        'error' => $e->getMessage()
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('SMS send process error: ' . $e->getMessage());
            // SMS hatası sınav kaydını etkilemesin
        }
    }

    /**
     * Öğrencinin sınav sonuçlarını listele
     */
    public function results(Request $request)
    {
        $studentId = auth()->id();

        $results = ExamResult::where('student_id', $studentId)
            ->with(['exam:id,name,start_time,teacher_id', 'exam.teacher:id,name'])
            ->orderBy('completed_at', 'desc')
            ->get()
            ->map(function($result) {
                return [
                    'id' => $result->id,
                    'exam_name' => $result->exam->name,
                    'teacher_name' => $result->exam->teacher->name,
                    'score' => $result->score,
                    'total_questions' => $result->total_questions,
                    'success_rate' => round($result->success_rate, 2),
                    'is_passed' => $result->isPassed(),
                    'time_spent' => $result->time_spent,
                    'completed_at' => $result->completed_at->toIso8601String(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }
}