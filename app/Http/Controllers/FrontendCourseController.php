<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseType;
use App\Models\CourseLevel;
use App\Models\CourseFrequency;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FrontendCourseController extends Controller
{
    /**
     * Tüm kursları listele
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Kurs kategorileri ve seviyeleri
        $courseTypes = CourseType::all();
        $courseLevels = CourseLevel::all();
        
        // Öne çıkan eğitmenler
        $featuredTeachers = User::whereHas('roles', function($query) {
            $query->where('name', 'ogretmen');
        })
        ->withCount('teachingCourses as courses_count')
        ->take(4)
        ->get();
        
        // Sorgu oluşturma
        $query = Course::query()
            ->where('is_active', true)
            ->with(['teacher', 'courseType', 'courseLevel', 'courseFrequency']);
            
        // Arama
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }
        
        // Kurs tipi filtreleme
        if ($request->has('course_type')) {
            $courseType = $request->course_type;
            // String ise array'e çevir
            if (!is_array($courseType)) {
                $courseType = [$courseType];
            }
            $query->whereIn('type_id', $courseType);
        }
        
        // Kurs seviyesi filtreleme
        if ($request->has('course_level')) {
            $courseLevel = $request->course_level;
            // String ise array'e çevir
            if (!is_array($courseLevel)) {
                $courseLevel = [$courseLevel];
            }
            $query->whereIn('level_id', $courseLevel);
        }
        
        // Kategori filtreleme
        if ($request->has('category')) {
            $category = $request->category;
            // String ise array'e çevir
            if (!is_array($category)) {
                $category = [$category];
            }
            $query->whereIn('category_id', $category);
        }
        
        // Eğitim durumu filtreleme
        if ($request->has('course_status')) {
            $today = Carbon::today();
            $courseStatus = $request->course_status;
            
            // String ise array'e çevir
            if (!is_array($courseStatus)) {
                $courseStatus = [$courseStatus];
            }
            
            $query->where(function($q) use ($courseStatus, $today) {
                foreach($courseStatus as $status) {
                    if ($status === 'upcoming') {
                        $q->orWhere('start_date', '>', $today);
                    } elseif ($status === 'ongoing') {
                        $q->orWhere(function($query) use ($today) {
                            $query->where('start_date', '<=', $today)
                                  ->where('end_date', '>=', $today);
                        });
                    } elseif ($status === 'completed') {
                        $q->orWhere('end_date', '<', $today);
                    }
                }
            });
        }
        
        // Özellik filtreleme
        if ($request->has('features')) {
            $features = $request->features;
            
            // String ise array'e çevir
            if (!is_array($features)) {
                $features = [$features];
            }
            
            foreach($features as $feature) {
                if ($feature === 'certificate') {
                    $query->where('has_certificate', true);
                } elseif ($feature === 'discount') {
                    $query->whereNotNull('discount_price');
                }
            }
        }
        
        // Fiyat aralığı filtreleme
        if ($request->has('max_price')) {
            $query->where(function($q) use ($request) {
                $q->where('price', '<=', $request->max_price)
                  ->orWhere(function($query) use ($request) {
                      $query->whereNotNull('discount_price')
                            ->where('discount_price', '<=', $request->max_price);
                  });
            });
        }
        
        // Sıralama
        if ($request->has('sort')) {
            switch($request->sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('students_count', 'desc');
                    break;
                case 'price_low':
                    $query->orderByRaw('COALESCE(discount_price, price) ASC');
                    break;
                case 'price_high':
                    $query->orderByRaw('COALESCE(discount_price, price) DESC');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->orderBy('display_order')->orderBy('created_at', 'desc');
            }
        } else {
            // Varsayılan sıralama
            $query->orderBy('display_order')->orderBy('created_at', 'desc');
        }
        
        // Sayfalama
        $courses = $query->paginate(12)->withQueryString();
        
        // Kategoriler
        $categories = Category::where('is_active', true)->orderBy('display_order')->get();
        
        return view('courses', compact('courses', 'courseTypes', 'courseLevels', 'featuredTeachers', 'categories'));
    }

    /**
     * Kurs detaylarını göster
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
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
    public function register($slug)
    {
        \Log::info('Register metodu çağrıldı. Slug: ' . $slug);
        
        try {
            // Kurs bulma işlemi
            $course = Course::where('slug', $slug)->first();
            
            if (!$course) {
                \Log::error('Kurs bulunamadı: ' . $slug);
                return redirect()->route('courses.index')->with('error', 'Kurs bulunamadı.');
            }
            
            \Log::info('Kurs bulundu: ' . $course->name);
            
            // İlişkileri yükleme
            $course->load(['courseType', 'courseLevel', 'teacher', 'courseFrequency', 'category']);
            
            // Kontroller...
            if (!$course->is_active) {
                \Log::info('Kurs aktif değil: ' . $course->name);
                return redirect()->route('courses.index')->with('error', 'Bu kurs şu anda kayıtlara kapalıdır.');
            }
            
            // Benzer kursları bul
            $similarCourses = $this->getSimilarCourses($course);
            
            \Log::info('Register view döndürülüyor');
            
            return view('courses.register', [
                'course' => $course,
                'similarCourses' => $similarCourses
            ]);
        } catch (\Exception $e) {
            \Log::error('Register metodu hatası: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'Bir hata oluştu.');
        }
    }
    
    /**
 * Kurs kaydını işle
 */
public function registerSubmit(Request $request, $slug)
{
    // Kursu bul veya 404 hatası ver
    $course = Course::where('slug', $slug)->firstOrFail();
    
    // Kullanıcı giriş yapmış mı kontrol et
    if (!Auth::check()) {
        return redirect()->route('login')
            ->with('error', 'Kurs kaydı yapmak için önce giriş yapmalısınız.');
    }
    
    $user = Auth::user();
    
    // Kullanıcı zaten bu kursa kayıtlı mı kontrol et
    if ($user->enrolledCourses()->where('course_id', $course->id)->exists()) {
        // İsteğe bağlı olarak, zaten kaydolduğuna dair bir mesaj gösterilebilir
        return redirect()->route('courses.detail', $course->slug)
            ->with('info', 'Bu kursa zaten kayıtlısınız.');
    }
    
    // Şartlar ve koşulları kabul ettiğinden emin ol
    $request->validate([
        'terms' => 'required',
    ], [
        'terms.required' => 'Kurs kaydı için şartlar ve koşulları kabul etmelisiniz.',
    ]);
    
    // Bültene abone olmak isteyip istemediğini kontrol et
    $subscribeNewsletter = $request->has('newsletter');
    
    // CourseUser pivot tablosuna kayıt ekle
    // Status_id = 1 (Onay bekliyor durumu için)
    $enrollmentData = [
        'enrollment_date' => now(),
        'status_id' => 4, // Onay bekliyor durumu
        'paid_amount' => 0, // Şimdilik 0, ödeme sistemine göre değişecek
        'payment_completed' => false,
        'notes' => 'Otomatik kayıt', 
        'approval_status' => false // Onay bekliyor
    ];
    
    // Kursa kaydol
    $user->enrolledCourses()->attach($course->id, $enrollmentData);
    
    // İsteğe bağlı: Kullanıcıyı bültene abone yap
    if ($subscribeNewsletter) {
        // Burada newsletter işlemleri yapılabilir
    }
    
    // Başarılı kayıt sonrası kullanıcıyı başarı sayfasına yönlendir
    return redirect()->route('course.register.success', ['slug' => $course->slug]);
}

    /**
     * Kayıt başarılı sayfasını göster
     */
    public function registerSuccess(Request $request)
    {
        $slug = $request->query('slug');
        
        // Slug varsa kursu getir
        $course = null;
        if ($slug) {
            $course = Course::where('slug', $slug)->first();
        }
        
        return view('courses.register-success', compact('course'));
    }
    /**
     * Kurs değerlendirme/yorum ekleme
     */
    public function review(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500'
        ]);
        
        $course = Course::findOrFail($id);
        
        // Kullanıcının daha önce yorum yapıp yapmadığını kontrol et
        $existingReview = $course->reviews()->where('user_id', auth()->id())->first();
        
        if ($existingReview) {
            // Mevcut yorumu güncelle
            $existingReview->update([
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);
            
            $message = 'Yorumunuz başarıyla güncellendi.';
        } else {
            // Yeni yorum ekle
            $course->reviews()->create([
                'user_id' => auth()->id(),
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);
            
            $message = 'Yorumunuz başarıyla eklendi.';
        }
        
        // Kurs ortalama puanını güncelle
        $avgRating = $course->reviews()->avg('rating');
        $course->update(['rating' => $avgRating]);
        
        return redirect()->back()->with('success', $message);
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
}