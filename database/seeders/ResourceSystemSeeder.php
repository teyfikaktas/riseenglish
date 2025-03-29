<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ResourceCategory;
use App\Models\ResourceType;
use App\Models\ResourceTag;
use App\Models\Resource;
use Illuminate\Support\Str;

class ResourceSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Kaynak Tipleri (ResourceTypes)
        $types = [
            'Vocabulary List',
            'Soru Tipleri',
            'Gramer',
            'Deneme Sınavı',
            'PDF Kitap',
            'Video Ders',
            'Flashcards',
        ];

        foreach ($types as $type) {
            ResourceType::create([
                'name' => $type,
                'slug' => Str::slug($type),
            ]);
        }

        // 2. Kategoriler (ResourceCategories)
        $categories = [
            'YDS',
            'YÖKDİL',
            'TOEFL',
            'IELTS',
            'Genel İngilizce',
        ];

        foreach ($categories as $category) {
            ResourceCategory::create([
                'name' => $category,
                'slug' => Str::slug($category),
            ]);
        }

        // Alt kategoriler
        $subcategories = [
            'YDS' => ['Kelime', 'Gramer', 'Okuma', 'Sınav Teknikleri'],
            'YÖKDİL' => ['Sosyal Bilimler', 'Fen Bilimleri', 'Sağlık Bilimleri'],
            'TOEFL' => ['Listening', 'Reading', 'Writing', 'Speaking'],
            'IELTS' => ['Academic', 'General Training'],
        ];

        foreach ($subcategories as $main => $subs) {
            $parent = ResourceCategory::where('name', $main)->first();
            
            foreach ($subs as $sub) {
                ResourceCategory::create([
                    'name' => $sub,
                    'slug' => Str::slug($main . '-' . $sub),
                    'parent_id' => $parent->id,
                ]);
            }
        }

        // 3. Etiketler (ResourceTags)
        $tags = [
            'Kelime',
            'Gramer',
            'Reading',
            'Listening',
            'Writing',
            'Speaking',
            'Akademik',
            'Deneme',
            'Başlangıç',
            'Orta Seviye',
            'İleri Seviye',
            'Ücretsiz',
            'Ücretli',
            'Popüler',
        ];

        foreach ($tags as $tag) {
            ResourceTag::create([
                'name' => $tag,
                'slug' => Str::slug($tag),
            ]);
        }

        // 4. Kaynaklar (Resources)
        $resourceData = [
            [
                'title' => 'YDS İçin Önemli Fiiller',
                'description' => 'YDS sınavında en çok çıkan fiillerin listesi',
                'type' => 'Vocabulary List',
                'category' => 'YDS-Kelime',
                'is_free' => true,
                'is_popular' => true,
                'image_path' => 'resources/voc-green.png', 
                'file_path' => 'files/yds-fiiller.pdf',
                'tags' => ['Kelime', 'Ücretsiz', 'Popüler']
            ],
            [
                'title' => 'YÖKDİL Sosyal Bilimler Kelime Listesi',
                'description' => 'YÖKDİL Sosyal Bilimler alanındaki önemli kelimeler',
                'type' => 'Vocabulary List',
                'category' => 'YÖKDİL-Sosyal Bilimler',
                'is_free' => true,
                'is_popular' => true,
                'image_path' => 'resources/voc-orange.png',
                'file_path' => 'files/yokdil-sosyal-kelimeler.pdf',
                'tags' => ['Kelime', 'Akademik', 'Ücretsiz']
            ],
            [
                'title' => 'YÖKDİL Sağlık Bilimleri Kelimeleri',
                'description' => 'YÖKDİL Sağlık Bilimleri sınavı için temel kelimeler',
                'type' => 'Vocabulary List',
                'category' => 'YÖKDİL-Sağlık Bilimleri',
                'is_free' => true,
                'is_popular' => true,
                'image_path' => 'resources/voc-green.png',
                'file_path' => 'files/yokdil-saglik-kelimeler.pdf',
                'tags' => ['Kelime', 'Akademik', 'Ücretsiz']
            ],
            [
                'title' => 'YÖKDİL Fen Bilimleri Kelimeleri',
                'description' => 'YÖKDİL Fen Bilimleri sınavı için gerekli terimler',
                'type' => 'Vocabulary List',
                'category' => 'YÖKDİL-Fen Bilimleri',
                'is_free' => true,
                'is_popular' => true,
                'image_path' => 'resources/voc-blue.png',
                'file_path' => 'files/yokdil-fen-kelimeler.pdf',
                'tags' => ['Kelime', 'Akademik', 'Ücretsiz']
            ],
            [
                'title' => 'YDS İçin Önemli Cloze Test Soruları',
                'description' => 'YDS sınavında çıkan cloze test örnek soruları',
                'type' => 'Soru Tipleri',
                'category' => 'YDS-Sınav Teknikleri',
                'is_free' => true,
                'is_popular' => false,
                'image_path' => 'resources/question-blue.png',
                'file_path' => 'files/yds-cloze-test.pdf',
                'tags' => ['Deneme', 'Orta Seviye', 'Ücretsiz']
            ],
            [
                'title' => 'YDS Paragraf Doldurma Teknikleri',
                'description' => 'YDS sınavında paragraf doldurma sorularını çözme teknikleri',
                'type' => 'Soru Tipleri',
                'category' => 'YDS-Okuma',
                'is_free' => true,
                'is_popular' => false,
                'image_path' => 'resources/question-blue.png',
                'file_path' => 'files/yds-paragraf-teknikleri.pdf',
                'tags' => ['Reading', 'İleri Seviye', 'Ücretsiz']
            ],
            [
                'title' => 'YDS İçin Preposition Çalışmaları',
                'description' => 'YDS sınavı için edatlar ve kullanımları',
                'type' => 'Gramer',
                'category' => 'YDS-Gramer',
                'is_free' => true,
                'is_popular' => false,
                'image_path' => 'resources/voc-green.png',
                'file_path' => 'files/yds-prepositions.pdf',
                'tags' => ['Gramer', 'Orta Seviye', 'Ücretsiz']
            ],
            [
                'title' => 'YDS Phrasal Verb Çalışması',
                'description' => 'YDS sınavında çıkan önemli phrasal verb\'ler',
                'type' => 'Vocabulary List',
                'category' => 'YDS-Kelime',
                'is_free' => true,
                'is_popular' => false,
                'image_path' => 'resources/voc-green.png',
                'file_path' => 'files/yds-phrasal-verbs.pdf',
                'tags' => ['Kelime', 'İleri Seviye', 'Ücretsiz']
            ],
        ];

        foreach ($resourceData as $data) {
            // Tür ve kategori ID'lerini bul
            $typeId = ResourceType::where('name', $data['type'])->first()->id;
            $categoryId = ResourceCategory::where('slug', Str::slug($data['category']))->first()->id;
            
            // Kaynak oluştur
            $resource = Resource::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'description' => $data['description'],
                'type_id' => $typeId,
                'category_id' => $categoryId,
                'is_free' => $data['is_free'],
                'is_popular' => $data['is_popular'],
                'image_path' => $data['image_path'],
                'file_path' => $data['file_path'],
                'download_count' => rand(10, 1000),
                'view_count' => rand(100, 5000),
            ]);
            
            // Etiketleri ekle
            foreach ($data['tags'] as $tagName) {
                $tag = ResourceTag::where('name', $tagName)->first();
                if ($tag) {
                    $resource->tags()->attach($tag->id);
                }
            }
        }
    }
}