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
        Schema::create('homework_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homework_id')->constrained('homeworks')->onDelete('cascade');
            // NOT: Burada 'homeworks' tablosuna referans veriliyor
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('file_path')->nullable();
            $table->text('comment')->nullable();
            $table->dateTime('submitted_at');
            $table->integer('score')->nullable();
            $table->text('feedback')->nullable();
            $table->dateTime('graded_at')->nullable();
            $table->enum('status', ['pending', 'graded', 'late', 'rejected'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
            
            // Bir öğrenci bir ödevi bir kez gönderebilir (ancak güncelleme yapabilir)
            $table->unique(['homework_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homework_submissions');
    }
};