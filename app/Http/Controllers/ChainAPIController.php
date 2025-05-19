<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChainProgress;
use App\Models\ChainActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ChainAPIController extends Controller
{
    public function getProgress()
    {
        if (!Auth::check()) {
            return response()->json([
                'isAuthenticated' => false
            ]);
        }

        $progress = ChainProgress::where('user_id', Auth::id())->first();
        
        if (!$progress) {
            return response()->json([
                'isAuthenticated' => true,
                'data' => [
                    'daysCompleted' => 0,
                    'currentStreak' => 0,
                    'longestStreak' => 0,
                    'currentLevel' => 'Bronz',
                    'levelColor' => '#CD7F32',
                    'nextLevelProgress' => 0,
                    'iconGender' => 'erkek',
                ]
            ]);
        }

        return response()->json([
            'isAuthenticated' => true,
            'data' => [
                'daysCompleted' => $progress->days_completed,
                'currentStreak' => $progress->current_streak,
                'longestStreak' => $progress->longest_streak,
                'currentLevel' => $progress->getCurrentLevel(),
                'levelColor' => $progress->getLevelColor(),
                'nextLevelProgress' => $progress->getNextLevelProgress(),
                'iconGender' => $progress->icon_gender ?? 'erkek',
            ]
        ]);
    }

    public function getTodayActivities()
    {
        if (!Auth::check()) {
            return response()->json(['activities' => []]);
        }

        $progress = ChainProgress::where('user_id', Auth::id())->first();
        
        if (!$progress) {
            return response()->json(['activities' => []]);
        }

        $today = now()->format('Y-m-d');
        $activities = ChainActivity::where('chain_progress_id', $progress->id)
            ->whereDate('activity_date', $today)
            ->get()
            ->map(function($activity) {
                return [
                    'id' => $activity->id,
                    'content' => $activity->content,
                    'file_path' => $activity->file_path ? Storage::url($activity->file_path) : null,
                    'file_name' => $activity->file_name,
                    'created_at' => $activity->created_at->format('H:i'),
                ];
            });

        return response()->json(['activities' => $activities]);
    }

    public function addActivity(Request $request)
    {
        Log::info('API addActivity called', $request->all());

        try {
            $request->validate([
                'activityContent' => 'required_without:activityFiles',
                'activityFiles.*' => 'sometimes|file|max:10240',
            ]);

            if (!Auth::check()) {
                return response()->json(['error' => 'Çalışma eklemek için giriş yapmalısınız!'], 401);
            }

            $progress = ChainProgress::firstOrCreate(
                ['user_id' => Auth::id()],
                ['days_completed' => 0, 'current_streak' => 0, 'longest_streak' => 0]
            );

            $createdActivity = false;

            // Dosya yükleme
            if ($request->hasFile('activityFiles')) {
                foreach ($request->file('activityFiles') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('activities', $fileName, 'public');
                    
                    ChainActivity::create([
                        'user_id' => Auth::id(),
                        'chain_progress_id' => $progress->id,
                        'teacher_id' => $request->teacher_id,
                        'content' => $request->activityContent,
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getMimeType(),
                        'activity_date' => now(),
                    ]);
                    
                    $createdActivity = true;
                }
            }

            // Sadece içerik varsa
            if (!$request->hasFile('activityFiles') && $request->activityContent) {
                ChainActivity::create([
                    'user_id' => Auth::id(),
                    'chain_progress_id' => $progress->id,
                    'teacher_id' => $request->teacher_id,
                    'content' => $request->activityContent,
                    'activity_date' => now(),
                ]);
                
                $createdActivity = true;
            }

            if ($createdActivity) {
                return response()->json(['success' => true, 'message' => 'Çalışma başarıyla eklendi!']);
            } else {
                return response()->json(['error' => 'Lütfen bir metin yazın veya dosya yükleyin!'], 400);
            }

        } catch (\Exception $e) {
            Log::error('API addActivity error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Bir hata oluştu: ' . $e->getMessage()], 500);
        }
    }

    public function deleteActivity($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Yetkilendirme hatası'], 401);
        }

        $activity = ChainActivity::find($id);
        
        if (!$activity) {
            return response()->json(['error' => 'Çalışma bulunamadı!'], 404);
        }
        
        if ($activity->user_id !== Auth::id()) {
            return response()->json(['error' => 'Bu çalışmayı silme yetkiniz yok!'], 403);
        }
        
        if ($activity->file_path) {
            Storage::disk('public')->delete($activity->file_path);
        }
        
        $activity->delete();
        
        return response()->json(['success' => true, 'message' => 'Çalışma başarıyla silindi!']);
    }

    public function setGender(Request $request)
    {
        $request->validate([
            'gender' => 'required|in:erkek,kadin'
        ]);

        if (!Auth::check()) {
            return response()->json(['error' => 'Yetkilendirme hatası'], 401);
        }

        $progress = ChainProgress::firstOrCreate(
            ['user_id' => Auth::id()],
            ['days_completed' => 0, 'current_streak' => 0, 'longest_streak' => 0]
        );

        $progress->icon_gender = $request->gender;
        $progress->save();

        return response()->json([
            'success' => true, 
            'message' => 'İkon tercihiniz kaydedildi!',
            'gender' => $request->gender
        ]);
    }

    public function completeDay()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Gün tamamlamak için giriş yapmalısınız.'], 401);
        }

        // Bugünkü çalışmaları kontrol et
        $progress = ChainProgress::where('user_id', Auth::id())->first();
        
        if (!$progress) {
            return response()->json(['error' => 'İlerleme kaydı bulunamadı!'], 404);
        }

        $today = now()->format('Y-m-d');
        
        $todayActivities = ChainActivity::where('chain_progress_id', $progress->id)
            ->whereDate('activity_date', $today)
            ->count();

        if ($todayActivities === 0) {
            return response()->json(['error' => 'Günü tamamlamak için önce çalışma eklemelisiniz!'], 400);
        }

        $lastCompleted = $progress->last_completed_at ? date('Y-m-d', strtotime($progress->last_completed_at)) : null;

        // Aynı gün tekrar tamamlanamaz
        if ($lastCompleted === $today) {
            return response()->json(['error' => 'Bugün zaten tamamlandı!'], 400);
        }

        $previousLevel = $progress->getCurrentLevel();

        $progress->days_completed++;

        if ($lastCompleted === null || $lastCompleted === now()->subDay()->format('Y-m-d')) {
            $progress->current_streak++;
        } else {
            $progress->current_streak = 1;
        }

        if ($progress->current_streak > $progress->longest_streak) {
            $progress->longest_streak = $progress->current_streak;
        }

        $progress->last_completed_at = now();
        $progress->save();

        $newLevel = $progress->getCurrentLevel();
        $levelUp = $previousLevel !== $newLevel;

        return response()->json([
            'success' => true,
            'message' => 'Gün başarıyla tamamlandı!',
            'levelUp' => $levelUp,
            'newData' => [
                'daysCompleted' => $progress->days_completed,
                'currentStreak' => $progress->current_streak,
                'longestStreak' => $progress->longest_streak,
                'currentLevel' => $newLevel,
                'levelColor' => $progress->getLevelColor(),
                'nextLevelProgress' => $progress->getNextLevelProgress(),
                'previousLevel' => $previousLevel
            ]
        ]);
    }

    public function resetChain()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Yetkilendirme hatası'], 401);
        }

        $progress = ChainProgress::where('user_id', Auth::id())->first();

        if (!$progress) {
            return response()->json(['error' => 'İlerleme kaydı bulunamadı!'], 404);
        }

        $longestStreak = $progress->longest_streak;

        $progress->days_completed = 0;
        $progress->current_streak = 0;
        $progress->last_completed_at = null;
        $progress->save();

        return response()->json([
            'success' => true,
            'message' => 'Zincir sıfırlandı. Yeniden başlayabilirsiniz!',
            'newData' => [
                'daysCompleted' => 0,
                'currentStreak' => 0,
                'longestStreak' => $longestStreak,
                'currentLevel' => 'Bronz',
                'levelColor' => '#CD7F32',
                'nextLevelProgress' => 0
            ]
        ]);
    }
}