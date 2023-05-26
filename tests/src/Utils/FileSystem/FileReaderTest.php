<?php

declare(strict_types=1);

namespace src\Utils\FileSystem;

use App\Utils\FileSystem\FileReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class FileReaderTest extends TestCase
{
    private const TEST_DATA_STRING_FROM_FILE = 'sample_data_string';
    private const TEST_DATA_DIRECTORY = 'tests/data';
    private FileReader $fileReader;

    private ?Filesystem $fileSystem;

    protected function setUp(): void
    {
        $this->fileSystem = $this->createMock(Filesystem::class);
        $this->fileReader = new FileReader(self::TEST_DATA_DIRECTORY, $this->fileSystem);
    }

    protected function tearDown(): void
    {
        unset($this->fileSystem, $this->fileReader);
    }

    public function testReadFromExistingFile(): void
    {
        $fileName = 'test.txt';

        $this->fileSystem->expects($this->once())
            ->method('exists')
            ->with(self::TEST_DATA_DIRECTORY . '/' . $fileName)
            ->willReturn(true);

       $result = $this->fileReader->getContents($fileName);
       $this->assertEquals(self::TEST_DATA_STRING_FROM_FILE, $result);
    }

    public function testReadFromNotExistingFile(): void
    {
        $fileName = 'test_1.txt';

        $this->fileSystem->expects($this->once())
            ->method('exists')
            ->with(self::TEST_DATA_DIRECTORY . '/' . $fileName)
            ->willReturn(false);

        $result = $this->fileReader->getContents($fileName);
        $this->assertEquals(null, $result);
    }

    public function testReadFromExistingFileThrowException(): void
    {
        $fileName = 'test_2.txt';

        $this->expectException(\InvalidArgumentException::class);

        $this->fileSystem->expects($this->once())
            ->method('exists')
            ->with(self::TEST_DATA_DIRECTORY . '/' . $fileName)
            ->willReturn(true);

        $this->fileReader->getContents($fileName);
    }
}