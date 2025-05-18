<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconGenderToChainProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chain_progress', function (Blueprint $table) {
            $table->string('icon_gender', 10)->nullable()->after('last_completed_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chain_progress', function (Blueprint $table) {
            $table->dropColumn('icon_gender');
        });
    }
}