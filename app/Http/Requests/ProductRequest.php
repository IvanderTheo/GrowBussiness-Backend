<?php

namespace App\Http\Requests;
use App\Services\PrepareValidation;

class ProductRequest extends PrepareValidation
{
    protected function prepareForValidation()
    {
        $this->merge([
            'category' => $this->sanitizeString($this->category),
        ]);
    }

    public function rules(): array
    {
        $rules = [
            'category' => 'required|string|exists:product_categories,name',
        ];
        if($this->routeIs('productModeling')) {
            $rules['product_name'] = 'required|string|max:255';
        }
        return $rules;
    }
}