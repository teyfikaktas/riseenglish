<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sınavlar tablosu
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->integer('time_per_question')->default(30); // Soru başı süre (saniye)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Sınav - Kelime Seti ilişkisi (pivot)
        Schema::create('exam_word_set', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('word_set_id')->constrained('word_sets')->onDelete('cascade');
            $table->timestamps();
        });

        // Sınav - Öğrenci ilişkisi (pivot)
        Schema::create('exam_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->integer('score')->nullable(); // 0-100 arası puan
            $table->json('answers')->nullable(); // Öğrencinin cevapları
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_student');
        Schema::dropIfExists('exam_word_set');
        Schema::dropIfExists('exams');
    }
};