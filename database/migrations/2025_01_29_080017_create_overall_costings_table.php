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
        Schema::create('overall_costings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productId')->constrained('recipe_master'); // Assuming 'recipe_products' table exists
            $table->decimal('rm_cost_unit', 10, 2);
            $table->decimal('pm_cost_unit', 10, 2);
            $table->decimal('rm_pm_cost', 10, 2);
            $table->decimal('overhead', 10, 2);
            $table->decimal('rm_suggested_mrp', 10, 2);
            $table->decimal('pm_suggested_mrp', 10, 2);
            $table->decimal('suggested_mrp', 10, 2);
            $table->decimal('suggested_margin', 10, 2);
            $table->decimal('overhead_amount', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->decimal('selling_rate', 10, 2);
            $table->decimal('selling_rate_before_tax', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('margin_amount', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->decimal('present_mrp', 10, 2);
            $table->decimal('margin', 10, 2);
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overall_costings');
    }
};
