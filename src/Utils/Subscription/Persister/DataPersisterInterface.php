<?php

declare(strict_types=1);

namespace App\Utils\Subscription\Persister;

interface DataPersisterInterface
{
    public function store(string $email): bool;
}