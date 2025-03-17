<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Announcement;
use Carbon\Carbon;
use Faker\Factory as Faker;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('tr_TR');
        
        // Tüm kursları al
        $courses = Course::all();
        
        if ($courses->count() === 0) {
            $this->command->info('Kurs bulunamadı. Önce kurs ekleyin.');
            return;
        }
        
        // Duyuru başlıkları ve içerikleri için hazır şablonlar
        $announcementTemplates = [
            [
                'title' => 'Ders Programı Değişikliği',
                'content' => 'Önümüzdeki hafta dersimiz farklı bir saatte yapılacaktır. Lütfen takviminizi güncelleyin.'
            ],
            [
                'title' => 'Önemli Proje Duyurusu',
                'content' => 'Dönem sonu projelerinin konuları ve grupları belirlenmiştir. Detaylar için lütfen öğrenci portalını kontrol ediniz.'
            ],
            [
                'title' => 'Sınav Tarihleri Açıklandı',
                'content' => 'Ara sınav ve final sınavı tarihleri kesinleşmiştir. Sınav programı ve içeriğine öğrenci portalından ulaşabilirsiniz.'
            ],
            [
                'title' => 'Ek Ders Duyurusu',
                'content' => 'Bu haftaki zorlu konuları pekiştirmek için Cuma günü ek bir ders yapılacaktır. Katılım zorunlu değildir.'
            ],
            [
                'title' => 'Ödev Teslim Tarihi Uzatıldı',
                'content' => 'Geçen hafta verilen ödevin teslim tarihi, gelen talepler doğrultusunda bir hafta uzatılmıştır. Yeni son teslim tarihi önümüzdeki Pazartesi günüdür.'
            ],
            [
                'title' => 'Konuk Konuşmacı Bilgilendirmesi',
                'content' => 'Önümüzdeki hafta sektörden deneyimli bir konuk konuşmacımız olacak. Tüm öğrencilerimizin katılımını bekliyoruz.'
            ],
            [
                'title' => 'Online Kaynaklar Güncellemesi',
                'content' => 'Ders materyallerine ek olarak faydalı olabilecek yeni online kaynaklar portal üzerinden paylaşılmıştır.'
            ],
        ];
        
        $this->command->info('Kurslara rastgele duyurular ekleniyor...');
        
        // Her kursa rastgele sayıda duyuru ekle
        foreach ($courses as $course) {
            // Kurs başına 2-5 arası rastgele duyuru
            $announcementCount = rand(2, 5);
            
            $this->command->info("Kurs: {$course->name} - {$announcementCount} duyuru ekleniyor.");
            
            // Duyuruları ekle
            for ($i = 0; $i < $announcementCount; $i++) {
                $template = $announcementTemplates[array_rand($announcementTemplates)];
                
                // Oluşturulma tarihi: son 30 gün içinde rastgele
                $createdAt = Carbon::now()->subDays(rand(0, 30));
                
                Announcement::create([
                    'course_id' => $course->id,
                    'title' => $template['title'],
                    'content' => $template['content'],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
        
        $this->command->info('Duyuru seed işlemi tamamlandı.');
    }
}