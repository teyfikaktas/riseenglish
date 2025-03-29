<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // resource_categories tablosu
        Schema::create('resource_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('resource_categories')->onDelete('set null');
            $table->timestamps();
        });

        // resources tablosu
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('category_id');
            $table->boolean('is_free')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->string('file_path')->nullable();
            $table->timestamps();
            
            $table->foreign('type_id')->references('id')->on('resource_types')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('resource_categories')->onDelete('cascade');
        });

        // resource_tags tablosu
        Schema::create('resource_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // resource_tag pivot tablosu
        Schema::create('resource_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('resource_id');
            $table->unsignedBigInteger('tag_id');
            
            $table->primary(['resource_id', 'tag_id']);
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('resource_tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resource_tag');
        Schema::dropIfExists('resource_tags');
        Schema::dropIfExists('resources');
        Schema::dropIfExists('resource_categories');
        Schema::dropIfExists('resource_types');
    }
}