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
        Schema::create('moh_for_recipe', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('name');
            $table->string('oh_type');
            $table->decimal('price', 8, 2)->default(0.00); // Price as decimal with 2 decimal points
            $table->decimal('percentage', 5, 2)->default(0.00); // Percentage as decimal with 2 decimal points
            $table->unsignedBigInteger('store_id');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('product_master');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moh_for_recipe');
    }
};
