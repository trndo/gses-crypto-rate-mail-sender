<?php

declare(strict_types=1);

namespace App\Tests\Utils\FileSystem;

use App\Utils\FileSystem\FileReader;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class FileReaderTest extends TestCase
{
    private FileReader $fileReader;
    private string $tempDirectory;

    private Filesystem $filesystem;

    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->filesystem = new Filesystem();
        $this->tempDirectory = sys_get_temp_dir().'/file_reader_test';
        $this->filesystem->mkdir($this->tempDirectory);

        $this->logger = $this->createMock(LoggerInterface::class);
        $this->fileReader = new FileReader($this->tempDirectory, $this->filesystem, $this->logger);
        $this->filesystem->mkdir($this->tempDirectory);
    }

    protected function tearDown(): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->tempDirectory);
    }

    public function testGetContentsReturnsFileContents(): void
    {
        $fileName = 'test.txt';
        $fileContents = 'Hello, World!';

        file_put_contents($this->tempDirectory.'/'.$fileName, $fileContents);

        $contents = $this->fileReader->getContents($fileName);

        $this->assertSame($fileContents, $contents);
    }

    public function testGetContentsReturnsNullIfFileDoesNotExist(): void
    {
        $fileName = 'nonexistent.txt';

        $contents = $this->fileReader->getContents($fileName);

        $this->assertNull($contents);
    }
}
