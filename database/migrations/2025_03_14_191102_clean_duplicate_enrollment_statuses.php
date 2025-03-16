<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Her durumdan sadece en düşük ID'ye sahip olanları tut, diğerlerini sil
        // Önce durumları grupla
        $statuses = DB::table('enrollment_statuses')
            ->select('name')
            ->groupBy('name')
            ->get();
            
        // Her durum için tekrar eden kayıtları sil
        foreach ($statuses as $status) {
            // Her durum için en düşük ID'li kaydı bul
            $keepId = DB::table('enrollment_statuses')
                ->where('name', $status->name)
                ->min('id');
                
            // Bu duruma ait diğer kayıtları bul
            $duplicateIds = DB::table('enrollment_statuses')
                ->where('name', $status->name)
                ->where('id', '!=', $keepId)
                ->pluck('id');
                
            if (count($duplicateIds) > 0) {
                // Önce bu duplicate ID'lere referans veren course_user kayıtlarını güncelle
                DB::table('course_user')
                    ->whereIn('status_id', $duplicateIds)
                    ->update(['status_id' => $keepId]);
                    
                // Şimdi güvenle duplicate kayıtları silebiliriz
                DB::table('enrollment_statuses')
                    ->whereIn('id', $duplicateIds)
                    ->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // İşlemleri geri alacak bir şey yapmıyoruz
    }
};