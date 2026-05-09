<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Gemini\Enums\ModelVariation;
use Gemini\GeminiHelper;
use Gemini\Laravel\Facades\Gemini;

class GeminiService
{
    protected $result;

    //helper
    public function generate($string) : String
    {
        $this->result = Gemini::generativeModel(
            model: GeminiHelper::generateGeminiModel(
                variation: ModelVariation::FLASH,
                generation: 2.5 // models/gemini-2.5-flash
            )
        )->generateContent($string);

        return $this->result->text(); //response
    }
}