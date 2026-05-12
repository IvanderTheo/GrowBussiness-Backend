<?php

namespace App\Services\HPP;

class HppCalculationService {
    public function calculate(
        array $variableCosts,
        array $fixedCosts,
        int $targetProduction = 1000
    ): array {

        // TOTAL VARIABLE COST

        $totalVariableCost = collect($variableCosts)
            ->sum(function ($item) {

                return (float) $item['cost_per_product'];
            });

        // TOTAL FIXED COST

        $totalFixedCost = collect($fixedCosts)
            ->sum(function ($item) {

                return (float) $item['total_monthly_cost'];
            });

        // FIXED COST ALLOCATION

        $fixedCostAllocation =
            $totalFixedCost / $targetProduction;

        // FINAL HPP

        $hppPerProduct =
            $totalVariableCost +
            $fixedCostAllocation;

        return [

            'target_production' => $targetProduction,

            'total_variable_cost' =>
                round($totalVariableCost, 2),

            'total_fixed_cost' =>
                round($totalFixedCost, 2),

            'fixed_cost_allocation' =>
                round($fixedCostAllocation, 2),

            'hpp_per_product' =>
                round($hppPerProduct, 2),
        ];
    }
}
