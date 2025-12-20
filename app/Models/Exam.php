<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'name',
        'description',
        'start_time',
        'time_per_question', // Soru başı süre (saniye)
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'is_active' => 'boolean',
        'time_per_question' => 'integer',
    ];

    // Öğretmen ilişkisi
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Kelime setleri ilişkisi (many-to-many)
    public function wordSets()
    {
        return $this->belongsToMany(WordSet::class, 'exam_word_set')
            ->withTimestamps();
    }

    // Öğrenciler ilişkisi (many-to-many)
    public function students()
    {
        return $this->belongsToMany(User::class, 'exam_student', 'exam_id', 'student_id')
            ->withPivot('started_at', 'completed_at', 'score', 'answers')
            ->withTimestamps();
    }

    // ✅ YENİ - Sınav sonuçları ilişkisi
    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    // Sınavdaki tüm kelimeleri al (seçilen setlerden)
    public function getAllWords()
    {
        $words = collect();
        
        foreach ($this->wordSets as $wordSet) {
            $words = $words->merge($wordSet->words);
        }
        
        return $words->shuffle(); // Karıştırılmış olarak döndür
    }

    // Belirli sayıda soru oluştur
    public function generateQuestions($questionCount = null)
    {
        $allWords = $this->getAllWords();
        
        if ($allWords->isEmpty()) {
            return collect();
        }

        // Eğer soru sayısı belirtilmemişse, tüm kelimeleri kullan
        $count = $questionCount ?? $allWords->count();
        
        // Kelime sayısından fazla soru istenirse, maksimum kelime sayısı kadar al
        $count = min($count, $allWords->count());
        
        return $allWords->random($count);
    }

    // Toplam kelime sayısı
    public function getTotalWordCount()
    {
        return $this->getAllWords()->count();
    }

    // Toplam sınav süresi (saniye)
    public function getTotalDuration()
    {
        $questionCount = $this->getTotalWordCount();
        return $questionCount * $this->time_per_question;
    }
}