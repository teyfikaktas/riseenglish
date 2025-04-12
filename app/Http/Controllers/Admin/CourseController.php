<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\EnrollmentStatus;

use App\Models\CourseType;
use App\Models\CourseLevel;
use App\Models\CourseFrequency;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;

class CourseController extends Controller
{
    
 
public function index(Request $request): View
{
    $categories = Category::orderBy('name')->get();
    $courseTypes = CourseType::orderBy('name')->get();
    $courseLevels = CourseLevel::orderBy('name')->get();
    $query = Course::query()
        ->with(['courseType', 'courseLevel', 'teacher', 'courseFrequency'])
        ->withCount('students');
    
    // Kurs tipi filtreleme
    if ($request->filled('type_id')) {
        $query->where('type_id', $request->type_id);
    }
    
    // Kurs seviyesi filtreleme
    if ($request->filled('level_id')) {
        $query->where('level_id', $request->level_id);
    }
    
    // Arama filtrelemesi
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('slug', 'like', '%' . $request->search . '%');
        });
    }
    
    // Sonuçları sıralama ve sayfalama
    $courses = $query->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString();
    
    // İstatistikler için verileri hazırla
    $active_courses_count = Course::where('is_active', true)->count();
    $total_students = \DB::table('course_user')->count();
    $new_courses_this_month = Course::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();
    
        return view('admin.courses.index', compact(
            'courses', 
            'active_courses_count', 
            'total_students', 
            'new_courses_this_month',
            'categories',
            'courseTypes',
            'courseLevels'
        ));
}


    public function detail($slug)
    {
        $course = Course::where('slug', $slug)
            ->with(['courseType', 'courseLevel', 'teacher', 'courseFrequency', 'category'])
            ->firstOrFail();
        
        // Benzer kursları bul
        $similarCourses = $this->getSimilarCourses($course);
        
        return view('courses.detail', [
            'course' => $course,
            'similarCourses' => $similarCourses
        ]);
    }
    
    /**
     * Kurs kayıt formunu göster
     */
    public function register($id)
    {
        $course = Course::with(['courseType', 'courseLevel', 'teacher', 'courseFrequency', 'category'])
            ->findOrFail($id);
        
        // Benzer kursları bul
        $similarCourses = $this->getSimilarCourses($course);
        
        return view('courses.register', [
            'course' => $course,
            'similarCourses' => $similarCourses
        ]);
    }
    
    /**
     * Kurs kaydını işle
     */
    public function registerSubmit(Request $request, $id)
    {
        // Form validasyonu
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:credit_card,bank_transfer,online_payment',
            'terms' => 'required|accepted',
            // Diğer alanlar
        ]);
        
        // Kaydı kaydetme işlemi ve ödeme işlemi burada yapılacak
        // Bu örnek için sadece başarı mesajı döndürüyoruz
        
        return redirect()->route('course.register.success')->with('success', 'Kayıt işleminiz başarıyla tamamlanmıştır!');
    }
    
    /**
     * Kurs kaydı başarılı sayfasını göster
     */
    public function registerSuccess()
    {
        return view('courses.register-success');
    }
    
    /**
     * Kurs değerlendirme/yorum ekleme
     */
    public function review(Request $request, $id)
    {
        // Yorum validasyonu
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);
        
        // Yorumu kaydet (ileride gerçek bir model kullanılabilir)
        // ...
        
        return redirect()->back()->with('success', 'Yorumunuz başarıyla kaydedildi. Teşekkür ederiz!');
    }
    
    /**
     * Benzer kursları bul
     */
    private function getSimilarCourses($course, $limit = 3)
    {
        // Benzer kursları bulma algoritması
        $similarCourses = Course::where('is_active', true)
            ->where('id', '!=', $course->id)
            ->with(['teacher', 'courseType', 'courseLevel'])
            ->when($course->category_id, function($query) use ($course) {
                // Aynı kategorideki kurslar
                return $query->where('category_id', $course->category_id);
            })
            ->when($course->level_id, function($query) use ($course) {
                // Aynı seviyedeki kurslar
                return $query->orWhere('level_id', $course->level_id);
            })
            ->when($course->type_id, function($query) use ($course) {
                // Aynı tipteki kurslar
                return $query->orWhere('type_id', $course->type_id);
            })
            ->when($course->teacher_id, function($query) use ($course) {
                // Aynı öğretmenin diğer kursları
                return $query->orWhere('teacher_id', $course->teacher_id);
            })
            ->inRandomOrder() // Rastgele sırala
            ->take($limit)
            ->get();
        
        // Yeterli benzer kurs bulunamadıysa, en popüler kurslardan tamamla
        if ($similarCourses->count() < $limit) {
            $moreCoursesNeeded = $limit - $similarCourses->count();
            $existingIds = $similarCourses->pluck('id')->push($course->id)->toArray();
            
            $popularCourses = Course::where('is_active', true)
                ->whereNotIn('id', $existingIds)
                ->with(['teacher', 'courseType', 'courseLevel'])
                ->orderBy('created_at', 'desc') // Yeni kurslar (ilerisi için öğrenci sayısı veya değerlendirme puanı ile sıralanabilir)
                ->take($moreCoursesNeeded)
                ->get();
            
            $similarCourses = $similarCourses->concat($popularCourses);
        }
        
        return $similarCourses;
    }
