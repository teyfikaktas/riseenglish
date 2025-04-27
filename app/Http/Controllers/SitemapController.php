<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Course;
use App\Models\ResourceCategory;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function generate()
    {
        $sitemap = Sitemap::create();

        // Ana sayfa
        $sitemap->add(
            Url::create('/')
                ->setLastModificationDate(Carbon::yesterday())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );

        // İletişim sayfası
        $sitemap->add(
            Url::create('/iletisim')
                ->setLastModificationDate(Carbon::yesterday())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.8)
        );

        // Kurslar ana sayfası
        $sitemap->add(
            Url::create('/egitimler')
                ->setLastModificationDate(Carbon::yesterday())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.9)
        );

        // Ücretsiz kaynaklar ana sayfası
        $sitemap->add(
            Url::create('/ucretsiz-kaynaklar')
                ->setLastModificationDate(Carbon::yesterday())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8)
        );

        // Tüm kursları ekle
        $courses = Course::where('is_active', true)->get();
        foreach ($courses as $course) {
            $sitemap->add(
                Url::create("/egitimler/{$course->slug}")
                    ->setLastModificationDate($course->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8)
            );
        }

        // Ücretsiz kaynakları ekle
        $resources = \App\Models\Resource::all(); // Filtre olmadan tüm kaynakları alın
        foreach ($resources as $resource) {
            $sitemap->add(
                Url::create("/ucretsiz-kaynaklar/{$resource->slug}")
                    ->setLastModificationDate($resource->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.7)
            );
        }

        // Kaynak kategorileri (varsa)
        try {
            $resourceCategories = ResourceCategory::all();
            foreach ($resourceCategories as $category) {
                $sitemap->add(
                    Url::create("/ucretsiz-kaynaklar/kategori/{$category->slug}")
                        ->setLastModificationDate($category->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.6)
                );
            }
        } catch (\Exception $e) {
            // ResourceCategory modeli yoksa veya bir hata olursa, bu bölümü atla
        }

        // Sitemap'i kaydet
        $sitemap->writeToFile(public_path('sitemap.xml'));

        return 'Sitemap başarıyla oluşturuldu!';
    }
}