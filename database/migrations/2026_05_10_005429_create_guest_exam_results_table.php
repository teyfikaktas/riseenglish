<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mock_exam_id')->constrained('mock_exams')->cascadeOnDelete();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->integer('score')->default(0);
            $table->integer('total_questions')->default(0);
            $table->decimal('success_rate', 5, 2)->default(0);
            $table->json('answers')->nullable();
            $table->boolean('violation')->default(false);
            $table->string('violation_reason')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['mock_exam_id', 'phone']);
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_exam_results');
    }
};