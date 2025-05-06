<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrivateLesson;
use App\Models\PrivateLessonSession;
use App\Models\PrivateLessonOccurrence;
use App\Models\PrivateLessonTeacherRole;
use App\Models\User;
use Carbon\Carbon;

class PrivateLessonController extends Controller
{
    
    /**
     * Display a listing of private lessons.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Basit istatistikler
        $totalLessons = PrivateLesson::count();
        $totalTeachers = PrivateLessonTeacherRole::where('can_teach_private', true)->count();
        $totalStudents = PrivateLessonSession::select('student_id')->distinct()->count('student_id');
        
        // Bu haftaki dersler
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weeklyLessons = PrivateLessonOccurrence::whereBetween('lesson_date', [$startOfWeek, $endOfWeek])->count();
        
        // En son eklenen 5 ders
        $upcomingLessons = PrivateLessonOccurrence::with(['session.privateLesson', 'session.teacher', 'session.student'])
            ->where('lesson_date', '>=', Carbon::today())
            ->orderBy('lesson_date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();
            
        return view('admin.private-lessons.index', compact(
            'totalLessons', 
            'totalTeachers', 
            'totalStudents', 
            'weeklyLessons',
            'upcomingLessons'
        ));
    }

    /**
     * Show the form for creating a new private lesson.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.private-lessons.create');
    }
  /**
     * Show the delete confirmation page for the lesson.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function confirmDelete($id)
    {
        try {
            // Ders seansını bul
            $session = PrivateLessonSession::with(['privateLesson', 'teacher', 'student'])
                ->where('id', $id)
                ->where('teacher_id', Auth::id()) // Sadece öğretmenin kendi derslerini silmesine izin ver
                ->firstOrFail();
            
            return view('ogretmen.private-lessons.delete', compact('session'));
            
        } catch (\Exception $e) {
            Log::error("Ders silme sayfası yüklenirken hata: " . $e->getMessage());
            return redirect()->route('ogretmen.private-lessons.index')
                ->with('error', 'Ders bilgileri yüklenirken bir hata oluştu: ' . $e->getMessage());
        }
    }
     /**
     * Delete the specified lesson.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            // Ders seansını bul
            $session = PrivateLessonSession::where('id', $id)
                ->where('teacher_id', Auth::id()) // Sadece öğretmenin kendi derslerini silmesine izin ver
                ->firstOrFail();
            
            // Geçmiş derslerin silinmesini engelle
            $lessonDate = Carbon::parse($session->start_date);
            $currentDate = Carbon::now('Europe/Istanbul')->startOfDay();
            
            if ($lessonDate->lt($currentDate)) {
                return redirect()->route('ogretmen.private-lessons.index')
                    ->with('error', 'Geçmiş tarihli dersler silinemez.');
            }
            
            $lessonId = $session->private_lesson_id;
            $deleteScope = $request->input('delete_scope', 'this_only');
            
            // Silme işlemi seçilen kapsama göre
            if ($deleteScope === 'all_future') {
                // Bu ve gelecekteki aynı saat ve dakikadaki dersleri sil
                $startTime = $session->start_time;
                $endTime = $session->end_time;
                
                $deletedCount = PrivateLessonSession::where('private_lesson_id', $lessonId)
                    ->where('teacher_id', Auth::id())
                    ->where('start_time', $startTime)
                    ->where('end_time', $endTime)
                    ->where(function($query) use ($session, $currentDate) {
                        $query->where('start_date', '>=', $session->start_date);
                    })
                    ->delete();
                
                $message = "{$deletedCount} ders başarıyla silindi.";
            } 
            else {
                // Sadece bu dersi sil
                $session->delete();
                
                // Bu silinen ders, bu ders serisinin son seansı mıydı kontrol et
                $remainingSessions = PrivateLessonSession::where('private_lesson_id', $lessonId)->exists();
                
                // Eğer kalan ders yoksa ana dersi de sil
                if (!$remainingSessions) {
                    PrivateLesson::where('id', $lessonId)->delete();
                }
                
                $message = "Ders başarıyla silindi.";
            }
            
            return redirect()->route('ogretmen.private-lessons.index')
                ->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error("Ders silme işleminde hata: " . $e->getMessage());
            return redirect()->route('ogretmen.private-lessons.index')
                ->with('error', 'Ders silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }
    /**
     * Store a newly created private lesson in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:15',
            'is_active' => 'sometimes|boolean',
        ]);

        $privateLesson = new PrivateLesson();
        $privateLesson->name = $request->name;
        $privateLesson->description = $request->description;
        $privateLesson->price = $request->price;
        $privateLesson->duration_minutes = $request->duration_minutes;
        $privateLesson->is_active = $request->has('is_active');
        $privateLesson->save();

        return redirect()->route('admin.private-lessons.index')
            ->with('success', 'Özel ders başarıyla oluşturuldu.');
    }
    
    /**
     * Display list of lessons.
     *
     * @return \Illuminate\Http\Response
     */
    public function lessons()
    {
        // Basit bir view dönelim
        return view('admin.private-lessons.lessons');
    }
    
    /**
     * Display reports view.
     *
     * @return \Illuminate\Http\Response
     */
    public function reports()
    {
        // Basit bir view dönelim
        return view('admin.private-lessons.reports');
    }
}