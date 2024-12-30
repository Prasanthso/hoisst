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
        Schema::create('oh_price_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('overheads_id');
            $table->string('old_price');
            $table->string('new_price');
            $table->integer('updated_by');
            $table->timestamps();

            $table->foreign('overheads_id')->references('id')->on('overheads');
            // $table->foreign('updated_by')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oh_price_histories');
    }
};
