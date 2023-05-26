<?php

declare(strict_types=1);

namespace App\Tests\Utils\CurrencyRateComparator\Comparator;

use App\Utils\CurrencyRateComparator\Comparator\CoinGateCurrencyRateComparator;
use App\Utils\CurrencyRateComparator\Currency;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class CoinGateCurrencyRateComparatorTest extends TestCase
{
    protected CoinGateCurrencyRateComparator $rateComparator;

    protected MockHttpClient $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = new MockHttpClient();
        $this->rateComparator = new CoinGateCurrencyRateComparator($this->httpClient);
    }

    protected function tearDown(): void
    {
        unset($this->httpClient, $this->rateComparator);
    }

    public function testCompareReturnFloat(): void
    {
        $mockResponse = new MockResponse(json_encode(979094.38));
        $this->httpClient->setResponseFactory($mockResponse);

        $result = $this->rateComparator->compare(Currency::BTC, Currency::UAH);

        $this->assertNotNull($result);
        $this->assertIsFloat($result);
        $this->assertEquals(979094.38, $result);
    }
}