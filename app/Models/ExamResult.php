<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'exam_id',
        'student_id',
        'score',
        'total_questions',
        'time_spent',
        'success_rate',
        'answers',
        'completed_at',
        'entered_at',
        'sms_sent',
        'violation',
        'violation_reason',
    ];
    
    protected $casts = [
        'answers' => 'array',
        'completed_at' => 'datetime',
        'entered_at' => 'datetime',
        'success_rate' => 'decimal:2',
        'sms_sent' => 'boolean',
        'violation' => 'boolean',
    ];
    
    // İlişkiler
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    // Başarı durumu
    public function isPassed()
    {
        return $this->success_rate >= 60;
    }
    
    // Doğru cevap sayısı
    public function getCorrectAnswersCount()
    {
        return collect($this->answers)->where('is_correct', true)->count();
    }
    
    // Yanlış cevap sayısı
    public function getWrongAnswersCount()
    {
        return collect($this->answers)->where('is_correct', false)->count();
    }
    
    // Başarı oranı hesapla
    public function calculateSuccessRate()
    {
        if ($this->total_questions == 0) {
            return 0;
        }
        return round(($this->getCorrectAnswersCount() / $this->total_questions) * 100);
    }
    
    // ✅ YENİ: Sıralı sonuçları getir
    public static function getRankedResults($examId)
    {
        $results = self::where('exam_id', $examId)
            ->whereNotNull('completed_at')
            ->with('student')
            ->get();
        
        // Her sonuç için skorları hesapla
        $rankedResults = $results->map(function($result) {
            return [
                'result' => $result,
                'correctCount' => $result->getCorrectAnswersCount(),
                'wrongCount' => $result->getWrongAnswersCount(),
                'successRate' => $result->calculateSuccessRate()
            ];
        });
        
        // Sırala: Önce başarı oranı, sonra doğru sayısı
        return $rankedResults->sortByDesc(function($item) {
            return [$item['successRate'], $item['correctCount']];
        })->values(); // Index'leri sıfırla (0, 1, 2, 3...)
    }
    
    // ✅ YENİ: Sıra badge'ini al
    public function getRankBadge($rank)
    {
        $badges = [
            0 => ['class' => 'rank-1', 'text' => 'GÜNÜN BİRİNCİSİ'],
            1 => ['class' => 'rank-2', 'text' => 'GÜNÜN İKİNCİSİ'],
            2 => ['class' => 'rank-3', 'text' => 'GÜNÜN ÜÇÜNCÜSÜ'],
        ];
        
        return $badges[$rank] ?? ['class' => '', 'text' => ''];
    }
}