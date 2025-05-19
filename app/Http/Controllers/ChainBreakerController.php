<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChainProgress;
use App\Models\ChainActivity;
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
    
    public function studentChainDetail($id)
    {
        // Öğretmen kontrolü
        if (!Auth::user()->hasRole('ogretmen')) {
            abort(403);
        }
        
        // Öğrenci bilgilerini al
        $student = User::with([
            'chainProgress', 
            'chainActivities' => function($query) {
                $query->orderBy('activity_date', 'desc');
            }
        ])->findOrFail($id);
        
        // Aktiviteleri tarihe göre grupla
        $activitiesByDate = $student->chainActivities
            ->groupBy(function($activity) {
                return $activity->activity_date->format('Y-m-d');
            });
        
        // Son 30 günlük aktiviteleri hazırla
        $today = now();
        $last30Days = [];
        
        // Son 30 günlük döngü (geriye doğru 29 gün + bugün)
        for ($i = 29; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            
            // Bu tarihte aktivite var mı kontrol et
            $hasActivity = isset($activitiesByDate[$dateStr]) && $activitiesByDate[$dateStr]->count() > 0;
            
            // Ödev yapılmış veya yapılmamış olarak işaretle
            $last30Days[$dateStr] = [
                'date' => $date,
                'has_activity' => $hasActivity,
                'day_name' => $date->locale('tr')->shortDayName,
                'day' => $date->day,
                'month' => $date->monthName,
                'is_today' => $date->isToday(),
                'is_future' => $date->isAfter($today),
                'is_past' => $date->isBefore($today) || $date->isToday(),
            ];
        }
        
        return view('teacher.student-chain-detail', [
            'student' => $student,
            'activitiesByDate' => $activitiesByDate,
            'last30Days' => $last30Days
        ]);
    }

    // Öğrenci zincir güncelleme metodu
    public function updateStudentChain(Request $request, $id)
    {
        // Öğretmen kontrolü
        if (!Auth::user()->hasRole('ogretmen')) {
            abort(403);
        }
        
        $request->validate([
            'adjustDays' => 'required|integer|between:-365,365',
            'adjustReason' => 'required|string|min:5|max:255'
        ]);
        
        $student = User::findOrFail($id);
        $progress = $student->chainProgress;
        
        if (!$progress) {
            $progress = ChainProgress::create([
                'user_id' => $student->id,
                'days_completed' => 0,
                'current_streak' => 0,
                'longest_streak' => 0
            ]);
        }
        
        // Eski değeri kaydet
        $oldDayCount = $progress->days_completed;
        
        // Gün sayısını ayarla
        $newDayCount = max(0, $oldDayCount + $request->adjustDays);
        
        $progress->days_completed = $newDayCount;
        $progress->current_streak = $newDayCount;
        $progress->longest_streak = max($progress->longest_streak, $newDayCount);
        $progress->save();
        
        // Log kaydı oluştur
        ChainActivity::create([
            'user_id' => $student->id,
            'chain_progress_id' => $progress->id,
            'teacher_id' => Auth::id(),
            'content' => "Öğretmen tarafından gün sayısı ayarlandı: {$request->adjustDays} gün ({$request->adjustReason})",
            'activity_date' => now(),
            'is_adjustment' => true
        ]);
        
        return redirect()->route('ogretmen.student.chain-detail', $id)
            ->with('success', "Gün sayısı başarıyla güncellendi! ($oldDayCount → $newDayCount)");
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
     * Öğrenci zinciri detaylarını PDF olarak dışa aktar
     */
    public function exportStudentChainPdf($id)
    {
        // Öğretmen kontrolü
        if (!Auth::user()->hasRole('ogretmen')) {
            abort(403);
        }
        
        // Öğrenci bilgilerini al
        $student = User::with([
            'chainProgress', 
            'chainActivities' => function($query) {
                $query->orderBy('activity_date', 'desc');
            }
        ])->findOrFail($id);
        
        // Aktiviteleri tarihe göre grupla
        $activitiesByDate = $student->chainActivities
            ->groupBy(function($activity) {
                return $activity->activity_date->format('Y-m-d');
            });
        
        // Son 30 günlük aktiviteleri hazırla
        $today = now();
        $last30Days = [];
        
        // Son 30 günlük döngü (geriye doğru 29 gün + bugün)
        for ($i = 29; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            
            // Bu tarihte aktivite var mı kontrol et
            $hasActivity = isset($activitiesByDate[$dateStr]) && $activitiesByDate[$dateStr]->count() > 0;
            
            // Ödev yapılmış veya yapılmamış olarak işaretle
            $last30Days[$dateStr] = [
                'date' => $date,
                'has_activity' => $hasActivity,
                'day_name' => $date->locale('tr')->shortDayName,
                'day' => $date->day,
                'month' => $date->monthName,
                'is_today' => $date->isToday(),
                'is_future' => $date->isAfter($today),
                'is_past' => $date->isBefore($today) || $date->isToday(),
            ];
        }
        
        // PDF oluştur
        $pdf = PDF::loadView('pdfs.student-chain-report', [
            'student' => $student,
            'activitiesByDate' => $activitiesByDate,
            'last30Days' => $last30Days,
            'exportDate' => now()->format('d.m.Y H:i')
        ]);
        
        // PDF'i indir
        return $pdf->download($student->name . '_' . $student->surname . '_çalışma_raporu.pdf');
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