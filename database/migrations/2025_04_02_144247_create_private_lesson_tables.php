<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivateLessonTables extends Migration
{
    public function up()
    {
        // Ana özel ders tablosu
        Schema::create('private_lessons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('duration_minutes')->default(60);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Öğretmen uygunluk tablosu (haftalık bazda)
        Schema::create('private_lesson_teacher_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users');
            $table->tinyInteger('day_of_week'); // 1=Pazartesi, 2=Salı, vb.
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        // Özel ders talepleri ve programlanmış dersler
        Schema::create('private_lesson_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('private_lesson_id')->constrained();
            $table->foreignId('teacher_id')->constrained('users');
            $table->foreignId('student_id')->constrained('users');
            $table->tinyInteger('day_of_week'); // 1=Pazartesi, 2=Salı, vb.
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_recurring')->default(true); // Haftalık tekrar ediyor mu
            $table->date('start_date'); // Derslerin başlangıç tarihi
            $table->date('end_date')->nullable(); // Derslerin bitiş tarihi (null ise süresiz)
            $table->string('location')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Spesifik tarihli ders oturumları (tekrarlanan derslerden üretilir)
        Schema::create('private_lesson_occurrences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')
                  ->constrained('private_lesson_sessions')
                  ->onDelete('cascade');
            $table->date('lesson_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->text('teacher_notes')->nullable();
            $table->timestamps();
        });

        // Ders materyalleri
        Schema::create('private_lesson_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('occurrence_id')
                  ->constrained('private_lesson_occurrences')
                  ->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        // Ödevler
        Schema::create('private_lesson_homeworks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('occurrence_id')
                  ->constrained('private_lesson_occurrences')
                  ->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date');
            $table->timestamps();
        });

        // Ödev teslimi
        Schema::create('private_lesson_homework_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homework_id')
                  ->constrained('private_lesson_homeworks')
                  ->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users');
            $table->text('submission_content')->nullable();
            $table->string('file_path')->nullable();
            $table->text('teacher_feedback')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamps();
        });

        // Bildirimler (SMS dahil)
        Schema::create('private_lesson_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users');
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('occurrence_id')
                  ->nullable()
                  ->constrained('private_lesson_occurrences')
                  ->onDelete('set null');
            $table->string('message');
            $table->boolean('is_sms')->default(false);
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // Öğretmen rolleri
        Schema::create('private_lesson_teacher_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('can_teach_private')->default(true);
            $table->boolean('can_teach_group')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('private_lesson_notifications');
        Schema::dropIfExists('private_lesson_homework_submissions');
        Schema::dropIfExists('private_lesson_homeworks');
        Schema::dropIfExists('private_lesson_materials');
        Schema::dropIfExists('private_lesson_occurrences');
        Schema::dropIfExists('private_lesson_sessions');
        Schema::dropIfExists('private_lesson_teacher_availabilities');
        Schema::dropIfExists('private_lessons');
        Schema::dropIfExists('private_lesson_teacher_roles');
    }
}