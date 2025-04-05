<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Schema::table('private_lesson_sessions', function (Blueprint $table) {
            $table->decimal('fee', 10, 2)->nullable()->after('location');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::table('private_lesson_sessions', function (Blueprint $table) {
            $table->dropColumn('fee');
        });
    }
};