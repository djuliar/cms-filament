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
            $table->string('slug')->default('');
            $table->text('content');
            $table->integer('category_id')->unsigned();
            $table->text('tags')->unsigned();
            $table->string('image')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('editable')->unsigned();
            $table->string('user_id_last_edit')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_author')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->boolean('featured')->default(0);
            $table->enum('status', ['PUBLISHED', 'DRAFT'])->default('PUBLISHED');
            $table->integer('read_count')->default(0);
            $table->date('date');
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
