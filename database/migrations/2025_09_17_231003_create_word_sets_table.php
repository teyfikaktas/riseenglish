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
        Schema::create('word_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Set adı (örn: "İş Hayatı", "Günlük Kelimeler")
            $table->text('description')->nullable(); // Set açıklaması
            $table->string('color')->default('#3B82F6'); // Set rengi (hex kodu)
            $table->boolean('is_active')->default(true); // Aktif/pasif durumu
            $table->integer('word_count')->default(0); // Set içindeki kelime sayısı
            $table->timestamps();
            
            // İndeksler
            $table->index(['user_id', 'is_active']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('word_sets');
    }
};