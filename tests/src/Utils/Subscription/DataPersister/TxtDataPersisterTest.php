<?php

declare(strict_types=1);

namespace App\Tests\Subscription\DataPersister;

use App\Utils\FileSystem\FileWriter;
use App\Utils\Subscription\DataProvider\DataProviderInterface;
use App\Utils\Subscription\Persister\TxtDataPersister;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class TxtDataPersisterTest extends TestCase
{
    private string $tempDirectory;
    private TxtDataPersister $txtDataPersister;
    private DataProviderInterface $dataProvider;

    protected function setUp(): void
    {
        $filesystem = new Filesystem();
        $this->tempDirectory = sys_get_temp_dir() . '/txt_data_persister_test';
        $filesystem->mkdir($this->tempDirectory);

        $fileWriter = new FileWriter($this->tempDirectory, new Filesystem());
        $this->dataProvider = $this->createMock(DataProviderInterface::class);
        $this->txtDataPersister = new TxtDataPersister($fileWriter, $this->dataProvider);
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
        $this->assertEquals(
            $email . ',',
            file_get_contents($this->tempDirectory . '/emails.txt')
        );
    }
}