<?php

declare(strict_types=1);

namespace App\Utils\FileSystem;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class FileReader
{
    public function __construct(
        private string $directory,
        private Filesystem $filesystem
    ) {}

    public function getContents(string $fileName): ?string
    {
        $filePath = $this->directory . '/' . $fileName;

        if (!$this->filesystem->exists($filePath)) {
            return null;
        }

        //TODO change error handling Exception
        try {
            $contents = file_get_contents($filePath);

            if (!$contents) {
                return null;
            }
        } catch (\Throwable $exception) {
            throw new \InvalidArgumentException(
                "An error occurred while reading the file at $fileName: " . $exception->getMessage()
            );
        }

        return $contents;
    }
}