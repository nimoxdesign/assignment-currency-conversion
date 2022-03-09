<?php
 
namespace App\CurrencyConversion\Provider;

use App\CurrencyConversion\Provider\AbstractProvider;

class CurrconvProvider extends AbstractProvider
{
    const PROVIDER_NAME = 'CurrConv.com';
    const API_KEY    = '1e15d84e6d1e691efea6';
    const API_URL    = 'https://free.currconv.com/api/v7/convert?';

    public function getRequestUrl(string $fromCurr, string $toCurr): string
    {
        return self::API_URL . $this->getCurrencyParams($fromCurr, $toCurr) . $this->getAdditionalParams();
    }

    private function getCurrencyParams(string $fromCurr, string $toCurr): string
    {
        return "q={$fromCurr}_{$toCurr}";
    }

    private function getAdditionalParams()
    {
        return '&compact=ultra&apiKey=' . self::API_KEY;
    }

    public function normalizeFetchResult(?array $result)
    {
        if (!$result || !count($result)) {
            return null;
        }

        $currencyPair = array_keys($result)[0];
        $currencies = explode('_', $currencyPair);
        
        return [
            'base_currency' => $currencies[0],
            'target_currency' => $currencies[1],
            'value' => (float)$result[$currencyPair],
        ];
    }
}
