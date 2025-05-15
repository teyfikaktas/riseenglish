<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

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
        'is_public',
        'public_token',
        'public_url',
    ];

    /**
     * Model boot metodu
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($document) {
            if ($document->is_public) {
                // Belge oluşturulurken public_token oluştur
                if (empty($document->public_token)) {
                    $document->public_token = Str::random(64);
                }
                
                // Public URL oluştur
                $document->public_url = route('public.document.show', $document->public_token);
            }
        });
        
        static::updating(function ($document) {
            // Public değeri değiştiyse
            if ($document->isDirty('is_public')) {
                if ($document->is_public) {
                    // Belge public olduğunda token oluştur
                    if (empty($document->public_token)) {
                        $document->public_token = Str::random(64);
                    }
                    
                    // Public URL oluştur
                    $document->public_url = route('public.document.show', $document->public_token);
                } else {
                    // Belge artık public değilse token ve URL'i temizle
                    $document->public_token = null;
                    $document->public_url = null;
                }
            }
        });
    }

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
    
    /**
     * Belgenin herkese açık bir URL'si olup olmadığını kontrol eder
     */
    public function hasPublicUrl()
    {
        return $this->is_public && !empty($this->public_token) && !empty($this->public_url);
    }
}