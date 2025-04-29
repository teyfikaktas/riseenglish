<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChainProgress;
use Illuminate\Support\Facades\Auth;

class ChainBreakerController extends Controller
{
    /**
     * Zinciri Kırma ana sayfasını göster
     */
    public function index()
    {
        // Eğer kullanıcı giriş yapmışsa, ilerleme bilgisini çek
        $progress = null;
        if (Auth::check()) {
            $progress = ChainProgress::where('user_id', Auth::id())->first();
            
            // Eğer kullanıcının kaydı yoksa oluştur
            if (!$progress) {
                $progress = new ChainProgress();
                $progress->user_id = Auth::id();
                $progress->days_completed = 0;
                $progress->current_streak = 0;
                $progress->longest_streak = 0;
                $progress->last_completed_at = null;
                $progress->save();
            }
        }
        
        return view('zinciri-kirma', compact('progress'));
    }
    
    /**
     * Kullanıcının günlük ilerlemesini kaydet
     */
    public function markDayComplete(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Oturum açmanız gerekiyor'], 401);
        }
        
        $progress = ChainProgress::where('user_id', Auth::id())->first();
        
        if (!$progress) {
            $progress = new ChainProgress();
            $progress->user_id = Auth::id();
            $progress->days_completed = 0;
            $progress->current_streak = 0;
            $progress->longest_streak = 0;
        }
        
        // Bugün zaten işaretlenmişse, işlem yapma
        $today = now()->format('Y-m-d');
        $lastCompleted = $progress->last_completed_at ? date('Y-m-d', strtotime($progress->last_completed_at)) : null;
        
        if ($lastCompleted === $today) {
            return response()->json(['error' => 'Bugün zaten tamamlandı'], 400);
        }
        
        // İlerlemede bir gün arttır
        $progress->days_completed++;
        
        // Streak kontrolü yap
        if ($lastCompleted === null || $lastCompleted === date('Y-m-d', strtotime('-1 day'))) {
            // Son tamamlanan gün dün ise veya ilk kez tamamlıyorsa, streak artar
            $progress->current_streak++;
        } else {
            // Son tamamlanan gün dün değilse, streak sıfırlanır ve yeniden başlar
            $progress->current_streak = 1;
        }
        
        // En uzun streak'i güncelle
        if ($progress->current_streak > $progress->longest_streak) {
            $progress->longest_streak = $progress->current_streak;
        }
        
        $progress->last_completed_at = now();
        $progress->save();
        
        // Seviye hesaplama
        $level = $this->calculateLevel($progress->days_completed);
        
        return response()->json([
            'success' => true,
            'days_completed' => $progress->days_completed,
            'current_streak' => $progress->current_streak,
            'longest_streak' => $progress->longest_streak,
            'level' => $level
        ]);
    }
    
    /**
     * Zinciri sıfırla (opsiyonel)
     */
    public function resetChain(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Oturum açmanız gerekiyor'], 401);
        }
        
        $progress = ChainProgress::where('user_id', Auth::id())->first();
        
        if (!$progress) {
            return response()->json(['error' => 'İlerleme bulunamadı'], 404);
        }
        
        // En uzun streak'i sakla ama mevcut durumu sıfırla
        $longestStreak = $progress->longest_streak;
        
        $progress->days_completed = 0;
        $progress->current_streak = 0;
        $progress->last_completed_at = null;
        $progress->save();
        
        return response()->json([
            'success' => true,
            'days_completed' => 0,
            'current_streak' => 0,
            'longest_streak' => $longestStreak,
            'level' => 'Bronz'
        ]);
    }
    
    /**
     * Günlerin tamamlanma sayısına göre seviye hesapla
     */
    private function calculateLevel($daysCompleted)
    {
        if ($daysCompleted >= 365) {
            return 'MASTER';
        } else if ($daysCompleted >= 300) {
            return 'Elmas';
        } else if ($daysCompleted >= 240) {
            return 'Zümrüt';
        } else if ($daysCompleted >= 180) {
            return 'Platin';
        } else if ($daysCompleted >= 90) {
            return 'Altın';
        } else if ($daysCompleted >= 60) {
            return 'Gümüş';
        } else if ($daysCompleted >= 30) {
            return 'Demir';
        } else {
            return 'Bronz';
        }
    }
}