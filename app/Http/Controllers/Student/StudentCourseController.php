<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
class StudentCourseController extends Controller
{
    /**
     * Öğrencinin kayıtlı olduğu kursları listeler
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Öğrencinin tüm onaylanmış ve aktif kurslarını getir
            $enrolledCourses = $user->enrolledCourses()
                ->wherePivot('status_id', 1) // Active status_id (onaylanmış)
                ->where('is_active', true)
                ->with('teacher', 'courseType', 'courseLevel') // İlişkili modelleri yükle
                ->get();
            
            // Öğrencinin onay bekleyen kurslarını getir - doğru syntax ile
// Öğrencinin onay bekleyen kurslarını getir (status_id = 4 olanlar)
        $pendingCourses = $user->enrolledCourses()
            ->where('is_active', true)
            ->wherePivot('status_id', 4) // Sadece status_id = 4 olanları getir
            ->with('teacher', 'courseType', 'courseLevel')
            ->get();
            
            // Aktif ödevleri getir
            $activeHomeworks = $this->getActiveHomeworks($user->id);
            
            return view('student.courses.index', compact('enrolledCourses', 'pendingCourses', 'activeHomeworks'));
        } catch (\Exception $e) {
            // Hata logla
            \Log::error('Kurs listesi hatası: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Kurslarınız listelenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        }
    }
    
    /**
     * Kurs detay sayfasını gösterir - sadece kayıtlı olan öğrenciler için
     */
    /**


 * Öğrencinin ödev dosyası yüklemesi için metod
 */
public function submitHomeworkFile(Request $request, $slug, $homeworkId)
{
    try {
        $request->validate([
            'comment' => 'nullable|string',
            'file' => 'required|file|max:10240', // 10MB maksimum
        ]);

        $user = Auth::user();
        
        // Kursu slug ile bul
        $course = Course::where('slug', $slug)->firstOrFail();
        
        // Ödevin var olup olmadığını kontrol et
        $homework = Homework::findOrFail($homeworkId);
        
        // Öğrencinin kursa kayıtlı olup olmadığını kontrol et
        $enrollment = $user->enrolledCourses()
            ->where('course_id', $course->id)
            ->wherePivot('status_id', 1) // Active status
            ->first();
            
        if (!$enrollment) {
            return redirect()->back()->with('error', 'Bu kursa erişim yetkiniz bulunmamaktadır.');
        }
        
        // Öğrencinin daha önce bu ödevi gönderip göndermediğini kontrol et
        $existingSubmission = HomeworkSubmission::where('user_id', $user->id)
            ->where('homework_id', $homeworkId)
            ->first();
            
        if ($existingSubmission) {
            return redirect()->back()->with('error', 'Bu ödevi daha önce gönderdiniz.');
        }
        
        // Dosya yükleme işlemi
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('homework_submissions', $fileName, 'public');
        }
        
        // Ödev gönderimini kaydet
        $submission = new HomeworkSubmission([
            'homework_id' => $homeworkId,
            'user_id' => $user->id,
            'comment' => $request->comment,
            'file_path' => $filePath,
            'is_reviewed' => false,
        ]);
        
        $submission->save();
        
        return redirect()->back()->with('success', 'Ödeviniz başarıyla yüklendi.');
    } catch (\Exception $e) {
        \Log::error('Ödev yükleme hatası: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Ödev yüklenirken bir hata oluştu: ' . $e->getMessage())->withInput();
    }
}
public function submitHomework(Request $request, $slug, $homeworkId)
{
    $course = Course::where('slug', $slug)->firstOrFail();
    $homework = Homework::findOrFail($homeworkId);
    
    // Yetkilendirme kontrolü (Öğrenci bu kursa kayıtlı mı?)
    if (!$course->students->contains(auth()->id())) {
        return redirect()->back()->with('error', 'Bu kursa kayıtlı değilsiniz.');
    }
    
    // Dosya yükleme
    $filePath = null;
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filePath = $file->store('homework_submissions', 'public');
    } else {
        return redirect()->back()->with('error', 'Lütfen bir dosya yükleyin.');
    }
    
    // Ödev gönderimini oluştur
    HomeworkSubmission::create([
        'homework_id' => $homeworkId,
        'user_id' => auth()->id(),
        'file_path' => $filePath,
        'comment' => $request->comment,
        'submitted_at' => now(),
        'status' => 'pending' // 'Teslim Edildi' yerine enum değerlerinden birini kullanın
    ]);
    
    return redirect()->back()->with('success', 'Ödeviniz başarıyla yüklendi.');
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
        
        // Öğrencinin teslim ettiği tüm ödevleri, puanları ile birlikte getir
        $submissions = HomeworkSubmission::where('user_id', $user->id)
            ->whereIn('homework_id', Homework::where('course_id', $course->id)->pluck('id'))
            ->with('homework')
            ->get();
        
        foreach ($submissions as $submission) {
            if ($submission->homework) {
                $pastHomeworks[] = [
                    'id' => $submission->homework->id,
                    'title' => $submission->homework->title,
                    'description' => $submission->homework->description,
                    'due_date' => $submission->homework->due_date ? $submission->homework->due_date->format('Y-m-d H:i:s') : null,
                    'submission_date' => $submission->created_at->format('Y-m-d H:i:s'),
                    'score' => $submission->score,
                    'max_score' => $submission->homework->max_score ?? 100,
                    'status' => $submission->score ? 'Değerlendirildi' : 'Değerlendiriliyor',
                    'file_path' => $submission->file_path,
                    'feedback' => $submission->feedback, // Eksik olan feedback anahtarı eklendi
                    'graded_at' => $submission->graded_at ? $submission->graded_at->format('Y-m-d H:i:s') : null // Değerlendirme tarihi de eklendi
                ];
            }
        }
        
        return view('student.courses.detail-enrollment', compact('course', 'announcements', 'homeworks', 'pastHomeworks'));
    } catch (\Exception $e) {
        // Hata logla
        \Log::error('Kurs detay hatası: ' . $e->getMessage());
        
        return redirect()->route('ogrenci.kurslarim')
            ->with('error', 'Kurs detayları yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
    }
}
    /**
     * Ödev gönder (şimdilik işlevsellik eklemiyoruz)
     */
    
    /**
     * Geçici duyurular oluştur (gerçek veritabanı yerine)
     */
     /**
     * Öğrencinin aktif ödevlerini getirir
     */
    private function getActiveHomeworks($userId)
    {
        try {
            // Öğrencinin kayıtlı olduğu kursları al
            $enrolledCourseIds = Auth::user()->enrolledCourses()
            ->wherePivotIn('status_id', [1, 3])
                ->pluck('courses.id');
            
            // Tamamlanmış ödevleri bul
            $completedHomeworkIds = HomeworkSubmission::where('user_id', $userId)
                ->pluck('homework_id')
                ->toArray();
            
            // Aktif ödevleri getir (tamamlanmamış ve süresi dolmamış)
            $homeworks = Homework::whereIn('course_id', $enrolledCourseIds)
                ->where('is_active', true)
                ->whereNotIn('id', $completedHomeworkIds) // Tamamlanmış ödevleri hariç tut
                ->with('course:id,name,slug') // Kurs bilgilerini de getir
                ->orderBy('due_date', 'asc')
                ->get();
            
            $formattedHomeworks = [];
            foreach ($homeworks as $homework) {
                $status = 'Tamamlanmadı';
                
                // Süresi geçmiş bir ödev mi kontrol et
                if ($homework->due_date && $homework->due_date->isPast()) {
                    $status = 'Süresi Doldu';
                }
                
                $formattedHomeworks[] = [
                    'id' => $homework->id,
                    'title' => $homework->title,
                    'description' => $homework->description,
                    'due_date' => $homework->due_date ? $homework->due_date->format('Y-m-d H:i:s') : null,
                    'status' => $status,
                    'course_id' => $homework->course_id,
                    'course_name' => $homework->course->name,
                    'course_slug' => $homework->course->slug
                ];
            }
            
            return $formattedHomeworks;
        } catch (\Exception $e) {
            \Log::error('Ödevler getirilemedi: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Geçici ödevler oluştur (gerçek veritabanı yerine)
     */
    private function getDummyHomeworks()
    {
        return [
            [
                'id' => 1,
                'title' => 'Hafta 1 - Tanışma Ödevi',
                'description' => 'Kendinizi İngilizce olarak tanıtan 1 dakikalık bir video hazırlayın.',
                'due_date' => '2023-04-10 23:59:59',
                'status' => 'Tamamlanmadı'
            ],
            [
                'id' => 2,
                'title' => 'Hafta 2 - Gramer Egzersizleri',
                'description' => 'Kitabınızdaki 25-30 arası sayfalardaki alıştırmaları yapınız.',
                'due_date' => '2023-04-17 23:59:59',
                'status' => 'Tamamlanmadı'
            ],
            [
                'id' => 3,
                'title' => 'Hafta 3 - Yazma Ödevi',
                'description' => 'Hayalinizdeki tatil hakkında 250 kelimelik bir kompozisyon yazınız.',
                'due_date' => '2023-04-24 23:59:59',
                'status' => 'Tamamlanmadı'
            ],
        ];
    }
}