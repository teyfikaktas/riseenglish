<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateUserPhonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            // Rastgele Türkiye telefon numarası oluşturma (5XX XXX XX XX formatında)
            $operatorCodes = ['50', '53', '54', '55', '56'];
            $randomOperator = $operatorCodes[array_rand($operatorCodes)];
            
            $phoneNumber = '+90' . $randomOperator . 
                           rand(0, 9) . rand(0, 9) . rand(0, 9) . 
                           rand(0, 9) . rand(0, 9) . rand(0, 9) . 
                           rand(0, 9);
            
            // Kullanıcıyı güncelle
            $user->phone = $phoneNumber;
            $user->save();
            
            echo "Kullanıcı güncellendi: {$user->name} - {$phoneNumber}" . PHP_EOL;
        }
        
        echo "Telefon numaraları başarıyla eklendi!" . PHP_EOL;
    }
}