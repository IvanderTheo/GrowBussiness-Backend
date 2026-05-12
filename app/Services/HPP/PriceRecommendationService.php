<?php

namespace App\Services\HPP;
use League\Csv\Reader;

class PriceRecommendationService {
    public function generate(
        $product_name,
        $category,
        $hpp_per_product
    ): array
    {

        // LOAD BUSINESS ANALYSIS CSV

        $csv = Reader::from(
            storage_path(
                'app/business_analysis_dummy.csv'
            ),
            'r'
        );

        $csv->setHeaderOffset(0);

        $records = collect($csv->getRecords());

        // FIND BUSINESS ANALYSIS - GENERAL MATCHING (FLEXIBLE)
        // Try to match by product name - allows general/flexible search

        $analysis = $records->first(function ($row)
            use ($product_name) {

            // Match by product name only - makes it general for all cities
            // This allows searching for "Pizza", "Burger", etc. without strict category matching
            return str_contains(
                $this->normalize(trim($row['product_name'])),
                $this->normalize(trim($product_name))
            );
        });

        // FALLBACK - USE GENERIC DEFAULTS (NO CITY/LOCATION)

        if (!$analysis) {

            $analysis = [
                'pricing_tier' => 'standard',
                'competitor_average_price' => 35000,
                'market_price_min' => 25000,
                'market_price_max' => 50000,
                'market_competitiveness' => 'medium',
                'target_customer' => 'middle_class',
                'customer_price_sensitivity' => 'medium',
                'business_goal' => 'balanced_profit',
                'pricing_strategy' => 'market_oriented',
                'product_quality' => 'standard',
                'product_uniqueness' => 'medium',
                'brand_positioning' => 'casual',
                'monthly_demand_level' => 'medium',
                'seasonality' => 'medium',
                'sales_trend' => 'stable',
                'production_capacity' => 1000,
                'estimated_monthly_sales' => 500,
                'waste_risk' => 'low',
                'psychological_pricing' => 34900,
                'competitor_count' => 15
            ];
        }

        // SMART MARGIN CALCULATION
        $competitiveMargin = 10;
        $standardMargin = 25;
        $premiumMargin = 50;

        // ========== MARKET COMPETITIVENESS ADJUSTMENT ==========
        if ($analysis['market_competitiveness'] === 'high') {
            $competitiveMargin -= 3;
            $standardMargin -= 5;
        }
        if ($analysis['market_competitiveness'] === 'low') {
            $competitiveMargin += 2;
            $standardMargin += 3;
            $premiumMargin += 5;
        }

        // ========== TARGET CUSTOMER SEGMENT ADJUSTMENT ==========
        if ($analysis['target_customer'] === 'student') {
            $competitiveMargin -= 2;
            $premiumMargin -= 10;
        }
        if ($analysis['target_customer'] === 'premium_customer') {
            $standardMargin += 3;
            $premiumMargin += 15;
        }
        if ($analysis['target_customer'] === 'middle_class') {
            $standardMargin += 2;
        }

        // ========== PRODUCT QUALITY ADJUSTMENT ==========
        if ($analysis['product_quality'] === 'premium') {
            $standardMargin += 5;
            $premiumMargin += 8;
        }
        if ($analysis['product_quality'] === 'luxury') {
            $standardMargin += 10;
            $premiumMargin += 20;
        }

        // ========== PRODUCT UNIQUENESS ADJUSTMENT ==========
        if ($analysis['product_uniqueness'] === 'high') {
            $premiumMargin += 10;
            $standardMargin += 3;
        }
        if ($analysis['product_uniqueness'] === 'medium') {
            $standardMargin += 1;
        }

        // ========== DEMAND LEVEL ADJUSTMENT ==========
        if ($analysis['monthly_demand_level'] === 'high') {
            $competitiveMargin += 2;
            $standardMargin += 2;
        }
        if ($analysis['monthly_demand_level'] === 'low') {
            $standardMargin -= 2;
        }

        // ========== SEASONALITY ADJUSTMENT ==========
        if ($analysis['seasonality'] === 'high') {
            $standardMargin += 4;
            $premiumMargin += 5;
        }

        // ========== SALES TREND ADJUSTMENT ==========
        if ($analysis['sales_trend'] === 'increasing') {
            $standardMargin += 3;
            $premiumMargin += 5;
        }
        if ($analysis['sales_trend'] === 'fluctuating') {
            $standardMargin -= 2;
        }

        // ========== WASTE RISK ADJUSTMENT ==========
        if ($analysis['waste_risk'] === 'high') {
            $standardMargin += 5;
            $premiumMargin += 3;
        }

        // ========== COMPETITOR ANALYSIS ADJUSTMENT ==========
        $competitorCount = (int) $analysis['competitor_count'];
        if ($competitorCount > 30) {
            $competitiveMargin -= 2;
            $standardMargin -= 3;
        } elseif ($competitorCount < 10) {
            $competitiveMargin += 3;
            $standardMargin += 4;
            $premiumMargin += 5;
        }

        // ========== PRICING STRATEGY ADJUSTMENT ==========
        if ($analysis['pricing_strategy'] === 'premium_branding') {
            $premiumMargin += 10;
            $standardMargin += 3;
        }
        if ($analysis['pricing_strategy'] === 'penetration') {
            $competitiveMargin += 2;
        }

        // GENERATE PRICE STRATEGY

        $strategies = [

            [
                'category' => 'competitive',
                'margin' => $competitiveMargin
            ],

            [
                'category' => 'standard',
                'margin' => $standardMargin
            ],

            [
                'category' => 'premium',
                'margin' => $premiumMargin
            ]
        ];

        // BUILD RESULT

        $results = collect($strategies)
            ->map(function ($strategy) use ($hpp_per_product, $analysis) {

                $profit = $hpp_per_product * ($strategy['margin'] / 100);
                $sellingPrice = $hpp_per_product + $profit;

                // PSYCHOLOGICAL PRICING
                $psychologicalPrice = floor($sellingPrice / 1000) * 1000 - 100 + 900;

                // AI ANALYSIS TEXT - COMPREHENSIVE
                $analysisText = $this->generateAnalysisText(
                    $analysis,
                    $strategy['category'],
                    $hpp_per_product,
                    $sellingPrice
                );

                return [

                    'category' => $strategy['category'],

                    'selling_price' => round($sellingPrice, 2),

                    'psychological_pricing' => $psychologicalPrice,

                    'profit_amount' => round($profit, 2),

                    'profit_margin_percentage' => round($strategy['margin'], 2),

                    // MARKET ANALYSIS
                    'competitor_average_price' => (float) $analysis['competitor_average_price'],
                    'market_price_min' => (float) $analysis['market_price_min'],
                    'market_price_max' => (float) $analysis['market_price_max'],
                    'market_competitiveness' => $analysis['market_competitiveness'],
                    'competitor_count' => (int) $analysis['competitor_count'],

                    // CUSTOMER ANALYSIS
                    'target_customer' => $analysis['target_customer'],
                    'customer_price_sensitivity' => $analysis['customer_price_sensitivity'],

                    // BUSINESS STRATEGY
                    'business_goal' => $analysis['business_goal'],
                    'pricing_strategy' => $analysis['pricing_strategy'],

                    // PRODUCT ANALYSIS
                    'product_quality' => $analysis['product_quality'],
                    'product_uniqueness' => $analysis['product_uniqueness'],
                    'brand_positioning' => $analysis['brand_positioning'],

                    // DEMAND ANALYSIS
                    'monthly_demand_level' => $analysis['monthly_demand_level'],
                    'seasonality' => $analysis['seasonality'],
                    'sales_trend' => $analysis['sales_trend'],

                    // OPERATIONAL ANALYSIS
                    'production_capacity' => (int) $analysis['production_capacity'],
                    'estimated_monthly_sales' => (int) $analysis['estimated_monthly_sales'],
                    'waste_risk' => $analysis['waste_risk'],

                    // AI ANALYSIS
                    'analysis' => $analysisText
                ];
            })
            ->values();

        // RECOMMENDED STRATEGY - PRIORITY BASED ON PRICING TIER

        $recommended = $results->firstWhere('category', 'standard');

        if ($analysis['pricing_tier'] === 'premium') {
            $recommended = $results->firstWhere('category', 'premium');
        } elseif ($analysis['pricing_tier'] === 'competitive') {
            $recommended = $results->firstWhere('category', 'competitive');
        }

        // FINAL RESPONSE WITH COMPREHENSIVE ANALYSIS

        return [
            'recommended' => $recommended,
            'recommendations' => $results->toArray(),
            'market_analysis' => [
                'market_competitiveness' => $analysis['market_competitiveness'],
                'competitor_average' => (float) $analysis['competitor_average_price'],
                'market_range' => [
                    'min' => (float) $analysis['market_price_min'],
                    'max' => (float) $analysis['market_price_max']
                ],
                'competitor_count' => (int) $analysis['competitor_count']
            ],
            'customer_analysis' => [
                'target_segment' => $analysis['target_customer'],
                'price_sensitivity' => $analysis['customer_price_sensitivity'],
                'demand_level' => $analysis['monthly_demand_level']
            ],
            'product_analysis' => [
                'quality' => $analysis['product_quality'],
                'uniqueness' => $analysis['product_uniqueness'],
                'brand_positioning' => $analysis['brand_positioning']
            ],
            'operational_insights' => [
                'seasonality' => $analysis['seasonality'],
                'sales_trend' => $analysis['sales_trend'],
                'waste_risk' => $analysis['waste_risk'],
                'production_capacity' => (int) $analysis['production_capacity'],
                'estimated_monthly_sales' => (int) $analysis['estimated_monthly_sales']
            ]
        ];
    }

