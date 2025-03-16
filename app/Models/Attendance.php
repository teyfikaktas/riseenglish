<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'session_id',
        'user_id',
        'is_present',
        'notes'
    ];
    
    protected $casts = [
        'is_present' => 'boolean'
    ];
    
    // Ait olduğu oturum
    public function session()
    {
        return $this->belongsTo(CourseSession::class, 'session_id');
    }
    
    // Katılımın ait olduğu öğrenci
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}