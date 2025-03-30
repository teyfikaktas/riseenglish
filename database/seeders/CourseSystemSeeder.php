<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CourseSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kurs Tipleri
        $courseTypes = [
            ['name' => 'Online', 'description' => 'Tüm dersler internet üzerinden canlı yapılır'],
            ['name' => 'Yüzyüze', 'description' => 'Tüm dersler fiziksel sınıf ortamında yapılır'],
            ['name' => 'Hibrit', 'description' => 'Dersler hem online hem de yüzyüze yapılır']
        ];

        foreach ($courseTypes as $type) {
            DB::table('course_types')->insert([
                'name' => $type['name'],
                'description' => $type['description'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 2. Kurs Frekansları
        $courseFrequencies = [
            ['name' => 'Günlük', 'description' => 'Haftada 5 gün ders', 'sessions_per_week' => 5],
            ['name' => 'Haftada 2', 'description' => 'Haftada 2 gün ders', 'sessions_per_week' => 2],
            ['name' => 'Haftada 3', 'description' => 'Haftada 3 gün ders', 'sessions_per_week' => 3],
            ['name' => 'Haftalık', 'description' => 'Haftada 1 gün ders', 'sessions_per_week' => 1],
            ['name' => 'İki Haftada Bir', 'description' => 'İki haftada bir ders', 'sessions_per_week' => 0.5]
        ];

        foreach ($courseFrequencies as $frequency) {
            DB::table('course_frequencies')->insert([
                'name' => $frequency['name'],
                'description' => $frequency['description'],
                'sessions_per_week' => $frequency['sessions_per_week'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 3. Kurs Seviyeleri
        $courseLevels = [
            ['name' => 'A1', 'description' => 'Başlangıç seviyesi'],
            ['name' => 'A2', 'description' => 'Temel seviye'],
            ['name' => 'B1', 'description' => 'Orta seviye başlangıç'],
            ['name' => 'B2', 'description' => 'Orta seviye ileri'],
            ['name' => 'C1', 'description' => 'İleri seviye'],
            ['name' => 'C2', 'description' => 'Profesyonel seviye']
        ];

        foreach ($courseLevels as $level) {
            DB::table('course_levels')->insert([
                'name' => $level['name'],
                'description' => $level['description'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 4. Değerlendirme Tipleri
        $assessmentTypes = [
            ['name' => 'Quiz', 'description' => 'Kısa sınav'],
            ['name' => 'Midterm', 'description' => 'Ara sınav'],
            ['name' => 'Final', 'description' => 'Final sınavı'],
            ['name' => 'Project', 'description' => 'Proje'],
            ['name' => 'Presentation', 'description' => 'Sunum'],
            ['name' => 'Speaking', 'description' => 'Konuşma sınavı'],
            ['name' => 'Writing', 'description' => 'Yazma sınavı']
        ];

        foreach ($assessmentTypes as $type) {
            DB::table('assessment_types')->insert([
                'name' => $type['name'],
                'description' => $type['description'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 5. Materyal Tipleri
        $materialTypes = [
            ['name' => 'PDF', 'description' => 'PDF dokümanı'],
            ['name' => 'Video', 'description' => 'Video kaydı'],
            ['name' => 'Audio', 'description' => 'Ses kaydı'],
            ['name' => 'Link', 'description' => 'Harici bağlantı'],
            ['name' => 'Other', 'description' => 'Diğer materyal']
        ];

        foreach ($materialTypes as $type) {
            DB::table('material_types')->insert([
                'name' => $type['name'],
                'description' => $type['description'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 6. Kayıt Durumları
        $enrollmentStatuses = [
            ['name' => 'Active', 'description' => 'Aktif öğrenci'],
            ['name' => 'Completed', 'description' => 'Kursu tamamladı'],
            ['name' => 'Dropped', 'description' => 'Kursu bıraktı'],
            ['name' => 'Waiting', 'description' => 'Kayıt beklemede']
        ];

        foreach ($enrollmentStatuses as $status) {
            DB::table('enrollment_statuses')->insert([
                'name' => $status['name'],
                'description' => $status['description'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 7. Kullanıcılar - Eğer users tablosu yoksa veya boş ise

        // 8. Kurslar
        $courses = [
            [
                'name' => 'İngilizce Konuşma Kursu - A1 Seviyesi',
                'teacher_id' => 1, // İlk öğretmen kullanıcı
                'description' => 'Başlangıç seviyesi İngilizce konuşma kursu',
                'objectives' => 'Günlük konuşma İngilizcesi, temel gramer, basit diyaloglar',
                'level_id' => 1, // A1
                'type_id' => 1, // Online
                'frequency_id' => 3, // Haftada 3
                'start_date' => Carbon::now()->addDays(10),
                'end_date' => Carbon::now()->addMonths(3),
            ],
            [
                'name' => 'İş İngilizcesi - B2 Seviyesi',
                'teacher_id' => 2, // İkinci öğretmen kullanıcı
                'description' => 'Profesyoneller için İş İngilizcesi kursu',
                'objectives' => 'Toplantı yönetimi, sunum becerileri, iş yazışmaları',
                'level_id' => 4, // B2
                'type_id' => 2, // Yüzyüze
                'frequency_id' => 2, // Haftada 2
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addMonths(4),
            ],
            [
                'name' => 'TOEFL Hazırlık Kursu',
                'teacher_id' => 3, // Üçüncü öğretmen kullanıcı
                'description' => 'TOEFL sınavına hazırlık kursu',
                'objectives' => 'Okuma, yazma, dinleme ve konuşma becerileri, sınav stratejileri',
                'level_id' => 5, // C1
                'type_id' => 3, // Hibrit
                'frequency_id' => 4, // Haftalık
                'start_date' => Carbon::now()->addDays(20),
                'end_date' => Carbon::now()->addMonths(5),
            ]
        ];

        // Kursları ekleyelim ve ID'lerini alalım
        foreach ($courses as $course) {
            // Slug oluştur
            $slug = Str::slug($course['name'], '-');
            
            // Slug'ın benzersiz olduğundan emin ol
            $count = 1;
            $originalSlug = $slug;
            while (DB::table('courses')->where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            
            $courseId = DB::table('courses')->insertGetId([
                'name' => $course['name'],
                'slug' => $slug, // SLUG EKLENDI!
                'teacher_id' => $course['teacher_id'],
                'description' => $course['description'],
                'objectives' => $course['objectives'],
                'level_id' => $course['level_id'],
                'type_id' => $course['type_id'], 
                'frequency_id' => $course['frequency_id'],
                'start_date' => $course['start_date'],
                'end_date' => $course['end_date'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Her kurs için materyaller
            for ($i = 1; $i <= rand(3, 8); $i++) {
                DB::table('course_materials')->insert([
                    'course_id' => $courseId,
                    'title' => "Materyal $i - " . $course['name'],
                    'description' => "Bu $i. materyal için açıklama",
                    'type_id' => rand(1, 5), // Rastgele materyal tipi
                    'file_path' => rand(1, 2) == 1 ? "materials/material-$courseId-$i.pdf" : null,
                    'external_link' => rand(1, 2) == 2 ? "https://example.com/material-$i" : null,
                    'is_required' => rand(0, 1),
                    'order' => $i,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            // Her kurs için oturumlar
            // Burada oturum ekleme kodu eklenebilir
        }
    }
}