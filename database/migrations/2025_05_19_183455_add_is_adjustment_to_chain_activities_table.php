<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('chain_activities', function (Illuminate\Database\Schema\Blueprint $table) {
        if (!Schema::hasColumn('chain_activities', 'is_adjustment')) {
            $table->boolean('is_adjustment')->default(false)->after('activity_date');
        }
    });
}

public function down()
{
    Schema::table('chain_activities', function (Illuminate\Database\Schema\Blueprint $table) {
        if (Schema::hasColumn('chain_activities', 'is_adjustment')) {
            $table->dropColumn('is_adjustment');
        }
    });
}

};
