<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Kullanıcıları listele
     */
    public function index(Request $request)
    {
        $query = User::query()->with('roles');
        
        // Arama fonksiyonu
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }
        
        // Rol filtresi
        if ($request->has('role') && $request->role != '') {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('roles.id', $request->role);
            });
        }
    
        // Sıralama
        $sortField = $request->sort ?? 'created_at';
        $sortDirection = $request->direction ?? 'desc';
        $query->orderBy($sortField, $sortDirection);
        
        $users = $query->paginate(10);
        
        // Role modelini kullanmak yerine, sisteminizdeki rol bilgilerini çekin
        // Örneğin, tüm rolleri bir array olarak tanımlayabilirsiniz
        $roles = [
            ['id' => 'yonetici', 'name' => 'Yönetici'],
            ['id' => 'ogretmen', 'name' => 'Öğretmen'],
            ['id' => 'ogrenci', 'name' => 'Öğrenci']
        ];
        
        return view('admin.users.index', compact('users', 'roles'));
    }
    
    /**
     * Yeni kullanıcı ekleme formunu göster
     */
    public function create()
    {
        // Role modelini kullanmak yerine, sisteminizdeki rol bilgilerini çekin
        $roles = [
            ['id' => 'yonetici', 'name' => 'Yönetici'],
            ['id' => 'ogretmen', 'name' => 'Öğretmen'],
            ['id' => 'ogrenci', 'name' => 'Öğrenci']
        ];
        
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Yeni kullanıcıyı kaydet
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'parent_phone_number' => 'nullable|string|max:20',
            'parent_phone_number_2' => 'nullable|string|max:20', // Eklendi
            'phone' => 'nullable|string|max:20',
            'roles' => 'required|array|min:1',
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'parent_phone_number' => $request->parent_phone_number,
            'parent_phone_number_2' => $request->parent_phone_number_2, // Eklendi
            'phone' => $request->phone,
        ]);
    
        foreach ($request->roles as $role) {
            $user->assignRole($role);
        }
    
        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }
    
    
    /**
     * Kullanıcı detaylarını göster
     */
    public function show(User $user)
    {
        $user->load(['roles']);
        
        // Kullanıcının kayıtlı olduğu kurslar
        $enrolledCourses = $user->enrolledCourses()
            ->with(['teacher', 'level', 'type'])
            ->paginate(5, ['*'], 'enrolled_page');
            
        // Eğer öğretmen ise verdiği kurslar
        $taughtCourses = null;
        if ($user->hasRole('ogretmen')) {
            $taughtCourses = Course::where('teacher_id', $user->id)
                ->with(['level', 'type'])
                ->paginate(5, ['*'], 'taught_page');
        }
        
        return view('admin.users.show', compact('user', 'enrolledCourses', 'taughtCourses'));
    }

    /**
     * Kullanıcı düzenleme formunu göster
     */
    public function edit(User $user)
    {
        // Role modelini kullanmak yerine, sisteminizdeki rol bilgilerini çekin
        $roles = [
            ['id' => 'yonetici', 'name' => 'Yönetici'],
            ['id' => 'ogretmen', 'name' => 'Öğretmen'],
            ['id' => 'ogrenci', 'name' => 'Öğrenci']
        ];
        
        // Kullanıcının rollerini çek
        $userRoles = $user->getRoleNames()->toArray(); // eğer Spatie permission paketi kullanıyorsanız
        
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

/**
 * Kullanıcı bilgilerini güncelle
 */
public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            Rule::unique('users')->ignore($user->id),
        ],
        'phone' => 'nullable|string|max:20',
        'parent_phone_number' => 'nullable|string|max:20',
        'parent_phone_number_2' => 'nullable|string|max:20', // Eklendi
        'roles' => 'required|array|min:1',
    ]);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'parent_phone_number' => $request->parent_phone_number,
        'parent_phone_number_2' => $request->parent_phone_number_2, // Eklendi
    ]);

    if ($request->filled('password')) {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user->update([
            'password' => Hash::make($request->password),
        ]);
    }

    $user->syncRoles($request->roles);

    return redirect()->route('admin.users.show', $user)
        ->with('success', 'Kullanıcı başarıyla güncellendi.');
}

    /**
     * Kullanıcı kurslarını yönet
     */
    public function manageCourses(User $user)
    {
        $user->load('roles');
        
        // Kullanıcının kayıtlı olduğu kurslar
        $enrolledCourses = $user->enrolledCourses()
            ->with(['teacher', 'level', 'type'])
            ->paginate(10, ['*'], 'enrolled_page');
            
        // Kayıtlı olmadığı tüm kurslar
        $availableCourses = Course::whereDoesntHave('students', function($query) use ($user) {
            $query->where('users.id', $user->id);
        })->where('is_active', true)
        ->with(['teacher', 'level', 'type'])
        ->paginate(10, ['*'], 'available_page');
        
        return view('admin.users.manage-courses', compact('user', 'enrolledCourses', 'availableCourses'));
    }

    /**
     * Kullanıcıyı kursa kaydet
     */
    public function enrollCourse(Request $request, User $user)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'status_id' => 'required|exists:statuses,id',
            'payment_completed' => 'boolean',
            'paid_amount' => 'nullable|numeric',
            'notes' => 'nullable|string'
        ]);

        // Kullanıcının zaten kursa kayıtlı olup olmadığını kontrol et
        $alreadyEnrolled = $user->enrolledCourses()->where('course_id', $request->course_id)->exists();
        
        if ($alreadyEnrolled) {
            return back()->with('error', 'Kullanıcı zaten bu kursa kayıtlı.');
        }

        $user->enrolledCourses()->attach($request->course_id, [
            'enrollment_date' => now(),
            'status_id' => $request->status_id,
            'payment_completed' => $request->payment_completed ?? false,
            'paid_amount' => $request->paid_amount,
            'notes' => $request->notes,
            'approval_status' => 'approved', // Yönetici tarafından eklendiği için otomatik onaylı
        ]);

        return back()->with('success', 'Kullanıcı kursa başarıyla kaydedildi.');
    }

    /**
     * Kullanıcının kurs kaydını güncelle
     */
    public function updateCourseEnrollment(Request $request, User $user, Course $course)
    {
        $request->validate([
            'status_id' => 'required|exists:statuses,id',
            'payment_completed' => 'boolean',
            'paid_amount' => 'nullable|numeric',
            'final_grade' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string'
        ]);

        $user->enrolledCourses()->updateExistingPivot($course->id, [
            'status_id' => $request->status_id,
            'payment_completed' => $request->payment_completed ?? false,
            'paid_amount' => $request->paid_amount,
            'final_grade' => $request->final_grade,
            'notes' => $request->notes,
            'completion_date' => $request->status_id == 3 ? now() : null, // 3 = Tamamlandı durumu
        ]);

        return back()->with('success', 'Kurs kaydı başarıyla güncellendi.');
    }

    /**
     * Kullanıcının kurs kaydını sil
     */
    public function unenrollCourse(User $user, Course $course)
    {
        $user->enrolledCourses()->detach($course->id);
        
        return back()->with('success', 'Kullanıcının kurs kaydı silindi.');
    }

    /**
     * Kullanıcıyı sil
     */
    public function destroy(User $user)
    {
        // Kullanıcının silinmesini engelleyecek ilişkiler varsa kontrol et
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla silindi.');
    }

    /**
     * Kullanıcının belirli bir kurs için kayıt verilerini döndürür (JSON)
     */
    public function getEnrollmentData(User $user, Course $course)
    {
        // Kullanıcının bu kursa kayıtlı olup olmadığını kontrol et
        $enrollment = $user->enrolledCourses()
            ->where('course_id', $course->id)
            ->first();
        
        if (!$enrollment) {
            return response()->json(['error' => 'Kullanıcı bu kursa kayıtlı değil.'], 404);
        }
        
        // Pivot verilerini döndür
        return response()->json([
            'status_id' => $enrollment->pivot->status_id,
            'payment_completed' => (bool)$enrollment->pivot->payment_completed,
            'paid_amount' => $enrollment->pivot->paid_amount,
            'final_grade' => $enrollment->pivot->final_grade,
            'notes' => $enrollment->pivot->notes,
            'enrollment_date' => $enrollment->pivot->enrollment_date,
            'completion_date' => $enrollment->pivot->completion_date,
        ]);
    }
}