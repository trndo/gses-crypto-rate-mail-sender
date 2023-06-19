<?php

declare(strict_types=1);

namespace App\Utils\CurrencyRateComparator;

interface CurrencyRateComparatorInterface
{
    public function compare(Currency $from, Currency $to): float;
}
