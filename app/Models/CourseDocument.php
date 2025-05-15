<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'uploaded_by',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'is_active',
        'students_can_download',
    ];

    /**
     * Belgenin ait olduğu kurs
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Belgeyi yükleyen kullanıcı
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
    
    /**
     * Belge boyutunu formatlı olarak döndürür (KB, MB cinsinden)
     */
    public function getFormattedSizeAttribute()
    {
        if ($this->file_size < 1024) {
            return $this->file_size . ' KB';
        } else {
            return round($this->file_size / 1024, 2) . ' MB';
        }
    }
}