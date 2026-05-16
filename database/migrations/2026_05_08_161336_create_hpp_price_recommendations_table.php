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
            $table->foreignId('result_id')->constrained('hpp_results')->cascadeOnDelete();
            $table->enum('category',['competitive','standard','premium']);

            // PRICE CALCULATION

            $table->decimal('selling_price', 15, 2);
            $table->decimal('profit_amount', 15, 2);
            $table->decimal('profit_margin_percentage', 5, 2);

            // BUSINESS ANALYSIS

            $table->decimal(
                'competitor_average_price',
                15,
                2
            )->nullable();
            $table->decimal(
                'market_price_min',
                15,
                2
            )->nullable();
            $table->decimal(
                'market_price_max',
                15,
                2
            )->nullable();
            $table->enum('market_competitiveness', [
                'low',
                'medium',
                'high'
            ])->nullable();

            // CUSTOMER ANALYSIS
            $table->enum('target_customer', [
                'student',
                'middle_class',
                'premium_customer',
                'office_worker'
            ])->nullable();
            $table->enum('customer_price_sensitivity', [
                'low',
                'medium',
                'high'
            ])->nullable();
            // BUSINESS STRATEGY

            $table->enum('business_goal', [
                'fast_turnover',
                'balanced_profit',
                'brand_equity'
            ])->nullable();

            $table->enum('pricing_strategy', [
                'penetration',
                'market_oriented',
                'premium_branding'
            ])->nullable();

            // PRODUCT ANALYSIS

            $table->enum('product_quality', [
                'standard',
                'premium',
                'luxury'
            ])->nullable();

            $table->enum('product_uniqueness', [
                'low',
                'medium',
                'high'
            ])->nullable();

            $table->enum('brand_positioning', [
                'mass_market',
                'casual',
                'modern',
                'homemade',
                'artisan'
            ])->nullable();

            // LOCATION ANALYSIS

            $table->string('city')->nullable();

            $table->enum('location_type', [
                'campus_area',
                'residential_area',
                'business_area',
                'mall',
                'tourist_area'
            ])->nullable();

            // DEMAND ANALYSIS

            $table->enum('monthly_demand_level', [
                'low',
                'medium',
                'high'
            ])->nullable();

            $table->enum('seasonality', [
                'low',
                'medium',
                'high'
            ])->nullable();

            $table->enum('sales_trend', [
                'stable',
                'increasing',
                'fluctuating'
            ])->nullable();

            // OPERATIONAL ANALYSIS
            $table->integer('production_capacity')
                ->nullable();

            $table->integer('estimated_monthly_sales')
                ->nullable();

            $table->enum('waste_risk', [
                'low',
                'medium',
                'high'
            ])->nullable();

            // SMART PRICING
            $table->decimal(
                'psychological_pricing',
                15,
                2
            )->nullable();

            $table->integer('competitor_count')
                ->nullable();

            // AI ANALYSIS
            $table->longText('analysis')
                ->nullable();
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
