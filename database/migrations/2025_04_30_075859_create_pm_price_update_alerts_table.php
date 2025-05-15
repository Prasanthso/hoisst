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
        Schema::create('pm_price_update_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('packing_material_ids'); // store an array of packing material IDs
            $table->timestamp('alerted_at');
            $table->string('channel'); // email, whatsapp, both
            $table->string('alert_type')->default('frequency');
             $table->unsignedBigInteger('store_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_price_update_alerts');
    }
};
