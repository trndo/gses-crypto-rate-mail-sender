<?php

declare(strict_types=1);

namespace src\Utils\Subscription\DataProvider;

use App\Utils\FileSystem\FileReader;
use App\Utils\Subscription\DataProvider\TxtDataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class TxtDataProviderTest extends TestCase
{
    private const STRING_DATA_FROM_FILES = 'email1@example.com,email2@example.com,email3@example.com';
    private const EMAILS = ['email1@example.com', 'email2@example.com', 'email3@example.com'];
    private const FILE_NAME = 'emails.txt';

    private string $tempDirectory;
    private TxtDataProvider $txtDataProvider;

    protected function setUp(): void
    {
        $filesystem = new Filesystem();
        $this->tempDirectory = sys_get_temp_dir() . '/txt_data_provider_test';
        $filesystem->mkdir($this->tempDirectory);

        $fileReader = new FileReader($this->tempDirectory, $filesystem);
        $this->txtDataProvider = new TxtDataProvider($fileReader);
    }

    protected function tearDown(): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->tempDirectory);
    }

    public function testGetAllReturnsEmptyArrayIfFileNotExists(): void
    {
        $emails = $this->txtDataProvider->getAll();

        $this->assertEmpty($emails);
    }

    public function testIfEmailExistsReturnsFalseIfEmailNotExists(): void
    {
        $email = 'non_existent_email@example.com';

        $exists = $this->txtDataProvider->ifEmailExists($email);

        $this->assertFalse($exists);
    }

    public function testGetAllReturnsEmailsArrayFromFileContents(): void
    {
        $fileName = self::FILE_NAME;
        $content = implode(',', self::EMAILS);
        $filePath = $this->tempDirectory . '/' . $fileName;
        file_put_contents($filePath, $content);

        $emails = $this->txtDataProvider->getAll();

        $this->assertEquals(self::EMAILS, $emails);
    }

    public function testIfEmailExistsReturnsTrueIfEmailExists(): void
    {
        $fileName = 'emails.txt';
        $content = implode(',', self::EMAILS);
        $filePath = $this->tempDirectory . '/' . $fileName;
        file_put_contents($filePath, $content);

        $email = 'email2@example.com';

        $exists = $this->txtDataProvider->ifEmailExists($email);

        $this->assertTrue($exists);
    }
}