<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class StudentExamController extends Controller
{
    
public function index(Request $request)
{
    $studentId = auth()->id();
    
    $exams = Exam::whereHas('students', function($query) use ($studentId) {
            $query->where('users.id', $studentId);
        })
        ->with(['teacher:id,name', 'wordSets:id,name'])
        ->withCount('wordSets')
        ->select('id', 'teacher_id', 'name', 'description', 'start_time', 'time_per_question', 'is_active')
        ->orderBy('is_active', 'desc')  // 👈 Önce aktifler (1, 0 sırası)
        ->orderBy('start_time', 'desc') // 👈 Sonra her grup kendi içinde en yeniden eskiye
        ->get()
        ->map(function($exam) use ($studentId) {
            // Tamamlanma durumunu kontrol et
            $isCompleted = ExamResult::where('exam_id', $exam->id)
                ->where('student_id', $studentId)
                ->whereNotNull('completed_at')
                ->exists();
            
            return [
                'id' => $exam->id,
                'name' => $exam->name,
                'description' => $exam->description,
                'teacher_name' => $exam->teacher->name,
                'start_time' => $exam->start_time->toIso8601String(),
                'time_per_question' => $exam->time_per_question,
                'is_active' => $exam->is_active,
                'is_completed' => $isCompleted,
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
     * ✅ Sınava giriş kontrolü ve log kaydetme
     */
    public function enterExam($examId)
    {
        try {
            $studentId = auth()->id();
            $student = auth()->user();
            $now = Carbon::now();
            
            // Sınavı bul
            $exam = Exam::whereHas('students', function($query) use ($studentId) {
                    $query->where('users.id', $studentId);
                })
                ->with('teacher:id,name')
                ->findOrFail($examId);
            
            // ✅ 1. Sınav aktif mi?
            if (!$exam->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu sınav aktif değil.'
                ], 403);
            }
            
            // ✅ 2. Sınav zamanı kontrolü
            $examStartTime = Carbon::parse($exam->start_time);
            $allowedStartTime = $examStartTime->copy()->subMinutes(5); // 5 dakika önce giriş izni
            
            if ($now->lt($allowedStartTime)) {
                $remainingMinutes = $now->diffInMinutes($allowedStartTime);
                return response()->json([
                    'success' => false,
                    'message' => "Sınav henüz başlamadı. Sınav {$remainingMinutes} dakika sonra başlayacak.",
                    'exam_start_time' => $examStartTime->toIso8601String(),
                    'remaining_minutes' => $remainingMinutes
                ], 403);
            }
            
            // ✅ 3. Sınav süresi geçmiş mi? (başlangıçtan 24 saat sonra geçersiz)
            $examExpireTime = $examStartTime->copy()->addHours(24);
            if ($now->gt($examExpireTime)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu sınavın süresi dolmuştur.'
                ], 403);
            }
            
            // ✅ 4. Daha önce tamamlanmış mı?
            $existingResult = ExamResult::where('exam_id', $examId)
                ->where('student_id', $studentId)
                ->whereNotNull('completed_at')
                ->first();
                
            if ($existingResult) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu sınavı zaten tamamladınız.',
                    'data' => [
                        'score' => $existingResult->score,
                        'total_questions' => $existingResult->total_questions,
                        'success_rate' => round($existingResult->success_rate, 2)
                    ]
                ], 403);
            }
            
            // ✅ 5. Giriş kaydı oluştur veya güncelle
            $examResult = ExamResult::firstOrCreate(
                [
                    'exam_id' => $examId,
                    'student_id' => $studentId,
                ],
                [
                    'entered_at' => $now,
                    'total_questions' => 0,
                    'score' => 0,
                ]
            );
            
            // İlk girişse entered_at'i kaydet
            if (!$examResult->entered_at) {
                $examResult->update(['entered_at' => $now]);
            }
            
            // ✅ 6. SMS gönder (sadece ilk girişte)
            if (!$examResult->sms_sent) {
                $this->sendExamEntryNotification($exam, $student);
                $examResult->update(['sms_sent' => true]);
            }
            
            Log::info('Sınava giriş yapıldı', [
                'exam_id' => $exam->id,
                'exam_name' => $exam->name,
                'student_id' => $student->id,
                'student_name' => $student->name,
                'entry_time' => $now->toDateTimeString(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Sınava giriş başarılı. Başarılar!',
                'data' => [
                    'exam_id' => $exam->id,
                    'exam_name' => $exam->name,
                    'start_time' => $examStartTime->toIso8601String(),
                    'time_per_question' => $exam->time_per_question,
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Sınava giriş hatası', [
                'exam_id' => $examId,
                'student_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Sınava giriş yapılırken bir hata oluştu.'
            ], 500);
        }
    }
    
    /**
     * ✅ Sınava giriş SMS bildirimi (Öğretmen + Veliler)
     */
    private function sendExamEntryNotification($exam, $student)
    {
        try {
            $teacher = $exam->teacher;
            $entryTime = Carbon::now()->locale('tr')->isoFormat('D MMMM YYYY, HH:mm');
            
            // Telefon numaralarını topla
            $phoneNumbers = [];
            
            // Öğretmen
            if (!empty($teacher->phone)) {
                $phoneNumbers[] = [
                    'number' => $teacher->phone,
                    'type' => 'Öğretmen',
                    'name' => $teacher->name
                ];
            }
            
            // 1. Veli
            if (!empty($student->parent_phone_number)) {
                $phoneNumbers[] = [
                    'number' => $student->parent_phone_number,
                    'type' => '1. Veli',
                    'name' => $student->name
                ];
            }
            
            // 2. Veli
            if (!empty($student->parent_phone_number_2)) {
                $phoneNumbers[] = [
                    'number' => $student->parent_phone_number_2,
                    'type' => '2. Veli',
                    'name' => $student->name
                ];
            }
            
            if (empty($phoneNumbers)) {
                Log::info('Exam entry SMS: Telefon numarası bulunamadı', [
                    'student_id' => $student->id,
                    'exam_id' => $exam->id
                ]);
                return;
            }
            
            foreach ($phoneNumbers as $phone) {
                try {
                    if ($phone['type'] === 'Öğretmen') {
                        $smsContent = sprintf(
                            "%s adlı öğrenciniz '%s' sınavına %s tarihinde giriş yaptı. - Rise English",
                            $student->name,
                            $exam->name,
                            $entryTime
                        );
                    } else {
                        $smsContent = sprintf(
                            "Sayın Veli, %s adlı öğrenciniz '%s' sınavına %s tarihinde giriş yaptı. - Rise English",
                            $student->name,
                            $exam->name,
                            $entryTime
                        );
                    }
                    
                    $smsResult = \App\Services\SmsService::sendSms($phone['number'], $smsContent);
                    
                    if ($smsResult) {
                        Log::info('Exam entry SMS gönderildi', [
                            'exam_id' => $exam->id,
                            'student_id' => $student->id,
                            'recipient_type' => $phone['type'],
                            'recipient_phone' => $phone['number']
                        ]);
                    } else {
                        Log::error('Exam entry SMS gönderilemedi', [
                            'student_id' => $student->id,
                            'recipient_type' => $phone['type']
                        ]);
                    }
                    
                } catch (\Exception $e) {
                    Log::error('Exam entry SMS hatası', [
                        'error' => $e->getMessage(),
                        'phone' => $phone['number']
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Exam entry notification error: ' . $e->getMessage());
        }
    }
    
    /**
     * Sınav detayı ve soruları getir
     */
    public function show($examId)
    {
        $studentId = auth()->id();
        
        $exam = Exam::whereHas('students', function($query) use ($studentId) {
                $query->where('users.id', $studentId);
            })
            ->with([
                'teacher:id,name',
                'wordSets'
            ])
            ->findOrFail($examId);
        
        // ✅ Öğrenci bu sınavı daha önce tamamlamış mı kontrol et
        $existingResult = ExamResult::where('exam_id', $examId)
            ->where('student_id', $studentId)
            ->whereNotNull('completed_at')
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
    
public function submitResult(Request $request, $examId)
{
    $validated = $request->validate([
        'score' => 'required|integer|min:0',
        'total_questions' => 'required|integer|min:1',
        'time_spent' => 'required|integer|min:0',
        'answers' => 'required|array',
        'answers.*.question_number' => 'required|integer',
        'answers.*.word_id' => 'required|integer',
        'answers.*.selected_answer' => 'nullable|string',
        'answers.*.is_correct' => 'required|boolean',
        'answers.*.time_taken' => 'required|integer|min:0',
        'violation' => 'sometimes|boolean',
        'violation_reason' => 'sometimes|string',
    ]);
    
    $studentId = auth()->id();
    $student = auth()->user();
    
    // Sınavı kontrol et
    $exam = Exam::whereHas('students', function($query) use ($studentId) {
        $query->where('users.id', $studentId);
    })->with('teacher:id,name')->findOrFail($examId);
    
    // ✅ SADECE completed_at dolu olanları kontrol et
    $existingResult = ExamResult::where('exam_id', $examId)
        ->where('student_id', $studentId)
        ->whereNotNull('answers')
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
        $successRate = $validated['total_questions'] > 0 
            ? ($validated['score'] / $validated['total_questions']) * 100 
            : 0;
        
        // ✅ Giriş kaydını bul veya oluştur (enterExam'den gelmişse zaten var)
        $result = ExamResult::updateOrCreate(
            [
                'exam_id' => $examId,
                'student_id' => $studentId,
            ],
            [
                'score' => $validated['score'],
                'total_questions' => $validated['total_questions'],
                'time_spent' => $validated['time_spent'],
                'success_rate' => $successRate,
                'answers' => $validated['answers'],
                'completed_at' => now(),
                'violation' => $validated['violation'] ?? false,
                'violation_reason' => $validated['violation_reason'] ?? null,
            ]
        );
        
        // ✅ İhlal yoksa veliye sonuç SMS'i gönder
        if (!($validated['violation'] ?? false)) {
            $this->sendExamResultSms($student, $exam, $result);
        } else {
            // İhlal varsa öğretmene bildir
            $this->sendViolationNotification($student, $exam, $validated['violation_reason']);
        }
        
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
     * İhlal bildirimi gönder (Öğretmene)
     */
    private function sendViolationNotification($student, $exam, $reason)
    {
        try {
            $teacher = $exam->teacher;
            
            if (empty($teacher->phone)) {
                return;
            }
            
            $smsContent = sprintf(
                "DİKKAT: %s adlı öğrenci '%s' sınavında kural ihlali yaptı. Sebep: %s - Rise English",
                $student->name,
                $exam->name,
                $reason
            );
            
            \App\Services\SmsService::sendSms($teacher->phone, $smsContent);
            
            Log::info('Exam violation SMS gönderildi', [
                'student_id' => $student->id,
                'exam_id' => $exam->id,
                'teacher_phone' => $teacher->phone,
                'reason' => $reason
            ]);
            
        } catch (\Exception $e) {
            Log::error('Violation notification error: ' . $e->getMessage());
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
                    }
                } catch (\Exception $e) {
                    Log::error('Exam result SMS hatası', [
                        'student_id' => $student->id,
                        'parent_type' => $phone['type'],
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            Log::error('SMS send process error: ' . $e->getMessage());
        }
    }
    
    /**
     * Öğrencinin sınav sonuçlarını listele
     */
    public function results(Request $request)
    {
        $studentId = auth()->id();
        
        $results = ExamResult::where('student_id', $studentId)
            ->whereNotNull('completed_at')
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
                    'violation' => $result->violation ?? false,
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }
}