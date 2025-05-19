<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChainProgress extends Model
{
    use HasFactory;
    protected $table = 'chain_progress'; // Migration'da kullandığınız tablo adı

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'days_completed',
        'current_streak',
        'longest_streak',
        'last_completed_at',
        'icon_gender', // Bu satırı ekleyin

    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_completed_at' => 'datetime',
    ];
    
    /**
     * Kullanıcı ilişkisi
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
 * Günlük çalışmalar
 */
public function activities()
{
    return $this->hasMany(ChainActivity::class);
}
    /**
     * Güncel seviyeyi hesapla
     */
    public function getCurrentLevel()
    {
        $days = $this->days_completed;
        
        if ($days >= 365) {
            return 'MASTER';
        } else if ($days >= 300) {
            return 'Elmas';
        } else if ($days >= 240) {
            return 'Zümrüt';
        } else if ($days >= 180) {
            return 'Platin';
        } else if ($days >= 90) {
            return 'Altın';
        } else if ($days >= 60) {
            return 'Gümüş';
        } else if ($days >= 30) {
            return 'Demir';
        } else {
            return 'Bronz';
        }
    }
    
    /**
     * Mevcut seviyenin rengini al
     */
    public function getLevelColor()
    {
        $level = $this->getCurrentLevel();
        
        $colors = [
            'Bronz' => '#CD7F32',
            'Demir' => '#71797E',
            'Gümüş' => '#C0C0C0',
            'Altın' => '#FFD700',
            'Platin' => '#E5E4E2',
            'Zümrüt' => '#50C878',
            'Elmas' => '#B9F2FF',
            'MASTER' => '#9370DB'
        ];
        
        return $colors[$level] ?? '#CD7F32';
    }
    
    /**
     * Bir sonraki seviyeye ne kadar kaldığını hesapla
     */
    public function getNextLevelProgress()
    {
        $days = $this->days_completed;
        $nextThreshold = 0;
        $currentThreshold = 0;
        
        if ($days < 30) {
            $nextThreshold = 30;
            $currentThreshold = 0;
        } else if ($days < 60) {
            $nextThreshold = 60;
            $currentThreshold = 30;
        } else if ($days < 90) {
            $nextThreshold = 90;
            $currentThreshold = 60;
        } else if ($days < 180) {
            $nextThreshold = 180;
            $currentThreshold = 90;
        } else if ($days < 240) {
            $nextThreshold = 240;
            $currentThreshold = 180;
        } else if ($days < 300) {
            $nextThreshold = 300;
            $currentThreshold = 240;
        } else if ($days < 365) {
            $nextThreshold = 365;
            $currentThreshold = 300;
        } else {
            // Zaten en üst seviyede
            return 100;
        }
        
        $progress = (($days - $currentThreshold) / ($nextThreshold - $currentThreshold)) * 100;
        return round($progress);
    }
}