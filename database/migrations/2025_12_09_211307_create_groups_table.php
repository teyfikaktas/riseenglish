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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Grup adı (örn: "A1 Grubu", "İleri Seviye 1")
            $table->text('description')->nullable(); // Grup açıklaması
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null'); // Grup öğretmeni
            $table->boolean('is_active')->default(true); // Aktif mi?
            $table->timestamps();
        });

        Schema::create('group_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('joined_at')->useCurrent(); // Gruba katılma tarihi
            $table->timestamps();
            
            // Bir öğrenci aynı gruba birden fazla eklenemez
            $table->unique(['group_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_user');
        Schema::dropIfExists('groups');
    }
};