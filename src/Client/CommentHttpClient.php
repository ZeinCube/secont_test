<?php

namespace App\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class CommentHttpClient
{
    private Client $client;

    public function __construct(private readonly string $serviceUrl, array $options = [])
    {
        $this->client = new Client([
            'base_url' => $this->serviceUrl,
            ...$options,
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function sendRequest(HttpMethodEnum $method, string $uri, array $options = []): ResponseInterface
    {
        return $this->client->request($method->value, $uri, $options);
    }
}