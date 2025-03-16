<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseType;
use App\Models\CourseLevel;
use App\Models\CourseFrequency;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TestCoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('TestCoursesSeeder başlatılıyor...');
        
        // Veritabanından mevcut ID'leri çek
        $teacherIds = $this->getExistingIds('users', function ($query) {
            $query->whereExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('model_has_roles')
                    ->whereRaw('model_has_roles.model_id = users.id')
                    ->whereExists(function ($roleQuery) {
                        $roleQuery->select(DB::raw(1))
                            ->from('roles')
                            ->whereRaw('roles.id = model_has_roles.role_id')
                            ->where('roles.name', 'ogretmen');
                    });
            });
        });
        
        $courseTypeIds = $this->getExistingIds('course_types');
        $courseLevelIds = $this->getExistingIds('course_levels');
        $courseFrequencyIds = $this->getExistingIds('course_frequencies');
        $categoryIds = $this->getExistingIds('categories', function ($query) {
            $query->where('is_active', true);
        });
        
        // ID'lerin kontrol edilmesi
        $this->checkAndCreateRequiredData($teacherIds, $courseTypeIds, $courseLevelIds, $courseFrequencyIds, $categoryIds);
        
        // ID'leri tekrar çek (bazıları eksik olabilir ve oluşturulmuş olabilir)
        $teacherIds = $this->getExistingIds('users', function ($query) {
            $query->whereExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('model_has_roles')
                    ->whereRaw('model_has_roles.model_id = users.id')
                    ->whereExists(function ($roleQuery) {
                        $roleQuery->select(DB::raw(1))
                            ->from('roles')
                            ->whereRaw('roles.id = model_has_roles.role_id')
                            ->where('roles.name', 'ogretmen');
                    });
            });
        });
        
        $courseTypeIds = $this->getExistingIds('course_types');
        $courseLevelIds = $this->getExistingIds('course_levels');
        $courseFrequencyIds = $this->getExistingIds('course_frequencies');
        $categoryIds = $this->getExistingIds('categories', function ($query) {
            $query->where('is_active', true);
        });
        
        // Kurs verileri
        $courses = [
            [
                'name' => 'PHP ile Web Geliştirme',
                'slug' => 'php-web-gelistirme',
                'description' => 'PHP ile modern web uygulamaları geliştirmeyi öğrenin. Laravel, Symfony ve daha fazlası.',
                'objectives' => 'Bu kursun sonunda, öğrenciler PHP ile web uygulamaları geliştirebilecek, veritabanı bağlantıları kurabilecek ve popüler frameworkleri kullanabilecekler.',
                'price' => 1499.99,
                'discount_price' => 1199.99,
                'total_hours' => 48,
                'max_students' => 20,
                'is_active' => true,
                'is_featured' => true,
                'has_certificate' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'JavaScript Temelleri',
                'slug' => 'javascript-temelleri',
                'description' => 'JavaScript\'in temel prensiplerini, DOM manipülasyonunu ve modern JavaScript özelliklerini öğrenin.',
                'objectives' => 'Kursiyer, JavaScript dilini temel seviyede öğrenecek, basit web uygulamaları geliştirebilecek ve modern JS kütüphanelerini kullanabilecek.',
                'price' => 899.99,
                'discount_price' => null,
                'total_hours' => 36,
                'max_students' => 25,
                'is_active' => true,
                'is_featured' => true,
                'has_certificate' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'Python ile Veri Analizi',
                'slug' => 'python-veri-analizi',
                'description' => 'Python, Pandas, NumPy ve Matplotlib kullanarak veri analizi ve veri görselleştirme tekniklerini öğrenin.',
                'objectives' => 'Bu kurs sonunda öğrenciler, Python ile veri analizi yapabilecek, verileri temizleyebilecek ve görselleştirebilecekler.',
                'price' => 1699.99,
                'discount_price' => 1299.99,
                'total_hours' => 54,
                'max_students' => 15,
                'is_active' => true,
                'is_featured' => false,
                'has_certificate' => true,
                'display_order' => 3,
            ],
            [
                'name' => 'UI/UX Tasarım Temel Eğitimi',
                'slug' => 'ui-ux-tasarim-egitimi',
                'description' => 'Kullanıcı arayüzü ve deneyimi tasarımının temellerini öğrenin. Figma ve Adobe XD ile pratik yapın.',
                'objectives' => 'Kursiyerler, kullanıcı odaklı tasarım prensiplerini öğrenecek, wireframe ve prototip oluşturabilecek hale gelecekler.',
                'price' => 1299.99,
                'discount_price' => 999.99,
                'total_hours' => 40,
                'max_students' => 20,
                'is_active' => true,
                'is_featured' => true,
                'has_certificate' => true,
                'display_order' => 4,
            ],
            [
                'name' => 'İngilizce Konuşma Becerileri',
                'slug' => 'ingilizce-konusma-becerileri',
                'description' => 'Günlük hayatta ve iş ortamında akıcı İngilizce konuşma becerilerini geliştirin.',
                'objectives' => 'Kursiyerler, günlük konuşmalarda ve profesyonel ortamlarda rahatça İngilizce konuşabilecek seviyeye gelecekler.',
                'price' => 1199.99,
                'discount_price' => null,
                'total_hours' => 60,
                'max_students' => 12,
                'is_active' => true,
                'is_featured' => false,
                'has_certificate' => true,
                'display_order' => 5,
            ],
            [
                'name' => 'Dijital Pazarlama Uzmanlığı',
                'slug' => 'dijital-pazarlama-uzmanligi',
                'description' => 'SEO, Google Ads, sosyal medya pazarlaması ve içerik stratejileri ile dijital pazarlama uzmanlığı.',
                'objectives' => 'Kursiyerler, dijital pazarlama kampanyaları oluşturabilecek, analiz edebilecek ve optimizasyon yapabilecek düzeye gelecekler.',
                'price' => 1599.99,
                'discount_price' => 1399.99,
                'total_hours' => 48,
                'max_students' => 20,
                'is_active' => false,
                'is_featured' => false,
                'has_certificate' => true,
                'display_order' => 6,
            ],
            [
                'name' => 'Kişisel Finans Yönetimi',
                'slug' => 'kisisel-finans-yonetimi',
                'description' => 'Bütçe oluşturma, yatırım stratejileri ve finansal hedeflerinize ulaşma yollarını öğrenin.',
                'objectives' => 'Kursiyerler, kişisel bütçe oluşturabilecek, tasarruf stratejileri geliştirebilecek ve temel yatırım araçlarını tanıyacaklar.',
                'price' => 799.99,
                'discount_price' => 599.99,
                'total_hours' => 24,
                'max_students' => 30,
                'is_active' => true,
                'is_featured' => true,
                'has_certificate' => false,
                'display_order' => 7,
            ],
            [
                'name' => 'Sağlıklı Beslenme ve Yaşam',
                'slug' => 'saglikli-beslenme-yasam',
                'description' => 'Beslenme prensipleri, egzersiz rutinleri ve sağlıklı bir yaşam için pratik bilgiler.',
                'objectives' => 'Katılımcılar, dengeli beslenme programı oluşturabilecek, doğru egzersiz rutinleri belirleyebilecek ve sağlıklı yaşam alışkanlıkları edinecekler.',
                'price' => 699.99,
                'discount_price' => null,
                'total_hours' => 20,
                'max_students' => 25,
                'is_active' => true,
                'is_featured' => false,
                'has_certificate' => false,
                'display_order' => 8,
            ],
            [
                'name' => 'İş İngilizcesi',
                'slug' => 'is-ingilizcesi',
                'description' => 'İş ortamında kullanılan İngilizce terimleri, sunum teknikleri ve profesyonel yazışmaları öğrenin.',
                'objectives' => 'Kursiyerler, iş toplantılarında ve profesyonel yazışmalarda İngilizceyi etkili bir şekilde kullanabilecek düzeye gelecekler.',
                'price' => 1099.99,
                'discount_price' => 899.99,
                'total_hours' => 36,
                'max_students' => 15,
                'is_active' => true,
                'is_featured' => true,
                'has_certificate' => true,
                'display_order' => 9,
            ],
            [
                'name' => 'React ile Modern Web Uygulamaları',
                'slug' => 'react-modern-web',
                'description' => 'React.js ile single page application geliştirmeyi, state yönetimini ve modern frontend tekniklerini öğrenin.',
                'objectives' => 'Kursiyerler, React bileşenleri oluşturabilecek, state yönetimi yapabilecek ve kapsamlı frontend uygulamaları geliştirebilecekler.',
                'price' => 1399.99,
                'discount_price' => 1099.99,
                'total_hours' => 42,
                'max_students' => 18,
                'is_active' => true,
                'is_featured' => true,
                'has_certificate' => true,
                'display_order' => 10,
            ],
        ];
        
        // Kursları oluştur
        $createdCount = 0;
        foreach ($courses as $courseData) {
            try {
                // Rastgele öğretmen, kategori, seviye, tip ve frekans ataması
                $courseData['teacher_id'] = $this->getRandomId($teacherIds);
                $courseData['category_id'] = $this->getRandomId($categoryIds);
                $courseData['level_id'] = $this->getRandomId($courseLevelIds);
                $courseData['type_id'] = $this->getRandomId($courseTypeIds);
                $courseData['frequency_id'] = $this->getRandomId($courseFrequencyIds);
                
                // Rastgele başlangıç ve bitiş tarihleri
                $startDate = Carbon::now()->addDays(rand(5, 30));
                $endDate = (clone $startDate)->addWeeks(rand(4, 16));
                
                $courseData['start_date'] = $startDate;
                $courseData['end_date'] = $endDate;
                
                // Rastgele başlangıç ve bitiş saatleri
                $startHour = rand(9, 17);
                $courseData['start_time'] = sprintf('%02d:00:00', $startHour);
                $courseData['end_time'] = sprintf('%02d:00:00', $startHour + rand(1, 3));
                
                // Rastgele konum ve online bağlantı bilgileri
                if (rand(0, 1)) {
                    $courseData['location'] = 'Sınıf ' . rand(101, 305) . ', ' . ['A', 'B', 'C'][rand(0, 2)] . ' Blok';
                    $courseData['meeting_link'] = null;
                    $courseData['meeting_password'] = null;
                } else {
                    $courseData['location'] = null;
                    $courseData['meeting_link'] = 'https://meet.example.com/' . \Str::random(10);
                    $courseData['meeting_password'] = \Str::random(6);
                }
                
                // Eğer zaten aynı slug ile bir kurs varsa, slug'ı güncelle
                if (Course::where('slug', $courseData['slug'])->exists()) {
                    $courseData['slug'] = $courseData['slug'] . '-' . rand(100, 999);
                }
                
                Course::create($courseData);
                $createdCount++;
            } catch (\Exception $e) {
                $this->command->error('Kurs oluşturulurken hata: ' . $e->getMessage());
            }
        }
        
        $this->command->info($createdCount . ' adet test kursu başarıyla oluşturuldu!');
    }
    
    /**
     * Belirtilen tablodan ID'leri çek
     */
    private function getExistingIds($table, $condition = null)
    {
        $query = DB::table($table);
        
        if ($condition !== null) {
            $condition($query);
        }
        
        return $query->pluck('id')->toArray();
    }
    
    /**
     * Rastgele bir ID seç
     */
    private function getRandomId($ids)
    {
        if (empty($ids)) {
            return null;
        }
        
        return $ids[array_rand($ids)];
    }
    
    /**
     * Gerekli verilerin kontrol edilmesi ve eksik ise oluşturulması
     */
    private function checkAndCreateRequiredData(&$teacherIds, &$courseTypeIds, &$courseLevelIds, &$courseFrequencyIds, &$categoryIds)
    {
        // Öğretmen kontrolü
        if (empty($teacherIds)) {
            $this->command->info('Öğretmen bulunamadı. Örnek öğretmen oluşturuluyor...');
            $teacher = User::create([
                'name' => 'Ahmet Öğretmen',
                'email' => 'ogretmen@example.com',
                'password' => bcrypt('password'),
            ]);
            
            // Role ekleme (roles ve model_has_roles tabloları var mı kontrol et)
            if (DB::table('roles')->where('name', 'ogretmen')->exists()) {
                $roleId = DB::table('roles')->where('name', 'ogretmen')->value('id');
                DB::table('model_has_roles')->insert([
                    'role_id' => $roleId,
                    'model_type' => User::class,
                    'model_id' => $teacher->id
                ]);
            }
            
            $teacherIds = [$teacher->id];
        }
        
        // Kurs tipleri kontrolü
        if (empty($courseTypeIds)) {
            $this->command->info('Kurs tipi bulunamadı. Örnek kurs tipleri oluşturuluyor...');
            $types = ['Yüz Yüze', 'Online', 'Hibrit', 'Workshop', 'Sertifika Programı'];
            foreach ($types as $type) {
                $courseType = CourseType::create(['name' => $type]);
                $courseTypeIds[] = $courseType->id;
            }
        }
        
        // Kurs seviyeleri kontrolü
        if (empty($courseLevelIds)) {
            $this->command->info('Kurs seviyesi bulunamadı. Örnek kurs seviyeleri oluşturuluyor...');
            $levels = ['Başlangıç', 'Orta', 'İleri', 'Uzman'];
            foreach ($levels as $level) {
                $courseLevel = CourseLevel::create(['name' => $level]);
                $courseLevelIds[] = $courseLevel->id;
            }
        }
        
        // Kurs frekansları kontrolü
        if (empty($courseFrequencyIds)) {
            $this->command->info('Kurs frekansı bulunamadı. Örnek kurs frekansları oluşturuluyor...');
            $frequencies = ['Günlük', 'Haftalık', 'Aylık', 'Tek Seferlik'];
            foreach ($frequencies as $frequency) {
                $courseFrequency = CourseFrequency::create(['name' => $frequency]);
                $courseFrequencyIds[] = $courseFrequency->id;
            }
        }
        
        // Kategoriler kontrolü
        if (empty($categoryIds)) {
            $this->command->info('Kategori bulunamadı. Örnek kategoriler oluşturuluyor...');
            $categories = ['Yazılım', 'Tasarım', 'İşletme', 'Pazarlama', 'Dil Eğitimi', 'Kişisel Gelişim', 'Finans', 'Sağlık'];
            foreach ($categories as $index => $category) {
                $cat = Category::create([
                    'name' => $category,
                    'slug' => \Str::slug($category),
                    'description' => $category . ' kategorisindeki eğitimler',
                    'is_active' => true,
                    'display_order' => $index + 1
                ]);
                $categoryIds[] = $cat->id;
            }
        }
    }
}