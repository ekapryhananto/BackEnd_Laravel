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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('description', 5000);
            $table->string('image_url');
            $table->integer('release_year');
            $table->string('price');
            $table->integer('total_page');
            $table->string('thickness')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('category_id');

            $table->foreign('category_id')->references('id')->on('categories');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
