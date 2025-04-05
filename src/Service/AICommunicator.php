<?php

namespace App\Service;

use OpenAI\Factory;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class AICommunicator
{
    public function __construct(
        private Factory $openAIFactory,
        private CustomHttpClient $httpClient,

        #[Autowire('%env(OPENAI_API_KEY)%')]
        private string $openaiApiKey,

        #[Autowire('%env(OPENAI_API_BASE_URI)%')]
        private string $openaiApiBaseUri,

        #[Autowire('%env(AI_MODEL)%')]
        private string $aiModel,
    ) {
    }

    public function askAI(string $context, string $request): string
    {
        $client = $this->openAIFactory
            ->withApiKey($this->openaiApiKey)
            ->withHttpClient($this->httpClient)
            ->withBaseUri($this->openaiApiBaseUri)
            ->make();

        $response = $client->chat()->create([
            'model' => $this->aiModel,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $context,
                ],
                [
                    'role' => 'user',
                    'content' => $request,
                ],
            ],
        ]);

        return $response->choices[0]->message->content ?? '';
    }
}
