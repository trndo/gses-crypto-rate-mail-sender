<?php

declare(strict_types=1);

namespace App\Utils\Subscription\DataProvider;

interface SubscriptionDataProviderInterface
{
    public function getAll(): array;

    public function ifEmailExists(string $email): bool;
}
