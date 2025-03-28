<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\User;
use App\Models\Homework;
use App\Models\Announcement;
use App\Models\HomeworkSubmission;


use Carbon\Carbon;

class TeacherController extends Controller
{
    /**
     * Öğretmen dashboard sayfası
     */
    public function index(Request $request)
    {
        $teacher = Auth::user();
        
        // Öğretmenin verdiği aktif kursları getir
        $courses = Course::where('teacher_id', $teacher->id)
                        ->where('is_active', true)
                        ->with(['category', 'level', 'students'])
                        ->orderBy('start_date', 'asc')
                        ->get();
        
        // Aktif kurs sayısı
        $activeCourses = $courses->count();
        
        // Toplam öğrenci sayısı (tüm kurslarda, tekrarsız)
        $totalStudents = $courses->flatMap(function($course) {
            return $course->students;
        })->unique('id')->count();
        
        // Ödev teslimleri için sorgu oluştur
        $query = HomeworkSubmission::whereHas('homework', function($query) use ($teacher) {
                        $query->whereHas('course', function($query) use ($teacher) {
                            $query->where('teacher_id', $teacher->id);
                        })->where('is_active', true);
                    })
                    ->whereHas('student')
                    ->whereNotNull('homework_id')
                    ->with(['homework.course', 'student'])
                    ->whereNull('graded_at')
                    ->orderBy('submitted_at', 'desc');
    
        // Filtreleme: Öğrenci adına göre
        if ($request->filled('student_name')) {
            $query->whereHas('student', function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('student_name') . '%');
            });
        }
    
        // Filtreleme: Kurs adına göre
        if ($request->filled('course_name')) {
            $query->whereHas('homework.course', function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('course_name') . '%');
            });
        }
    
        // Sayfalandırma: Her sayfada 5 ödev göster
        $recentHomeworks = $query->paginate(5);
        
        // Bekleyen ödev sayısı (filtresiz toplam)
        $pendingHomeworks = HomeworkSubmission::whereHas('homework', function($query) use ($teacher) {
                                $query->whereHas('course', function($query) use ($teacher) {
                                    $query->where('teacher_id', $teacher->id);
                                })->where('is_active', true);
                            })
                            ->whereHas('student')
                            ->whereNotNull('homework_id')
                            ->whereNull('graded_at')
                            ->count();
        
        return view('teacher.index', compact(
            'courses', 
            'activeCourses', 
            'totalStudents', 
            'recentHomeworks', 
            'pendingHomeworks'
        ));
    }
    public function fetchHomeworks(Request $request)
{
    $teacher = Auth::user();
    
    $query = HomeworkSubmission::whereHas('homework', function($query) use ($teacher) {
                    $query->whereHas('course', function($query) use ($teacher) {
                        $query->where('teacher_id', $teacher->id);
                    })->where('is_active', true);
                })
                ->whereHas('student')
                ->whereNotNull('homework_id')
                ->with(['homework.course', 'student'])
                ->whereNull('graded_at')
                ->orderBy('submitted_at', 'desc');

    if ($request->filled('student_name')) {
        $query->whereHas('student', function($query) use ($request) {
            $query->where('name', 'like', '%' . $request->input('student_name') . '%');
        });
    }

    if ($request->filled('course_name')) {
        $query->whereHas('homework.course', function($query) use ($request) {
            $query->where('name', 'like', '%' . $request->input('course_name') . '%');
        });
    }

    $recentHomeworks = $query->paginate(5);

    return response()->json([
        'html' => view('teacher.partials.homework-table', compact('recentHomeworks'))->render(),
        'pagination' => $recentHomeworks->links()->toHtml(),
    ]);
}
/**
 * Kurs toplantı bilgilerini güncelle
 */
public function updateMeetingInfo(Request $request, $id)
{
    $teacher = Auth::user();
    
    // Kursun bu öğretmene ait olduğunu kontrol et
    $course = Course::where('id', $id)
                    ->where('teacher_id', $teacher->id)
                    ->firstOrFail();
    
    // Form validasyonu
    $validated = $request->validate([
        'meeting_link' => 'nullable|url|max:255',
        'meeting_password' => 'nullable|string|max:50',
    ]);
    
    // Toplantı bilgilerini güncelle
    $course->meeting_link = $validated['meeting_link'];
    $course->meeting_password = $validated['meeting_password'];
    $course->save();
    
    return redirect()->route('ogretmen.course.detail', $id)
                     ->with('success', 'Toplantı bilgileri başarıyla güncellendi.');
}
    /**
 * Ödev oluşturma
 */
