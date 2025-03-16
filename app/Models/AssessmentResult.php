<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentResult extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'assessment_id',
        'user_id',
        'score',
        'feedback'
    ];
    
    protected $casts = [
        'score' => 'decimal:2'
    ];
    
    // Ait olduğu değerlendirme
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
    
    // Sonucun ait olduğu öğrenci
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}