<?php
 
namespace App\CurrencyConversion\Provider;

use App\CurrencyConversion\Provider\AbstractProvider;

class ExchangeratesapiProvider extends AbstractProvider
{
    // This has impact on how data can be gathered. 
    // Set-in-stone Base Currency is EUR on free version.
    const PAID_VERSION = false;

    const PROVIDER_NAME = 'ExchangeRatesAPI.io';
    const API_KEY    = 'a3144987b9db9bf54a59835a90373e57';
    const API_URL    = 'http://api.exchangeratesapi.io/v1/latest?';

    public function getRequestUrl(string $fromCurr, string $toCurr): string
    {
        return self::API_URL . $this->getCurrencyParams($fromCurr, $toCurr) . $this->getAdditionalParams();
    }

    private function getCurrencyParams(string $fromCurr, string $toCurr): string
    {
        if (self::PAID_VERSION) {
            return "base={$fromCurr}&symbols={$toCurr}";
        }

        return "symbols={$fromCurr},{$toCurr}";
    }

    private function getAdditionalParams()
    {
        return '&access_key=' . self::API_KEY;
    }

    public function normalizeFetchResult(?array $result)
    {
        if (!$result || !count($result) || !isset($result['success'])) {
            return null;
        }

        $rates = $result['rates'];
        $currencies = array_keys($rates);
        
        if (self::PAID_VERSION) {
            return [
                'base_currency' => $result['base'],
                'target_currency' => end($currencies),
                'value' => (float)end($rates),
            ];
        } else {
            $baseCurrencyValue = (float)$rates[$currencies[0]];
            $targetCurrencyValue = (float)$rates[$currencies[1]];
            return [
                'base_currency' => $currencies[0],
                'target_currency' => $currencies[1],
                'value' => $targetCurrencyValue / $baseCurrencyValue,
            ];
        }
    }
}
