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
        Schema::create('private_lesson_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('private_lesson_sessions')->onDelete('cascade');
            $table->integer('questions_solved')->default(0);
            $table->integer('questions_correct')->default(0);
            $table->integer('questions_wrong')->default(0);
            $table->integer('questions_unanswered')->default(0);
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();
            $table->text('participation')->nullable();
            $table->text('teacher_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_lesson_reports');
    }
};