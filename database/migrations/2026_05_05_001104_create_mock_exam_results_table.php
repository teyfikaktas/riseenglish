<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_exam_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mock_exam_id')->constrained('mock_exams')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();

            // Exam pivot ile birebir
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->integer('score')->default(0);
            $table->json('answers')->nullable();

            $table->timestamps();

            $table->unique(['mock_exam_id', 'student_id']);
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_exam_student');
    }
};