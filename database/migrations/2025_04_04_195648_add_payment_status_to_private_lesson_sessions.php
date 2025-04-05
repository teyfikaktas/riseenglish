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
        Schema::table('private_lesson_sessions', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->after('fee');
            $table->decimal('paid_amount', 10, 2)->default(0)->after('payment_status');
            $table->timestamp('payment_date')->nullable()->after('paid_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('private_lesson_sessions', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'paid_amount', 'payment_date']);
        });
    }
};