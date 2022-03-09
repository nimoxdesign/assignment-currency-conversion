<?php

namespace App\CurrencyConversion\Controller;

use App\CurrencyConversion\Factory\ProviderFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProviderListController extends AbstractController
{
    const DEFAULT_PROVIDER = 'currconv';

    private $repository;
    private $request;

    #[Route('/api/currencyconversion/providers', name: 'app_currencyconversion_providers', methods: 'GET')]
    public function index(
        ProviderFactory $providerFactory
    ): JsonResponse
    {
        return new JsonResponse(
            $providerFactory->getProviderList()
        );
    }
}
