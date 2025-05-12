<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('chain_activities', function (Blueprint $table) {
            $table->boolean('is_adjustment')->default(false)->after('file_type');
        });
    }

    public function down()
    {
        Schema::table('chain_activities', function (Blueprint $table) {
            $table->dropColumn('is_adjustment');
        });
    }
};