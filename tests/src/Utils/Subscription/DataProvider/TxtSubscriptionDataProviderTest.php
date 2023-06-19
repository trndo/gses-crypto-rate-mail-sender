<?php

declare(strict_types=1);

namespace App\Tests\Subscription\DataProvider;

use App\Utils\FileSystem\FileReader;
use App\Utils\Subscription\DataProvider\TxtSubscriptionDataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class TxtSubscriptionDataProviderTest extends TestCase
{
    private const EMAILS = ['email1@example.com', 'email2@example.com', 'email3@example.com'];
    private const FILE_NAME = 'emails.txt';

    private string $tempDirectory;
    private TxtSubscriptionDataProvider $txtDataProvider;

    protected function setUp(): void
    {
        $filesystem = new Filesystem();
        $this->tempDirectory = sys_get_temp_dir().'/txt_data_provider_test';
        $filesystem->mkdir($this->tempDirectory);

        $logger = $this->createMock(LoggerInterface::class);
        $fileReader = new FileReader($this->tempDirectory, $filesystem, $logger);
        $this->txtDataProvider = new TxtSubscriptionDataProvider($fileReader);
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
        $filePath = $this->tempDirectory.'/'.$fileName;
        file_put_contents($filePath, $content);

        $emails = $this->txtDataProvider->getAll();

        $this->assertSame(self::EMAILS, $emails);
    }

    public function testIfEmailExistsReturnsTrueIfEmailExists(): void
    {
        $fileName = 'emails.txt';
        $content = implode(',', self::EMAILS);
        $filePath = $this->tempDirectory.'/'.$fileName;
        file_put_contents($filePath, $content);

        $email = 'email2@example.com';

        $exists = $this->txtDataProvider->ifEmailExists($email);

        $this->assertTrue($exists);
    }
}
