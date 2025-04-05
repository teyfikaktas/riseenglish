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