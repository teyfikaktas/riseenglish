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
public function index(Request $request)
{
    $today = \Carbon\Carbon::today();
    
    // Bugünün sınavları (ayrı query, pagination yok)
    $todayExamsQuery = Exam::where('teacher_id', auth()->id())
        ->whereDate('start_time', $today)
        ->withCount('students')
        ->with('wordSets');
    
    // Diğer sınavlar (paginated)
    $query = Exam::where('teacher_id', auth()->id())
        ->whereDate('start_time', '!=', $today)
        ->withCount('students')
        ->with('wordSets');
    
    // Arama
    if ($request->filled('search')) {
        $search = $request->search;
        
        $todayExamsQuery->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
        
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereDate('start_time', 'like', "%{$search}%");
        });
    }
$examNames = Exam::where('teacher_id', auth()->id())
    ->selectRaw("TRIM(SUBSTRING_INDEX(name, ' - ', 1)) as base_name")
    ->distinct()
    ->orderBy('base_name')
    ->pluck('base_name');
    $todayExams = $todayExamsQuery->orderBy('start_time', 'asc')->get();
    
    $exams = $query->orderBy('is_active', 'desc')
        ->orderBy('start_time', 'desc')
        ->paginate(10);
    
$groups = \App\Models\Group::where('is_active', true)
    ->with('teacher')
    ->withCount('students')
    ->orderBy('name')
    ->get();

return view('exams.index', compact('exams', 'todayExams', 'examNames', 'groups'));}
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

public function bulkDeletePreview(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
        'name_like'  => 'nullable|string',
    ]);

    $query = Exam::where('teacher_id', auth()->id())
        ->whereBetween('start_time', [
            \Carbon\Carbon::parse($request->start_date)->startOfDay(),
            \Carbon\Carbon::parse($request->end_date)->endOfDay(),
        ]);

    if ($request->filled('name_like')) {
        $query->where('name', 'like', $request->name_like . '%');
    }

    $exams = $query->withCount(['students', 'results'])->orderBy('start_time')->get();

    $totalStudentRows  = $exams->sum('students_count');
    $totalResultRows   = $exams->sum('results_count');

    return response()->json([
        'exams'      => $exams->map(fn($e) => ['id' => $e->id, 'name' => $e->name, 'start_time' => $e->start_time]),
        'exam_count' => $exams->count(),
        'student_rows' => $totalStudentRows,
        'result_rows'  => $totalResultRows,
    ]);
}

public function bulkDelete(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
        'name_like'  => 'nullable|string',
    ]);

    $examIds = Exam::where('teacher_id', auth()->id())
        ->whereBetween('start_time', [
            \Carbon\Carbon::parse($request->start_date)->startOfDay(),
            \Carbon\Carbon::parse($request->end_date)->endOfDay(),
        ])
        ->when($request->filled('name_like'), fn($q) => $q->where('name', 'like', $request->name_like . '%'))
        ->pluck('id');

    \DB::table('exam_results')->whereIn('exam_id', $examIds)->delete();
    \DB::table('exam_student')->whereIn('exam_id', $examIds)->delete();
    \DB::table('exam_word_set')->whereIn('exam_id', $examIds)->delete();
    Exam::whereIn('id', $examIds)->delete();

    return redirect()->route('exams.index')->with('success', $examIds->count() . ' sınav silindi.');
}


