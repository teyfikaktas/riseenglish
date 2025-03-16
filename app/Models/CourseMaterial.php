<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'type_id',
        'file_path',
        'external_link',
        'is_required',
        'order'
    ];
    
    protected $casts = [
        'is_required' => 'boolean'
    ];
    
    // Ait olduğu kurs
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    // Materyal tipi
    public function type()
    {
        return $this->belongsTo(MaterialType::class, 'type_id');
    }
}