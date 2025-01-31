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
        Schema::create('recipedetails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->text('description')->nullable(); // Recipe description
            $table->text('instructions')->nullable(); // Cooking instructions
            $table->string('video_path')->nullable(); // Path to the video file
            $table->string('status')->default('active');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('product_master');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipedetails');
    }
};
