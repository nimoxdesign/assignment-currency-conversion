<?php

namespace App\Controller;

use App\CurrencyConversion\Factory\ProviderFactory;
use Money\Currencies\ISOCurrencies;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(ProviderFactory $providerFactory, ISOCurrencies $currencies): Response
    {
        return $this->render('default/index.html.twig', [
            'providers' => $providerFactory->getProviderList(),
            'currencies' => $currencies,
        ]);
    }
}
