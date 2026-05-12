<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Gemini\Enums\ModelVariation;
use Gemini\GeminiHelper;
use Gemini\Laravel\Facades\Gemini;
use App\Models\User;
use Exception;

class GeminiService
{
    protected $result;

    //helper
    public function generate(User $user, array $conversation) : String
    {
        if ($user->ai_token <= 0) {
            throw new Exception('AI token limit exceeded');
        }

        // ambil last user prompt
        $context = '';

        foreach ($conversation as $message) {
            $context .= strtoupper($message['role']) . ': ';
            $context .= $message['message'] . "\n";
        }

        //response
        $this->result = Gemini::generativeModel(
            model: GeminiHelper::generateGeminiModel(
                variation: ModelVariation::FLASH,
                generation: 2.5 // models/gemini-2.5-flash
            )
        )->generateContent($context);

        // kurangi token user
        $user->decrement('ai_token');
        
        return $this->result->text(); //response
    }

    // temp chat
    public function tempChat (User $user, $prompt): String {
        if ($user->ai_token <= 0) {
            throw new Exception('AI token limit exceeded');
        }
        //response
        $this->result = Gemini::generativeModel(
            model: GeminiHelper::generateGeminiModel(
                variation: ModelVariation::FLASH,
                generation: 2.5 // models/gemini-2.5-flash
            )
        )->generateContent($prompt);

        // kurangi token user
        $user->decrement('ai_token');
        
        return $this->result->text(); //response
    }
}