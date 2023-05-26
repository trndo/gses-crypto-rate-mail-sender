<?php

declare(strict_types=1);

namespace App\Utils\Subscription\DataProvider;

use App\Utils\FileSystem\FileReader;

class TxtDataProvider implements DataProviderInterface
{
    public function __construct(
        private FileReader $fileReader,
    ) {}

    public function getAll(): array
    {
        $content = $this->fileReader->getContents('emails.txt');

        return $content ? explode(',', $this->fileReader->getContents('emails.txt')) : [];
    }

    public function ifEmailExists(string $email): bool
    {
        $content = $this->getAll();

        return in_array($email, $content, true);
    }
}