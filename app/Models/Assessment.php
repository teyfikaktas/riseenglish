<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'type_id',
        'weight',
        'due_date'
    ];
    
    protected $casts = [
        'weight' => 'decimal:2',
        'due_date' => 'date'
    ];
    
    // Ait olduğu kurs
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    // Değerlendirme tipi
    public function type()
    {
        return $this->belongsTo(AssessmentType::class, 'type_id');
    }
    
    // Değerlendirme sonuçları
    public function results()
    {
        return $this->hasMany(AssessmentResult::class);
    }
    
    // Değerlendirmeye katılan öğrenciler
    public function students()
    {
        return $this->belongsToMany(User::class, 'assessment_results', 'assessment_id', 'user_id')
                   ->withPivot('score', 'feedback')
                   ->withTimestamps();
    }
}