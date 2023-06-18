<?php

declare(strict_types=1);

namespace App\Utils\FileSystem;

use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class FileReader
{
    public function __construct(
        private string $directory,
        private Filesystem $filesystem,
        private LoggerInterface $logger,
    ) {
    }

    public function getContents(string $fileName): ?string
    {
        $filePath = $this->directory.'/'.$fileName;

        if ( ! $this->filesystem->exists($filePath)) {
            return null;
        }

        try {
            $contents = file_get_contents($filePath);

            if ( ! $contents) {
                return null;
            }
        } catch (\Throwable $exception) {
            $this->logger->info('Error while reading file '.$filePath.'Message: '.$exception->getMessage());
            throw new \InvalidArgumentException('An error occurred while reading the file');
        }

        return $contents;
    }
}
