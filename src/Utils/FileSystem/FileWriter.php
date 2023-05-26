<?php

declare(strict_types=1);

namespace App\Utils\FileSystem;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class FileWriter
{
    public function __construct(
        private readonly string $directory,
        private Filesystem $filesystem
    ) {}

    public function writeTo(string $fileName, string $content): string
    {
        $filePath = $this->composeFullFilePath($fileName);

        try {
            $this->filesystem->dumpFile($filePath, $content);
        } catch (IOExceptionInterface $exception) {
            throw new \InvalidArgumentException(
                "An error occurred while creating the file at $fileName: " . $exception->getMessage()
            );
        }

        return $filePath;
    }

    public function appendTo(string $fileName, string $content): string
    {
        $filePath = $this->composeFullFilePath($fileName);

        if (!$this->filesystem->exists($filePath)) {
            return $this->writeTo($fileName, $content);
        }

        try {
            $this->filesystem->appendToFile($filePath, $content);
        } catch (IOExceptionInterface $exception) {
            throw new \InvalidArgumentException(
                "An error occurred while appending data to the file at $fileName: " . $exception->getMessage()
            );
        }

        return $filePath;
    }

    private function composeFullFilePath(string $fileName): string
    {
        return $this->directory . '/' . $fileName;
    }
}