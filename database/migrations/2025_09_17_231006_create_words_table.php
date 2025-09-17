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
            $table->foreignId('word_set_id')->constrained()->onDelete('cascade');
            $table->string('english_word'); // İngilizce kelime
            $table->string('turkish_meaning'); // Türkçe anlamı
            $table->text('example_sentence')->nullable(); // Örnek cümle (İngilizce)
            $table->text('example_sentence_turkish')->nullable(); // Örnek cümle (Türkçe)
            $table->string('word_type')->nullable(); // Kelime türü (noun, verb, adjective, etc.)
            $table->string('pronunciation')->nullable(); // Telaffuz
            $table->integer('difficulty_level')->default(1); // Zorluk seviyesi (1-5)
            $table->boolean('is_learned')->default(false); // Öğrenildi mi?
            $table->timestamp('last_practiced_at')->nullable(); // Son pratik tarihi
            $table->integer('correct_answers')->default(0); // Doğru cevap sayısı
            $table->integer('wrong_answers')->default(0); // Yanlış cevap sayısı
            $table->timestamps();
            
            // İndeksler
            $table->index(['word_set_id', 'is_learned']);
            $table->index('english_word');
            $table->index('difficulty_level');
            $table->index('last_practiced_at');
            
            // Unique constraint - aynı set içinde aynı kelime tekrar olmasın
            $table->unique(['word_set_id', 'english_word']);
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