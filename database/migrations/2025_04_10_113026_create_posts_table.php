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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->foreign('category_id')->references('id')->on('categories');
            $table->string('title', 255);
            $table->text('body')->nullable();
            $table->smallInteger('publish_flg')->default(0);
            $table->integer('view_counter')->nullable()->default(0);
            $table->integer('favorite_counter')->nullable()->default(0);
            $table->boolean('delete_flg')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
