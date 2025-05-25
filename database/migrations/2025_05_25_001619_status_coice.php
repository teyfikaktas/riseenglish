<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_test_results', function (Blueprint $table) {
            // Eğer status sütunu enum ise, text'e çevir
            $table->string('status', 20)->change();
        });
    }

    public function down()
    {
        Schema::table('user_test_results', function (Blueprint $table) {
            $table->enum('status', ['started', 'completed', 'abandoned'])->change();
        });
    }
};