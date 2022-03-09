<?php

namespace App\CurrencyConversion\Factory;

use App\CurrencyConversion\Provider\ProviderInterface;
use App\CurrencyConversion\Locator\ProviderLocator;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ProviderFactory
{
    private $providerLocator;

    public function __construct(ProviderLocator $providerLocator)
    {
        $this->providerLocator = $providerLocator;
    }

    public function create(?string $serviceName): ?ProviderInterface
    {
        if (!$serviceName) {
            return $this->providerLocator->getFirstProvider();
        }

        $provider = $this->providerLocator->getProvider($serviceName);
        
        if (!$provider) {
            $this->providerNotFound($serviceName);
        }

        return $provider;
    }

    public function getProviderList()
    {
        return $this->providerLocator->getProviderList();
    }

    private function providerNotFound(string $serviceName)
    {
        throw new ServiceNotFoundException(
            $serviceName, 
            null, 
            null, 
            [], 
            "{$serviceName} as a Provider does not exist"
        );
    }
}
