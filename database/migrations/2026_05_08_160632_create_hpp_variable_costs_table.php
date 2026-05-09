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
        Schema::create('hpp_variable_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calculation_id')->constrained('hpp_calculations');
            $table->string("material_name");
            $table->decimal('usage_amount');
            $table->string('usage_unit');
            $table->decimal('purchase_total_price');
            $table->decimal('purchase_quantity');
            $table->string('purchase_unit');
            $table->decimal('cost_per_unit');
            $table->decimal('cost_per_product');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hpp_variable_costs');
    }
};