/**
 * Ödev oluşturma (SMS bildirimi eklenmiş)
 */
public function createHomework(Request $request, $courseId)
{
    // Form validasyonu
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'due_date' => 'required|date|after:today',
        'send_notification' => 'nullable|boolean', // SMS bildirimi gönder seçeneği
    ]);
    
    // Kursu kontrol et - öğretmene ait olduğundan emin ol
    $course = Course::where('id', $courseId)
                    ->where('teacher_id', Auth::id())
                    ->firstOrFail();
    
    // Ödevi veritabanına kaydet
    $homework = new Homework();
    $homework->course_id = $course->id;
    $homework->title = $validated['title'];
    $homework->description = $validated['description'];
    $homework->due_date = Carbon::parse($validated['due_date']);
    $homework->published_at = now();
    $homework->is_active = true;
    $homework->max_score = $request->input('max_score', 100); // Varsayılan olarak 100 puan

    // Eğer ödev dosyası yüklenmiş ise
    if ($request->hasFile('file_path') && $request->file('file_path')->isValid()) {
        $file = $request->file('file_path');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('homework_files', $fileName, 'public');
        $homework->file_path = $filePath;
    }
    
    $homework->save();
    
    // SMS bildirimi gönderme seçeneği işaretlendiyse
    if ($request->has('send_notification') && $request->input('send_notification') == 1) {
        // SmsService sınıfını kullanarak bildirimleri gönder
        $notificationResult = $this->sendHomeworkNotification($course, $homework);
        
        // SMS gönderim sonucuna göre mesaj göster
        if ($notificationResult['success']) {
            return redirect()->route('ogretmen.course.detail', $courseId)
                ->with('success', 'Ödev başarıyla oluşturuldu ve ' . $notificationResult['sent_count'] . ' bildirim gönderildi.');
        } else {
            return redirect()->route('ogretmen.course.detail', $courseId)
                ->with('success', 'Ödev başarıyla oluşturuldu fakat bildirimler gönderilemedi: ' . $notificationResult['message']);
        }
    }
    
    return redirect()->route('ogretmen.course.detail', $courseId)
                     ->with('success', 'Ödev başarıyla oluşturuldu.');
}

/**
 * Ödev bildirimi gönder
 */
