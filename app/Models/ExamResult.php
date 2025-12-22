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
    
// ✅ YENİ: Sıralı sonuçları getir (eşit puanlarda aynı sıra)
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
    $sorted = $rankedResults->sortByDesc(function($item) {
        return [$item['successRate'], $item['correctCount']];
    })->values();
    
    // Sıralama ekle (eşit puanlarda aynı sıra)
    $currentRank = 0;
    $previousRate = null;
    $previousCorrect = null;
    $skipCount = 0;
    
    foreach($sorted as $index => $item) {
        // Eğer puan farklıysa sırayı güncelle
        if ($previousRate !== $item['successRate'] || $previousCorrect !== $item['correctCount']) {
            $currentRank = $index + 1 + $skipCount;
            $skipCount = 0;
        } else {
            // Aynı puan, aynı sıra
            $skipCount++;
        }
        
        $sorted[$index]['rank'] = $currentRank;
        $previousRate = $item['successRate'];
        $previousCorrect = $item['correctCount'];
    }
    
    return $sorted;
}

// ✅ GÜNCELLE: Sıra badge'ini al (rank parametresi alacak)
public function getRankBadge($rank)
{
    $badges = [
        1 => ['class' => 'rank-1', 'text' => 'GÜNÜN BİRİNCİSİ'],
        2 => ['class' => 'rank-2', 'text' => 'GÜNÜN İKİNCİSİ'],
        3 => ['class' => 'rank-3', 'text' => 'GÜNÜN ÜÇÜNCÜSÜ'],
    ];
    
    return $badges[$rank] ?? ['class' => '', 'text' => ''];
}
}