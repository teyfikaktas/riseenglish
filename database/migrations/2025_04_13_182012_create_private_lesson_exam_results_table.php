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
        Schema::create('private_lesson_exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('private_lesson_reports')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->string('subject_name');
            $table->integer('questions_correct')->default(0);
            $table->integer('questions_wrong')->default(0);
            $table->integer('questions_unanswered')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_lesson_exam_results');
    }
};