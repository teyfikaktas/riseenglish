<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Migration içinde direkt veri ekleme
        $subjects = [
            'Matematik',
            'Fizik',
            'Kimya',
            'Biyoloji',
            'Türkçe',
            'İngilizce',
            'Tarih'
        ];

        foreach ($subjects as $subject) {
            DB::table('subjects')->insert([
                'name' => $subject,
                'slug' => Str::slug($subject),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};