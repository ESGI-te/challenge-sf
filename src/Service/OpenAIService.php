<?php

namespace App\Service;

use Orhanerday\OpenAi\OpenAi;

class OpenAIService
{
    protected OpenAi $openAi;
    public function __construct($openAiKey) {
        $this->openAi = new OpenAi($openAiKey);
    }

    public function generateText(string $prompt): string {
        $completion = $this->openAi->completion([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'temperature' => 0.9,
            'max_tokens' => 3200,
            'frequency_penalty' => 0,
            'presence_penalty' => 0.6,
        ]);
        $completionDecoded = json_decode($completion);

        return $completionDecoded->choices[0]->text;
    }

    public function generateImage(string $prompt): string {
        return $this->openAi->image([
            "prompt" => $prompt,
            "n" => 1,
            "size" => "256x256",
            "response_format" => "url",
        ]);
    }
}