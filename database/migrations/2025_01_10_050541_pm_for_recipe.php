<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pm_for_recipe', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('packing_material_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('quantity', 10, 2); // Numeric field for quantity with two decimal points
            $table->string('code'); // String field for the code
            $table->string('uom'); // String field for the unit of measurement
            $table->decimal('price', 10, 2); // Numeric field for price with two decimal points
            $table->decimal('amount', 10, 2); // Numeric field for amount with two decimal points
            $table->unsignedBigInteger('store_id');
            $table->timestamps(); // Timestamp fields for created_at and updated_at

            $table->foreign('packing_material_id')->references('id')->on('packing_materials');
            $table->foreign('product_id')->references('id')->on('product_master'); // Optional: Cascade delete
        });
    }

    public function down()
    {
        Schema::dropIfExists('pm_for_recipe');
    }
};
