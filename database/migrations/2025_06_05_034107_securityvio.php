<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_test_results', function (Blueprint $table) {
            // Güvenlik ihlali kolonları
            $table->integer('security_violations')->default(0)->after('status');
            $table->json('violation_details')->nullable()->after('security_violations');
            $table->string('termination_reason')->nullable()->after('violation_details');
            $table->timestamp('terminated_at')->nullable()->after('termination_reason');
        });

        // Enum'a yeni değerler ekle
        DB::statement("ALTER TABLE user_test_results MODIFY COLUMN status ENUM('started', 'completed', 'abandoned', 'terminated_security', 'terminated_time') DEFAULT 'started'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_test_results', function (Blueprint $table) {
            $table->dropColumn([
                'security_violations',
                'violation_details',
                'termination_reason',
                'terminated_at'
            ]);
        });

        // Enum'u eski haline döndür
        DB::statement("ALTER TABLE user_test_results MODIFY COLUMN status ENUM('started', 'completed', 'abandoned') DEFAULT 'started'");
    }
};