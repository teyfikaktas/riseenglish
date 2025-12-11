<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\CustomRegisterController;
use App\Http\Controllers\Auth\ContactController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\WordSetsController;
use App\Http\Controllers\Admin\PrivateLessonController;
use App\Http\Controllers\Ogrenci\TestCategoryController;
use App\Http\Controllers\Ogrenci\TestController;
use App\Http\Controllers\Teacher\TeacherPrivateLessonController;
use App\Http\Controllers\Student\StudentPrivateLessonController;
use App\Http\Controllers\ResourceDownloadController;
use App\Http\Controllers\Admin\TestDashboardController;
use App\Http\Controllers\Admin\TestCategoryController as AdminTestCategoryController;
use App\Http\Controllers\Admin\TestController as AdminTestController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\UsefulResourceController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseTypeController;
use App\Http\Controllers\Admin\CourseLevelController;
use App\Http\Controllers\Admin\CourseFrequencyController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\FrontendCourseController;
use App\Models\Course;
use Illuminate\Support\Facades\URL;

if (env('APP_ENV') === 'production') {
    URL::forceScheme('https');
}

Route::get('/generate-sitemap', [App\Http\Controllers\SitemapController::class, 'generate'])
    ->middleware(['auth', 'role:yonetici'])
    ->name('generate-sitemap');

Route::post('/resources/download', [ResourceDownloadController::class, 'download'])->name('resources.download');

Route::get('robots.txt', function () {
    $content = "User-agent: *\n";
    $content .= "Disallow: /admin/\n";
    $content .= "Disallow: /oturum-ac\n";
    $content .= "Disallow: /kayit-ol\n";
    $content .= "Disallow: /profil\n";
    $content .= "Disallow: /ogretmen/\n";
    $content .= "Disallow: /ogrenci/\n";
    $content .= "Disallow: /kurs-kayit/\n";
    $content .= "Disallow: /telefon-dogrulama/\n";
    $content .= "Disallow: /reset-password\n";
    $content .= "Disallow: /forgot-password\n";
    $content .= "Allow: /\n";
    $content .= "Allow: /egitimler\n";
    $content .= "Allow: /iletisim\n";
    $content .= "Allow: /ucretsiz-kaynaklar\n";
    $content .= "Allow: /useful-resources\n";
    $content .= "Sitemap: " . url('sitemap.xml');
    
    return response($content, 200)->header('Content-Type', 'text/plain');
});

Route::get('/iletisim', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/iletisim/gonder', [\App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');

Route::get('/ucretsiz-kaynaklar', [App\Http\Controllers\PublicResourceController::class, 'index'])->name('public.resources.index');
Route::get('/ucretsiz-kaynaklar/{slug}', [App\Http\Controllers\PublicResourceController::class, 'show'])->name('public.resources.show');

Route::post('/send-otp', [App\Http\Controllers\OtpController::class, 'sendOtp'])
    ->middleware('auth')
    ->name('send-otp');

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

