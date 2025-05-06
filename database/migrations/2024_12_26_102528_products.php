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
        Schema::create('product_master', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('pdcode', 64)->unique();
            $table->string('hsnCode');
            $table->string('uom');
            $table->string('itemWeight');
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
            $table->string('itemType_id')->nullable();
            $table->string('purcCost')->nullable();
            $table->string('margin');
            $table->string('price');
            $table->string('tax');
            $table->string('update_frequency');
            // $table->string('cost')->nullable();
            $table->enum('recipe_created_status', ['yes', 'no'])->default('no');
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
        Schema::dropIfExists('product_master');
    }
};