    /**
     * Generate comprehensive analysis text based on pricing strategy
     */
    private function generateAnalysisText($analysis, $category, $hpp, $sellingPrice): string
    {
        $profitMargin = round((($sellingPrice - $hpp) / $hpp) * 100, 1);
        
        $segments = [];
        
        // Market segment analysis
        $segments[] = "Market: " . strtoupper($analysis['market_competitiveness']);
        
        // Customer analysis
        $segments[] = "Target: " . str_replace('_', ' ', $analysis['target_customer']);
        
        // Quality & positioning
        $segments[] = "Quality: " . $analysis['product_quality'];
        $segments[] = "Position: " . $analysis['brand_positioning'];
        
        // Demand & trend
        $segments[] = "Demand: " . $analysis['monthly_demand_level'];
        $segments[] = "Trend: " . $analysis['sales_trend'];
        
        // Business metrics
        $segments[] = "Margin: " . $profitMargin . "%";
        
        // Competitor context
        if ((int)$analysis['competitor_count'] > 30) {
            $segments[] = "High comp - differentiate";
        } elseif ((int)$analysis['competitor_count'] < 10) {
            $segments[] = "Low comp - strong position";
        }
        
        // Recommendation based on category
        if ($category === 'competitive') {
            $segments[] = "FOCUS: Market penetration & volume";
        } elseif ($category === 'premium') {
            $segments[] = "FOCUS: Premium segment & quality";
        } else {
            $segments[] = "FOCUS: Balanced profit & stability";
        }
        
        return implode(" | ", $segments);
    }
    private function normalize(?string $value): string
    {
        if (is_null($value)) {
            return '';
        }
        
        // Remove BOM
        $value = preg_replace('/^\xEF\xBB\xBF/', '', $value);
        
        // Convert to lowercase and trim
        return trim(mb_strtolower($value));
    }
}