Route::middleware(['auth'])->group(function () {
    Route::get('/telefon-dogrulama', function () {
        if (Auth::user()->phone_verified) {
            return redirect()->route('home')->with('info', 'Telefonunuz zaten doğrulanmış.');
        }
        return view('auth.verify-phone');
    })->name('verification.phone.notice');
    
    Route::post('/telefon-dogrulama/otp', [App\Http\Controllers\OtpController::class, 'verify'])
        ->name('verification.phone.verify');
    
    Route::post('/telefon-dogrulama/send', [App\Http\Controllers\OtpController::class, 'sendOtp'])
        ->name('verification.phone.send');
    
    Route::prefix('kelimelerim')->name('word-sets.')->group(function () {
        Route::get('/', [WordSetsController::class, 'index'])->name('index');
        Route::get('/yeni', [WordSetsController::class, 'create'])->name('create');
        Route::post('/', [WordSetsController::class, 'store'])->name('store');
        Route::get('/{wordSet}', [WordSetsController::class, 'show'])->name('show');
        Route::get('/{wordSet}/duzenle', [WordSetsController::class, 'edit'])->name('edit');
        Route::put('/{wordSet}', [WordSetsController::class, 'update'])->name('update');
        Route::delete('/{wordSet}', [WordSetsController::class, 'destroy'])->name('destroy');
        Route::post('/{wordSet}/import-excel', [WordSetsController::class, 'importExcel'])->name('import-excel');
        Route::post('/{wordSet}/kelime-ekle', [WordSetsController::class, 'addWord'])->name('add-word');
        Route::delete('/{wordSet}/kelime/{userWord}', [WordSetsController::class, 'deleteWord'])->name('delete-word');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/categories/{lang}', function($lang) {
        $userId = auth()->id();
        
        \Log::info('Categories API Called', [
            'lang' => $lang,
            'userId' => $userId
        ]);
        
        $categories = \App\Models\WordSet::where('is_active', 1)
            ->where(function($query) use ($userId) {
                $query->where('user_id', 1)->orWhere('user_id', $userId);
            })
            ->whereHas('words', function($query) use ($lang) {
                $query->where('lang', $lang);
            })
            ->select('id', 'name', 'description', 'color', 'word_count', 'user_id')
            ->get()
            ->map(function($category) use ($lang) {
                $wordCount = $category->words()->where('lang', $lang)->count();
                $category->total_sets = $wordCount > 0 ? ceil($wordCount / 50) : 0;
                return $category;
            });
        
        return response()->json($categories);
    });
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/ana-sayfa', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/oturum-ac', function() {
    if (Auth::check()) {
        if (Auth::user()->hasRole('yonetici')) {
            return redirect('/admin/dashboard');
        }
        if (Auth::user()->hasRole('ogretmen')) {
            return redirect('/ogretmen/panel');
        }
        return redirect('/');
    }
    return view('auth.login');
});

Route::get('/belge/{token}', [App\Http\Controllers\PublicDocumentController::class, 'show'])
    ->name('public.document.show');

Route::get('/home', function () {
    if (Auth::check() && Auth::user()->hasRole('yonetici')) {
        return redirect('/admin/dashboard');
    }
    
    if (Auth::check() && Auth::user()->hasRole('ogretmen')) {
        return redirect('/ogretmen/panel');
    }
    
    if (!Auth::user()->phone_verified) {
        return redirect()->route('verification.phone.notice');
    }
    
    if (!Auth::user()->teacher_approved) {
        return redirect()->route('waiting-approval');
    }
    
    return to_route('standard.home');
})->middleware('auth')->name('home');

Route::get('/onay-bekleniyor', function() {
    return view('auth.waiting-approval');
})->middleware('auth')->name('waiting-approval');

Route::get('/standard-home', [App\Http\Controllers\HomeController::class, 'index'])
    ->middleware(['auth', 'verified.phone', 'teacher.approved'])
    ->name('standard.home');

Route::middleware(['auth', 'role:ogretmen'])->group(function () {
    Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create');
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
});

Route::get('/kayit-ol', [CustomRegisterController::class, 'create'])->name('register');
Route::post('/kayit-ol', [CustomRegisterController::class, 'store']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::get('/egitimler', [FrontendCourseController::class, 'index'])->name('courses.index');
Route::get('/egitimler/{slug}', [FrontendCourseController::class, 'detail'])->name('courses.detail');

Route::middleware(['auth', 'verified.phone', 'teacher.approved'])->group(function () {
    Route::get('/kurs-kayit/{slug}', [FrontendCourseController::class, 'register'])->name('course.register');
    Route::post('/kurs-kayit/{slug}', [FrontendCourseController::class, 'registerSubmit'])->name('course.register.submit');
    Route::get('/kurs-kayit-basarili', [FrontendCourseController::class, 'registerSuccess'])->name('course.register.success');
    Route::post('/egitimler/{id}/yorum', [FrontendCourseController::class, 'review'])->name('course.review');
});

Route::prefix('ogrenci')->name('ogrenci.')->group(function () {
    Route::get('/test-categories', [TestCategoryController::class, 'index'])->name('test-categories.index');
    Route::get('/test-categories/{category:slug}', [TestCategoryController::class, 'show'])->name('test-categories.show');
    Route::get('/tests/{test:slug}', [TestController::class, 'show'])->name('tests.show');
    Route::get('/test-taking/{test:slug}', [TestController::class, 'take'])->name('tests.take');
    Route::get('/ogrenme-paneli', [App\Http\Controllers\Student\LearningPanelController::class, 'index'])->name('learning-panel.index');
    Route::get('/ogrenme-paneli/sorular', [App\Http\Controllers\Student\LearningPanelController::class, 'questions'])->name('learning-panel.questions');
});

Route::middleware(['auth', 'verified.phone', 'teacher.approved'])->group(function () {
    Route::get('/zinciri-kirma', [App\Http\Controllers\ChainBreakerController::class, 'index'])->name('zinciri-kirma');
    Route::post('/zinciri-kirma/tamamla', [App\Http\Controllers\ChainBreakerController::class, 'markDayComplete'])->name('zinciri-kirma.tamamla');
    Route::post('/zinciri-kirma/sifirla', [App\Http\Controllers\ChainBreakerController::class, 'resetChain'])->name('zinciri-kirma.sifirla');
});

Route::middleware(['auth', 'role:yonetici'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('groups', App\Http\Controllers\Admin\AdminGroupController::class);
        Route::post('/groups/{group}/add-student', [App\Http\Controllers\Admin\AdminGroupController::class, 'addStudent'])->name('groups.add-student');
        Route::delete('/groups/{group}/students/{user}', [App\Http\Controllers\Admin\AdminGroupController::class, 'removeStudent'])->name('groups.remove-student');
        
        Route::get('/test-dashboard', [TestDashboardController::class, 'index'])->name('test-dashboard.index');
        Route::resource('test-categories', AdminTestCategoryController::class);
        Route::resource('tests', AdminTestController::class);
        Route::get('/tests/{test}/sorular', [AdminTestController::class, 'manageQuestions'])->name('tests.manage-questions');
        Route::post('/tests/{test}/soru-ekle', [AdminTestController::class, 'addQuestion'])->name('tests.add-question');
        Route::delete('/tests/{test}/sorular/{question}', [AdminTestController::class, 'removeQuestion'])->name('tests.remove-question');
        
        Route::resource('questions', QuestionController::class);
        Route::get('/questions/bulk/create', [QuestionController::class, 'bulkCreate'])->name('questions.bulk-create');
        Route::post('/questions/bulk/store', [QuestionController::class, 'bulkStore'])->name('questions.bulk-store');
        Route::put('/questions/{question}/categories', [QuestionController::class, 'updateCategories'])->name('questions.update-categories');
        Route::post('/questions/{question}/clone', [QuestionController::class, 'clone'])->name('questions.clone');
        
        Route::resource('users', \App\Http\Controllers\Admin\AdminUserController::class);
        Route::post('/users/{user}/approve', [\App\Http\Controllers\Admin\AdminUserController::class, 'approve'])->name('users.approve');
        Route::post('/users/{user}/unapprove', [AdminUserController::class, 'unapprove'])
    ->name('admin.users.unapprove'); // <-- Bu noktalı virgül eksikti
        Route::get('/users/{user}/manage-courses', [\App\Http\Controllers\Admin\AdminUserController::class, 'manageCourses'])->name('users.manageCourses');
        Route::post('/users/{user}/enroll-course', [\App\Http\Controllers\Admin\AdminUserController::class, 'enrollCourse'])->name('users.enrollCourse');
        Route::put('/users/{user}/courses/{course}', [\App\Http\Controllers\Admin\AdminUserController::class, 'updateCourseEnrollment'])->name('users.updateCourseEnrollment');
        Route::delete('/users/{user}/courses/{course}', [\App\Http\Controllers\Admin\AdminUserController::class, 'unenrollCourse'])->name('users.unenrollCourse');
        Route::get('/users/{user}/courses/{course}/enrollment-data', [\App\Http\Controllers\Admin\AdminUserController::class, 'getEnrollmentData'])->name('users.enrollmentData');
        
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
        
        Route::resource('/resources', App\Http\Controllers\ResourceController::class);
        Route::resource('/resource-categories', App\Http\Controllers\ResourceCategoryController::class);
        Route::resource('/resource-types', App\Http\Controllers\ResourceTypeController::class);
        Route::resource('/resource-tags', App\Http\Controllers\ResourceTagController::class);
        
        Route::get('/sms', [App\Http\Controllers\Admin\SmsController::class, 'index'])->name('sms.index');
        Route::post('/sms/send-individual', [App\Http\Controllers\Admin\SmsController::class, 'sendIndividual'])->name('sms.send-individual');
        Route::post('/sms/send-bulk', [App\Http\Controllers\Admin\SmsController::class, 'sendBulk'])->name('sms.send-bulk');
        Route::get('/sms/search-users', [App\Http\Controllers\Admin\SmsController::class, 'searchUsers'])->name('sms.search-users');
        Route::get('/sms/search-courses', [App\Http\Controllers\Admin\SmsController::class, 'searchCourses'])->name('sms.search-courses');
        
        Route::get('/contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
        Route::post('/contacts/{contact}/mark-as-read', [App\Http\Controllers\Admin\ContactController::class, 'markAsRead'])->name('contacts.mark-as-read');
        Route::post('/contacts/{contact}/mark-as-unread', [App\Http\Controllers\Admin\ContactController::class, 'markAsUnread'])->name('contacts.mark-as-unread');
        Route::delete('/contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
    });
});

Route::prefix('useful-resources')->name('useful-resources.')->group(function () {
    Route::get('/', [UsefulResourceController::class, 'index'])->name('index');
    Route::get('/popular', [UsefulResourceController::class, 'popular'])->name('popular');
    Route::get('/most-viewed', [UsefulResourceController::class, 'mostViewed'])->name('most-viewed');
    Route::get('/search', [UsefulResourceController::class, 'search'])->name('search');
    Route::get('/category/{category}', [UsefulResourceController::class, 'category'])->name('category');
    Route::get('/{slug}', [UsefulResourceController::class, 'show'])->name('show');
    Route::get('/{slug}/download', [UsefulResourceController::class, 'download'])->name('download');
});
Route::get('/api/useful-resources', [UsefulResourceController::class, 'api']);

Route::middleware(['auth', 'role:ogretmen', 'verified.phone'])->group(function () {
    Route::prefix('ogretmen')->name('ogretmen.')->group(function () {
        Route::get('/panel', [App\Http\Controllers\Teacher\TeacherController::class, 'index'])->name('panel');
        
        Route::get('/ogrenci-onaylari', [App\Http\Controllers\Teacher\StudentApprovalController::class, 'index'])->name('student-approvals');
        Route::post('/ogrenci-onay/{id}', [App\Http\Controllers\Teacher\StudentApprovalController::class, 'approve'])->name('student-approve');
        
        Route::get('/check-lesson-conflict', [TeacherPrivateLessonController::class, 'checkLessonConflictApi'])->name('check.lesson.conflict');
        Route::get('/ozel-ders-seans/{id}/sil', [TeacherPrivateLessonController::class, 'confirmDeleteSession'])->name('private-lessons.session.delete');
        Route::get('/zinciri-kirma-takip', function() {
            return view('teacher.chain-breaker-dashboard');
        })->name('chain-breaker-dashboard');
        Route::get('/ogrenci/{id}/zincir-detay', [App\Http\Controllers\ChainBreakerController::class, 'studentChainDetail'])->name('student.chain-detail');
        Route::post('/ogrenci/{id}/zincir-guncelle', [App\Http\Controllers\ChainBreakerController::class, 'updateStudentChain'])->name('student.chain-update');
        Route::delete('/ozel-ders-seans/{id}', [TeacherPrivateLessonController::class, 'destroySession'])->name('private-lessons.session.destroy');
        Route::get('/belgeler', [App\Http\Controllers\Teacher\CourseDocumentController::class, 'listAllDocuments'])->name('documents.list');
        Route::get('/kurs/{courseId}/belgeler', [App\Http\Controllers\Teacher\CourseDocumentController::class, 'index'])->name('documents.index');
        Route::get('/kurs/{courseId}/belge/ekle', [App\Http\Controllers\Teacher\CourseDocumentController::class, 'create'])->name('documents.create');
        Route::post('/kurs/{courseId}/belge/kaydet', [App\Http\Controllers\Teacher\CourseDocumentController::class, 'store'])->name('documents.store');
        Route::get('/kurs/{courseId}/belge/{documentId}/duzenle', [App\Http\Controllers\Teacher\CourseDocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/kurs/{courseId}/belge/{documentId}', [App\Http\Controllers\Teacher\CourseDocumentController::class, 'update'])->name('documents.update');
        Route::delete('/kurs/{courseId}/belge/{documentId}', [App\Http\Controllers\Teacher\CourseDocumentController::class, 'destroy'])->name('documents.destroy');
        Route::get('/kurs/{courseId}/belge/{documentId}/indir', [App\Http\Controllers\Teacher\CourseDocumentController::class, 'download'])->name('documents.download');
        Route::get('/ozel-ders/takvim', [TeacherPrivateLessonController::class, 'calendar'])->name('private-lessons.calendar');
        Route::get('/ozel-ders-seans/{id}/rapor-olustur', [TeacherPrivateLessonController::class, 'showCreateReport'])->name('private-lessons.session.createReport');
        Route::post('/ozel-ders-seans/{id}/rapor-kaydet', [TeacherPrivateLessonController::class, 'storeReport'])->name('private-lessons.session.storeReport');
        Route::get('/ozel-ders-seans/{id}/rapor', [TeacherPrivateLessonController::class, 'showReport'])->name('private-lessons.session.showReport');
        Route::get('/ozel-ders-seans/{id}/rapor-duzenle', [TeacherPrivateLessonController::class, 'editReport'])->name('private-lessons.session.editReport');
        Route::put('/ozel-ders-seans/{id}/rapor-guncelle', [TeacherPrivateLessonController::class, 'updateReport'])->name('private-lessons.session.updateReport');
        Route::delete('/ozel-ders-seans/{id}/rapor-sil', [TeacherPrivateLessonController::class, 'deleteReport'])->name('private-lessons.session.deleteReport');
        Route::get('/ozel-ders-seans/{id}/pdf-rapor', [TeacherPrivateLessonController::class, 'generatePdfReport'])->name('private-lessons.session.pdfReport');
        Route::get('private-lessons/homework/{homeworkId}/file/{fileId}/download', [TeacherPrivateLessonController::class, 'downloadSubmissionFile'])->name('private-lessons.submission-file.download');
        Route::post('private-lessons/{id}/undo-complete', [TeacherPrivateLessonController::class, 'undoCompleteLesson'])->name('private-lessons.undo-complete');
        Route::get('/odevlerim', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'allHomeworks'])->name('private-lessons.homeworks');
        Route::get('private-lessons/session/{id}/topics', [App\Http\Controllers\Teacher\SessionTopicsController::class, 'manage'])->name('private-lessons.session.topics');
        Route::post('private-lessons/session/{id}/topics/add', [App\Http\Controllers\Teacher\SessionTopicsController::class, 'addTopic'])->name('private-lessons.session.topics.add');
        Route::delete('private-lessons/session/{id}/topics/remove', [App\Http\Controllers\Teacher\SessionTopicsController::class, 'removeTopic'])->name('private-lessons.session.topics.remove');
        Route::get('private-lessons/session/{id}/topics/view', [App\Http\Controllers\Teacher\SessionTopicsController::class, 'view'])->name('private-lessons.session.topics.view');
        Route::post('private-lessons/session/{sessionId}/topics/quick-add', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'quickAddTopic'])->name('private-lessons.session.topics.quick-add');
        Route::get('/ozel-ders-grup/{id}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'showLesson'])->name('private-lessons.showLesson');
        Route::get('/ozel-ders-grup/{id}/duzenle', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'editLesson'])->name('private-lessons.editLesson');
        Route::put('/ozel-ders-grup/{id}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'updateLesson'])->name('private-lessons.updateLesson');
        Route::post('/kurs/{id}/toplanti-bilgileri', [App\Http\Controllers\Teacher\TeacherController::class, 'updateMeetingInfo'])->name('course.update-meeting-info');
        Route::post('/ozel-ders/{id}/tamamla', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'completeLesson'])->name('private-lessons.completeLesson');
        Route::get('/kurs/{id}', [App\Http\Controllers\Teacher\TeacherController::class, 'courseDetail'])->name('course.detail');
        Route::get('/ozel-ders/{id}/odev-ekle', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'showAddHomework'])->name('private-lessons.homework.create');
        Route::post('/ozel-ders/{id}/odev-ekle', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'storeHomework'])->name('private-lessons.homework.store');
        Route::get('/ozel-ders/{id}/odevler', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'viewHomeworks'])->name('private-lessons.homework.index');
        Route::delete('/ozel-ders-odev/{homeworkId}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'deleteHomework'])->name('private-lessons.homework.delete');
        Route::get('/ozel-ders-odev/{homeworkId}/teslimler', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'viewHomeworkSubmissions'])->name('private-lessons.homework.submissions');
        Route::get('/ozel-ders-odev/{homeworkId}/indir', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'downloadHomework'])->name('private-lessons.homework.download');
        Route::get('/ozel-ders-teslim/{submissionId}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'viewSubmission'])->name('private-lessons.submission.view');
        Route::post('/ozel-ders-teslim/{submissionId}/degerlendir', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'gradeSubmission'])->name('private-lessons.submission.grade');
        Route::get('/ozel-ders-teslim/{submissionId}/indir', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'downloadSubmission'])->name('private-lessons.submission.download');
        Route::get('/ozel-ders-seans/{id}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'showSession'])->name('private-lessons.session.show');
        Route::get('/kurs/{courseId}/teslimler', [App\Http\Controllers\Teacher\TeacherController::class, 'loadStudentSubmissions'])->name('course.submissions.load');
        Route::post('/ozel-ders/{id}/tamamla', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'completeLesson'])->name('private-lessons.complete');
        Route::post('/private-lessons/{lessonId}/toggle-active', [TeacherPrivateLessonController::class, 'toggleLessonActive'])->name('private-lessons.toggleActive');
        Route::post('/kurs/{courseId}/duyuru', [App\Http\Controllers\Teacher\TeacherController::class, 'createAnnouncement'])->name('course.create-announcement');
        Route::post('/kurs/{courseId}/odev', [App\Http\Controllers\Teacher\TeacherController::class, 'createHomework'])->name('course.create-homework');
        Route::get('/fetch-homeworks', [App\Http\Controllers\Teacher\TeacherController::class, 'fetchHomeworks'])->name('fetch-homeworks');
        Route::get('/teslim/{id}', [App\Http\Controllers\Teacher\TeacherController::class, 'viewSubmission'])->name('submission.view');
        Route::get('/teslim/{id}/degerlendir', [App\Http\Controllers\Teacher\TeacherController::class, 'evaluateSubmission'])->name('submission.evaluate');
        Route::post('/teslim/{id}/degerlendir', [App\Http\Controllers\Teacher\TeacherController::class, 'saveEvaluation'])->name('submission.save-evaluation');
        Route::get('/ozel-ders/{id}/materyal-ekle', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'showAddMaterial'])->name('private-lessons.material.create');
        Route::post('/ozel-ders/{id}/materyal-ekle', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'storeMaterial'])->name('private-lessons.material.store');
        Route::delete('/ozel-ders-materyal/{materialId}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'deleteMaterial'])->name('private-lessons.material.delete');
        Route::get('/ozel-ders-materyal/{id}/indir', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'downloadMaterial'])->name('private-lessons.material.download');
        Route::get('/ozel-derslerim', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'index'])->name('private-lessons.index');
        Route::get('/ozel-ders-talep', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'pendingRequests'])->name('private-lessons.pendingRequests');
        Route::get('/ozel-ders/olustur', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'create'])->name('private-lessons.create');
        Route::post('/ozel-ders/kaydet', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'store'])->name('private-lessons.store');
        Route::get('/ozel-ders-grup/{id}/seans-ekle', [TeacherPrivateLessonController::class, 'showAddSession'])->name('private-lessons.showAddSession');
        Route::post('/ozel-ders-grup/{id}/seans-ekle', [TeacherPrivateLessonController::class, 'storeNewSession'])->name('private-lessons.storeNewSession');
        Route::get('/ozel-ders/{id}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'show'])->name('private-lessons.show');
        Route::get('/ozel-ders/{id}/duzenle', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'edit'])->name('private-lessons.edit');
        Route::put('/ozel-ders/{id}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'update'])->name('private-lessons.update');
        Route::delete('/ozel-ders/{id}', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'destroy'])->name('private-lessons.destroy');
        Route::post('/ozel-ders/{id}/onayla', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'approve'])->name('private-lessons.approve');
        Route::post('/ozel-ders/{id}/reddet', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'reject'])->name('private-lessons.reject');
        Route::post('/ozel-ders/{id}/odeme-al', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'takePayment'])->name('private-lessons.takePayment');
        Route::post('/ozel-ders/{id}/odeme-guncelle', [App\Http\Controllers\Teacher\TeacherPrivateLessonController::class, 'updatePaymentStatus'])->name('private-lessons.updatePaymentStatus');
    });
});