public function create(): View
{
    $courseTypes = CourseType::all();
    $courseLevels = CourseLevel::all();
    $courseFrequencies = CourseFrequency::all();
    $teachers = User::whereHas('roles', function($query) {
        $query->where('name', 'ogretmen');
    })->get();
    $categories = \App\Models\Category::where('is_active', true)->orderBy('display_order')->get();
    
    return view('admin.courses.create', [
        'courseTypes' => $courseTypes,
        'courseLevels' => $courseLevels,
        'courseFrequencies' => $courseFrequencies,
        'teachers' => $teachers,
        'categories' => $categories
    ]);
}

/**
 * Kurs düzenleme formunu göster
 */
public function edit(Course $course): View
{
    $courseTypes = CourseType::all();
    $courseLevels = CourseLevel::all();
    $courseFrequencies = CourseFrequency::all();
    $teachers = User::whereHas('roles', function($query) {
        $query->where('name', 'ogretmen');
    })->get();
    $categories = \App\Models\Category::where('is_active', true)->orderBy('display_order')->get();
    
    return view('admin.courses.edit', [
        'course' => $course,
        'courseTypes' => $courseTypes,
        'courseLevels' => $courseLevels,
        'courseFrequencies' => $courseFrequencies,
        'teachers' => $teachers,
        'categories' => $categories
    ]);
}

    /**
     * Yeni kursu kaydet
     */
/**
 * Yeni kursu kaydet
 */
/**
 * Yeni kursu kaydet
 */
public function store(Request $request)
{
    // Form validasyonu
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:50|unique:courses',
        'teacher_id' => 'required|exists:users,id',
        'type_id' => 'required|exists:course_types,id',
        'level_id' => 'required|exists:course_levels,id',
        'category_id' => 'nullable|exists:categories,id', // Yeni kategori alanı
        'description' => 'nullable|string',
        'objectives' => 'nullable|string',
        'frequency_id' => 'nullable|exists:course_frequencies,id',
        'total_hours' => 'nullable|numeric|min:1',
        'max_students' => 'nullable|integer|min:1',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'start_time' => 'nullable|date_format:H:i',
        'end_time' => 'nullable|date_format:H:i',
        'meeting_link' => 'nullable|url|max:255',
        'meeting_password' => 'nullable|string|max:50',
        'location' => 'nullable|string|max:255',
        'price' => 'nullable|numeric|min:0',
        'discount_price' => 'nullable|numeric|min:0', // Yeni indirimli fiyat
        'display_order' => 'nullable|integer|min:0', // Yeni sıralama
        'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    
    // Checkbox değerlerini kontrol et (checkbox işaretlenmediğinde form verisi içermez)
    $validatedData['is_active'] = $request->has('is_active');
    $validatedData['has_certificate'] = $request->has('has_certificate');
    $validatedData['is_featured'] = $request->has('is_featured'); // Yeni alan için checkbox kontrolü
    
    // Varsayılan değerler
    $validatedData['display_order'] = $validatedData['display_order'] ?? 0;
    
    // Thumbnail yüklenmiş mi kontrol et
    if ($request->hasFile('thumbnail')) {
        $thumbnail = $request->file('thumbnail');
        $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
        $path = $thumbnail->storeAs('courses', $filename, 'public');
        $validatedData['thumbnail'] = $path;
    }
    
    try {
        // Kursu oluştur
        $course = Course::create($validatedData);
        
        return redirect()->route('admin.courses.index')
            ->with('success', 'Kurs başarıyla oluşturuldu');
    } catch (\Exception $e) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Kurs oluşturulurken bir hata oluştu: ' . $e->getMessage());
    }
}
    /**
     * Kurs detaylarını göster
     */
    public function show(Course $course): View
    {
        return view('admin.courses.show', [
            'course' => $course
        ]);
    }


/**
 * Kursu güncelle
 */
/**
 * Kursu güncelle
 */
