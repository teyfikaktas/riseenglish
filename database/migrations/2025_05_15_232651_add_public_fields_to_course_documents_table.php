<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublicFieldsToCourseDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_documents', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('students_can_download');
            $table->string('public_token', 64)->nullable()->unique()->after('is_public');
            $table->string('public_url')->nullable()->after('public_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_documents', function (Blueprint $table) {
            $table->dropColumn(['is_public', 'public_token', 'public_url']);
        });
    }
}