private function sendHomeworkNotification($course, $homework)
{
    try {
        $teacher = Auth::user();
        
        // Log bilgisi
        \Illuminate\Support\Facades\Log::info('Ödev SMS bildirimi başlatılıyor', [
            'kurs_id' => $course->id,
            'kurs_adı' => $course->name,
            'ödev_id' => $homework->id,
            'ödev_başlık' => $homework->title,
            'öğretmen' => $teacher->name
        ]);
        
        // Kursa kayıtlı ve onaylı öğrencileri getir
        $students = $course->students()
            ->wherePivot('approval_status', 1)
            ->wherePivot('status_id', 1) // Aktif öğrenciler
            ->get();
        
        if ($students->isEmpty()) {
            \Illuminate\Support\Facades\Log::warning('Bu kursta onaylanmış öğrenci bulunmamaktadır.', [
                'kurs_id' => $course->id,
                'kurs_adı' => $course->name
            ]);
            
            return [
                'success' => false,
                'message' => 'Bu kursta bildirim gönderilecek onaylanmış öğrenci bulunmamaktadır.',
                'sent_count' => 0,
                'error_count' => 0
            ];
        }
        
        // SMS başarı ve hata sayaçları
        $sentCount = 0;
        $errorCount = 0;
        $results = [];
        
        // Teslim tarihi formatı
        $dueDateFormatted = $homework->due_date ? $homework->due_date->format('d.m.Y') : 'belirtilmedi';
        
        // Bildirim için hazırlanan SMS mesajı
        $message = "Sevgili {ÖĞRENCİ}, {KURS} kursunda {ÖĞRETMEN} yeni bir ödev oluşturdu: {ÖDEV}. Teslim tarihi: {TARİH}. Detaylar için lütfen kurs sayfasını ziyaret ediniz.";
        
        // Her öğrenci için SMS gönder
        foreach ($students as $student) {
            // Öğrenciye özel mesaj
            $personalizedMessage = str_replace(
                ['{ÖĞRENCİ}', '{KURS}', '{ÖĞRETMEN}', '{ÖDEV}', '{TARİH}'],
                [$student->name, $course->name, $teacher->name, $homework->title, $dueDateFormatted],
                $message
            );
            
            // Mesaj uzunluğu kontrolü
            if (strlen($personalizedMessage) > 160) {
                $personalizedMessage = substr($personalizedMessage, 0, 157) . '...';
            }
            
            // Öğrenciye SMS gönder
            if ($student->phone) {
                $studentResult = \App\Services\SmsService::sendSms($student->phone, $personalizedMessage);
                
                if ($studentResult['success']) {
                    $sentCount++;
                } else {
                    $errorCount++;
                }
                
                $results[] = [
                    'type' => 'student',
                    'user_id' => $student->id,
                    'name' => $student->name,
                    'phone' => $student->phone,
                    'result' => $studentResult
                ];
            }
            
            // Öğrencinin velisine SMS gönder (eğer parent_phone_number alanı varsa)
            if (isset($student->parent_phone_number) && !empty($student->parent_phone_number)) {
                // Veliye özel mesaj
                $parentMessage = str_replace(
                    ['{ÖĞRENCİ}', '{KURS}', '{ÖĞRETMEN}', '{ÖDEV}', '{TARİH}'],
                    [$student->name, $course->name, $teacher->name, $homework->title, $dueDateFormatted],
                    "Sevgili Veli, {ÖĞRENCİ} adlı öğrenciniz için {KURS} kursunda yeni bir ödev ({ÖDEV}) verilmiştir. Teslim tarihi: {TARİH}."
                );
                
                // Mesaj uzunluğu kontrolü
                if (strlen($parentMessage) > 160) {
                    $parentMessage = substr($parentMessage, 0, 157) . '...';
                }
                
                $parentResult = \App\Services\SmsService::sendSms($student->parent_phone_number, $parentMessage);
                
                if ($parentResult['success']) {
                    $sentCount++;
                } else {
                    $errorCount++;
                }
                
                $results[] = [
                    'type' => 'parent',
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'phone' => $student->parent_phone_number,
                    'result' => $parentResult
                ];
            }
        }
        
        // Tüm sonuçları log'a kaydet
        \Illuminate\Support\Facades\Log::info('Ödev bildirimi tamamlandı', [
            'kurs_id' => $course->id,
            'ödev_id' => $homework->id,
            'gönderilen' => $sentCount,
            'hata' => $errorCount
        ]);
        
        return [
            'success' => $sentCount > 0,
            'message' => "Toplam {$sentCount} SMS başarıyla gönderildi. {$errorCount} hata oluştu.",
            'sent_count' => $sentCount,
            'error_count' => $errorCount,
            'details' => $results
        ];
        
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Ödev bildirimi hatası', [
            'kurs_id' => $course->id,
            'ödev_id' => $homework->id,
            'hata' => $e->getMessage(),
            'dosya' => $e->getFile(),
            'satır' => $e->getLine()
        ]);
        
        return [
            'success' => false,
            'message' => 'Bildirim gönderilirken bir hata oluştu: ' . $e->getMessage(),
            'sent_count' => 0,
            'error_count' => 0
        ];
    }
}
    /**
 * Duyuru oluşturma
 */
