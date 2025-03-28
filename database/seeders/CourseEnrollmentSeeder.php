<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use App\Models\EnrollmentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CourseEnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tüm kursları al
        $courses = Course::all();
        
        // Öğrenci rolüne sahip tüm kullanıcıları al veya yeni rastgele kullanıcılar oluştur
        $students = User::whereHas('roles', function($query) {
            $query->where('name', 'ogrenci');
        })->get();
        
        // Yeterli öğrenci yoksa yeni öğrenciler oluştur
        if ($students->count() < 30) {
            for ($i = 0; $i < 30; $i++) {
                $newStudent = User::factory()->create();
                $newStudent->assignRole('ogrenci');
                $students->push($newStudent);
            }
        }
        
        // Kayıt durumlarını al
        $statuses = EnrollmentStatus::all();
        
        // Her kurs için rastgele sayıda öğrenci ekle
        foreach ($courses as $course) {
            // Her kurs için 5-20 arası rastgele sayıda öğrenci seç
            $enrollmentCount = rand(5, 20);
            
            // Rastgele seçilen öğrenciler
            $randomStudents = $students->random(min($enrollmentCount, $students->count()));
            
            foreach ($randomStudents as $student) {
                // Rastgele bir kayıt durumu seç
                $status = $statuses->random();
                
                // Kayıt tarihi: Şimdi ile 6 ay öncesi arasında rastgele
                $enrollmentDate = Carbon::now()->subDays(rand(0, 180));
                
                // Ödeme miktarı: 0 ile kurs fiyatı arasında rastgele
                $paidAmount = $course->price ? rand(0, $course->price) : 0;
                
                // Ödeme tamamlanma durumu
                $paymentCompleted = $paidAmount >= ($course->price ?? 0);
                
                // Rastgele onay durumu (yüzde 70 oranında onaylanmış)
                $approvalStatus = (rand(1, 10) <= 7);
                
                // Kurs tamamlanma tarihi (sadece 'Completed' durumu için)
                $completionDate = $status->name === 'Completed' ? 
                    $enrollmentDate->copy()->addDays(rand(30, 90)) : null;
                
                // Final notu (sadece 'Completed' durumu için)
                $finalGrade = $status->name === 'Completed' ? rand(50, 100) : null;
                
                // Eğer bu öğrenci zaten bu kursa kayıtlı değilse, kayıt oluştur
                if (!DB::table('course_user')
                    ->where('course_id', $course->id)
                    ->where('user_id', $student->id)
                    ->exists()) {
                    
                    DB::table('course_user')->insert([
                        'course_id' => $course->id,
                        'user_id' => $student->id,
                        'enrollment_date' => $enrollmentDate,
                        'status_id' => $status->id,
                        'paid_amount' => $paidAmount,
                        'payment_completed' => $paymentCompleted,
                        'completion_date' => $completionDate,
                        'final_grade' => $finalGrade,
                        'notes' => "Otomatik oluşturulan test kaydı",
                        'approval_status' => $approvalStatus,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        
        $this->command->info('Toplam ' . $courses->count() . ' kurs için rastgele kayıtlar oluşturuldu.');
    }
}