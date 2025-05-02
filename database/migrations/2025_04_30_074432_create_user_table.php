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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->string('whatsapp_number')->nullable();
            $table->tinyInteger('whatsapp_enabled')->default(0);
            $table->string('mobile_number');
            $table->text('user_address')->nullable();
            $table->unsignedBigInteger('store_id');
            $table->string('store_location')->nullable();
            $table->string('user_image')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            // Foreign key constraint if you have a stores table
            $table->foreign('store_id')->references('id')->on('store')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
