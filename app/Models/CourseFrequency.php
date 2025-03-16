<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseFrequency extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'sessions_per_week',
        'is_active'
    ];
    
    protected $casts = [
        'sessions_per_week' => 'float',
        'is_active' => 'boolean'
    ];
    
    // Bu frekansa ait kurslar
    public function courses()
    {
        return $this->hasMany(Course::class, 'frequency_id');
    }
}