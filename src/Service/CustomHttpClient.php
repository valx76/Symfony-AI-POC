<?php

namespace App\Service;

use Nyholm\Psr7\Response;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

// We're using an OpenAI client to query for Gemini.
// The client expects the OpenAI response to contain an 'id' key but Gemini doesn't set it.
// This HttpClient will just add the 'id' key if it doesn't exist.
final readonly class CustomHttpClient implements ClientInterface
{
    public function __construct(
        private ClientInterface $httpClient,
    ) {
    }

    /**
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $response = $this->httpClient->sendRequest($request);

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        if (is_array($data)) {
            $data['id'] = $data['id'] ?? uniqid();
        }

        return new Response(
            $response->getStatusCode(),
            $response->getHeaders(),
            json_encode($data, JSON_THROW_ON_ERROR)
        );
    }
}
