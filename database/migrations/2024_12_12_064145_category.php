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
        if(!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id(); // Auto-incrementing primary key
                $table->string('categoryname')->unique(); // Column for category name
                $table->unsignedBigInteger('store_id');
                $table->timestamps(); // Created and updated timestamps
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
