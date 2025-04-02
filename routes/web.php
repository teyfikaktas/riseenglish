<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\CustomRegisterController;
use App\Http\Controllers\Auth\ContactController;
use App\Http\Controllers\ResourceController;

use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseTypeController;
use App\Http\Controllers\Admin\CourseLevelController;
use App\Http\Controllers\Admin\CourseFrequencyController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\FrontendCourseController;
use App\Models\Course;

Route::get('/iletisim', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/iletisim/gonder', [\App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');

Route::get('/ucretsiz-kaynaklar', [App\Http\Controllers\PublicResourceController::class, 'index'])->name('public.resources.index');
Route::get('/ucretsiz-kaynaklar/{slug}', [App\Http\Controllers\PublicResourceController::class, 'show'])->name('public.resources.show');
// Ana sayfa için kaynak yönlendirmesi (opsiyonel)

Route::post('/send-otp', [App\Http\Controllers\OtpController::class, 'sendOtp'])
    ->middleware('auth')
    ->name('send-otp');

// Telefon doğrulama için route'lar
Route::middleware(['auth'])->group(function () {
    Route::get('/telefon-dogrulama', function () {
        // Eğer kullanıcının telefonu zaten doğrulanmışsa ana sayfaya yönlendir
        if (Auth::user()->phone_verified) {
            return redirect()->route('home')->with('info', 'Telefonunuz zaten doğrulanmış.');
        }
        return view('auth.verify-phone');
    })->name('verification.phone.notice');
    
    Route::post('/telefon-dogrulama/otp', [App\Http\Controllers\OtpController::class, 'verify'])
        ->name('verification.phone.verify');
        
    // OTP gönderme route'u
    Route::post('/telefon-dogrulama/send', [App\Http\Controllers\OtpController::class, 'sendOtp'])
        ->name('verification.phone.send');
});

// Ana Route'lar
// Ana route
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/ana-sayfa', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/oturum-ac', function() {
    // Eğer kullanıcı zaten giriş yapmışsa, rol kontrolü yap
    if (Auth::check()) {
        // Yönetici ise dashboard'a yönlendir
        if (Auth::user()->hasRole('yonetici')) {
            return redirect('/admin/dashboard');
        }
        // Öğretmen ise öğretmen paneline yönlendir
        if (Auth::user()->hasRole('ogretmen')) {
            return redirect('/ogretmen/panel');
        }
        // Normal kullanıcı ise ana sayfaya yönlendir
        return redirect('/');
    }
    // Giriş yapmamışsa, login view'ını göster
    return view('auth.login');
});

Route::get('/home', function () {
    // Yönetici kullanıcıları doğrudan yönetici paneline yönlendir
    if (Auth::check() && Auth::user()->hasRole('yonetici')) {
        return redirect('/admin/dashboard');
    }
    
    // Öğretmen kullanıcıları doğrudan öğretmen paneline yönlendir
    if (Auth::check() && Auth::user()->hasRole('ogretmen')) {
        return redirect('/ogretmen/panel');
    }
    
    // Doğrudan controller'a yönlendir
    return to_route('standard.home');
})->middleware('auth')->name('home');

// Normal kullanıcılar için home route
Route::get('/standard-home', [App\Http\Controllers\HomeController::class, 'index'])
    ->middleware(['auth', 'verified.phone'])
    ->name('standard.home');

