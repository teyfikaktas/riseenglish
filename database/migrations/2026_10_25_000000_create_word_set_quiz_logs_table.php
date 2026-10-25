<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('word_set_quiz_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('word_set_id')->constrained('word_sets')->cascadeOnDelete();
            $table->integer('score');
            $table->integer('total_questions');
            $table->decimal('success_rate', 8, 2);
            $table->integer('time_spent')->nullable();
            $table->json('answers')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('word_set_quiz_logs');
    }
};