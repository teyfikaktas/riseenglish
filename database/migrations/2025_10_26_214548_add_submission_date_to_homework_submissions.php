<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('private_lesson_homework_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('private_lesson_homework_submissions', 'submission_date')) {
                $table->timestamp('submission_date')->nullable()->after('student_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('private_lesson_homework_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('private_lesson_homework_submissions', 'submission_date')) {
                $table->dropColumn('submission_date');
            }
        });
    }
};