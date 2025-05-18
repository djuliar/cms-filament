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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->text('content')->nullable();
            $table->integer('category_id')->unsigned()->nullable()->default(1);
            $table->text('tags')->nullable();
            $table->text('image')->nullable();
            $table->integer('user_id')->unsigned()->nullable()->default(1);
            $table->integer('user_id_last_edit')->unsigned()->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_author', 50)->nullable();
            $table->string('meta_keyword', 100)->nullable();
            $table->string('og_image', 150)->nullable();
            $table->string('og_title', 200)->nullable();
            $table->text('og_description')->nullable();
            $table->boolean('featured')->unsigned()->nullable()->default(0);
            $table->boolean('editable')->unsigned()->nullable()->default(1);
            $table->boolean('status')->unsigned()->nullable()->default(1);
            $table->integer('read_count')->unsigned()->nullable()->default(0);
            $table->timestamp('date')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
