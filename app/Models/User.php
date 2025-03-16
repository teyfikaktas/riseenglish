<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
// Öğretmen olarak verdiği kurslar
    public function teachingCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // Öğrenci olarak katıldığı kurslar
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
                ->withPivot('enrollment_date', 'status_id', 'paid_amount', 'payment_completed', 'completion_date', 'final_grade', 'notes')
                ->withTimestamps();
    }

    // Kullanıcının katılımları
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    // Kullanıcının değerlendirme sonuçları
    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class, 'user_id');
    }

    // Kullanıcının yaptığı kurs değerlendirmeleri
    public function courseReviews()
    {
        return $this->hasMany(CourseReview::class, 'user_id');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}