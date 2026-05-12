<?php

namespace App\Services\HPP;

use League\Csv\Reader;

class ProductModelingService
{
    public function searchProduct(
        string $productName,
        string $category
    ) {
        $csv = Reader::from(
            storage_path(
                'app/hpp_variable_costs_dummy.csv'
            ),
            'r'
        );

        $csv->setHeaderOffset(0);

        $records = collect($csv->getRecords());

        // 1. search persis sama
        $exact = $records->filter(function ($row)
            use ($productName, $category) {

            return
                $this->normalize($row['product_name']) === $productName && $this->normalize($row['category']) === $category;
        });

        if ($exact->isNotEmpty()) {
            return [
                'match_type' => 'exact',
                'data' => $exact->values()
            ];
        }

        // 2. mirip
        $contains = $records->filter(function ($row)
            use ($productName) {
            return
                str_contains(
                    $productName,
                    $this->normalize($row['product_name'])
                );
        });

        if ($contains->isNotEmpty()) {
            return [
                'match_type' => 'contains',
                'data' => $contains->values()
            ];
        }

        // 3. kemiripan
        $bestScore = 0;
        $bestProduct = null;

        foreach ($records as $row) {

            $this->normalize(similar_text(
                $productName,
                $this->normalize($row['product_name']),
                $percent
            ));

            if ($percent > $bestScore) {
                $bestScore = $percent;
                $bestProduct = $this->normalize($row['product_name']);
            }
        }

        if ($bestScore >= 60) {

            $similar = $records->filter(function ($row)
                use ($bestProduct) {

                return
                    $this->normalize($row['product_name'])
                    === $bestProduct;
            });

            return [
                'match_type' => 'similarity',
                'similarity_score' => $bestScore,
                'matched_product' => $bestProduct,
                'data' => $similar->values()
            ];
        }

        // 4. AI FALLBACK
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