// Özel kayıt rotalarımız
Route::get('/kayit-ol', [CustomRegisterController::class, 'create'])->name('register');
Route::post('/kayit-ol', [CustomRegisterController::class, 'store']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Kurs listeleme ve detay sayfaları - Frontend Controller
Route::get('/egitimler', [FrontendCourseController::class, 'index'])->name('courses.index');
Route::get('/egitimler/{slug}', [FrontendCourseController::class, 'detail'])->name('courses.detail');

// Kurs kayıt işlemleri - Telefon doğrulaması gerekli
Route::middleware(['auth', 'verified.phone'])->group(function () {
    Route::get('/kurs-kayit/{slug}', [FrontendCourseController::class, 'register'])->name('course.register');
    Route::post('/kurs-kayit/{slug}', [FrontendCourseController::class, 'registerSubmit'])->name('course.register.submit');
    Route::get('/kurs-kayit-basarili', [FrontendCourseController::class, 'registerSuccess'])->name('course.register.success');
    
    // Kurs değerlendirme
    Route::post('/egitimler/{id}/yorum', [FrontendCourseController::class, 'review'])->name('course.review');
});

// Yönetici rotaları - Laravel 12 tarzında middleware kullanımı
Route::middleware(['auth', 'role:yonetici'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/sms', [App\Http\Controllers\Admin\SmsController::class, 'index'])->name('sms.index');
        Route::post('/sms/send-individual', [App\Http\Controllers\Admin\SmsController::class, 'sendIndividual'])->name('sms.send-individual');
        Route::post('/sms/send-bulk', [App\Http\Controllers\Admin\SmsController::class, 'sendBulk'])->name('sms.send-bulk');
        Route::resource('/resources', App\Http\Controllers\ResourceController::class);
    // Kaynak Kategorileri Yönetimi
    Route::resource('/resource-categories', App\Http\Controllers\ResourceCategoryController::class);
    
    // Kaynak Türleri Yönetimi
    Route::resource('/resource-types', App\Http\Controllers\ResourceTypeController::class);
    
    // Kaynak Etiketleri Yönetimi
    Route::resource('/resource-tags', App\Http\Controllers\ResourceTagController::class);
        Route::resources([
            'course-types' => CourseTypeController::class,
            'course-levels' => CourseLevelController::class,
            'course-frequencies' => CourseFrequencyController::class,
            'courses' => CourseController::class,
        ]);
           // Eksik olan rotalar - bunları ekleyin
           Route::get('/sms/search-users', [App\Http\Controllers\Admin\SmsController::class, 'searchUsers'])->name('sms.search-users');
           Route::get('/sms/search-courses', [App\Http\Controllers\Admin\SmsController::class, 'searchCourses'])->name('sms.search-courses');
        Route::get('/course-levels/list', [CourseLevelController::class, 'getList'])->name('course-levels.list');
        Route::get('/course-types/list', [CourseTypeController::class, 'getList'])->name('course-types.list');
        Route::get('/courses/{course}/enrollments', [CourseController::class, 'enrollments'])->name('courses.enrollments');
        Route::put('/courses/{course}/enrollments/{user}', [CourseController::class, 'updateEnrollment'])->name('courses.enrollments.update');
        Route::get('/courses/{course}/enrollments/{user}/data', [CourseController::class, 'getEnrollmentData'])->name('courses.enrollments.data');
        Route::get('/contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
        Route::post('/contacts/{contact}/mark-as-read', [App\Http\Controllers\Admin\ContactController::class, 'markAsRead'])->name('contacts.mark-as-read');
        Route::post('/contacts/{contact}/mark-as-unread', [App\Http\Controllers\Admin\ContactController::class, 'markAsUnread'])->name('contacts.mark-as-unread');
        Route::delete('/contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
        // Kullanıcı yönetimi için resource routes
        Route::resource('users', \App\Http\Controllers\Admin\AdminUserController::class);
        
        // Özel kullanıcı yönetimi rotaları
        Route::get('/users/{user}/manage-courses', [\App\Http\Controllers\Admin\AdminUserController::class, 'manageCourses'])
            ->name('users.manageCourses');
        
        Route::post('/users/{user}/enroll-course', [\App\Http\Controllers\Admin\AdminUserController::class, 'enrollCourse'])
            ->name('users.enrollCourse');
        
        Route::put('/users/{user}/courses/{course}', [\App\Http\Controllers\Admin\AdminUserController::class, 'updateCourseEnrollment'])
            ->name('users.updateCourseEnrollment');
        
        Route::delete('/users/{user}/courses/{course}', [\App\Http\Controllers\Admin\AdminUserController::class, 'unenrollCourse'])
            ->name('users.unenrollCourse');
        
        // Kullanıcı kurs kayıt verilerini getiren API
        Route::get('/users/{user}/courses/{course}/enrollment-data', [\App\Http\Controllers\Admin\AdminUserController::class, 'getEnrollmentData'])
            ->name('users.enrollmentData');   
    });
});

// Öğretmen rotaları
Route::middleware(['auth', 'role:ogretmen', 'verified.phone'])->group(function () {
    Route::prefix('ogretmen')->name('ogretmen.')->group(function () {
        // Öğretmen ana sayfası/dashboard
        Route::get('/panel', [App\Http\Controllers\Teacher\TeacherController::class, 'index'])
            ->name('panel');
            Route::post('/kurs/{id}/toplanti-bilgileri', [App\Http\Controllers\Teacher\TeacherController::class, 'updateMeetingInfo'])
            ->name('course.update-meeting-info');
        // Kurs detay sayfası
        Route::get('/kurs/{id}', [App\Http\Controllers\Teacher\TeacherController::class, 'courseDetail'])
            ->name('course.detail');
            Route::get('/kurs/{courseId}/teslimler', [App\Http\Controllers\Teacher\TeacherController::class, 'loadStudentSubmissions'])
            ->name('course.submissions.load');
            Route::get('/kurs/{courseId}/teslimler/{studentId?}', [App\Http\Controllers\Teacher\TeacherController::class, 'loadStudentSubmissions'])
            ->name('course.submissions.load');
        // Duyuru oluşturma
        Route::post('/kurs/{courseId}/duyuru', [App\Http\Controllers\Teacher\TeacherController::class, 'createAnnouncement'])
            ->name('course.create-announcement');
        
        // Ödev oluşturma
        Route::post('/kurs/{courseId}/odev', [App\Http\Controllers\Teacher\TeacherController::class, 'createHomework'])
            ->name('course.create-homework');
            Route::get('/fetch-homeworks', [App\Http\Controllers\Teacher\TeacherController::class, 'fetchHomeworks'])->name('fetch-homeworks');
        // Ödev Teslimi Görüntüleme ve Değerlendirme Rotaları
        Route::get('/teslim/{id}', [App\Http\Controllers\Teacher\TeacherController::class, 'viewSubmission'])
            ->name('submission.view');
            
        Route::get('/teslim/{id}/degerlendir', [App\Http\Controllers\Teacher\TeacherController::class, 'evaluateSubmission'])
            ->name('submission.evaluate');
            
        Route::post('/teslim/{id}/degerlendir', [App\Http\Controllers\Teacher\TeacherController::class, 'saveEvaluation'])
            ->name('submission.save-evaluation');
    });
});

// Öğrenci rotaları
Route::middleware(['auth', 'role:ogrenci', 'verified.phone'])->group(function () {
    Route::prefix('ogrenci')->name('ogrenci.')->group(function () {
        Route::get('/ayarlar', [App\Http\Controllers\Student\StudentSettingsController::class, 'index'])
        ->name('settings.index');
    Route::post('/ayarlar/profil', [App\Http\Controllers\Student\StudentSettingsController::class, 'updateProfile'])
        ->name('settings.update-profile');
    Route::post('/ayarlar/sifre', [App\Http\Controllers\Student\StudentSettingsController::class, 'updatePassword'])
        ->name('settings.update-password');
        // Öğrencinin kursları
        Route::get('/kurslarim', [App\Http\Controllers\Student\StudentCourseController::class, 'index'])
            ->name('kurslarim');
            
        // Kurs detay sayfası - Sadece kayıtlı öğrenciler için
        Route::get('/kurslarim/{slug}', [App\Http\Controllers\Student\StudentCourseController::class, 'showCourseDetail'])
        ->name('kurs-detay');
        // Ödev ekleme (sonradan işlevsellik eklenecek)
        Route::post('/kurslarim/{slug}/odev-yukle/{homeworkId}', [App\Http\Controllers\Student\StudentCourseController::class, 'submitHomework'])
        ->name('odev-yukle');
    });
});

// Oturum açmış kullanıcı profil yönetimi - Telefon Doğrulaması Gerekli
Route::middleware(['auth', 'verified.phone'])->group(function () {
    Route::get('/profil', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profil/duzenle', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil/guncelle', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profil/kurslarim', [App\Http\Controllers\ProfileController::class, 'courses'])->name('profile.courses');
    Route::get('/profil/sertifikalarim', [App\Http\Controllers\ProfileController::class, 'certificates'])->name('profile.certificates');
});