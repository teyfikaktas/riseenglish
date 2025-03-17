<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

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
            
            return view('student.courses.index', compact('enrolledCourses'));
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
 * Kurs detay sayfasını gösterir - sadece kayıtlı olan öğrenciler için
 */
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
        
        // Kurs duyurularını getir (gerçek veri)
        $announcements = Announcement::where('course_id', $course->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Kurs ödevlerini getir (dummy veri)
        $homeworks = $this->getDummyHomeworks();
        
        return view('student.courses.detail-enrollment', compact('course', 'announcements', 'homeworks'));
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
    public function submitHomework(Request $request, $id)
    {
        return redirect()->back()->with('success', 'Ödeviniz başarıyla gönderildi.');
    }
    
    /**
     * Geçici duyurular oluştur (gerçek veritabanı yerine)
     */
 
    
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