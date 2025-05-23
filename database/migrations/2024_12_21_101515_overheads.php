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
            // $table->string('hsncode');
            $table->string('itemweight');
            $table->integer('category_id1')->nullable();
            $table->integer('category_id2')->nullable();
            $table->integer('category_id3')->nullable();
            $table->integer('category_id4')->nullable();
            $table->integer('category_id5')->nullable();
            $table->integer('category_id6')->nullable();
            $table->integer('category_id7')->nullable();
            $table->integer('category_id8')->nullable();
            $table->integer('category_id9')->nullable();
            $table->integer('category_id10')->nullable();
            // $table->string('itemType_id');
            $table->string('price');
            // $table->string('tax');
            $table->string('update_frequency');
            $table->string('price_update_frequency');
            $table->string('price_threshold');
            $table->string('status', 20)->default('active');
            $table->unsignedBigInteger('store_id');
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
