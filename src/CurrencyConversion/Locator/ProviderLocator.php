<?php

namespace App\CurrencyConversion\Locator;

use App\CurrencyConversion\Provider\ProviderInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;

class ProviderLocator
{
    private $locator;
    private $providers;

    public function __construct(ServiceLocator $locator)
    {
        $this->locator   = $locator;
        $this->providers = [];

        $this->registerProviders();
    }

    private function registerProviders(): self
    {
        $services = $this->locator->getProvidedServices();

        foreach ($services as $key => $service) {
            // Out of all services, we want a list specific to Providers implementing the ProviderInterface
            $interfaces = class_implements($service);
            if (!$interfaces || !in_array(ProviderInterface::class, $interfaces)) {
                continue;
            }
            
            $this->providers[$key] = [
                'name'  => constant("{$service}::PROVIDER_NAME"),
                // 'class' => $service, we will not be registering the class here, that is what the locator is for. 
            ];
        }      

        return $this;
    }

    public function getProviderList(): array
    {
        return $this->providers;
    }

    public function getProvider(string $key): ?ProviderInterface
    {
        // $this->locator->has($key) will not be specific enough. We need a certain Interface to be implemented
        if ($this->hasProvider($key)) {
            return $this->locator->get($key);
        }

        return null;
    }

    public function hasProvider(string $key): bool
    {
        return array_key_exists($key, $this->providers);
    }

    /**
     * We assume there is always at least 1 provider registered as a service
     */
    public function getFirstProvider()
    {
        return $this->getProvider(array_keys($this->providers)[0]);
    }
}
