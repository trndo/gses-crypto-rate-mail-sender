<?php

declare(strict_types=1);

namespace App\Tests\FileSystem;

use App\Utils\FileSystem\FileWriter;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class FileWriterTest extends TestCase
{
    private const TEST_FILE = 'test_file.txt';

    private FileWriter $fileWriter;

    private Filesystem $filesystemMock;

    private string $tempDirectory;

    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $filesystem = new Filesystem();
        $this->tempDirectory = sys_get_temp_dir().'/file_writer_test';
        $filesystem->mkdir($this->tempDirectory);

        $this->logger = $this->createMock(LoggerInterface::class);
        $this->fileWriter = new FileWriter($this->tempDirectory, $filesystem, $this->logger);
        $this->filesystemMock = $this->createMock(Filesystem::class);
    }

    protected function tearDown(): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->tempDirectory);
    }

    public function testWriteToCreatesFileWithContent(): void
    {
        $fileName = self::TEST_FILE;
        $content = 'Test content 1';

        $filePath = $this->fileWriter->writeTo($fileName, $content);

        $this->assertFileExists($filePath);
        $this->assertSame($content, file_get_contents($filePath));
    }

    public function testWriteToThrowsExceptionOnFailure(): void
    {
        $fileName = self::TEST_FILE;
        $content = 'Test content 2';

        $this->filesystemMock->expects($this->once())
            ->method('dumpFile')
            ->willThrowException(new IOException('Test exception'));

        $fileWriter = new FileWriter($this->tempDirectory, $this->filesystemMock, $this->logger);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('An error occurred while creating the file');

        $fileWriter->writeTo($fileName, $content);
    }

    public function testAppendToWritesContentToFile(): void
    {
        $fileName = self::TEST_FILE;
        $initialContent = 'Initial content';
        $appendedContent = 'Appended content';
        $filePath = $this->tempDirectory.'/'.$fileName;
        file_put_contents($filePath, $initialContent);

        $filePath = $this->fileWriter->appendTo($fileName, $appendedContent);

        $this->assertSame($initialContent.$appendedContent, file_get_contents($filePath));
    }

    public function testAppendToWritesNewFileIfNotExists(): void
    {
        $fileName = self::TEST_FILE;
        $content = 'New file content';

        $filePath = $this->fileWriter->appendTo($fileName, $content);

        $this->assertFileExists($filePath);
        $this->assertSame($content, file_get_contents($filePath));
    }

    public function testAppendToThrowsExceptionOnFailure(): void
    {
        $fileName = self::TEST_FILE;
        $content = 'Test content';

        $this->filesystemMock->expects($this->once())->method('exists')->willReturn(true);
        $this->filesystemMock->expects($this->once())
            ->method('appendToFile')
            ->willThrowException(new IOException('Test exception'));

        $fileWriter = new FileWriter($this->tempDirectory, $this->filesystemMock, $this->logger);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('An error occurred while appending data to the file');

        $fileWriter->appendTo($fileName, $content);
    }
}
