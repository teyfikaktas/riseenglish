<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_exams', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // RS-XXXXXXXX
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('word_set_id')->constrained('word_sets')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->integer('time_per_question')->default(20);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('code');
            $table->index('teacher_id');
            $table->index('start_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_exams');
    }
};