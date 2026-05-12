<?php

namespace App\Services;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

abstract class PrepareValidation extends FormRequest
{
    protected function sanitizeString(?string $value): ?string
    {
        if (!$value) {
            return $value;
        }

        $value = Str::lower($value);

        $value = preg_replace('/[^a-zA-Z0-9\s&]/', '', $value);

        $value = preg_replace('/\s+/', ' ', $value);

        return trim($value);
    }
}
