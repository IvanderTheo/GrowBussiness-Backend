<?php

namespace App\Services\HPP;

use League\Csv\Reader;

class ProductFixedCostService {
    public function searchFixedCost(
        string $category,
    ) {
        $csv = Reader::from(
            storage_path(
                'app/hpp_fixed_costs_dummy.csv'
            ),
            'r'
        );

        $csv->setHeaderOffset(0);

        $records = collect($csv->getRecords());

        $exact = $records->filter(function ($row)
        use ($category) {

            return $this->normalize($row['category']) === $category;
        });

        if ($exact->isNotEmpty()) {
            return [
                'match_type' => 'exact',
                'data' => $exact->values()
            ];
        }

        // ai fallback
        return [
            'match_type' => 'ai_fallback',
            'data' => collect()
        ];
    }

    private function normalize(?string $value): string
    {
        $value = preg_replace('/^\xEF\xBB\xBF/', '', $value);

        return trim(
            mb_strtolower($value ?? '')
        );
    }
}