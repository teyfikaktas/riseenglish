<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WordSet;
use App\Models\User;
use App\Models\Exam;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ExamController extends Controller
{

    /**
 * Sınavı sil
 */
public function destroy(Exam $exam)
{
    if ($exam->teacher_id !== auth()->id()) {
        abort(403, 'Bu sınavı silme yetkiniz yok.');
    }
    
    $exam->wordSets()->detach();
    $exam->students()->detach();
    $exam->delete();
    
    return redirect()
        ->route('exams.index')
        ->with('success', 'Sınav başarıyla silindi!');
}

/**
 * Sınav raporunu PDF olarak indir
 */
public function downloadReport(Exam $exam)
{
    if ($exam->teacher_id !== auth()->id()) {
        abort(403, 'Bu sınava erişim yetkiniz yok.');
    }
    
    // Sınav verilerini yükle
    $exam->load(['students', 'wordSets', 'results.student']);
    
    // PDF oluştur
    $pdf = PDF::loadView('exams.report-pdf', compact('exam'));
    
    // Dosya adı
    $fileName = 'Sinav_Raporu_' . $exam->id . '_' . date('d-m-Y') . '.pdf';
    
    // PDF'i indir
    return $pdf->download($fileName);
}
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
        /**
     * Öğretmenin sınavlarını listele
     */
    public function index()
    {
        $exams = Exam::where('teacher_id', auth()->id())
            ->with(['students', 'wordSets'])
            ->withCount('students')
            ->orderBy('start_time', 'desc')
            ->paginate(20);
            
        return view('exams.index', compact('exams'));
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
            'is_recurring' => 'nullable|boolean',
            'end_date' => 'nullable|date|after:start_time',
        ]);
        
        // Eğer tekrarlayan sınav seçilmişse
        if ($request->has('is_recurring') && $request->is_recurring && $request->end_date) {
            $startDate = \Carbon\Carbon::parse($validated['start_time']);
            $endDate = \Carbon\Carbon::parse($validated['end_date']);
            
            $createdExams = [];
            $currentDate = $startDate->copy();
            
            // Her gün için sınav oluştur
            while ($currentDate->lte($endDate)) {
                $exam = Exam::create([
                    'teacher_id' => auth()->id(),
                    'name' => $validated['exam_name'] . ' - ' . $currentDate->isoFormat('D MMMM'),
                    'description' => $validated['description'],
                    'start_time' => $currentDate->format('Y-m-d H:i:s'),
                    'time_per_question' => $validated['time_per_question'],
                    'is_active' => true,
                ]);
                
                $exam->wordSets()->attach($validated['word_sets']);
                $exam->students()->attach($validated['students']);
                
                $createdExams[] = $exam;
                
                // Bir sonraki güne geç
                $currentDate->addDay();
            }
            
            // Toplu SMS gönder - TEK SMS
            if (!empty($createdExams)) {
                $this->sendBulkExamCreatedSms($createdExams, $validated['students']);
            }
            
            return redirect()
                ->route('word-sets.index')
                ->with('success', count($createdExams) . ' adet sınav başarıyla oluşturuldu ve SMS bildirimleri gönderildi!');
            
        } else {
            // Tek sınav oluştur (mevcut mantık)
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
            
            $this->sendExamCreatedSms($exam, $validated['students']);
            
            return redirect()
                ->route('word-sets.index')
                ->with('success', 'Sınav başarıyla oluşturuldu ve SMS bildirimleri gönderildi!');
        }
                
    } catch (\Exception $e) {
        Log::error('Exam Store Error: ' . $e->getMessage());
        return back()->withInput()->with('error', 'Sınav oluşturulurken bir hata oluştu');
    }
}

/**
 * Toplu sınav oluşturulduğunda tek SMS gönder
 */
private function sendBulkExamCreatedSms($exams, $studentIds)
{
    try {
        $teacher = auth()->user();
        $firstExam = $exams[0];
        $lastExam = end($exams);
        
        $startDate = \Carbon\Carbon::parse($firstExam->start_time)->locale('tr');
        $endDate = \Carbon\Carbon::parse($lastExam->start_time)->locale('tr');
        
        $formattedStart = $startDate->isoFormat('D MMMM');
        $formattedEnd = $endDate->isoFormat('D MMMM');
        $examTime = $startDate->format('H:i');
        
        foreach ($studentIds as $studentId) {
            $student = User::find($studentId);
            if (!$student) continue;
            
            $phoneNumbers = [];
            
            if (!empty($student->phone)) {
                $phoneNumbers[] = ['number' => $student->phone, 'type' => 'Öğrenci', 'name' => $student->name];
            }
            if (!empty($student->parent_phone_number)) {
                $phoneNumbers[] = ['number' => $student->parent_phone_number, 'type' => '1. Veli', 'name' => $student->name];
            }
            if (!empty($student->parent_phone_number_2)) {
                $phoneNumbers[] = ['number' => $student->parent_phone_number_2, 'type' => '2. Veli', 'name' => $student->name];
            }
            
            if (empty($phoneNumbers)) {
                Log::warning('Bulk exam SMS: Telefon numarası bulunamadı', [
                    'student_id' => $student->id,
                    'student_name' => $student->name
                ]);
                continue;
            }
            
            foreach ($phoneNumbers as $phone) {
                try {
                    if ($phone['type'] === 'Öğrenci') {
                        $smsContent = sprintf(
                            "Sayın %s, %s-%s tarihleri arasında her gün saat %s'te '%s' sınavı yapılacaktır (Toplam %d gün). Öğretmen: %s - Rise English",
                            $student->name,
                            $formattedStart,
                            $formattedEnd,
                            $examTime,
                            $validated['exam_name'] ?? 'Quiz',
                            count($exams),
                            $teacher->name
                        );
                    } else {
                        $smsContent = sprintf(
                            "Sayın Veli, %s adlı öğrenciniz için %s-%s tarihleri arasında her gün saat %s'te '%s' sınavı yapılacaktır (Toplam %d gün). Öğretmen: %s - Rise English",
                            $student->name,
                            $formattedStart,
                            $formattedEnd,
                            $examTime,
                            $validated['exam_name'] ?? 'Quiz',
                            count($exams),
                            $teacher->name
                        );
                    }
                    
                    $smsResult = \App\Services\SmsService::sendSms($phone['number'], $smsContent);
                    
                    if ($smsResult) {
                        Log::info('Bulk exam SMS gönderildi', [
                            'student_id' => $student->id,
                            'student_name' => $student->name,
                            'exam_count' => count($exams),
                            'recipient_type' => $phone['type'],
                            'recipient_phone' => $phone['number'],
                            'date_range' => "{$formattedStart} - {$formattedEnd}"
                        ]);
                    } else {
                        Log::error('Bulk exam SMS gönderilemedi', [
                            'student_id' => $student->id,
                            'recipient_type' => $phone['type'],
                            'recipient_phone' => $phone['number']
                        ]);
                    }
                    
                } catch (\Exception $e) {
                    Log::error('Bulk exam SMS hatası', [
                        'student_id' => $student->id,
                        'recipient_type' => $phone['type'],
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
        
    } catch (\Exception $e) {
        Log::error('Bulk exam SMS process error: ' . $e->getMessage());
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
