<?php

namespace App\CurrencyConversion\Api;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProviderApi
{
    const STATUS_OK = 200;

    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function fetch(string $method, string $url, array $postParams = [])
    {
        $response = $this->httpClient->request(
            $method,
            $url
        );

        if (!($response->getStatusCode() === self::STATUS_OK)) {
            return null;
        }
        
        return $response->toArray();
    }
}
