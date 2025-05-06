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
        Schema::create('overall_costing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productId')->constrained('recipe_master'); // Assuming 'recipe_products' table exists
            $table->decimal('rm_cost_unit', 10, 2)->default(0);
            $table->decimal('pm_cost_unit', 10, 2)->default(0);
            $table->decimal('rm_pm_cost', 10, 2)->default(0);
            $table->decimal('overhead', 10, 2)->default(0);
            // $table->decimal('rm_sg_mrp', 10, 2);
            // $table->decimal('pm_sg_mrp', 10, 2);
            // $table->decimal('sg_mrp', 10, 2);
            // $table->decimal('sg_margin', 10, 2);
            // $table->decimal('oh_amt', 10, 2);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('margin', 10, 2)->default(0);
            $table->decimal('margin_amt', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('sugg_rate', 10, 2)->default(0);
            $table->decimal('sugg_rate_bf', 10, 2)->default(0);
            $table->decimal('suggested_mrp', 10, 2)->default(0);
            $table->string('status')->default('active');
            $table->unsignedBigInteger('store_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overall_costing');
    }
};
