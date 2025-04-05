<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddCompletedStatusToSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. İlk olarak ilgili tablolarda session_id sütunu var mı kontrol et
        //    Yoksa ekle, varsa geç
        if (!Schema::hasColumn('private_lesson_materials', 'session_id')) {
            Schema::table('private_lesson_materials', function (Blueprint $table) {
                $table->unsignedBigInteger('session_id')->nullable();
                
                // Add foreign key
                $table->foreign('session_id')
                      ->references('id')
                      ->on('private_lesson_sessions')
                      ->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('private_lesson_homeworks', 'session_id')) {
            Schema::table('private_lesson_homeworks', function (Blueprint $table) {
                $table->unsignedBigInteger('session_id')->nullable();
                
                // Add foreign key
                $table->foreign('session_id')
                      ->references('id')
                      ->on('private_lesson_sessions')
                      ->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('private_lesson_notifications', 'session_id')) {
            Schema::table('private_lesson_notifications', function (Blueprint $table) {
                $table->unsignedBigInteger('session_id')->nullable();
                
                // Add foreign key
                $table->foreign('session_id')
                      ->references('id')
                      ->on('private_lesson_sessions')
                      ->onDelete('cascade');
            });
        }

        // 2. Eğer occurrences tablosu varsa, verileri aktarıp kaldır
        if (Schema::hasTable('private_lesson_occurrences')) {
            // Veri aktarımını yap (eğer session_id sütunu her iki tabloda da varsa)
            if (Schema::hasColumn('private_lesson_materials', 'occurrence_id') && 
                Schema::hasColumn('private_lesson_materials', 'session_id')) {
                
                DB::statement('
                    UPDATE private_lesson_materials m
                    JOIN private_lesson_occurrences o ON m.occurrence_id = o.id
                    SET m.session_id = o.session_id
                ');
            }

            if (Schema::hasColumn('private_lesson_homeworks', 'occurrence_id') && 
                Schema::hasColumn('private_lesson_homeworks', 'session_id')) {
                
                DB::statement('
                    UPDATE private_lesson_homeworks h
                    JOIN private_lesson_occurrences o ON h.occurrence_id = o.id
                    SET h.session_id = o.session_id
                ');
            }

            if (Schema::hasColumn('private_lesson_notifications', 'occurrence_id') && 
                Schema::hasColumn('private_lesson_notifications', 'session_id')) {
                
                DB::statement('
                    UPDATE private_lesson_notifications n
                    JOIN private_lesson_occurrences o ON n.occurrence_id = o.id
                    SET n.session_id = o.session_id
                ');
            }

            // occurrence_id'yi kaldır (eğer varsa)
            if (Schema::hasColumn('private_lesson_materials', 'occurrence_id')) {
                Schema::table('private_lesson_materials', function (Blueprint $table) {
                    // Foreign key var mı kontrol et, varsa düşür
                    if (DB::getSchemaBuilder()->getColumnListing('private_lesson_materials')) {
                        $table->dropForeign(['occurrence_id']);
                    }
                    
                    $table->dropColumn('occurrence_id');
                });
            }

            if (Schema::hasColumn('private_lesson_homeworks', 'occurrence_id')) {
                Schema::table('private_lesson_homeworks', function (Blueprint $table) {
                    // Foreign key var mı kontrol et, varsa düşür
                    if (DB::getSchemaBuilder()->getColumnListing('private_lesson_homeworks')) {
                        $table->dropForeign(['occurrence_id']);
                    }
                    
                    $table->dropColumn('occurrence_id');
                });
            }

            if (Schema::hasColumn('private_lesson_notifications', 'occurrence_id')) {
                Schema::table('private_lesson_notifications', function (Blueprint $table) {
                    // Foreign key var mı kontrol et, varsa düşür
                    if (DB::getSchemaBuilder()->getColumnListing('private_lesson_notifications')) {
                        $table->dropForeign(['occurrence_id']);
                    }
                    
                    $table->dropColumn('occurrence_id');
                });
            }

            // Occurrences tablosunu kaldır
            Schema::dropIfExists('private_lesson_occurrences');
        }

        // 3. Session tablosuna teacher_notes ekle (eğer yoksa)
        if (!Schema::hasColumn('private_lesson_sessions', 'teacher_notes')) {
            Schema::table('private_lesson_sessions', function (Blueprint $table) {
                $table->text('teacher_notes')->nullable()->after('notes');
            });
        }

        // 4. Status enum'ına 'completed' değerini ekle
        // Enum kontrolü DB::raw ile yapılmıyoruz - bunun yerine DB::statement kullanıyoruz
        DB::statement("ALTER TABLE private_lesson_sessions MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'cancelled', 'completed') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 1. completed durumundaki kayıtları güncelle
        DB::table('private_lesson_sessions')
            ->where('status', 'completed')
            ->update(['status' => 'approved']);

        // 2. Status enum'u güncelle
        DB::statement("ALTER TABLE private_lesson_sessions MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'cancelled') NOT NULL");

        // 3. Teacher notes alanını kaldır (eğer eklenmediyse)
        if (Schema::hasColumn('private_lesson_sessions', 'teacher_notes')) {
            Schema::table('private_lesson_sessions', function (Blueprint $table) {
                $table->dropColumn('teacher_notes');
            });
        }
    }
}