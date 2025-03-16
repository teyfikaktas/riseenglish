<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentStatus extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean'
    ];
    
    // Bu durumda olan kayıtlar
    public function enrollments()
    {
        return $this->hasMany(CourseUser::class, 'status_id');
    }
}