public function createAnnouncement(Request $request, $courseId)
{
    // Form validasyonu
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'send_notification' => 'nullable|boolean', // SMS bildirimi gönder seçeneği
    ]);
    
    // Kursu kontrol et - öğretmene ait olduğundan emin ol
    $course = Course::where('id', $courseId)
                    ->where('teacher_id', Auth::id())
                    ->firstOrFail();
    
    // Duyuruyu veritabanına kaydet
    $announcement = new Announcement();
    $announcement->course_id = $course->id;
    $announcement->title = $validated['title'];
    $announcement->content = $validated['content'];
    $announcement->save();
    
    // SMS bildirimi gönderme seçeneği işaretlendiyse
    if ($request->has('send_notification') && $request->input('send_notification') == 1) {
        // SmsService sınıfını kullanarak bildirimleri gönder
        $notificationResult = \App\Services\SmsService::sendCourseAnnouncementNotification(
            $course->id,
            $announcement->id,
            Auth::id()
        );
        
        // SMS gönderim sonucuna göre mesaj göster
        if ($notificationResult['success']) {
            return redirect()->route('ogretmen.course.detail', $courseId)
                ->with('success', 'Duyuru başarıyla oluşturuldu ve ' . $notificationResult['sent_count'] . ' bildirim gönderildi.');
        } else {
            return redirect()->route('ogretmen.course.detail', $courseId)
                ->with('success', 'Duyuru başarıyla oluşturuldu fakat bildirimler gönderilemedi: ' . $notificationResult['message']);
        }
    }
    
    return redirect()->route('ogretmen.course.detail', $courseId)
                     ->with('success', 'Duyuru başarıyla oluşturuldu.');
}
public function showCourseDetail($slug)
{
    try {
        $user = Auth::user();
        $course = Course::where('slug', $slug)->firstOrFail();
        
        // Kullanıcının bu kursa ACTIVE (status_id: 1) kaydı olup olmadığını kontrol et
        $enrollment = $user->enrolledCourses()
            ->where('course_id', $course->id)
            ->wherePivot('status_id', 1) 
            ->first();
        
        if (!$enrollment) {
            return redirect()->route('courses.detail', $course->slug)
                ->with('error', 'Bu kursa erişim izniniz bulunmamaktadır. Lütfen önce kursa kaydolun veya yönetici onayını bekleyin.');
        }
        
        // Kurs duyurularını getir
        $announcements = Announcement::where('course_id', $course->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Öğrencinin tamamladığı ödevleri bul
        $completedHomeworkIds = HomeworkSubmission::where('user_id', $user->id)
            ->pluck('homework_id')
            ->toArray();
        
        // Gerçek kurs ödevlerini veritabanından çek
        $homeworksFromDB = Homework::where('course_id', $course->id)
            ->where('is_active', true)
            ->orderBy('due_date', 'desc')
            ->get();
        
        // Aktif ödevleri formatla
        $homeworks = [];
        foreach ($homeworksFromDB as $homework) {
            $status = 'Tamamlanmadı';
            
            // Tamamlanmış bir ödev mi kontrol et
            if (in_array($homework->id, $completedHomeworkIds)) {
                $status = 'Tamamlandı';
            } 
            // Süresi geçmiş bir ödev mi kontrol et
            elseif ($homework->due_date && $homework->due_date->isPast()) {
                $status = 'Süresi Doldu';
            }
            
            $homeworks[] = [
                'id' => $homework->id,
                'title' => $homework->title,
                'description' => $homework->description,
                'due_date' => $homework->due_date ? $homework->due_date->format('Y-m-d H:i:s') : null,
                'status' => $status
            ];
        }
        
        // Eğer veritabanından hiç ödev gelmezse, dummy verilerle devam et
        if (empty($homeworks)) {
            $homeworks = $this->getDummyHomeworks();
        }
        
        // Geçmiş ödevleri getir (Tamamlanan ödevler)
        $pastHomeworks = [];
        
        // Öğrencinin teslim ettiği tüm ödevleri, puanlar ve geri bildirimlerle birlikte getir
        $submissions = HomeworkSubmission::where('user_id', $user->id)
            ->whereIn('homework_id', Homework::where('course_id', $course->id)->pluck('id'))
            ->with('homework')
            ->get();
        
        foreach ($submissions as $submission) {
            if ($submission->homework) {
                $pastHomeworks[] = [
                    'id' => $submission->id, // Submission ID
                    'homework_id' => $submission->homework->id,
                    'title' => $submission->homework->title,
                    'description' => $submission->homework->description,
                    'due_date' => $submission->homework->due_date ? $submission->homework->due_date->format('Y-m-d H:i:s') : null,
                    'submission_date' => $submission->submitted_at->format('Y-m-d H:i:s'),
                    'score' => $submission->score,
                    'max_score' => $submission->homework->max_score,
                    'status' => $submission->graded_at ? 'Değerlendirildi' : 'Değerlendiriliyor',
                    'file_path' => $submission->file_path,
                    'feedback' => $submission->feedback, // Öğretmenin geri bildirimi
                    'graded_at' => $submission->graded_at ? $submission->graded_at->format('Y-m-d H:i:s') : null,
                ];
            }
        }
        
        return view('student.courses.detail', compact('course', 'announcements', 'homeworks', 'pastHomeworks'));
    } catch (\Exception $e) {
        // Hata logla
        \Log::error('Kurs detay hatası: ' . $e->getMessage());
        
        return redirect()->route('ogrenci.kurslarim')
            ->with('error', 'Kurs detayları yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
    }
}

/**
 * Kurs detay sayfası
 */
/**
 * Kurs detay sayfası
 */

    /**
     * Kurs detay sayfası - Updated for submissions
     */
    public function courseDetail($id)
    {
        $teacher = Auth::user();
        
        // Kurs bilgisini getir ve öğretmene ait olduğunu kontrol et
        $course = Course::where('id', $id)
                        ->where('teacher_id', $teacher->id)
                        ->with(['category', 'level', 'students', 'announcements']) 
                        ->firstOrFail();
        
        // Kursa kayıtlı öğrenciler
        $students = $course->students;
        
        // Kurs duyuruları
    $announcements = Announcement::where('course_id', $course->id)
                                ->orderBy('created_at', 'desc') // En yeniden eskiye sıralama
                                ->get();
    
        
        // Kurs ödevleri
        $homeworks = Homework::where('course_id', $course->id)
                        ->where('is_active', true)
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        // Her bir ödev için teslim sayısını hesaplayalım
        foreach ($homeworks as $homework) {
            $homework->submission_count = $homework->submissions()->count();
        }
        
        // Öğrencilerin ödev teslimleri
        $submissions = HomeworkSubmission::whereHas('homework', function($query) use ($course) {
                            $query->where('course_id', $course->id);
                        })
                        ->with(['homework', 'student'])
                        ->orderBy('submitted_at', 'desc')
                        ->get();
        
        return view('teacher.course_detail', compact(
            'course',
            'students',
            'announcements',
            'homeworks',
            'submissions'
        ));
    }

    /**
     * Ödev değerlendirme sayfasını göster
     */
    public function viewSubmission($id)
    {
        $teacher = Auth::user();
        
        // Ödev teslimi bilgisini getir
        $submission = HomeworkSubmission::with(['homework', 'student'])
                                      ->findOrFail($id);
        
        // Ödevin bağlı olduğu kursun bu öğretmene ait olduğunu kontrol et
        $homework = $submission->homework;
        $course = Course::where('id', $homework->course_id)
                        ->where('teacher_id', $teacher->id)
                        ->firstOrFail();
        
        return view('teacher.submission_view', compact('submission', 'course'));
    }

    /**
     * Ödev değerlendirme sayfasını göster
     */
    public function evaluateSubmission($id)
    {
        $teacher = Auth::user();
        
        // Ödev teslimi bilgisini getir
        $submission = HomeworkSubmission::with(['homework', 'student'])
                                      ->findOrFail($id);
        
        // Ödevin bağlı olduğu kursun bu öğretmene ait olduğunu kontrol et
        $homework = $submission->homework;
        $course = Course::where('id', $homework->course_id)
                        ->where('teacher_id', $teacher->id)
                        ->firstOrFail();
        
        return view('teacher.submission_evaluate', compact('submission', 'course'));
    }

    /**
     * Ödevi değerlendir
     */
    /**
     * Ödevi değerlendir
     */
    public function saveEvaluation(Request $request, $id)
    {
        $teacher = Auth::user();
        
        // Form validasyonu
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'required|string',
            'send_notification' => 'nullable|boolean', // SMS bildirimi gönder seçeneği
        ]);
        
        // Ödev teslimini getir
        $submission = HomeworkSubmission::with(['homework', 'student'])->findOrFail($id);
        
        // Bu ödevin bu öğretmene ait bir kursta olduğunu kontrol et
        $homework = Homework::where('id', $submission->homework_id)
                           ->whereHas('course', function($query) use ($teacher) {
                               $query->where('teacher_id', $teacher->id);
                           })
                           ->firstOrFail();
        
        // Kursu al
        $course = Course::find($homework->course_id);
        
        // Değerlendirmeyi kaydet
        $submission->score = $validated['score'];
        $submission->feedback = $validated['feedback'];
        $submission->graded_at = now();
        $submission->status = 'graded';
        $submission->save();
        
        // SMS bildirimi gönderme seçeneği işaretlendiyse
        if ($request->has('send_notification') && $request->input('send_notification') == 1) {
            // Bildirim gönder
            $notificationResult = $this->sendEvaluationNotification($submission, $course, $teacher);
            
            // SMS gönderim sonucuna göre mesaj göster
            if ($notificationResult['success']) {
                return redirect()->route('ogretmen.course.detail', $course->id)
                    ->with('success', 'Ödev başarıyla değerlendirildi ve ' . $notificationResult['sent_count'] . ' bildirim gönderildi.');
            } else {
                return redirect()->route('ogretmen.course.detail', $course->id)
                    ->with('success', 'Ödev başarıyla değerlendirildi fakat bildirimler gönderilemedi: ' . $notificationResult['message']);
            }
        }
        
        return redirect()->route('ogretmen.course.detail', $course->id)
                         ->with('success', 'Ödev başarıyla değerlendirildi.');
    }
    
    /**
     * Ödev değerlendirme bildirimi gönder
     */
    private function sendEvaluationNotification($submission, $course, $teacher)
    {
        try {
            $student = $submission->student;
            $homework = $submission->homework;
            
            // Log bilgisi
            \Illuminate\Support\Facades\Log::info('Ödev değerlendirme SMS bildirimi başlatılıyor', [
                'ödev_id' => $homework->id,
                'ödev_başlık' => $homework->title,
                'öğrenci' => $student->name,
                'puan' => $submission->score,
                'öğretmen' => $teacher->name
            ]);
            
            // SMS başarı ve hata sayaçları
            $sentCount = 0;
            $errorCount = 0;
            $results = [];
            
            // Öğrencinin puanı
            $scorePercentage = round(($submission->score / $homework->max_score) * 100);
            
            // Bildirim için hazırlanan SMS mesajı
            $message = "Sevgili {ÖĞRENCİ}, {KURS} kursundaki '{ÖDEV}' başlıklı ödeviniz değerlendirildi. Puanınız: {PUAN}/{MAKSIMUM} ({YÜZDE}%). Detaylar için kurs sayfasını ziyaret ediniz.";
            
            // Öğrenciye özel mesaj
            $personalizedMessage = str_replace(
                ['{ÖĞRENCİ}', '{KURS}', '{ÖDEV}', '{PUAN}', '{MAKSIMUM}', '{YÜZDE}'],
                [$student->name, $course->name, $homework->title, $submission->score, $homework->max_score, $scorePercentage],
                $message
            );
            
            // Mesaj uzunluğu kontrolü
            if (strlen($personalizedMessage) > 160) {
                $personalizedMessage = substr($personalizedMessage, 0, 157) . '...';
            }
            
            // Öğrenciye SMS gönder
            if ($student->phone) {
                $studentResult = \App\Services\SmsService::sendSms($student->phone, $personalizedMessage);
                
                if ($studentResult['success']) {
                    $sentCount++;
                } else {
                    $errorCount++;
                }
                
                $results[] = [
                    'type' => 'student',
                    'user_id' => $student->id,
                    'name' => $student->name,
                    'phone' => $student->phone,
                    'result' => $studentResult
                ];
            }
            
            // Öğrencinin velisine SMS gönder (eğer parent_phone_number alanı varsa)
            if (isset($student->parent_phone_number) && !empty($student->parent_phone_number)) {
                // Veliye özel mesaj
                $parentMessage = str_replace(
                    ['{ÖĞRENCİ}', '{KURS}', '{ÖDEV}', '{PUAN}', '{MAKSIMUM}', '{YÜZDE}'],
                    [$student->name, $course->name, $homework->title, $submission->score, $homework->max_score, $scorePercentage],
                    "Sayın Veli, {ÖĞRENCİ} adlı öğrencinizin {KURS} kursundaki '{ÖDEV}' ödevi değerlendirildi. Puanı: {PUAN}/{MAKSIMUM} ({YÜZDE}%)."
                );
                
                // Mesaj uzunluğu kontrolü
                if (strlen($parentMessage) > 160) {
                    $parentMessage = substr($parentMessage, 0, 157) . '...';
                }
                
                $parentResult = \App\Services\SmsService::sendSms($student->parent_phone_number, $parentMessage);
                
                if ($parentResult['success']) {
                    $sentCount++;
                } else {
                    $errorCount++;
                }
                
                $results[] = [
                    'type' => 'parent',
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'phone' => $student->parent_phone_number,
                    'result' => $parentResult
                ];
            }
            
            // Tüm sonuçları log'a kaydet
            \Illuminate\Support\Facades\Log::info('Ödev değerlendirme bildirimi tamamlandı', [
                'ödev_id' => $homework->id,
                'öğrenci_id' => $student->id,
                'gönderilen' => $sentCount,
                'hata' => $errorCount
            ]);
            
            return [
                'success' => $sentCount > 0,
                'message' => "Toplam {$sentCount} SMS başarıyla gönderildi. {$errorCount} hata oluştu.",
                'sent_count' => $sentCount,
                'error_count' => $errorCount,
                'details' => $results
            ];
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Ödev değerlendirme bildirimi hatası', [
                'ödev_id' => $submission->homework_id,
                'öğrenci_id' => $submission->user_id,
                'hata' => $e->getMessage(),
                'dosya' => $e->getFile(),
                'satır' => $e->getLine()
            ]);
            
            return [
                'success' => false,
                'message' => 'Bildirim gönderilirken bir hata oluştu: ' . $e->getMessage(),
                'sent_count' => 0,
                'error_count' => 0
            ];
        }
    }
