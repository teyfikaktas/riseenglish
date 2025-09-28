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
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->string('word')->index(); // Kelime (İngilizce/Almanca)
            $table->text('definition'); // Türkçe tanım/açıklama
            $table->enum('lang', ['en', 'de'])->index(); // Kelimenin dili (en: İngilizce, de: Almanca)
            $table->string('category')->nullable()->index(); // Kategori (isteğe bağlı)
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->nullable(); // Zorluk seviyesi
            $table->boolean('is_active')->default(true)->index(); // Aktif/pasif
            $table->timestamps();
            
            // İndeksler
            $table->index(['lang', 'is_active']);
            $table->index(['category', 'lang']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('words');
    }
};