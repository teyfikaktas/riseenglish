<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

class CreateDefaultRoles extends Migration
{
    public function up()
    {
        // Rolleri oluştur
        Role::create(['name' => 'ogrenci']);
        Role::create(['name' => 'ogretmen']);
        Role::create(['name' => 'yonetici']);
    }

    public function down()
    {
        // Rolleri sil
        Role::where('name', 'ogrenci')->delete();
        Role::where('name', 'ogretmen')->delete();
        Role::where('name', 'yonetici')->delete();
    }
}