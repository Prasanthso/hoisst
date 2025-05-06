<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

            Schema::create('categoryitems', function (Blueprint $table) {
                $table->id(); // Auto-incrementing primary key
                $table->string('categoryId');
                $table->string('itemname');
                $table->string('description')->nullable();
                $table->string('created_user')->nullable();
                $table->string('status');
                $table->unsignedBigInteger('store_id');
                $table->timestamps(); // Created and updated timestamps
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoryitems');
    }
};
