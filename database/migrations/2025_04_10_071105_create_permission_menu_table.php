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
        Schema::create('permission_menu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_category_id');
            $table->string('menuName');
            $table->string('status')->default('active');
            $table->timestamps();

            $table->foreign('permission_category_id')
                ->references('id')
                ->on('permission_category')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_menu');
    }
};