public function update(Request $request, Course $course)
{
    try {
        // Form validasyonu
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:50|unique:courses,slug,' . $course->id,
            'teacher_id' => 'required|exists:users,id',
            'type_id' => 'required|exists:course_types,id',
            'level_id' => 'required|exists:course_levels,id',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'objectives' => 'nullable|string',
            'frequency_id' => 'nullable|exists:course_frequencies,id',
            'total_hours' => 'nullable|numeric|min:0',
            'max_students' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'meeting_link' => 'nullable|url|max:255',
            'meeting_password' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'display_order' => 'nullable|integer|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Checkbox değerlerini kontrol et (checkbox işaretlenmediğinde değerler gelmez)
        $validatedData['is_active'] = $request->has('is_active');
        $validatedData['has_certificate'] = $request->has('has_certificate');
        $validatedData['is_featured'] = $request->has('is_featured');
        
        // Boş string'leri null olarak kaydet
        foreach ($validatedData as $key => $value) {
            if ($value === '') {
                $validatedData[$key] = null;
            }
        }
        
        // Tarihleri ve zamanları düzgün formata dönüştür
        if (!empty($validatedData['start_date'])) {
            $validatedData['start_date'] = date('Y-m-d', strtotime($validatedData['start_date']));
        }
        
        if (!empty($validatedData['end_date'])) {
            $validatedData['end_date'] = date('Y-m-d', strtotime($validatedData['end_date']));
        }
        
        if (!empty($validatedData['start_time'])) {
            $validatedData['start_time'] = date('H:i:s', strtotime($validatedData['start_time']));
        }
        
        if (!empty($validatedData['end_time'])) {
            $validatedData['end_time'] = date('H:i:s', strtotime($validatedData['end_time']));
        }
        
        // Thumbnail yüklenmiş mi kontrol et
        if ($request->hasFile('thumbnail')) {
            // Eski resmi silme işlemi
            if ($course->thumbnail && \Storage::disk('public')->exists($course->thumbnail)) {
                \Storage::disk('public')->delete($course->thumbnail);
            }
            
            $thumbnail = $request->file('thumbnail');
            $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
            $path = $thumbnail->storeAs('courses', $filename, 'public');
            $validatedData['thumbnail'] = $path;
        }
        
        // Kursu güncelle
        $course->update($validatedData);
        
        // İlişkiler ile birlikte kursu tekrar yükleme
        $course->refresh();
        
        return redirect()->route('admin.courses.index')
            ->with('success', 'Kurs başarıyla güncellendi');
            
    } catch (\Exception $e) {
        // Hata detaylarını loglayalım
        \Log::error('Kurs güncelleme hatası: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Kurs güncellenirken bir hata oluştu: ' . $e->getMessage());
    }
}
public function getCategoryList()
{
    $categories = Category::orderBy('name')->get(['id', 'name']);
    return response()->json($categories);
}
    /**
     * Kursu sil
     */
/**
 * Kursu sil
 */
public function destroy(Course $course)
{
    try {
        // Kurs resmi varsa sil
        if ($course->thumbnail && \Storage::disk('public')->exists($course->thumbnail)) {
            \Storage::disk('public')->delete($course->thumbnail);
        }
        
        // Kursu sil
        $course->delete();
        
        return redirect()->route('admin.courses.index')
            ->with('success', 'Kurs başarıyla silindi');
    } catch (\Exception $e) {
        return redirect()->route('admin.courses.index')
            ->with('error', 'Kurs silinirken bir hata oluştu: ' . $e->getMessage());
    }
}
// CourseController.php içinde enrollments metodu:
public function enrollments(Course $course)
{
    $enrollments = $course->students()
        ->withPivot(['status_id', 'enrollment_date', 'paid_amount', 'payment_completed', 'approval_status', 'notes'])
        ->paginate(10);
    
    $statuses = EnrollmentStatus::all();
    
    return view('admin.courses.enrollments', compact('course', 'enrollments', 'statuses'));
}

public function updateEnrollment(Request $request, Course $course, User $user)
{
    $validated = $request->validate([
        'status_id' => 'required|exists:enrollment_statuses,id',
        'approval_status' => 'required|boolean',
        'notes' => 'nullable|string'
    ]);
    
    $course->students()->updateExistingPivot($user->id, $validated);
    
    return redirect()->back()->with('success', 'Kayıt durumu güncellendi');
}
public function getEnrollmentData(Course $course, User $user)
{
    $enrollment = $course->students()
        ->wherePivot('user_id', $user->id)
        ->first()->pivot;
    
    return response()->json([
        'status_id' => $enrollment->status_id,
        'approval_status' => $enrollment->approval_status,
        'notes' => $enrollment->notes
    ]);
}
}