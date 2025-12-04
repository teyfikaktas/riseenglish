<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->integer('score'); // Doğru sayısı
            $table->integer('total_questions'); // Toplam soru sayısı
            $table->integer('time_spent'); // Toplam süre (saniye)
            $table->decimal('success_rate', 5, 2); // Başarı oranı (%)
            $table->json('answers'); // Detaylı cevaplar
            $table->timestamp('completed_at'); // Sınav tamamlanma zamanı
            $table->timestamps();

            // Bir öğrenci bir sınavı sadece bir kez yapabilir
            $table->unique(['exam_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};