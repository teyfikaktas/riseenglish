<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WordSet;
use App\Models\User;
use App\Models\Exam;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
public function create()
{
    try {
        $userId = auth()->id();
        
        // Öğrencileri ve onların setlerini çek
        $students = User::role('ogrenci')
            ->with(['wordSets' => function($query) {
                $query->where('is_active', 1)
                      ->withCount('words')
                      ->select('id', 'name', 'description', 'color', 'user_id', 'word_count', 'created_at')
                      ->orderBy('created_at', 'desc');
            }])
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();
        
        // Grupları çek (aktif gruplar ve öğrencileriyle)
        $groups = \App\Models\Group::where('is_active', true)
            ->with(['students', 'teacher'])
            ->withCount('students')
            ->orderBy('name')
            ->get();
        
        // Öğretmenin ve genel setleri
        $teacherWordSets = WordSet::where('is_active', 1)
            ->where(function($query) use ($userId) {
                $query->where('user_id', 1)
                      ->orWhere('user_id', 36)
                      ->orWhere('user_id', $userId);
            })
            ->withCount('words')
            ->select('id', 'name', 'description', 'color', 'user_id', 'word_count')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($set) use ($userId) {
                return [
                    'id' => $set->id,
                    'name' => $set->name,
                    'description' => $set->description,
                    'color' => $set->color,
                    'word_count' => $set->words_count ?? $set->word_count,
                ];
            });
        
        return view('exams.create', compact('teacherWordSets', 'students', 'groups'));
        
    } catch (\Exception $e) {
        Log::error('Exam Create Error: ' . $e->getMessage());
        return back()->with('error', 'Bir hata oluştu');
    }
}
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'exam_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'time_per_question' => 'required|integer|min:5|max:300',
                'word_sets' => 'required|array|min:1',
                'word_sets.*' => 'exists:word_sets,id',
                'students' => 'required|array|min:1',
                'students.*' => 'exists:users,id',
            ]);
            
            $exam = Exam::create([
                'teacher_id' => auth()->id(),
                'name' => $validated['exam_name'],
                'description' => $validated['description'],
                'start_time' => $validated['start_time'],
                'time_per_question' => $validated['time_per_question'],
                'is_active' => true,
            ]);
            
            $exam->wordSets()->attach($validated['word_sets']);
            $exam->students()->attach($validated['students']);
            
            // ✅ Öğrencilere ve velilerine SMS gönder
            $this->sendExamCreatedSms($exam, $validated['students']);
            
            return redirect()
                ->route('word-sets.index')
                ->with('success', 'Sınav başarıyla oluşturuldu ve SMS bildirimleri gönderildi!');
                
        } catch (\Exception $e) {
            Log::error('Exam Store Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Sınav oluşturulurken bir hata oluştu');
        }
    }
    
    /**
     * Sınav oluşturulduğunda öğrencilere ve velilerine SMS gönder
     */
    private function sendExamCreatedSms($exam, $studentIds)
    {
        try {
            $teacher = auth()->user();
            $examDate = \Carbon\Carbon::parse($exam->start_time)->locale('tr');
            $formattedDate = $examDate->isoFormat('D MMMM YYYY, dddd HH:mm');
            
            // Her öğrenci için SMS gönder
            foreach ($studentIds as $studentId) {
                $student = User::find($studentId);
                
                if (!$student) {
                    continue;
                }
                
                // Telefon numaralarını topla
                $phoneNumbers = [];
                
                // Öğrencinin kendi telefonu
                if (!empty($student->phone)) {
                    $phoneNumbers[] = [
                        'number' => $student->phone,
                        'type' => 'Öğrenci',
                        'name' => $student->name
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
                    Log::warning('Exam created SMS: Telefon numarası bulunamadı', [
                        'student_id' => $student->id,
                        'student_name' => $student->name,
                        'exam_id' => $exam->id
                    ]);
                    continue;
                }
                
                // Her telefon numarasına SMS gönder
                foreach ($phoneNumbers as $phone) {
                    try {
                        // SMS içeriğini hazırla
                        if ($phone['type'] === 'Öğrenci') {
                            $smsContent = sprintf(
                                "Sayın %s, '%s' sınavı %s tarihinde yapılacaktır. Öğretmen: %s. - Rise English",
                                $student->name,
                                $exam->name,
                                $formattedDate,
                                $teacher->name
                            );
                        } else {
                            $smsContent = sprintf(
                                "Sayın Veli, %s adlı öğrenciniz için '%s' sınavı %s tarihinde yapılacaktır. Öğretmen: %s. - Rise English",
                                $student->name,
                                $exam->name,
                                $formattedDate,
                                $teacher->name
                            );
                        }
                        
                        // SMS gönder
                        $smsResult = \App\Services\SmsService::sendSms($phone['number'], $smsContent);
                        
                        if ($smsResult) {
                            Log::info('Exam created SMS gönderildi', [
                                'student_id' => $student->id,
                                'student_name' => $student->name,
                                'recipient_type' => $phone['type'],
                                'recipient_phone' => $phone['number'],
                                'exam_id' => $exam->id,
                                'exam_name' => $exam->name,
                                'teacher_name' => $teacher->name,
                                'exam_date' => $formattedDate
                            ]);
                        } else {
                            Log::error('Exam created SMS gönderilemedi', [
                                'student_id' => $student->id,
                                'student_name' => $student->name,
                                'recipient_type' => $phone['type'],
                                'recipient_phone' => $phone['number']
                            ]);
                        }
                        
                    } catch (\Exception $e) {
                        Log::error('Exam created SMS hatası', [
                            'student_id' => $student->id,
                            'student_name' => $student->name,
                            'recipient_type' => $phone['type'],
                            'recipient_phone' => $phone['number'],
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Exam created SMS process error: ' . $e->getMessage());
            // SMS hatası sınav oluşturulmasını etkilemesin
        }
    }
}
