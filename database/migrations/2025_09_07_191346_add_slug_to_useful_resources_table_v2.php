<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\UsefulResource;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        // Önce nullable olarak ekle
        Schema::table('useful_resources', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });
        
        // Mevcut kayıtlar için slug oluştur
        $resources = UsefulResource::all();
        foreach ($resources as $resource) {
            $slug = Str::slug($resource->title);
            $originalSlug = $slug;
            $counter = 1;
            
            // Benzersizlik kontrolü
            while (UsefulResource::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $resource->update(['slug' => $slug]);
        }
        
        // Şimdi unique constraint ekle
        Schema::table('useful_resources', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
            $table->index('slug');
        });
    }

    public function down()
    {
        Schema::table('useful_resources', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropColumn('slug');
        });
    }
};