Route::middleware(['auth', 'role:ogrenci|ogretmen', 'verified.phone', 'teacher.approved'])->group(function () {
    Route::prefix('ogrenci')->name('ogrenci.')->group(function () {
        Route::get('/word-match-game', function () {
            return view('game');
        })->name('word-match-game');
        Route::get('/ayarlar', [App\Http\Controllers\Student\StudentSettingsController::class, 'index'])->name('settings.index');
        Route::post('/ayarlar/profil', [App\Http\Controllers\Student\StudentSettingsController::class, 'updateProfile'])->name('settings.update-profile');
        Route::post('/ayarlar/sifre', [App\Http\Controllers\Student\StudentSettingsController::class, 'updatePassword'])->name('settings.update-password');
        Route::get('/belgeler', [App\Http\Controllers\Student\StudentDocumentController::class, 'listAllDocuments'])->name('documents.list');
        Route::get('/kurslarim/{slug}/belgeler', [App\Http\Controllers\Student\StudentDocumentController::class, 'index'])->name('documents.index');
        Route::get('/kurslarim/{slug}/belge/{documentId}/indir', [App\Http\Controllers\Student\StudentDocumentController::class, 'download'])->name('documents.download');
        Route::get('/test/{slug}', [TestController::class, 'show'])->name('tests.show');
        Route::get('/test/{slug}/baslat', [TestController::class, 'start'])->name('tests.start');
        Route::post('/test/{slug}/gonder', [TestController::class, 'submit'])->name('tests.submit');
        Route::get('/sonuc/{id}', [TestController::class, 'result'])->name('tests.result');
        Route::get('/gecmis', [TestController::class, 'history'])->name('tests.history');
        Route::get('/kurslarim', [App\Http\Controllers\Student\StudentCourseController::class, 'index'])->name('kurslarim');
        Route::get('/ozel-derslerim', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'index'])->name('private-lessons.index');
        Route::get('/tamamlanan-dersler', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'completed'])->name('private-lessons.completed');
        Route::get('/ozel-ders/{id}', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'showLesson'])->name('private-lessons.lesson');
        Route::delete('/odev-teslim-dosya/{id}/sil', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'deleteSubmissionFile'])->name('private-lessons.submission-file.delete');
        Route::get('/ozel-ders-seans/{id}', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'showSession'])->name('private-lessons.session');
        Route::get('/test/{slug}/pdf-indir', [TestController::class, 'downloadTestPdf'])->name('tests.download-pdf');
        Route::get('/sonuc/{id}/pdf-indir', [TestController::class, 'downloadResultPdf'])->name('test-results.download-pdf');
        Route::get('/gecmis/pdf-indir', [TestController::class, 'downloadHistoryPdf'])->name('tests.history.download-pdf');
        Route::get('/ders-materyalleri', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'materials'])->name('private-lessons.materials');
        Route::get('/materyal/{id}/indir', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'downloadMaterial'])->name('private-lessons.material.download');
        Route::get('/odevlerim', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'homeworks'])->name('private-lessons.homeworks');
        Route::get('/odev/{id}', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'showHomework'])->name('private-lessons.homework');
        Route::get('/odev-teslim-dosya/{id}/indir', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'downloadSubmission'])->name('private-lessons.submission-file.download');
        Route::post('/odev/{id}/teslim-et', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'submitHomework'])->name('private-lessons.homework.submit');
        Route::get('/odev-teslim/{id}/indir', [App\Http\Controllers\Student\StudentPrivateLessonController::class, 'downloadSubmission'])->name('private-lessons.submission.download');
        Route::get('/kurslarim/{slug}', [App\Http\Controllers\Student\StudentCourseController::class, 'showCourseDetail'])->name('kurs-detay');
        Route::post('/kurslarim/{slug}/odev-yukle/{homeworkId}', [App\Http\Controllers\Student\StudentCourseController::class, 'submitHomework'])->name('odev-yukle');
    });
});

Route::get('/export/leaderboard', [App\Http\Controllers\LeaderboardExportController::class, 'export'])->name('export.leaderboard');

Route::middleware(['auth', 'verified.phone', 'teacher.approved'])->group(function () {
    Route::get('/profil', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profil/duzenle', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil/guncelle', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profil/kurslarim', [App\Http\Controllers\ProfileController::class, 'courses'])->name('profile.courses');
    Route::get('/profil/sertifikalarim', [App\Http\Controllers\ProfileController::class, 'certificates'])->name('profile.certificates');
});