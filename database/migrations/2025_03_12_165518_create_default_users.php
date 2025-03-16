<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Rolleri oluştur (eğer yoksa)
        $adminRole = Role::firstOrCreate(['name' => 'yonetici']);
        $studentRole = Role::firstOrCreate(['name' => 'ogrenci']);
        $teacherRole = Role::firstOrCreate(['name' => 'ogretmen']);

        // Yönetici kullanıcısı oluştur
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Öğrenci kullanıcısı oluştur
        $student = User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        
        // Öğretmen kullanıcısı oluştur
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name' => 'Teacher User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Rolleri ata (eğer henüz atanmamışsa)
        if (!$admin->hasRole('yonetici')) {
            $admin->assignRole('yonetici');
        }
        
        if (!$student->hasRole('ogrenci')) {
            $student->assignRole('ogrenci');
        }
        
        if (!$teacher->hasRole('ogretmen')) {
            $teacher->assignRole('ogretmen');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Kullanıcıları sil
        $admin = User::where('email', 'admin@example.com')->first();
        if ($admin) {
            $admin->delete();
        }

        $student = User::where('email', 'student@example.com')->first();
        if ($student) {
            $student->delete();
        }
        
        $teacher = User::where('email', 'teacher@example.com')->first();
        if ($teacher) {
            $teacher->delete();
        }

        // Not: Rolleri silmiyoruz çünkü başka kullanıcılar da bu rollere sahip olabilir
    }
};