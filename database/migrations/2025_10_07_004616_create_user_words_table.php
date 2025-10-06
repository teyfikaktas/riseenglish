<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_words', function (Blueprint $table) {
            $table->id();
            $table->foreignId('word_set_id')->constrained('word_sets')->onDelete('cascade');
            $table->string('english_word');
            $table->string('turkish_meaning');
            $table->string('word_type')->nullable(); // noun, verb, adjective, etc.
            $table->timestamps();

            // Aynı sette aynı kelime tekrar eklenmemeli
            $table->unique(['word_set_id', 'english_word']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_words');
    }
};