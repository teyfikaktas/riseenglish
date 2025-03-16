<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseReview extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'course_id',
        'user_id',
        'rating',
        'comment',
        'is_anonymous',
        'is_approved'
    ];
    
    protected $casts = [
        'rating' => 'integer',
        'is_anonymous' => 'boolean',
        'is_approved' => 'boolean'
    ];
    
    // Ait olduğu kurs
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    // Değerlendirmeyi yapan kullanıcı
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}