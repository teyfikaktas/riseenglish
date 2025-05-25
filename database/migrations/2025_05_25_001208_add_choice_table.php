<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->string('choice_letter', 1); // A, B, C, D, E
            $table->text('choice_text');
            $table->boolean('is_correct')->default(false);
            $table->text('explanation')->nullable();
            $table->integer('order_number')->default(0);
            $table->timestamps();

            $table->index(['question_id', 'order_number']);
            $table->index(['question_id', 'is_correct']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('choices');
    }
};