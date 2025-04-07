<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
// Migration dosyasında:
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('parent_phone_number_2')->nullable()->after('parent_phone_number');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('parent_phone_number_2');
    });
}
};
