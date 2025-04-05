<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PrivateLesson\PrivateLesson;
use App\Models\PrivateLesson\PrivateLessonTeacherRole;
use App\Models\PrivateLesson\PrivateLessonSession;
use App\Models\PrivateLesson\PrivateLessonOccurrence;
use App\Models\PrivateLesson\PrivateLessonMaterial;
use App\Models\PrivateLesson\PrivateLessonHomework;
use App\Models\PrivateLesson\PrivateLessonTeacherAvailability;
use App\Models\User;
use Carbon\Carbon;

class PrivateLessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Öğretmen ve öğrenci kullanıcıları oluştur
        $teachers = $this->createTeachers();
        $students = $this->createStudents();

        // Özel ders türleri oluştur
        $lessons = $this->createPrivateLessons();

        // Öğretmen uygunluklarını oluştur
        $this->createTeacherAvailabilities($teachers);

        // Özel ders planlamalarını oluştur
        $sessions = $this->createSessions($lessons, $teachers, $students);

        // Özel ders tekrarlarını oluştur
        $occurrences = $this->createOccurrences($sessions);

        // Ders materyallerini oluştur
        $this->createMaterials($occurrences);

        // Ödevleri oluştur
        $this->createHomeworks($occurrences);
    }

    private function createTeachers()
    {
        $teachers = [];

        $teacherData = [
            ['name' => 'Ahmet Yılmaz', 'email' => 'ahmet@example.com'],
            ['name' => 'Mehmet Demir', 'email' => 'mehmet@example.com'],
            ['name' => 'Ayşe Şahin', 'email' => 'ayse@example.com'],
            ['name' => 'Kemal Yılmaz', 'email' => 'kemal@example.com'],
            ['name' => 'Seda Demir', 'email' => 'seda@example.com'],
        ];

        foreach ($teacherData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt('password'),
                ]
            );

            PrivateLessonTeacherRole::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'can_teach_private' => true,
                    'can_teach_group' => rand(0, 1) ? true : false,
                ]
            );

            $teachers[] = $user;
        }

        return $teachers;
    }

    private function createStudents()
    {
        $students = [];

        $studentData = [
            ['name' => 'Zeynep Kaya', 'email' => 'zeynep@example.com'],
            ['name' => 'Ali Yıldız', 'email' => 'ali@example.com'],
            ['name' => 'Mustafa Öztürk', 'email' => 'mustafa@example.com'],
            ['name' => 'Deniz Kara', 'email' => 'deniz@example.com'],
            ['name' => 'Can Yücel', 'email' => 'can@example.com'],
            ['name' => 'Zeynep Öz', 'email' => 'zeynepoz@example.com'],
        ];

        foreach ($studentData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt('password'),
                ]
            );

            $students[] = $user;
        }

        return $students;
    }

    private function createPrivateLessons()
    {
        $lessons = [];

        $lessonData = [
            [
                'name' => 'Bireysel Piyano Dersi',
                'description' => 'Başlangıç ve ileri seviye piyano eğitimi.',
                'price' => 250,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Bireysel Gitar Dersi',
                'description' => 'Klasik ve akustik gitar eğitimi.',
                'price' => 200,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Bireysel Keman Dersi',
                'description' => 'Başlangıç ve ileri seviye keman eğitimi.',
                'price' => 300,
                'duration_minutes' => 90,
                'is_active' => true,
            ],
            [
                'name' => 'Bireysel Davul Dersi',
                'description' => 'Temel ritim ve davul teknikleri eğitimi.',
                'price' => 220,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Bireysel Vokal Dersi',
                'description' => 'Ses eğitimi ve şarkı söyleme teknikleri.',
                'price' => 280,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
        ];

        foreach ($lessonData as $data) {
            $lesson = PrivateLesson::updateOrCreate(
                ['name' => $data['name']],
                $data
            );

            $lessons[] = $lesson;
        }

        return $lessons;
    }

    private function createTeacherAvailabilities($teachers)
    {
        foreach ($teachers as $teacher) {
            for ($day = 1; $day <= 5; $day++) {
                PrivateLessonTeacherAvailability::updateOrCreate(
                    [
                        'teacher_id' => $teacher->id,
                        'day_of_week' => $day,
                    ],
                    [
                        'start_time' => '09:00:00',
                        'end_time' => '18:00:00',
                        'is_available' => true,
                    ]
                );
            }

            // Cumartesi günü kısmi çalışma
            PrivateLessonTeacherAvailability::updateOrCreate(
                [
                    'teacher_id' => $teacher->id,
                    'day_of_week' => 6,
                ],
                [
                    'start_time' => '10:00:00',
                    'end_time' => '14:00:00',
                    'is_available' => rand(0, 1) ? true : false,
                ]
            );

            // Pazar günü
            PrivateLessonTeacherAvailability::updateOrCreate(
                [
                    'teacher_id' => $teacher->id,
                    'day_of_week' => 7,
                ],
                [
                    'start_time' => '00:00:00',
                    'end_time' => '00:00:00',
                    'is_available' => false,
                ]
            );
        }
    }

    private function createSessions($lessons, $teachers, $students)
    {
        $sessions = [];
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->addMonths(3)->endOfMonth();

        for ($i = 0; $i < 15; $i++) {
            $lesson = $lessons[array_rand($lessons)];
            $teacher = $teachers[array_rand($teachers)];
            $student = $students[array_rand($students)];
            $dayOfWeek = rand(1, 6);
            $startHour = rand(9, 17);
            $startTime = sprintf('%02d:00:00', $startHour);
            $endTime = sprintf('%02d:00:00', $startHour + 1);

            $session = PrivateLessonSession::create([
                'private_lesson_id' => $lesson->id,
                'teacher_id' => $teacher->id,
                'student_id' => $student->id,
                'day_of_week' => $dayOfWeek,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'is_recurring' => true,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'location' => 'Stüdyo ' . rand(1, 5),
                'status' => 'active',
                'notes' => 'Periyodik ders planlaması',
            ]);

            $sessions[] = $session;
        }

        return $sessions;
    }

    private function createOccurrences($sessions)
    {
        $occurrences = [];
        $statuses = ['scheduled', 'completed', 'cancelled'];

        foreach ($sessions as $session) {
            $currentDate = Carbon::parse($session->start_date);
            $endDate = Carbon::parse($session->end_date);

            while ($currentDate <= $endDate) {
                if ($currentDate->dayOfWeek == $session->day_of_week - 1) {
                    $status = $statuses[array_rand($statuses)];
                    
                    // Geçmiş dersler tamamlandı olarak ayarla
                    if ($currentDate < Carbon::now()) {
                        $status = 'completed';
                    }

                    $occurrence = PrivateLessonOccurrence::create([
                        'session_id' => $session->id,
                        'lesson_date' => $currentDate,
                        'start_time' => $session->start_time,
                        'end_time' => $session->end_time,
                        'status' => $status,
                        'teacher_notes' => $status == 'completed' ? 'Ders başarıyla tamamlandı.' : null,
                    ]);

                    $occurrences[] = $occurrence;
                }

                $currentDate->addDay();
            }
        }

        return $occurrences;
    }

    private function createMaterials($occurrences)
    {
        $titles = [
            'Temel Notalar',
            'Akor Çalışmaları',
            'Ritim Egzersizleri',
            'Legato Teknikleri',
            'Staccato Çalışmaları',
            'Parmak Egzersizleri',
            'Teori Notları',
            'Gam Çalışmaları'
        ];
        
        $descriptions = [
            'Başlangıç seviyesi çalışma notları',
            'Orta seviye teknik çalışmalar',
            'İleri seviye teknik çalışmalar',
            'Temel müzik teorisi notları',
            'Parmak tekniği egzersizleri',
        ];

        foreach ($occurrences as $index => $occurrence) {
            if ($index % 3 == 0) { // Her 3 derste bir materyal ekle
                PrivateLessonMaterial::create([
                    'occurrence_id' => $occurrence->id,
                    'title' => $titles[array_rand($titles)],
                    'description' => $descriptions[array_rand($descriptions)],
                    'file_path' => 'materials/example-' . rand(1, 10) . '.pdf',
                ]);
            }
        }
    }

    private function createHomeworks($occurrences)
    {
        $titles = [
            'Temel Çalışma',
            'Gam Çalışması',
            'Akor Geçişleri',
            'Parça Analizi',
            'Eser Çalışması',
            'Teori Ödevleri',
            'Ritim Çalışması',
            'Teknik Etüt'
        ];
        
        $descriptions = [
            'Verilen parçayı çalışın',
            'Gam ve arpej egzersizlerini tamamlayın',
            'Verilen akorları çalışın ve geçişleri pekiştirin',
            'Eseri analiz edin ve yorumlayın',
            'Teorik bilgileri not alın ve alıştırmaları yapın',
        ];

        foreach ($occurrences as $index => $occurrence) {
            if ($occurrence->status == 'completed' || $index % 2 == 0) { // Tamamlanan derslere veya her 2 derste bir ödev ekle
                PrivateLessonHomework::create([
                    'occurrence_id' => $occurrence->id,
                    'title' => $titles[array_rand($titles)],
                    'description' => $descriptions[array_rand($descriptions)],
                    'due_date' => Carbon::parse($occurrence->lesson_date)->addDays(7),
                ]);
            }
        }
    }
}