<?php

namespace App\CurrencyConversion\Controller;

use App\CurrencyConversion\Factory\ProviderFactory;
use App\CurrencyConversion\Repository\CurrencyConversionRepository;
use App\CurrencyConversion\Entity\CurrencyConversion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ConversionDataController extends AbstractController
{
    const DEFAULT_PROVIDER        = 'currconv';
    const DEFAULT_BASE_CURRENCY   = 'EUR';
    const DEFAULT_TARGET_CURRENCY = 'USD';

    private $repository;
    private $request;

    #[Route('/api/currencyconversion/data', name: 'app_currencyconversion_data', methods: 'GET')]
    public function index(
        Request $request, 
        CurrencyConversionRepository $repository, 
        ProviderFactory $providerFactory
    ): JsonResponse
    {
        $this->repository = $repository;
        $this->request = $request;
        $this->providerFactory = $providerFactory;

        $this->checkRequest();

        $searchResult = $this->_getResultFromDatabase();

        if (!$searchResult) {
            $searchResult = $this->_getResultFromProvider();

            if ($searchResult) {
                $searchResult = $this->_addResultToDatabase($searchResult);
            }
        }

        return new JsonResponse(
            $searchResult 
                ? [
                    'success' => true,
                    'data' => $searchResult->toArray()
                 ] 
                : [
                    'error' => true,
                    'message' => 'no results found, or service is down.'
                ]
        );
    }

    private function checkRequest(): self
    {
        $req = $this->request;
        $reqQuery = $this->request->query;

        // Might there be tampering with the request url, e.g. missing params, empty values, set these back to defaults.
        $toCheck = [
            'provider'        => self::DEFAULT_PROVIDER,
            'base_currency'   => self::DEFAULT_BASE_CURRENCY,
            'target_currency' => self::DEFAULT_TARGET_CURRENCY,
        ];

        foreach ($toCheck as $key => $defaultValue) {
            $reqQuery->set(
                $key, 
                ($reqQuery->has($key) && $req->get($key) 
                    ? $req->get($key)
                    : $defaultValue
                )
            );
        }

        return $this;
    }

    private function _getResultFromDatabase(): ?CurrencyConversion
    {
        $dateTime = new \DateTime('now');
        $dateTime->setTime(0, 0, 0);

        $req = $this->request;

        return $this->repository->findOneBy(
            [
                'provider' => [
                    'value' => $req->get('provider'),
                ],
                'base_currency' => [
                    'value' => $req->get('base_currency'),
                ],
                'target_currency' => [
                    'value' => $req->get('target_currency'),
                ],
                'created_at' => [
                    'evaluation' => '>=',
                    'value'      => $dateTime,
                ],
            ]
        );
    }

    /**
     * Save result to database to "cache" the value
     * Saves on having to put in another external API request
     */
    private function _addResultToDatabase(array $result): CurrencyConversion
    {
        $dateTime = new \DateTime('now');

        $entity = $this->repository->createEntity();
        $entity
            ->setProvider($this->request->get('provider'))
            ->setBaseCurrency($result['base_currency'])
            ->setTargetCurrency($result['target_currency'])
            ->setValue($result['value'])
            ->setCreatedAt($dateTime)
        ;

        $this->repository->add($entity);

        return $entity;
    }

    private function _getResultFromProvider()
    {
        $provider = $this->providerFactory->create($this->request->get('provider'));
        $req = $this->request;

        return $provider->fetch(
            $provider->getRequestUrl(
                $req->get('base_currency'),
                $req->get('target_currency')
            )
        );
    }
}
