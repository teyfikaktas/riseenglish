<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSession extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'session_date',
        'start_time',
        'end_time',
        'is_completed',
        'homework',
        'meeting_link',
        'location'
    ];
    
    protected $casts = [
        'session_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_completed' => 'boolean'
    ];
    
    // Ait olduğu kurs
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    // Bu oturuma ait katılımlar
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'session_id');
    }
    
    // Oturuma katılan öğrenciler
    public function students()
    {
        return $this->belongsToMany(User::class, 'attendances', 'session_id', 'user_id')
                  ->withPivot('is_present', 'notes')
                  ->withTimestamps();
    }
}