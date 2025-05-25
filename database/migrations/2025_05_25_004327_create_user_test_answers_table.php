<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_create_user_test_answers_table.php
public function up()
{
    Schema::create('user_test_answers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_test_result_id')->constrained()->onDelete('cascade');
        $table->foreignId('question_id')->constrained()->onDelete('cascade');
        $table->foreignId('selected_choice_id')->constrained('choices')->onDelete('cascade');
        $table->boolean('is_correct')->default(false);
        $table->integer('points_earned')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_test_answers');
    }
};
