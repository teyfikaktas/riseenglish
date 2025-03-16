<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseAnnouncement;
use App\Models\CourseHomework;
use Illuminate\Support\Facades\Auth;

class StudentCourseController extends Controller
{
    /**
     * Öğrencinin kayıtlı olduğu kursları listeler
     */
    public function index()
    {
        $user = Auth::user();
        $enrolledCourses = $user->enrolledCourses()->get();
        
        return view('student.courses.index', compact('enrolledCourses'));
    }
    
    /**
     * Kurs detay sayfasını gösterir - sadece kayıtlı olan öğrenciler için
     */
    public function showCourseDetail($id)
    {
        $user = Auth::user();
        $course = Course::findOrFail($id);
        
        // Kullanıcının bu kursa kayıtlı olup olmadığını kontrol et
        if (!$user->enrolledCourses->contains($course->id)) {
            return redirect()->route('ogrenci.kurslarim')
                ->with('error', 'Bu kursa erişim izniniz bulunmamaktadır.');
        }
        
        // Kurs duyurularını getir (gerçek veri olmadığı için şimdilik rastgele duyurular oluşturalım)
        $announcements = $this->getDummyAnnouncements();
        
        // Kurs ödevlerini getir (gerçek veri olmadığı için şimdilik rastgele ödevler oluşturalım)
        $homeworks = $this->getDummyHomeworks();
        
        return view('student.courses.detail-enrollment', compact('course', 'announcements', 'homeworks'));
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
    private function getDummyAnnouncements()
    {
        return [
            [
                'id' => 1,
                'title' => 'Haftalık Program Değişikliği',
                'content' => 'Önümüzdeki hafta dersin saati 18:00\'dan 19:00\'a alınmıştır. Lütfen dikkat ediniz.',
                'created_at' => '2023-04-01 10:15:00'
            ],
            [
                'id' => 2,
                'title' => 'Yeni Kaynaklar Eklendi',
                'content' => 'Ders materyallerine yeni kaynaklar eklenmiştir. Kaynaklar sekmesinden erişebilirsiniz.',
                'created_at' => '2023-03-25 14:30:00'
            ],
            [
                'id' => 3,
                'title' => 'Quiz Hatırlatması',
                'content' => 'Yarın dersimizin ilk 15 dakikasında küçük bir quiz yapılacaktır. Lütfen hazırlıklı geliniz.',
                'created_at' => '2023-03-20 09:45:00'
            ],
        ];
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