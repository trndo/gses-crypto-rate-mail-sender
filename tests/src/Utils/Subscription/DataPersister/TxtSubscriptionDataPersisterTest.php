<?php

declare(strict_types=1);

namespace App\Tests\Subscription\DataPersister;

use App\Utils\FileSystem\FileWriter;
use App\Utils\Subscription\DataProvider\SubscriptionDataProviderInterface;
use App\Utils\Subscription\Persister\TxtSubscriptionDataPersister;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class TxtSubscriptionDataPersisterTest extends TestCase
{
    private string $tempDirectory;
    private TxtSubscriptionDataPersister $txtDataPersister;
    private SubscriptionDataProviderInterface $dataProvider;

    protected function setUp(): void
    {
        $filesystem = new Filesystem();
        $this->tempDirectory = sys_get_temp_dir().'/txt_data_persister_test';
        $filesystem->mkdir($this->tempDirectory);

        $logger = $this->createMock(LoggerInterface::class);
        $fileWriter = new FileWriter($this->tempDirectory, new Filesystem(), $logger);
        $this->dataProvider = $this->createMock(SubscriptionDataProviderInterface::class);
        $this->txtDataPersister = new TxtSubscriptionDataPersister($fileWriter, $this->dataProvider);
    }

    protected function tearDown(): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->tempDirectory);
    }

    public function testStoreReturnsFalseIfEmailExists(): void
    {
        $email = 'existing_email@example.com';
        $this->dataProvider->expects($this->once())->method('ifEmailExists')->willReturn(true);

        $result = $this->txtDataPersister->store($email);

        $this->assertFalse($result);
    }

    public function testStoreReturnsTrueAndAppendsEmailToFileIfEmailDoesNotExist(): void
    {
        $email = 'new_email@example.com';
        $this->dataProvider->expects($this->once())->method('ifEmailExists')->willReturn(false);

        $result = $this->txtDataPersister->store($email);

        $this->assertTrue($result);
        $this->assertSame(
            $email.',',
            file_get_contents($this->tempDirectory.'/emails.txt')
        );
    }
}
