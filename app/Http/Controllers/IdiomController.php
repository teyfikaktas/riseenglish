<?php

namespace App\Http\Controllers;

use App\Models\Idiom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class IdiomController extends Controller
{
    public function getDailyIdiom()
    {
        // 1. Bugün için belirlenmiş bir idiom var mı diye bakalım
        $todayIdiom = Idiom::where('display_date', Carbon::today())
            ->where('is_active', true)
            ->first();
            
        // 2. Eğer bugün için belirlenmiş idiom yoksa, 
        // Günün sırasına göre bir idiom seçelim (döngüsel olarak)
        if (!$todayIdiom) {
            $totalIdioms = Idiom::where('is_active', true)->count();
            
            if ($totalIdioms > 0) {
                // Yılın günü mod toplam idiom sayısı = bugünkü idiom indeksi
                $dayOfYear = Carbon::today()->dayOfYear;
                $idiomIndex = $dayOfYear % $totalIdioms;
                
                // İndeksi 0 tabanlı olduğu için 0 durumunu kontrol edelim
                if ($idiomIndex == 0 && $totalIdioms > 0) {
                    $idiomIndex = $totalIdioms;
                }
                
                $todayIdiom = Idiom::where('is_active', true)
                    ->skip($idiomIndex - 1)
                    ->first();
            }
        }
        
        // Eğer hiç idiom yoksa null dönecek
        return $todayIdiom;
    }
}