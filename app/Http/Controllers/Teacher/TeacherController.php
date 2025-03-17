<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\User;
use App\Models\Homework;
use App\Models\Announcement;

use Carbon\Carbon;

class TeacherController extends Controller
{
    /**
     * Öğretmen dashboard sayfası
     */
    public function index()
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
        
        // Son gelen ödevler (değerlendirilmemiş)
        // Not: Bu kısım Homework modeli ve ilişkilerine bağlı olarak ayarlanmalı
        // Şimdilik dummy veri kullanıyoruz
        $recentHomeworks = $this->getDummyHomeworks();
        
        // Bekleyen ödev sayısı
        $pendingHomeworks = $recentHomeworks->count();
        
        return view('teacher.index', compact(
            'courses', 
            'activeCourses', 
            'totalStudents', 
            'recentHomeworks', 
            'pendingHomeworks'
        ));
    }
    
    /**
     * Örnek ödev verileri (gerçek uygulama için bu kısım veritabanından çekilecek)
     */
    private function getDummyHomeworks()
    {
        $teacher = Auth::user();
        $courses = Course::where('teacher_id', $teacher->id)->pluck('id')->toArray();
        
        // Gerçek uygulama için bu kısım veritabanından çekilecek
        // Şimdilik manuel olarak oluşturuyoruz
        $homeworks = collect([]);
        
        // Örnek ödev verisi 1
        $homework1 = new \stdClass();
        $homework1->id = 1;
        $homework1->title = "Python ile Veri Analizi Ödevi";
        $homework1->submitted_at = Carbon::now()->subDays(1);
        
        $student1 = new \stdClass();
        $student1->name = "Ahmet Yılmaz";
        $homework1->student = $student1;
        
        $course1 = new \stdClass();
        $course1->name = "Python Programlama";
        $homework1->course = $course1;
        
        // Örnek ödev verisi 2
        $homework2 = new \stdClass();
        $homework2->id = 2;
        $homework2->title = "Web Tasarım Projesi";
        $homework2->submitted_at = Carbon::now()->subDays(2);
        
        $student2 = new \stdClass();
        $student2->name = "Ayşe Demir";
        $homework2->student = $student2;
        
        $course2 = new \stdClass();
        $course2->name = "Frontend Geliştirme";
        $homework2->course = $course2;
        
        // Örnek ödev verisi 3
        $homework3 = new \stdClass();
        $homework3->id = 3;
        $homework3->title = "Veri Tabanı İlişkisel Model";
        $homework3->submitted_at = Carbon::now()->subHours(5);
        
        $student3 = new \stdClass();
        $student3->name = "Mehmet Kaya";
        $homework3->student = $student3;
        
        $course3 = new \stdClass();
        $course3->name = "SQL ve Veri Tabanı Yönetimi";
        $homework3->course = $course3;
        
        // Ödevleri koleksiyona ekle
        $homeworks->push($homework1);
        $homeworks->push($homework2);
        $homeworks->push($homework3);
        
        return $homeworks;
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
            ->wherePivot('status_id', 1) // Active status_id
            ->first();
        
        if (!$enrollment) {
            // Kursun genel detay sayfasına yönlendir
            return redirect()->route('courses.detail', $course->slug)
                ->with('error', 'Bu kursa erişim izniniz bulunmamaktadır. Lütfen önce kursa kaydolun veya yönetici onayını bekleyin.');
        }
        
        // Gerçek kurs duyurularını getir
        $announcements = $course->announcements()->orderBy('created_at', 'desc')->get();
        
        // Kurs ödevlerini getir
        $homeworks = $this->getDummyHomeworks();
        
        return view('student.courses.detail', compact('course', 'announcements', 'homeworks'));
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
public function courseDetail($id)
{
    $teacher = Auth::user();
    
    // Kurs bilgisini getir ve öğretmene ait olduğunu kontrol et
    $course = Course::where('id', $id)
                    ->where('teacher_id', $teacher->id)
                    ->with(['category', 'level', 'students', 'announcements']) // homeworks'ü kaldırdık
                    ->firstOrFail();
    
    // Kursa kayıtlı öğrenciler
    $students = $course->students;
    
    // Kurs duyuruları - artık gerçek veritabanından geliyor
    $announcements = $course->announcements;
    
    // Kurs ödevleri (dummy veri)
    $homeworks = $this->getDummyCourseHomeworks($course->id);
    
    return view('teacher.course_detail', compact(
        'course',
        'students',
        'announcements',
        'homeworks'
    ));
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