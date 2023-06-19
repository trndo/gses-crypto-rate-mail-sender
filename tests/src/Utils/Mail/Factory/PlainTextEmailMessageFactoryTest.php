<?php

declare(strict_types=1);

namespace src\Utils\Mail\Factory;

use App\Utils\Mail\Factory\PlainTextEmailMessageFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mime\Address;

class PlainTextEmailMessageFactoryTest extends TestCase
{
    private PlainTextEmailMessageFactory $emailFactory;
    private ParameterBagInterface $parameterBag;

    protected function setUp(): void
    {
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->emailFactory = new PlainTextEmailMessageFactory($this->parameterBag);
    }

    public function testCreateWithEmailAddress(): void
    {
        $to = 'recipient@example.com';
        $body = 'Hello, World!';
        $from = 'sender@example.com';

        $email = $this->emailFactory->create($to, $body, $from);

        $this->assertSame($to, $email->getTo()[0]->getAddress());
        $this->assertSame($from, $email->getFrom()[0]->getAddress());
        $this->assertSame($body, $email->getTextBody());
    }

    public function testCreateWithMultipleEmailAddresses(): void
    {
        $to = ['recipient1@example.com', 'recipient2@example.com'];
        $body = 'Hello, World!';
        $defaultFrom = 'sender@example.com';

        $this->parameterBag->expects($this->once())
            ->method('get')
            ->with('default_email')
            ->willReturn($defaultFrom);

        $email = $this->emailFactory->create($to, $body);

        $this->assertSame($to, array_map(fn (Address $a) => $a->getAddress(), $email->getTo()));
        $this->assertSame($defaultFrom, $email->getFrom()[0]->getAddress());
        $this->assertSame($body, $email->getTextBody());
    }

    public function testCreateWithDefaultFromEmailAddress(): void
    {
        $to = 'recipient@example.com';
        $body = 'Hello, World!';
        $defaultFrom = 'default@example.com';

        $this->parameterBag->expects($this->once())
            ->method('get')
            ->with('default_email')
            ->willReturn($defaultFrom);

        $email = $this->emailFactory->create($to, $body);

        $this->assertSame($to, $email->getTo()[0]->getAddress());
        $this->assertSame($defaultFrom, $email->getFrom()[0]->getAddress());
        $this->assertSame($body, $email->getTextBody());
    }
}