/**
 * Örnek duyuru verileri
 */
private function getDummyAnnouncements($courseId)
{
    $announcements = collect([]);
    
    // Örnek duyuru 1
    $announcement1 = new \stdClass();
    $announcement1->id = 1;
    $announcement1->title = "Ders Programı Değişikliği";
    $announcement1->content = "Önümüzdeki hafta dersimiz Çarşamba saat 15:00'te yapılacaktır.";
    $announcement1->created_at = Carbon::now()->subDays(2);
    
    // Örnek duyuru 2
    $announcement2 = new \stdClass();
    $announcement2->id = 2;
    $announcement2->title = "Ödev Teslim Tarihi Uzatıldı";
    $announcement2->content = "Veri Analizi ödevinin teslim tarihi bir hafta uzatılmıştır.";
    $announcement2->created_at = Carbon::now()->subDays(5);
    
    $announcements->push($announcement1);
    $announcements->push($announcement2);
    
    return $announcements;
}
// TeacherController.php
public function loadStudentSubmissions($courseId, $studentId = null)
{
    $teacher = Auth::user();
    
    // Kursun öğretmene ait olduğunu kontrol et
    $course = Course::where('id', $courseId)
                    ->where('teacher_id', $teacher->id)
                    ->firstOrFail();
    
    // Ödev teslimlerini getir
    $query = HomeworkSubmission::whereHas('homework', function($query) use ($course) {
        $query->where('course_id', $course->id);
    })->with(['homework', 'student']);
    
    // Eğer studentId belirtilmişse, sadece o öğrencinin teslimlerini getir
    if ($studentId) {
        $query->where('user_id', $studentId);
    }
    
    $submissions = $query->orderBy('submitted_at', 'desc')->get();
    
    // Yanıtı JSON formatında döndür
    return response()->json([
        'success' => true,
        'submissions' => $submissions->map(function ($submission) {
            return [
                'id' => $submission->id,
                'homework' => [
                    'id' => $submission->homework->id,
                    'title' => $submission->homework->title,
                ],
                'student_name' => $submission->student->name,
                'submitted_at' => $submission->submitted_at->toDateTimeString(),
                'graded_at' => $submission->graded_at ? $submission->graded_at->toDateTimeString() : null,
                'score' => $submission->score,
                'file_path' => $submission->file_path,
                'feedback' => $submission->feedback,
                'status' => $submission->graded_at ? 'Değerlendirildi' : 'Bekliyor',
            ];
        })
    ]);
}
/**
 * Örnek ödev verileri (belirli bir kurs için)
 */
private function getDummyCourseHomeworks($courseId)
{
    $homeworks = collect([]);
    
    // Örnek ödev 1
    $homework1 = new \stdClass();
    $homework1->id = 1;
    $homework1->title = "Hafta 1: Giriş Ödevi";
    $homework1->description = "Kurs konusuyla ilgili bir araştırma yazısı hazırlayınız.";
    $homework1->due_date = Carbon::now()->addDays(7);
    $homework1->created_at = Carbon::now()->subDays(2);
    $homework1->submission_count = 5;
    
    // Örnek ödev 2
    $homework2 = new \stdClass();
    $homework2->id = 2;
    $homework2->title = "Hafta 2: Uygulama Projesi";
    $homework2->description = "Öğrenilen konseptleri kullanarak küçük bir proje geliştirin.";
    $homework2->due_date = Carbon::now()->addDays(14);
    $homework2->created_at = Carbon::now()->subDays(1);
    $homework2->submission_count = 0;
    
    $homeworks->push($homework1);
    $homeworks->push($homework2);
    
    return $homeworks;
}
}