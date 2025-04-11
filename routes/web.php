<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\CustomRegisterController;
use App\Http\Controllers\Auth\ContactController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\Admin\PrivateLessonController;

use App\Http\Controllers\Teacher\TeacherPrivateLessonController;
use App\Http\Controllers\Student\StudentPrivateLessonController;

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
// SMS ile şifre sıfırlama route'ları
Route::get('forgot-password-sms', [App\Http\Controllers\Auth\SmsPasswordResetController::class, 'showForgotForm'])
    ->middleware('guest')
    ->name('password.sms.request');

Route::post('forgot-password-sms', [App\Http\Controllers\Auth\SmsPasswordResetController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.sms.email');

Route::get('reset-password-sms/{token}', [App\Http\Controllers\Auth\SmsPasswordResetController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.sms.reset');

Route::post('reset-password-sms', [App\Http\Controllers\Auth\SmsPasswordResetController::class, 'reset'])
    ->middleware('guest')
    ->name('password.sms.update');
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
        // Özel Ders Yönetimi temel rotaları (resource controller)

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

// routes/web.php dosyasına eklenecek route tanımlamaları

Route::middleware(['auth', 'role:ogretmen', 'verified.phone'])->group(function () {
    Route::prefix('ogretmen')->name('ogretmen.')->group(function () {
        // Öğretmen ana sayfası/dashboard
        Route::get('/panel', [App\Http\Controllers\Teacher\TeacherController::class, 'index'])
            ->name('panel');
            Route::get('/check-lesson-conflict', [TeacherPrivateLessonController::class, 'checkLessonConflictApi'])->name('check.lesson.conflict');

// Ders bazlı route'lar
// Öğretmen rotaları içinde, özel ders rotaları arasına ekleyin
Route::get('/ozel-ders-grup/{id}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'showLesson'])
    ->name('private-lessons.showLesson');
Route::get('/ozel-ders-grup/{id}/duzenle', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'editLesson'])
    ->name('private-lessons.editLesson');
Route::put('/ozel-ders-grup/{id}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'updateLesson'])
    ->name('private-lessons.updateLesson');
        // Kurs yönetimi route'ları
        Route::post('/kurs/{id}/toplanti-bilgileri', [App\Http\Controllers\Teacher\TeacherController::class, 'updateMeetingInfo'])
            ->name('course.update-meeting-info');
            Route::post('/ozel-ders/{id}/tamamla', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'completeLesson'])
    ->name('private-lessons.completeLesson');
        Route::get('/kurs/{id}', [App\Http\Controllers\Teacher\TeacherController::class, 'courseDetail'])
            ->name('course.detail');
            // Homework management routes
Route::get('/ozel-ders/{id}/odev-ekle', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'showAddHomework'])
->name('private-lessons.homework.create');
Route::post('/ozel-ders/{id}/odev-ekle', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'storeHomework'])
->name('private-lessons.homework.store');
Route::get('/ozel-ders/{id}/odevler', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'viewHomeworks'])
->name('private-lessons.homework.index');
Route::delete('/ozel-ders-odev/{homeworkId}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'deleteHomework'])
->name('private-lessons.homework.delete');
Route::get('/ozel-ders-odev/{homeworkId}/teslimler', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'viewHomeworkSubmissions'])
->name('private-lessons.homework.submissions');
Route::get('/ozel-ders-odev/{homeworkId}/indir', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'downloadHomework'])
->name('private-lessons.homework.download');
Route::get('/ozel-ders-teslim/{submissionId}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'viewSubmission'])
->name('private-lessons.submission.view');
Route::post('/ozel-ders-teslim/{submissionId}/degerlendir', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'gradeSubmission'])
->name('private-lessons.submission.grade');
Route::get('/ozel-ders-teslim/{submissionId}/indir', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'downloadSubmission'])
->name('private-lessons.submission.download');
            // Öğretmen rotaları içinde, özel ders rotaları arasına ekleyin
        Route::get('/ozel-ders-seans/{id}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'showSession'])
        ->name('private-lessons.session.show');
        Route::get('/kurs/{courseId}/teslimler', [App\Http\Controllers\Teacher\TeacherController::class, 'loadStudentSubmissions'])
            ->name('course.submissions.load');
            Route::post('/ozel-ders/{id}/tamamla', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'completeLesson'])
    ->name('private-lessons.complete');
        Route::get('/kurs/{courseId}/teslimler/{studentId?}', [App\Http\Controllers\Teacher\TeacherController::class, 'loadStudentSubmissions'])
            ->name('course.submissions.load');
            Route::post('/private-lessons/{lessonId}/toggle-active', [TeacherPrivateLessonController::class, 'toggleLessonActive'])
    ->name('private-lessons.toggleActive');
        Route::post('/kurs/{courseId}/duyuru', [App\Http\Controllers\Teacher\TeacherController::class, 'createAnnouncement'])
            ->name('course.create-announcement');
            
        Route::post('/kurs/{courseId}/odev', [App\Http\Controllers\Teacher\TeacherController::class, 'createHomework'])
            ->name('course.create-homework');
            
        Route::get('/fetch-homeworks', [App\Http\Controllers\Teacher\TeacherController::class, 'fetchHomeworks'])
            ->name('fetch-homeworks');
        
        // Ödev teslim değerlendirme route'ları
        Route::get('/teslim/{id}', [App\Http\Controllers\Teacher\TeacherController::class, 'viewSubmission'])
            ->name('submission.view');
            
        Route::get('/teslim/{id}/degerlendir', [App\Http\Controllers\Teacher\TeacherController::class, 'evaluateSubmission'])
            ->name('submission.evaluate');
            
        Route::post('/teslim/{id}/degerlendir', [App\Http\Controllers\Teacher\TeacherController::class, 'saveEvaluation'])
            ->name('submission.save-evaluation');
            // Ders materyali ekleme rotaları
        // Ders materyali ekleme rotaları
        Route::get('/ozel-ders/{id}/materyal-ekle', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'showAddMaterial'])
        ->name('private-lessons.material.create');
        Route::post('/ozel-ders/{id}/materyal-ekle', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'storeMaterial'])
        ->name('private-lessons.material.store');
        Route::delete('/ozel-ders-materyal/{materialId}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'deleteMaterial'])
        ->name('private-lessons.material.delete');

        // Ders materyali indirme rotası
        Route::get('/ozel-ders-materyal/{id}/indir', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'downloadMaterial'])
        ->name('private-lessons.material.download');
        // Özel Ders Yönetimi route'ları
        Route::get('/ozel-derslerim', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'index'])
            ->name('private-lessons.index');
            
        Route::get('/ozel-ders-talep', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'pendingRequests'])
            ->name('private-lessons.pendingRequests');
            
        Route::get('/ozel-ders/olustur', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'create'])
            ->name('private-lessons.create');
            
        Route::post('/ozel-ders/kaydet', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'store'])
            ->name('private-lessons.store');
            
        // Yeni eklenen detay görüntüleme route'u
        Route::get('/ozel-ders/{id}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'show'])
            ->name('private-lessons.show');
            
        Route::get('/ozel-ders/{id}/duzenle', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'edit'])
            ->name('private-lessons.edit');
            
        Route::put('/ozel-ders/{id}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'update'])
            ->name('private-lessons.update');
            
        Route::delete('/ozel-ders/{id}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'destroy'])
            ->name('private-lessons.destroy');
            
        Route::post('/ozel-ders/{id}/onayla', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'approve'])
            ->name('private-lessons.approve');
            
        Route::post('/ozel-ders/{id}/reddet', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'reject'])
            ->name('private-lessons.reject');
            // Ders bazlı route'lar

        // Yeni ödeme işlemleri rotaları
        Route::post('/ozel-ders/{id}/odeme-al', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'takePayment'])
            ->name('private-lessons.takePayment');
            
        Route::post('/ozel-ders/{id}/odeme-guncelle', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'updatePaymentStatus'])
            ->name('private-lessons.updatePaymentStatus');
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
             // Özel Ders Yönetimi için rotalar
        Route::get('/ozel-derslerim', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'index'])
        ->name('private-lessons.index');
        Route::get('/tamamlanan-dersler', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'completed'])
    ->name('private-lessons.completed');
    Route::get('/ozel-ders/{id}', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'showLesson'])
        ->name('private-lessons.lesson');
        
    Route::get('/ozel-ders-seans/{id}', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'showSession'])
        ->name('private-lessons.session');
        
    // Materyal yönetimi
    Route::get('/ders-materyalleri', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'materials'])
        ->name('private-lessons.materials');
        
    Route::get('/materyal/{id}/indir', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'downloadMaterial'])
        ->name('private-lessons.material.download');
        
    // Ödev yönetimi
    Route::get('/odevlerim', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'homeworks'])
        ->name('private-lessons.homeworks');
        
    Route::get('/odev/{id}', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'showHomework'])
        ->name('private-lessons.homework');
        
    Route::get('/odev/{id}/indir', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'downloadHomework'])
        ->name('private-lessons.homework.download');
        
    Route::post('/odev/{id}/teslim-et', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'submitHomework'])
        ->name('private-lessons.homework.submit');
        
    Route::get('/odev-teslim/{id}/indir', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'downloadSubmission'])
        ->name('private-lessons.submission.download');
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