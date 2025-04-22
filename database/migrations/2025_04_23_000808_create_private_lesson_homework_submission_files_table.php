<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivateLessonHomeworkSubmissionFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('private_lesson_homework_submission_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('private_lesson_homework_submissions')->onDelete('cascade');
            $table->string('file_path');
            $table->string('original_filename');
            $table->timestamp('submission_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('private_lesson_homework_submission_files');
    }
}