<?php

namespace App\Http\Controllers;

use App\Models\Idiom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class IdiomController extends Controller
{
/**
 * 365 gün için 100 deyim temel algoritma
 * display_date alanı olmadan çalışır
 */
protected function getDailyIdiom()
{
    // Toplam aktif deyim sayısını al
    $totalIdioms = Idiom::where('is_active', true)->count();
    \Log::info("Toplam aktif idiom sayısı: " . $totalIdioms);
    
    // Eğer hiç deyim yoksa null döndür
    if ($totalIdioms == 0) {
        return null;
    }
    
    // Eğer 100'den az deyim varsa, var olanları kullan
    $actualIdiomCount = min($totalIdioms, 100);
    
    // Yılın gününü al (1-365/366)
    $dayOfYear = Carbon::today()->dayOfYear;
    
    // Bugün gösterilecek deyimin indeksini hesapla
    // Yılın günü deyim sayısına bölünür ve kalan alınır
    $idiomIndex = ($dayOfYear % $actualIdiomCount);
    
    // İndeks 0 ise, son deyimi göster
    if ($idiomIndex == 0) {
        $idiomIndex = $actualIdiomCount;
    }
    
    \Log::info("Yılın günü: {$dayOfYear}, Deyim indeksi: {$idiomIndex}");
    
    // Hesaplanan indekse göre deyimi seç
    $idiom = Idiom::where('is_active', true)
        ->orderBy('id')
        ->skip($idiomIndex - 1)
        ->first();
    
    // Deyimi bulamazsak (örneğin, silinmiş veya devre dışı bırakılmışsa), ilk aktif deyimi al
    if (!$idiom) {
        \Log::info("Hesaplanan indekse göre deyim bulunamadı, ilk aktif deyimi alıyorum");
        $idiom = Idiom::where('is_active', true)->first();
    }
    
    return $idiom;
}
}