<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\CurrencyRateComparator\CurrencyRateComparatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class CurrencyRateController
{
    public function __construct(
        private CurrencyRateComparatorInterface $rateComparator
    ) {
    }

    #[Route('/rate')]
    public function getRates(): JsonResponse
    {
        return new JsonResponse($this->rateComparator->compare(Currency::BTC, Currency::UAH));
    }
}
