<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('teacher_approved')->default(false)->after('phone_verified');
            $table->timestamp('approved_at')->nullable()->after('teacher_approved');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['teacher_approved', 'approved_at', 'approved_by']);
        });
    }
};