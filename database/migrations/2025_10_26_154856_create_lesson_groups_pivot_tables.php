<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. private_lessons tablosuna group_id ekle
        Schema::table('private_lessons', function (Blueprint $table) {
            $table->string('group_id', 50)->nullable()->after('id');
            $table->index('group_id');
        });

        // 2. private_lesson_sessions tablosuna group_id ekle
        Schema::table('private_lesson_sessions', function (Blueprint $table) {
            $table->string('group_id', 50)->nullable()->after('private_lesson_id');
            $table->index('group_id');
        });

        // 3. SADECE GRUP - ÖĞRENCİ PIVOT TABLOSU
        Schema::create('lesson_group_students', function (Blueprint $table) {
            $table->id();
            $table->string('group_id', 50);
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['group_id', 'student_id']);
            $table->index('group_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_group_students');
        
        Schema::table('private_lesson_sessions', function (Blueprint $table) {
            $table->dropIndex(['group_id']);
            $table->dropColumn('group_id');
        });
        
        Schema::table('private_lessons', function (Blueprint $table) {
            $table->dropIndex(['group_id']);
            $table->dropColumn('group_id');
        });
    }
};