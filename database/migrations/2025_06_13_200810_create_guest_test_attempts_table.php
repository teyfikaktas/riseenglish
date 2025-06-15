<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('guest_test_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->string('session_id', 255);
            $table->ipAddress('ip_address');
            $table->text('user_agent')->nullable();
            
            // Test bilgileri
            $table->integer('total_questions')->default(0);
            $table->integer('score')->default(0);
            $table->integer('correct_answers')->default(0);
            $table->integer('wrong_answers')->default(0);
            $table->integer('empty_answers')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->integer('duration_seconds')->default(0);
            
            // Test durumu
            $table->enum('status', [
                'started', 
                'completed', 
                'terminated_security', 
                'abandoned'
            ])->default('started');
            
            // Zaman bilgileri
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('terminated_at')->nullable();
            $table->string('termination_reason')->nullable();
            
            // Güvenlik
            $table->integer('security_violations')->default(0);
            $table->json('violation_details')->nullable();
            
            // Cevaplar
            $table->json('answers')->nullable();
            
            $table->timestamps();
            
            // İndeksler
            $table->index(['ip_address', 'created_at']);
            $table->index(['session_id', 'test_id']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('guest_test_attempts');
    }
};