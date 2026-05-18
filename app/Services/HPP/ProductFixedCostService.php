<?php

namespace App\Services\HPP;

use League\Csv\Reader;

class ProductFixedCostService
{
    public function searchFixedCost(
        string $category
    ): array {

        $csv = Reader::from(
            storage_path(
                'app/hpp_fixed_costs_dummy.csv'
            ),
            'r'
        );

        $csv->setHeaderOffset(0);

        $records = collect(
            $csv->getRecords()
        );

        $normalizedCategory =
            $this->normalize($category);

        // EXACT SEARCH
        $exact = $records
            ->filter(function ($row)
            use ($normalizedCategory) {

                return
                    $this->normalize(
                        $row['category']
                    )
                    ===
                    $normalizedCategory;
            });

        // FOUND
        if ($exact->isNotEmpty()) {

            return [

                'match_type' => 'exact',

                'data' =>
                    $exact
                    ->values()
                    ->toArray()
            ];
        }

        // AI FALLBACK
        return [

            'match_type' => 'ai_fallback',

            'data' => []
        ];
    }

    private function normalize(
        ?string $value
    ): string {

        $value =
            preg_replace(
                '/^\xEF\xBB\xBF/',
                '',
                $value
            );

        return trim(
            mb_strtolower(
                $value ?? ''
            )
        );
    }
}