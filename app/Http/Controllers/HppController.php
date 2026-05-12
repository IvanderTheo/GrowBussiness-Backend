<?php

namespace App\Http\Controllers;

use App\Services\HPP\HppCalculationService;
use App\Services\HPP\PriceRecommendationService;
use Exception;
use Illuminate\Http\Request;

class HppController extends Controller
{
    //
    public function __construct(
        protected HppCalculationService $hppCalculation,
        protected PriceRecommendationService $priceRecommendation
    ) {}
    public function countHpp(Request $request) {
        try {
            $variableCosts = $request->variable_costs;

            $fixedCosts = $request->fixed_costs;

            $targetProduction =
                $request->target_production ?? 1000;

            $result = $this
                ->hppCalculation
                ->calculate(
                    $variableCosts,
                    $fixedCosts,
                    $targetProduction
                );

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function recommendation(Request $request) {
        $result = $this->priceRecommendation
                        ->generate
                        (
                            $request->product_name,
                            $request->category,
                            $request->hpp_per_product
                        );

        return response()->json($result);
    }
}
