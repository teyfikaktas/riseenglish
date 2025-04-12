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
        Schema::create('downloaders', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('email');
            $table->string('phone');
            $table->boolean('is_subscribed')->default(false);
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            // Email ve telefon için indeks oluştur
            $table->index('email');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloaders');
    }
};