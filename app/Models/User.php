<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'parent_phone_number',
        'parent_phone_number_2',
        'phone',
        'phone_verified',
        'phone_verified_at',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function teachingCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
                ->withPivot('enrollment_date', 'status_id', 'paid_amount', 'payment_completed', 'completion_date', 'final_grade', 'notes')
                ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    public function chainActivities()
    {
        return $this->hasMany(ChainActivity::class);
    }

    public function chainProgress()
    {
        return $this->hasOne(ChainProgress::class);
    }

    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class, 'user_id');
    }

    public function courseReviews()
    {
        return $this->hasMany(CourseReview::class, 'user_id');
    }

    public function wordSets()
    {
        return $this->hasMany(WordSet::class, 'user_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }

    public function privateLessonSessions()
    {
        return $this->hasMany(PrivateLessonSession::class, 'student_id');
    }

    public function teachingGroups()
    {
        return $this->hasMany(Group::class, 'teacher_id');
    }

    // Tüm membership kayıtları
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    // Aktif membership
    public function activeMembership()
    {
        return $this->hasOne(Membership::class)
                    ->where('is_active', true)
                    ->where('expires_at', '>', now())
                    ->latest('starts_at');
    }

    // Membership geçerli mi?
    public function hasMembership(): bool
    {
        return $this->activeMembership()->exists();
    }
}