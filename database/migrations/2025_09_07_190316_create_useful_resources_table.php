// database/migrations/create_useful_resources_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('useful_resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type'); // pdf, docx, pptx vs
            $table->bigInteger('file_size'); // byte cinsinden
            $table->string('category')->default('general'); // grammar, vocabulary vs
            $table->integer('sort_order')->default(0); // sıralama için
            $table->integer('view_count')->default(0); // izlenme sayısı
            $table->integer('download_count')->default(0); // indirilme sayısı
            $table->boolean('is_popular')->default(false); // popüler mi?
            $table->boolean('is_active')->default(true); // aktif mi?
            $table->timestamps();
            
            $table->index(['is_active', 'sort_order']);
            $table->index(['is_popular', 'view_count']);
            $table->index('category');
        });
    }

    public function down()
    {
        Schema::dropIfExists('useful_resources');
    }
};