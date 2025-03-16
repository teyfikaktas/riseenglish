<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Genel İngilizce',
                'description' => 'Günlük yaşamda kullanılan temel İngilizce becerileri geliştiren kurslar.',
                'is_active' => true,
                'is_featured' => true,
                'display_order' => 1,
                'discount_price' => null,
            ],
            [
                'name' => 'İş İngilizcesi',
                'description' => 'İş hayatında ve profesyonel ortamlarda kullanılan İngilizce kursları.',
                'is_active' => true,
                'is_featured' => true,
                'display_order' => 2,
                'discount_price' => null,
            ],
            [
                'name' => 'Akademik İngilizce',
                'description' => 'Akademik çalışmalar ve eğitim için gerekli İngilizce becerileri geliştiren kurslar.',
                'is_active' => true,
                'is_featured' => false,
                'display_order' => 3,
                'discount_price' => null,
            ],
            [
                'name' => 'Konuşma Pratiği',
                'description' => 'İngilizce konuşma becerilerini geliştirmeye odaklanan konuşma kulüpleri ve pratik kursları.',
                'is_active' => true,
                'is_featured' => true,
                'display_order' => 4,
                'discount_price' => null,
            ],
            [
                'name' => 'Sınav Hazırlık',
                'description' => 'TOEFL, IELTS, YDS gibi sınavlara hazırlık kursları.',
                'is_active' => true,
                'is_featured' => true,
                'display_order' => 5,
                'discount_price' => null,
            ],
            [
                'name' => 'Çocuklar İçin İngilizce',
                'description' => 'Çocuklar ve gençler için özel tasarlanmış eğlenceli İngilizce kursları.',
                'is_active' => true,
                'is_featured' => false,
                'display_order' => 6,
                'discount_price' => null,
            ],
            [
                'name' => 'Özel Amaçlı İngilizce',
                'description' => 'Tıbbi İngilizce, Hukuk İngilizcesi, Turizm İngilizcesi gibi özel alanlara yönelik kurslar.',
                'is_active' => true,
                'is_featured' => false,
                'display_order' => 7,
                'discount_price' => null,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => $category['is_active'],
                'is_featured' => $category['is_featured'],
                'display_order' => $category['display_order'],
                'discount_price' => $category['discount_price'],
            ]);
        }
    }
}