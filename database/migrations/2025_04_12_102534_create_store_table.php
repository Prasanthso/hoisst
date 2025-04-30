<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('store', function (Blueprint $table) {
            $table->id();
            $table->string('store_name');
            $table->string('store_id')->unique();
            $table->time('start_time');
            $table->time('close_time');
            $table->string('mobile_number');
            $table->string('email')->nullable();
            $table->string('store_incharge_name');
            $table->string('gst_no')->nullable();
            $table->text('store_address')->nullable();
            $table->string('store_location')->nullable();
            $table->string('store_region')->nullable();
            $table->string('pincode')->nullable();
            $table->string('store_image')->nullable(); // store image path
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store');
    }
};
