<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kurs Tipleri (Online, Yüzyüze, Hibrit)
        Schema::create('course_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Kurs Frekansları (Günlük, Haftada 2 kez, vb.)
        Schema::create('course_frequencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('sessions_per_week');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Kurs Seviyeleri (A1, A2, B1, B2, C1, C2)
        Schema::create('course_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Değerlendirme Tipleri (Quiz, Midterm, Final, vb.)
        Schema::create('assessment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Materyal Tipleri (PDF, Video, Audio, vb.)
        Schema::create('material_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 6. Kayıt Durumları (Active, Completed, Dropped, Waiting)
        Schema::create('enrollment_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 7. Kurslar Tablosu (Ana tablo)
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('teacher_id')->constrained('users');
            $table->text('description')->nullable();
            $table->text('objectives')->nullable();
            $table->foreignId('level_id')->constrained('course_levels');
            $table->foreignId('type_id')->constrained('course_types');
            $table->foreignId('frequency_id')->constrained('course_frequencies');
            $table->integer('total_hours');
            $table->integer('max_students')->default(20);
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('meeting_link')->nullable();
            $table->string('meeting_password')->nullable();
            $table->string('location')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->boolean('has_certificate')->default(true);
            $table->string('thumbnail')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 8. Kurs-Kullanıcı İlişkisi (Kayıtlar)
        Schema::create('course_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('enrollment_date')->default(now());
            $table->foreignId('status_id')->constrained('enrollment_statuses');
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->boolean('payment_completed')->default(false);
            $table->date('completion_date')->nullable();
            $table->decimal('final_grade', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['course_id', 'user_id']);
        });

        // 9. Kurs Materyalleri
        Schema::create('course_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('type_id')->constrained('material_types');
            $table->string('file_path')->nullable();
            $table->string('external_link')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 10. Kurs Oturumları (Dersler)
        Schema::create('course_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_completed')->default(false);
            $table->text('homework')->nullable();
            $table->string('meeting_link')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });

        // 11. Derslere Katılım
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('course_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_present')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['session_id', 'user_id']);
        });

        // 12. Değerlendirmeler (Sınavlar, Ödevler)
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('type_id')->constrained('assessment_types');
            $table->decimal('weight', 5, 2); // Değerlendirmenin not ağırlığı (%)
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        // 13. Değerlendirme Sonuçları
        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->text('feedback')->nullable();
            $table->timestamps();
            $table->unique(['assessment_id', 'user_id']);
        });

        // 14. Kurs Değerlendirmeleri (Yorumlar)
        Schema::create('course_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            $table->unique(['course_id', 'user_id']);
        });
    }

    public function down(): void
    {
        // Tabloları ters sırada siliyoruz (ilişkili tablolar önce silinmeli)
        Schema::dropIfExists('course_reviews');
        Schema::dropIfExists('assessment_results');
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('course_sessions');
        Schema::dropIfExists('course_materials');
        Schema::dropIfExists('course_user');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('enrollment_statuses');
        Schema::dropIfExists('material_types');
        Schema::dropIfExists('assessment_types');
        Schema::dropIfExists('course_levels');
        Schema::dropIfExists('course_frequencies');
        Schema::dropIfExists('course_types');
    }
};