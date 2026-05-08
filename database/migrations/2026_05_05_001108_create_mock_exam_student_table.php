<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mock_exam_id')->constrained('mock_exams')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();

            // ExamResult ile birebir aynı kolonlar
            $table->integer('score')->default(0);
            $table->integer('total_questions')->default(0);
            $table->integer('time_spent')->default(0); // saniye
            $table->decimal('success_rate', 5, 2)->default(0);
            $table->json('answers')->nullable();

            $table->dateTime('entered_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            $table->boolean('sms_sent')->default(false);
            $table->boolean('violation')->default(false);
            $table->text('violation_reason')->nullable();

            $table->timestamps();

            // Aynı öğrenci aynı denemeye 1 kere girer
            $table->unique(['mock_exam_id', 'student_id']);
            $table->index('student_id');
            $table->index('completed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_exam_results');
    }
};