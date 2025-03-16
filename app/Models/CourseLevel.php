<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLevel extends Model
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
    
    // Bu seviyeye ait kurslar
    public function courses()
    {
        return $this->hasMany(Course::class, 'level_id');
    }
}