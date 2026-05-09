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
        Schema::create('hpp_price_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('result_id')->constrained('hpp_results');
            $table->enum('category',['competitive','standart','premium']);
            $table->decimal('selling_price');
            $table->decimal('profit_amount');
            $table->decimal('profit_margin_precentage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hpp_price_recommendations');
    }
};
