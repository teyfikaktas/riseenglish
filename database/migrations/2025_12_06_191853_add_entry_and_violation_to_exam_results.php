<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->timestamp('entered_at')->nullable()->after('created_at');
            $table->boolean('sms_sent')->default(false)->after('entered_at');
            $table->boolean('violation')->default(false)->after('completed_at');
            $table->text('violation_reason')->nullable()->after('violation');
        });
    }

    public function down()
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropColumn(['entered_at', 'sms_sent', 'violation', 'violation_reason']);
        });
    }
};