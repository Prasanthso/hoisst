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
        Schema::create('recipe_master', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('rpcode', 64)->unique();
            $table->string('Output');
            $table->string('uom');
            $table->decimal('totalCost', 10, 2); // Decimal for monetary value
            $table->decimal('singleCost', 10, 2); // Decimal for monetary value
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('product_master')
                ->onDelete('cascade'); // Optional: Cascade delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_master');
    }
};
