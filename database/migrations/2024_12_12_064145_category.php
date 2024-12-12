<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

            Schema::create('categories', function (Blueprint $table) {
                $table->id(); // Auto-incrementing primary key
                $table->string('categoryname'); // Column for category name
                $table->timestamps(); // Created and updated timestamps
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
