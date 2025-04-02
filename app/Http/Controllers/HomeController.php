<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Idiom; // Idiom modelini import edin
use Carbon\Carbon; // Carbon'u import edin
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Yönetici kullanıcıları doğrudan yönetici paneline yönlendir
        if (Auth::check() && Auth::user()->hasRole('yonetici')) {
            return redirect('/admin/dashboard');
        }
        
        // Öğretmen kullanıcıları doğrudan öğretmen paneline yönlendir
        if (Auth::check() && Auth::user()->hasRole('ogretmen')) {
            return redirect('/ogretmen/panel');
        }
        
        // Öne çıkan kursları getir
        $featuredCourses = Course::where('is_featured', true)
            ->where('is_active', true)
            ->orderBy('display_order')
            ->with(['teacher', 'courseType', 'courseLevel'])
            ->take(6)
            ->get();
        
        // Günlük deyimi al
        $dailyIdiom = $this->getDailyIdiom();
        
        return view('welcome', compact('featuredCourses', 'dailyIdiom'));
    }
    
    /**
     * Günlük deyimi al
     */
    protected function getDailyIdiom()
    {
        // Debug: Toplam aktif idiom sayısını logla
        $totalIdioms = Idiom::where('is_active', true)->count();
        \Log::info("Toplam aktif idiom sayısı: " . $totalIdioms);
        
        // Bugün için belirlenmiş bir idiom var mı kontrol et
        $todayIdiom = Idiom::where('display_date', Carbon::today())
            ->where('is_active', true)
            ->first();
        
        \Log::info("Bugün için belirlenmiş idiom: " . ($todayIdiom ? 'Var' : 'Yok'));
        
        if (!$todayIdiom) {
            if ($totalIdioms > 0) {
                $dayOfYear = Carbon::today()->dayOfYear;
                $idiomIndex = $dayOfYear % $totalIdioms;
                
                if ($idiomIndex == 0 && $totalIdioms > 0) {
                    $idiomIndex = $totalIdioms;
                }
                
                \Log::info("Yılın günü: " . $dayOfYear . ", İdiom indeksi: " . $idiomIndex);
                
                $todayIdiom = Idiom::where('is_active', true)
                    ->skip($idiomIndex - 1)
                    ->first();
                
                \Log::info("Seçilen idiom: " . ($todayIdiom ? $todayIdiom->english_phrase : 'Yok'));
            }
        }
        
        return $todayIdiom;
    }
}