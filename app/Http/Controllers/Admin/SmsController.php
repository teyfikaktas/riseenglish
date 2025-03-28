<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Netgsm\Otp\otp;

class SmsController extends Controller
{
    /**
     * SMS yönetim sayfasını göster
     */
    public function index()
    {
        // Tüm kullanıcıları telefon numarasına göre getir
        $allUsers = User::whereNotNull('phone')->orderBy('name')->get();
        
        // Kullanıcıları filtrele - sadece öğrenci rolüne sahip olanları al
        $users = collect();
        foreach ($allUsers as $user) {
            if ($user->hasRole('ogrenci')) {
                $users->push($user);
            }
        }
        
        // Aktif kursları getir
        $courses = Course::where('is_active', true)->orderBy('name')->get();
        
        // Toplam kullanıcı sayısı
        $totalUsers = User::count();
        
        // Aktif kurs sayısı
        $activeCourses = Course::where('is_active', true)->count();
        
        // Öğrenci sayısı - hasRole ile kontrol edilen kullanıcı sayısı
        $totalStudents = User::whereNotNull('phone')->get()->filter(function($user) {
            return $user->hasRole('ogrenci');
        })->count();
        
        return view('admin.sms.index', compact('users', 'courses', 'totalUsers', 'activeCourses', 'totalStudents'));
    }

    /**
     * API: Kullanıcı arama
     */
    public function searchUsers(Request $request)
    {
        // Debug için log
        \Log::info('searchUsers method called', [
            'query' => $request->get('query'),
            'all_params' => $request->all(),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);
        
        $query = $request->get('query');
        
        if (empty($query) || strlen($query) < 3) {
            \Log::info('Query too short', ['length' => strlen($query)]);
            return response()->json(['users' => [], 'message' => 'Query must be at least 3 characters']);
        }
        
        try {
            // Kullanıcıları ara ve telefonu olanları getir
            $allUsers = User::whereNotNull('phone')
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('phone', 'LIKE', "%{$query}%");
                })
                ->orderBy('name')
                ->get();
                
            // Log results
            \Log::info('Found users before role filter', [
                'count' => $allUsers->count(),
                'sample' => $allUsers->take(3)->map(function($user) {
                    return ['id' => $user->id, 'name' => $user->name, 'phone' => $user->phone];
                })
            ]);
            
            // Sadece öğrenci rolüne sahip kullanıcıları filtrele
            $users = $allUsers->filter(function($user) {
                $hasRole = $user->hasRole('ogrenci'); 
                \Log::info('User role check', [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'has_student_role' => $hasRole
                ]);
                return $hasRole;
            })->values();
            
            // Log filtered results
            \Log::info('Users with student role', [
                'count' => $users->count(),
                'sample' => $users->take(3)->map(function($user) {
                    return ['id' => $user->id, 'name' => $user->name];
                })
            ]);
            
            // JSON formatında kullanıcı listesini döndür
            $response = [
                'users' => $users->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'phone' => $user->phone
                    ];
                }),
                'debug' => [
                    'query' => $query,
                    'total_found' => $allUsers->count(),
                    'students_found' => $users->count()
                ]
            ];
            
            \Log::info('Response payload', ['response' => $response]);
            
            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Error in searchUsers', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Arama sırasında bir hata oluştu', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Kurs arama
     */
    public function searchCourses(Request $request)
    {
        $query = $request->get('query');
        
        if (strlen($query) < 3) {
            return response()->json(['courses' => []]);
        }
        
        // Aktif kursları ara
        $courses = Course::where('is_active', true)
                   ->where('name', 'LIKE', "%{$query}%")
                   ->orderBy('name')
                   ->get();
        
        // JSON formatında kurs listesini döndür
        return response()->json([
            'courses' => $courses->map(function($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name
                ];
            })
        ]);
    }

    /**
     * Bireysel SMS gönderimi
     */

    /**
     * Toplu SMS gönderimi
     */
    public function sendBulk(Request $request)
    {
        $request->validate([
            'target_group' => 'required|string|in:all_users,all_students,course_students',
            'course_id' => 'required_if:target_group,course_students|exists:courses,id',
            'message' => 'required|string|max:160',
        ]);
    
        $targetGroup = $request->target_group;
        $courseId = $request->course_id;
        $message = $request->message;
        
        // Hedef gruba göre kullanıcıları belirle
        $recipients = [];
        
        if ($targetGroup === 'all_users') {
            $recipients = User::whereNotNull('phone')->get();
            $successMessage = 'Tüm kullanıcılara SMS başarıyla gönderildi.';
        } elseif ($targetGroup === 'all_students') {
            // Tüm kullanıcıları getir
            $allUsers = User::whereNotNull('phone')->get();
            
            // Öğrenci rolüne sahip kullanıcıları filtrele
            $recipients = $allUsers->filter(function($user) {
                return $user->hasRole('ogrenci');
            })->values();
            
            $successMessage = 'Tüm öğrencilere SMS başarıyla gönderildi.';
        } elseif ($targetGroup === 'course_students') {
            // Belirli kursa kayıtlı öğrencileri bul
            $course = Course::findOrFail($courseId);
            $recipients = $course->students()->whereNotNull('phone')->get();
            
            $successMessage = $course->name . ' kursundaki öğrencilere SMS başarıyla gönderildi.';
        }
        
        // SMS gönderimi burada gerçekleştirilecek
        // Şimdilik sadece simüle ediyoruz
        
        return redirect()->back()->with('success', $successMessage . ' (' . count($recipients) . ' kişi)');
    }
    
}