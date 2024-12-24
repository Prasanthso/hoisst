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
        Schema::create('overheads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ohcode', 64)->unique();
            $table->string('uom');
            $table->integer('category_id1')->nullable();
            $table->integer('category_id2')->nullable();
            $table->integer('category_id3')->nullable();
            $table->integer('category_id4')->nullable();
            $table->integer('category_id5')->nullable();
            $table->string('price');
            $table->string('price_update_frequency');
            $table->string('price_threshold');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overheads');
    }
};
