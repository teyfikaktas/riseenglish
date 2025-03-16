<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseUser extends Pivot
{
    protected $table = 'course_user';
    
    public $incrementing = true;
    
    protected $fillable = [
        'course_id',
        'user_id',
        'enrollment_date',
        'status_id',
        'paid_amount',
        'payment_completed',
        'completion_date',
        'final_grade',
        'notes',
        'approval_status'
    ];
    
    protected $casts = [
        'enrollment_date' => 'datetime',
        'payment_completed' => 'boolean',
        'completion_date' => 'datetime',
        'approval_status' => 'boolean'
    ];
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function status()
    {
        return $this->belongsTo(EnrollmentStatus::class, 'status_id');
    }
}