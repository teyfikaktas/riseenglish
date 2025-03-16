<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HomeworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tüm kursları al
        $courses = Course::all();
        
        // Öğrenci rolüne sahip kullanıcıları al
        $students = User::whereHas('roles', function($query) {
            $query->where('name', 'student');
        })->get();
        
        // Eğer öğrenci bulunamazsa, tüm kullanıcıları al
        if ($students->isEmpty()) {
            $students = User::all();
        }
        
        // Kurs başlıkları
        $homeworkTitles = [
            'Haftalık Alıştırmalar',
            'Present Perfect Tense Alıştırmaları',
            'Past Continuous vs Past Simple Pratik',
            'Modal Verbs - Haftalık Quiz',
            'Future Tenses Uygulaması',
            'Conditionals Practice',
            'Reading Comprehension Exercise',
            'Writing Assignment',
            'Listening Practice',
            'Grammar Review',
            'Vocabulary Test',
            'Konuşma Pratiği Ödevi',
            'Dönem Sonu Projesi',
            'Araştırma Ödevi',
            'Grup Çalışması',
            'Sunum Hazırlama',
            'Case Study Analysis',
        ];
        
        // Açıklamalar
        $descriptions = [
            'Bu haftaki konu ile ilgili alıştırmaları tamamlayınız.',
            'Ders kitabındaki alıştırmaları yaparak sisteme yükleyiniz.',
            'Verilen konuyla ilgili araştırma yapıp bir sayfa özet hazırlayınız.',
            'Derste gördüğümüz konularla ilgili örnekler bulup analiz ediniz.',
            'Kitaptaki ilgili bölümü okuyup soruları cevaplayınız.',
            'Online kaynakları kullanarak konuyla ilgili örnekler toplayınız.',
            'Konu ile ilgili kendi görüşlerinizi içeren bir kompozisyon yazınız.',
            'Bu konu hakkında bildiklerinizi sınıfta sunmak için hazırlık yapınız.',
            'Verilen konuyu araştırıp arkadaşlarınızla tartışınız.',
            'Bu konuda günlük hayattan örnekler bulunuz ve analiz ediniz.',
        ];
        
        // Statüsler
        $statuses = ['pending', 'graded', 'late', 'rejected'];
        
        // Geri bildirimler
        $feedbacks = [
            'Çok iyi çalışma! Konuyu doğru şekilde anlamışsınız.',
            'Güzel bir çalışma olmuş, ancak bazı eksikler var.',
            'Daha fazla örnek ekleyebilirdiniz.',
            'Konuyu biraz daha detaylandırmanız gerekiyor.',
            'Harika bir ödev, tebrikler!',
            'İyi bir başlangıç, ama geliştirilebilir.',
            'Örnekleriniz konuyu iyi açıklıyor.',
            'Bazı kavramları karıştırmışsınız, lütfen tekrar gözden geçirin.',
            'Ödevde birkaç yazım hatası var, dikkat etmenizi öneririm.',
            'Çok yaratıcı bir yaklaşım, teşekkürler.',
        ];
        
        // Her kurs için 3-7 arası ödev oluştur
        foreach ($courses as $course) {
            $homeworkCount = rand(3, 7);
            
            for ($i = 0; $i < $homeworkCount; $i++) {
                // Başlangıç tarihi ve bitiş tarihi arasında rastgele bir tarih oluştur
                $startDate = $course->start_date ?? Carbon::now()->subMonths(2);
                $endDate = $course->end_date ?? Carbon::now()->addMonths(2);
                
                // Ödev oluşturma tarihi (published_at) - kurs başlangıç tarihinden sonra
                $publishedDate = Carbon::parse($startDate)->addDays(rand(5, 30));
                
                // Son teslim tarihi - oluşturma tarihinden 1-2 hafta sonra
                $dueDate = Carbon::parse($publishedDate)->addDays(rand(7, 14));
                
                // Eğer son teslim tarihi kurs bitiş tarihinden sonra ise, bitiş tarihinden önce olacak şekilde ayarla
                if ($course->end_date && $dueDate->gt(Carbon::parse($endDate))) {
                    $dueDate = Carbon::parse($endDate)->subDays(rand(1, 5));
                }
                
                // Ödev oluştur
                $homework = Homework::create([
                    'title' => $homeworkTitles[array_rand($homeworkTitles)],
                    'description' => $descriptions[array_rand($descriptions)],
                    'course_id' => $course->id,
                    'published_at' => $publishedDate,
                    'due_date' => $dueDate,
                    'max_score' => rand(5, 10) * 10, // 50, 60, 70, ... 100
                    'is_active' => rand(0, 5) > 0, // %80 olasılıkla aktif
                ]);
                
                // Bu kursa kayıtlı öğrenciler için ödev gönderileri oluştur
                $courseStudents = $course->students;
                
                // Eğer kursa kayıtlı öğrenci yoksa, tüm öğrencilerden rastgele seç
                if ($courseStudents->isEmpty()) {
                    $courseStudents = $students->random(min(5, $students->count()));
                }
                
                foreach ($courseStudents as $student) {
                    // %80 ihtimalle ödev gönderisi oluştur
                    if (rand(0, 4) > 0) {
                        // Ödev son teslim tarihinden önce veya sonra gönderme %80/%20 olasılık
                        $isLate = rand(0, 4) === 0;
                        
                        $submittedAt = $isLate 
                            ? Carbon::parse($dueDate)->addDays(rand(1, 5)) 
                            : Carbon::parse($dueDate)->subDays(rand(1, 5));
                        
                        // Öğrencinin geçmiş tarihteki ödevler için gönderisi
                        if ($dueDate->lt(Carbon::now())) {
                            $status = $statuses[array_rand($statuses)];
                            $score = $status === 'graded' ? rand(5, 10) * 10 : null; // 50-100 arası not
                            $gradedAt = $status === 'graded' ? $submittedAt->copy()->addDays(rand(1, 3)) : null;
                            $feedback = $status === 'graded' ? $feedbacks[array_rand($feedbacks)] : null;
                        } else {
                            // Henüz son teslim tarihi gelmemiş ödevler için
                            $status = 'pending';
                            $score = null;
                            $gradedAt = null;
                            $feedback = null;
                        }
                        
                        HomeworkSubmission::create([
                            'homework_id' => $homework->id,
                            'user_id' => $student->id,
                            'comment' => 'Ödevimi tamamladım.',
                            'submitted_at' => $submittedAt,
                            'status' => $status,
                            'score' => $score,
                            'feedback' => $feedback,
                            'graded_at' => $gradedAt,
                        ]);
                    }
                }
            }
        }
    }
}