public function downloadDailyReport(Request $request)
{
    $request->validate(['date' => 'required|date']);
    $date = \Carbon\Carbon::parse($request->date);
    $teacherId = auth()->id();

    $exams = Exam::where('teacher_id', $teacherId)
        ->whereDate('start_time', $date->toDateString())
        ->with(['students:id,name', 'results.student:id,name'])
        ->get();

    if ($exams->isEmpty()) {
        return back()->with('error', 'Seçilen tarihte sınav bulunamadı.');
    }

    $allEnrolledStudents = collect();
    $enteredResults = collect();

    foreach ($exams as $exam) {
        $allEnrolledStudents = $allEnrolledStudents->merge($exam->students);
        $enteredResults = $enteredResults->merge($exam->results->where('score', '>', 0));
    }

    $allEnrolledStudents = $allEnrolledStudents->unique('id');
    $enteredResults = $enteredResults->sortByDesc('success_rate')->values();

    $enteredStudentIds = $enteredResults->pluck('student_id')->toArray();

    $notEnteredStudents = $allEnrolledStudents->filter(function ($student) use ($enteredStudentIds) {
        return !in_array($student->id, $enteredStudentIds);
    });

$pdf = PDF::loadView('exams.daily-report-pdf', [
    'enteredResults'    => $enteredResults,      // düzeltildi
    'notEnteredResults' => $notEnteredStudents,   // düzeltildi
    'date'              => $date,
    'teacher'           => auth()->user(),
    'enteredCount'      => $enteredResults->count(),
    'notEnteredCount'   => $notEnteredStudents->count(),
]);

    return $pdf->download('Gunluk_Rapor_' . $date->format('d-m-Y') . '.pdf');
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
public function groupDailyReport(Request $request, \App\Models\Group $group)
{
    $request->validate(['date' => 'required|date']);
    $date = \Carbon\Carbon::parse($request->date);
    $teacherId = auth()->id();

    // Gruptaki öğrenci ID'leri
    $studentIds = $group->students()->pluck('users.id')->toArray();

    if (empty($studentIds)) {
        return back()->with('error', 'Bu grupta öğrenci bulunamadı.');
    }

    // Bugünkü tüm sınavları çek
    $allExams = Exam::where('teacher_id', $teacherId)
        ->whereDate('start_time', $date->toDateString())
        ->with(['results', 'students'])
        ->orderBy('start_time')
        ->get();

    // Sadece bu gruptaki öğrencilere atanmış sınavları filtrele
    $exams = $allExams->filter(function($exam) use ($studentIds) {
        return $exam->students->whereIn('id', $studentIds)->isNotEmpty();
    })->values();

    if ($exams->isEmpty()) {
        return back()->with('error', 'Bugün bu gruba ait sınav bulunamadı.');
    }

    // Gruptaki öğrenciler
    $students = $group->students()->orderBy('name')->get();

    // Matris oluştur
    $matrix = [];
    foreach ($students as $student) {
        $row = [];
        foreach ($exams as $exam) {
            $result = $exam->results->where('student_id', $student->id)->first();
            if ($result && $result->score > 0) {
                $row[$exam->id] = [
                    'success_rate' => $result->success_rate,
                    'correct'      => $result->getCorrectAnswersCount(),
                    'wrong'        => $result->getWrongAnswersCount(),
                ];
            } else {
                $row[$exam->id] = null;
            }
        }
        $matrix[$student->id] = $row;
    }

    $pdf = PDF::loadView('exams.group-daily-report-pdf', [
        'group'    => $group,
        'students' => $students,
        'exams'    => $exams,
        'matrix'   => $matrix,
        'date'     => $date,
        'teacher'  => auth()->user(),
    ]);

    $pdf->setPaper('A4', 'landscape');

    $fileName = 'Grup_Rapor_' . $group->name . '_' . $date->format('d-m-Y') . '.pdf';
    return $pdf->download($fileName);
}

public function selfCreate()
{
    $student = auth()->user();

    $wordSets = $student->wordSets()
        ->where('is_active', 1)
        ->withCount('words')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('exams.self-create', compact('wordSets'));
}

public function selfStore(Request $request)
{
    $validated = $request->validate([
        'word_sets'          => 'required|array|min:1',
        'word_sets.*'        => 'exists:word_sets,id',
        'time_per_question'  => 'required|integer|min:30|max:60',
        'start_date'         => 'required|date|after_or_equal:today',
        'end_date'           => 'required|date|after_or_equal:start_date',
    ]);

    $student   = auth()->user();
    $startDate = \Carbon\Carbon::parse($validated['start_date']);
    $endDate   = \Carbon\Carbon::parse($validated['end_date']);

    // Max 7 gün kontrolü
    if ($startDate->diffInDays($endDate) > 6) {
        return back()->withInput()->with('error', 'En fazla 7 günlük aralık seçebilirsiniz.');
    }

    $createdExams = [];
    $currentDate  = $startDate->copy();

    while ($currentDate->lte($endDate)) {
        $exam = \App\Models\Exam::create([
            'teacher_id'        => $student->id,
            'name'              => 'Kendi Sınavım - ' . $currentDate->isoFormat('D MMMM, dddd'),
            'description'       => 'Öğrenci tarafından oluşturuldu',
            'start_time'        => $currentDate->copy()->setTime(9, 0)->format('Y-m-d H:i:s'),
            'time_per_question' => $validated['time_per_question'],
            'is_active'         => true,
        ]);

        $exam->wordSets()->attach($validated['word_sets']);
        $exam->students()->attach([$student->id]);

        $createdExams[] = $exam;
        $currentDate->addDay();
    }

    $firstDate = $startDate->isoFormat('D MMMM');
    $lastDate  = $endDate->isoFormat('D MMMM');
    $count     = count($createdExams);

    return redirect()
        ->back()
        ->with('success', "Tebrikler! {$firstDate} - {$lastDate} tarihleri arasında {$count} sınavınız oluşturuldu.");
}

/**
     * Grup Haftalık Rapor - Pazartesi-Cumartesi arası günlük toplamlar
     * Route: GET /group-weekly-report/{group}?start_date=2025-03-08
     */
    public function groupWeeklyReport(Request $request, \App\Models\Group $group)
    {
        $request->validate(['start_date' => 'required|date']);

        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate   = $startDate->copy()->addDays(6); // 7 gün: Pzt-Pazar
        $teacherId = auth()->id();

        // Gruptaki öğrenci ID'leri
        $studentIds = $group->students()->pluck('users.id')->toArray();

        if (empty($studentIds)) {
            return back()->with('error', 'Bu grupta öğrenci bulunamadı.');
        }

        // Gruptaki öğrenciler
        $students = $group->students()->orderBy('name')->get();

        // 7 günlük dizi oluştur
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            $days[] = [
                'date' => $currentDate,
                'key'  => $currentDate->format('Y-m-d'),
            ];
        }

        // Tüm tarih aralığındaki sınavları çek
        $allExams = Exam::where('teacher_id', $teacherId)
            ->whereDate('start_time', '>=', $startDate->toDateString())
            ->whereDate('start_time', '<=', $endDate->toDateString())
            ->with(['results', 'students'])
            ->orderBy('start_time')
            ->get();

        // Sadece bu gruptaki öğrencilere atanmış sınavları filtrele
        $exams = $allExams->filter(function ($exam) use ($studentIds) {
            return $exam->students->whereIn('id', $studentIds)->isNotEmpty();
        })->values();

        if ($exams->isEmpty()) {
            return back()->with('error', 'Bu tarih aralığında bu gruba ait sınav bulunamadı.');
        }

        // Matris oluştur: student_id => date_key => { correct, wrong }
        $matrix = [];
        foreach ($students as $student) {
            foreach ($days as $dayData) {
                $matrix[$student->id][$dayData['key']] = null;
            }
        }

        foreach ($exams as $exam) {
            $examDate = \Carbon\Carbon::parse($exam->start_time)->format('Y-m-d');

            foreach ($students as $student) {
                $result = $exam->results->where('student_id', $student->id)->first();

                if ($result && $result->score > 0) {
                    $correct = $result->getCorrectAnswersCount();
                    $wrong   = $result->getWrongAnswersCount();

                    // Aynı gün birden fazla sınav varsa topla
                    if ($matrix[$student->id][$examDate] !== null) {
                        $matrix[$student->id][$examDate]['correct'] += $correct;
                        $matrix[$student->id][$examDate]['wrong']   += $wrong;
                    } else {
                        $matrix[$student->id][$examDate] = [
                            'correct' => $correct,
                            'wrong'   => $wrong,
                        ];
                    }
                }
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exams.group-weekly-report-pdf', [
            'group'     => $group,
            'students'  => $students,
            'days'      => $days,
            'matrix'    => $matrix,
            'startDate' => $startDate,
            'endDate'   => $endDate,
            'teacher'   => auth()->user(),
        ]);

        $pdf->setPaper('A4', 'landscape');

        $fileName = 'Haftalik_Rapor_' . $group->name . '_' . $startDate->format('d-m-Y') . '.pdf';
        return $pdf->download($fileName);
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
