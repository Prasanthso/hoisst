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
        Schema::create('recipedetails_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recipe_id');
            $table->string('old_video');
            $table->string('new_video');
            $table->integer('changed_by');
            $table->integer('approved_by');
            $table->unsignedBigInteger('store_id');
            $table->timestamps();

            $table->foreign('recipe_id')->references('id')->on('recipedetails');
            // $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipedetails_histories');
    }
};
