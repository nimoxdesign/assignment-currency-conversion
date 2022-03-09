<?php

namespace App\CurrencyConversion\Provider;

use App\CurrencyConversion\Provider\ProviderInterface;
use App\CurrencyConversion\Api\ProviderApi;

abstract class AbstractProvider implements ProviderInterface
{
    const API_METHOD = 'GET';

    private $providerApi;

    public function __construct(ProviderApi $providerApi)
    {
        $this->providerApi = $providerApi;
    }

    public function fetch(string $url, array $postParams = [])
    {
        $result = $this->providerApi->fetch(self::API_METHOD, $url, $postParams);
        $result = $this->normalizeFetchResult($result);
        
        return $result;
    }

    public function normalizeFetchResult(?array $result)
    {
        return $result;
    }
}
