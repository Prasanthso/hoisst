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
        Schema::create('low_margin_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('product_master')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->float('calculated_margin');
            $table->float('threshold_margin');
            $table->timestamp('alerted_at');
            $table->string('channel'); // email / whatsapp / both
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('low_margin_alerts');
    }
};
