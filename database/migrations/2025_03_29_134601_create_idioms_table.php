<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('idioms', function (Blueprint $table) {
            $table->id();
            $table->string('english_phrase');
            $table->string('turkish_translation');
            $table->text('example_sentence_1');
            $table->text('example_sentence_2')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('display_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('idioms');
    }
};