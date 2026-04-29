<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('word_sets', function (Blueprint $table) {
        $table->foreignId('category_id')->nullable()->after('user_id')
              ->constrained('word_set_categories')->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('word_sets', function (Blueprint $table) {
        $table->dropForeign(['category_id']);
        $table->dropColumn('category_id');
    });
}
};
