<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'teacher_id',
        'description',
        'objectives',
        'level_id',
        'type_id',
        'frequency_id',
        'total_hours',
        'max_students',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'meeting_link',
        'meeting_password',
        'location',
        'price',
        'is_active',
        'has_certificate',
        'thumbnail',
        'category_id', // Yeni eklenen kategori ID'si
        'is_featured', // Yeni eklenen kolon
        'display_order', // Yeni eklenen gösterme sırası
        'discount_price', // Yeni eklenen indirimli fiyat


    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'has_certificate' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2', // Yeni eklenen indirimli fiyat
    ];
/**
 * Kursa ait belgeler
 */
public function documents()
{
    return $this->hasMany(CourseDocument::class);
}
    // Kursu veren öğretmen
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    
    // Kursa kayıtlı öğrenciler
// Course.php modelinde students metodunu güncelle
public function students()
{
    return $this->belongsToMany(User::class, 'course_user')
                ->using(CourseUser::class)
                ->withPivot([
                    'enrollment_date', 
                    'status_id', 
                    'paid_amount', 
                    'payment_completed', 
                    'completion_date', 
                    'final_grade', 
                    'notes',
                    'approval_status'
                ])
                ->withTimestamps();
}
    
    // Kurs seviyesi
    public function level()
    {
        return $this->belongsTo(CourseLevel::class, 'level_id');
    }
    
    // Kurs tipi
    public function type()
    {
        return $this->belongsTo(CourseType::class, 'type_id');
    }
    
    // 'courseType' özel ilişkisi - 'type' ile aynı işlev ama farklı isim
    public function courseType()
    {
        return $this->belongsTo(CourseType::class, 'type_id');
    }
    
    // Kurs frekansı
    public function frequency()
    {
        return $this->belongsTo(CourseFrequency::class, 'frequency_id');
    }
    
    // 'courseFrequency' özel ilişkisi - 'frequency' ile aynı işlev ama farklı isim
    public function courseFrequency()
    {
        return $this->belongsTo(CourseFrequency::class, 'frequency_id');
    }
    
    // Kurs seviyesi için 'courseLevel' alternatif ilişkisi
    public function courseLevel()
    {
        return $this->belongsTo(CourseLevel::class, 'level_id');
    }
    
    // Kurs materyalleri
    public function materials()
    {
        return $this->hasMany(CourseMaterial::class);
    }
    
    // Kurs oturumları
    public function sessions()
    {
        return $this->hasMany(CourseSession::class);
    }
    
    // Kurs değerlendirmeleri
    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }
    
    // Kurs yorumları
    public function reviews()
    {
        return $this->hasMany(CourseReview::class);
    }
    
    // Kursa katılım bilgileri
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    
    // Kurs durumu - Eğer aktif/pasif durumunu ayrı bir tablodan alıyorsanız
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    /**
 * İndirim yüzdesini hesapla
 */
public function getDiscountPercentageAttribute()
{
    if ($this->discount_price && $this->price > 0) {
        $discountPercentage = (($this->price - $this->discount_price) / $this->price) * 100;
        return round($discountPercentage);
    }
    
    return 0;
}
// app/Models/Course.php
public function announcements()
{
    return $this->hasMany(Announcement::class);
}
public function homeworks()
{
    return $this->hasMany(Homework::class);
}
}