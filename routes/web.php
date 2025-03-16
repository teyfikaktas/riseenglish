<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\CustomRegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseTypeController;
use App\Http\Controllers\Admin\CourseLevelController;
use App\Http\Controllers\Admin\CourseFrequencyController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\FrontendCourseController;
use App\Models\Course;

// Ana Route'lar
Route::get('/', function () {
    // Yönetici kullanıcıları doğrudan yönetici paneline yönlendir
    if (Auth::check() && Auth::user()->hasRole('yonetici')) {
        return redirect('/admin/dashboard');
    }
    
    // Öne çıkan kursları getir
    $featuredCourses = Course::where('is_featured', true)
                             ->where('is_active', true)
                             ->orderBy('display_order')
                             ->with(['teacher', 'courseType', 'courseLevel'])
                             ->take(6)
                             ->get();
    
    return view('welcome', compact('featuredCourses'));
});

Route::get('/ana-sayfa', function () {
    // Yönetici kullanıcıları doğrudan yönetici paneline yönlendir
    if (Auth::check() && Auth::user()->hasRole('yonetici')) {
        return redirect('/admin/dashboard');
    }
    
    // Öne çıkan kursları getir
    $featuredCourses = Course::where('is_featured', true)
                             ->where('is_active', true)
                             ->orderBy('display_order')
                             ->with(['teacher', 'courseType', 'courseLevel'])
                             ->take(3)
                             ->get();
    
    return view('welcome', compact('featuredCourses'));
});

Route::get('/oturum-ac', function() {
    // Eğer kullanıcı zaten giriş yapmışsa, rol kontrolü yap
    if (Auth::check()) {
        // Yönetici ise dashboard'a yönlendir
        if (Auth::user()->hasRole('yonetici')) {
            return redirect('/admin/dashboard');
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
    
    // Doğrudan controller'a yönlendir
    return to_route('standard.home');
})->middleware('auth')->name('home');

// Normal kullanıcılar için home route
Route::get('/standard-home', [App\Http\Controllers\HomeController::class, 'index'])
    ->middleware('auth')
    ->name('standard.home');

// Özel kayıt rotalarımız
Route::get('/kayit-ol', [CustomRegisterController::class, 'create'])->name('register');
Route::post('/kayit-ol', [CustomRegisterController::class, 'store']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Kurs listeleme ve detay sayfaları - Frontend Controller
Route::get('/egitimler', [FrontendCourseController::class, 'index'])->name('courses.index');
Route::get('/egitimler/{slug}', [FrontendCourseController::class, 'detail'])->name('courses.detail');

// Kurs kayıt işlemleri
Route::get('/kurs-kayit/{id}', [FrontendCourseController::class, 'register'])->name('course.register');
Route::post('/kurs-kayit/{id}', [FrontendCourseController::class, 'registerSubmit'])->name('course.register.submit');
Route::get('/kurs-kayit-basarili', [FrontendCourseController::class, 'registerSuccess'])->name('course.register.success');

// Kurs değerlendirme
Route::post('/egitimler/{id}/yorum', [FrontendCourseController::class, 'review'])->name('course.review')->middleware('auth');

// Yönetici rotaları - Laravel 12 tarzında middleware kullanımı
Route::middleware(['auth', 'role:yonetici'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Kurs tipleri için resource route
        Route::resources([
            'course-types' => CourseTypeController::class,
            'course-levels' => CourseLevelController::class,
            'course-frequencies' => CourseFrequencyController::class,
            'courses' => CourseController::class,
        ]);
        
        Route::get('/course-levels/list', [CourseLevelController::class, 'getList'])->name('course-levels.list');
        Route::get('/course-types/list', [CourseTypeController::class, 'getList'])->name('course-types.list');
        Route::get('/courses/{course}/enrollments', [CourseController::class, 'enrollments'])->name('courses.enrollments');
        Route::put('/courses/{course}/enrollments/{user}', [CourseController::class, 'updateEnrollment'])->name('courses.enrollments.update');
        Route::get('/courses/{course}/enrollments/{user}/data', [CourseController::class, 'getEnrollmentData'])->name('courses.enrollments.data');
        
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
            ->name('users.enrollmentData');    });
});
Route::middleware(['auth', 'role:ogrenci'])->group(function () {
    Route::prefix('ogrenci')->name('ogrenci.')->group(function () {
        
        // Öğrencinin kursları
        Route::get('/kurslarim', [App\Http\Controllers\Student\StudentCourseController::class, 'index'])
            ->name('kurslarim');
            
        // Kurs detay sayfası - Sadece kayıtlı öğrenciler için
        Route::get('/kurslarim/{id}', [App\Http\Controllers\Student\StudentCourseController::class, 'showCourseDetail'])
            ->name('kurs-detay');
            
        // Ödev ekleme (sonradan işlevsellik eklenecek)
        Route::post('/kurslarim/{id}/odev-ekle', [App\Http\Controllers\Student\StudentCourseController::class, 'submitHomework'])
            ->name('odev-ekle');
    });
});
// Oturum açmış kullanıcı profil yönetimi
Route::middleware(['auth'])->group(function () {
    Route::get('/profil', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profil/duzenle', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil/guncelle', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profil/kurslarim', [App\Http\Controllers\ProfileController::class, 'courses'])->name('profile.courses');
    Route::get('/profil/sertifikalarim', [App\Http\Controllers\ProfileController::class, 'certificates'])->name('profile.certificates');
});