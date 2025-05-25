<?php
// database/migrations/2024_01_01_000001_create_test_system_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Test Kategorileri Tablosu
        Schema::create('test_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // YDT, YÖKDİL, Zamanlar vs.
            $table->string('slug')->unique(); // ydt, yokdil, zamanlar
            $table->text('description')->nullable(); // Kategori açıklaması
            $table->string('icon')->nullable(); // Emoji veya icon class
            $table->string('difficulty_level')->nullable(); // Kolay, Orta, Zor
            $table->string('color')->default('#1a2e5a'); // Kategori rengi
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0); // Sıralama
            $table->timestamps();
        });

        // Testler Tablosu
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Test başlığı
            $table->string('slug')->unique(); // test-1, ydt-deneme-1
            $table->text('description')->nullable(); // Test açıklaması
            $table->integer('duration_minutes')->nullable(); // Test süresi (dakika)
            $table->string('difficulty_level')->nullable(); // Kolay, Orta, Zor
            $table->integer('question_count')->default(0); // Soru sayısı
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // Öne çıkan test
            $table->integer('sort_order')->default(0); // Sıralama
            $table->timestamps();
        });

        // Sorular Tablosu
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text'); // Soru metni
            $table->enum('question_type', ['multiple_choice', 'true_false', 'fill_blank', 'matching'])->default('multiple_choice');
            $table->json('options')->nullable(); // Şıklar (JSON formatında)
            $table->string('correct_answer'); // Doğru cevap
            $table->text('explanation')->nullable(); // Açıklama
            $table->string('difficulty_level')->nullable(); // Kolay, Orta, Zor
            $table->integer('points')->default(1); // Puan değeri
            $table->string('image_path')->nullable(); // Soru görseli
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Soru - Test Kategorileri İlişki Tablosu (Many-to-Many)
        Schema::create('question_test_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Aynı soru aynı kategoriye birden fazla kez eklenmesin
            $table->unique(['question_id', 'test_category_id'], 'question_category_unique');
        });

        // Soru - Test İlişki Tablosu (Many-to-Many)
        Schema::create('question_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->integer('order_number')->default(1); // Testteki soru sırası
            $table->timestamps();
            
            // Aynı soru aynı teste birden fazla kez eklenmesin
            $table->unique(['question_id', 'test_id'], 'question_test_unique');
        });

        // Test Kategorisi - Test İlişki Tablosu (Many-to-Many)
        Schema::create('test_category_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Aynı test aynı kategoriye birden fazla kez eklenmesin
            $table->unique(['test_category_id', 'test_id'], 'category_test_unique');
        });

        // Kullanıcı Test Sonuçları Tablosu
        Schema::create('user_test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->integer('score')->default(0); // Aldığı puan
            $table->integer('total_questions'); // Toplam soru sayısı
            $table->integer('correct_answers')->default(0); // Doğru cevap sayısı
            $table->integer('wrong_answers')->default(0); // Yanlış cevap sayısı
            $table->integer('empty_answers')->default(0); // Boş cevap sayısı
            $table->decimal('percentage', 5, 2)->default(0); // Yüzde başarı
            $table->integer('duration_seconds')->nullable(); // Testi bitirme süresi
            $table->timestamp('started_at')->nullable(); // Teste başlama zamanı
            $table->timestamp('completed_at')->nullable(); // Testi bitirme zamanı
            $table->enum('status', ['started', 'completed', 'abandoned'])->default('started');
            $table->json('answers')->nullable(); // Verilen cevaplar (JSON)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_test_results');
        Schema::dropIfExists('test_category_tests');
        Schema::dropIfExists('question_tests');
        Schema::dropIfExists('question_test_categories');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('tests');
        Schema::dropIfExists('test_categories');
    }
};