<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->integer('time_spent')->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->integer('time_spent')->default(null)->change();
        });
    }
};