<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Yönetici paneli ana sayfasını gösterir
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Toplam kullanıcı sayısı
        $totalUsers = User::count();
        
        // Yönetici rolüne sahip kullanıcı sayısı
        $adminUsers = User::whereHas('roles', function($query) {
            $query->where('name', 'yonetici');
        })->count();
        
        // Öğrenci rolüne sahip kullanıcı sayısı
        $studentUsers = User::whereHas('roles', function($query) {
            $query->where('name', 'ogrenci');
        })->count();
        
        // Son 10 kaydolan kullanıcı
        $recentUsers = User::with('roles')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Bu verileri görünüme gönder
        return view('admin.dashboard', compact(
            'totalUsers',
            'adminUsers',
            'studentUsers',
            'recentUsers'
        ));
    }
    
    /**
     * Sistem istatistiklerini getirir (AJAX için)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats()
    {
        // Toplam kullanıcı sayısı
        $totalUsers = User::count();
        
        // Yönetici rolüne sahip kullanıcı sayısı
        $adminUsers = User::whereHas('roles', function($query) {
            $query->where('name', 'yonetici');
        })->count();
        
        // Öğrenci rolüne sahip kullanıcı sayısı
        $studentUsers = User::whereHas('roles', function($query) {
            $query->where('name', 'ogrenci');
        })->count();
        
        // Toplam kurs sayısı (routes dosyasında Course modeli import edildiği için muhtemelen var)
        $totalCourses = \App\Models\Course::count();
        
        // Aktif kurs sayısı
        $activeCourses = \App\Models\Course::where('is_active', true)->count();
        
        // Son 30 gündeki yeni kullanıcı sayısı
        $newUsers = User::where('created_at', '>=', now()->subDays(30))->count();
        
        return response()->json([
            'totalUsers' => $totalUsers,
            'adminUsers' => $adminUsers,
            'studentUsers' => $studentUsers,
            'totalCourses' => $totalCourses,
            'activeCourses' => $activeCourses,
            'newUsers' => $newUsers
        ]);
    }
    
    /**
     * Son etkinlikleri getirir (AJAX için)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecentActivities(Request $request)
    {
        // Son kaydolan kullanıcılar
        $recentUsers = User::with('roles')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name'),
                    'created_at' => $user->created_at->format('d.m.Y H:i'),
                    'type' => 'user_registered'
                ];
            });
            
        // Son kurs kayıtları (CourseEnrollment model yapınıza göre değişebilir)
        // Burada varsayılan olarak bir users ve courses arasında many-to-many ilişki varsayıyorum
        $recentEnrollments = DB::table('course_user') // Bu tablo adı sizin yapınıza göre değişebilir
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->orderBy('course_user.created_at', 'desc')
            ->select('users.name as user_name', 'courses.title as course_title', 'course_user.created_at')
            ->take(5)
            ->get()
            ->map(function($enrollment) {
                return [
                    'user_name' => $enrollment->user_name,
                    'course_title' => $enrollment->course_title,
                    'created_at' => date('d.m.Y H:i', strtotime($enrollment->created_at)),
                    'type' => 'course_enrollment'
                ];
            });
            
        // Tüm aktiviteleri birleştir
        $activities = $recentUsers->merge($recentEnrollments);
        
        return response()->json([
            'activities' => $activities->sortByDesc('created_at')->values()->all()
        ]);
    }
}