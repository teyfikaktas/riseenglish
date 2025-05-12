<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('private_lesson_homework_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('private_lesson_homework_submissions', 'is_latest')) {
                $table->boolean('is_latest')->default(false)->after('submission_content');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('private_lesson_homework_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('private_lesson_homework_submissions', 'is_latest')) {
                $table->dropColumn('is_latest');
            }
        });
    }
};