<?php

declare(strict_types=1);

namespace src\Utils\Mail\Sender;

use App\Utils\Mail\Sender\SwiftMailerEmailSender;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SwiftMailerEmailSenderTest extends TestCase
{
    private SwiftMailerEmailSender $emailSender;
    private MailerInterface $mailer;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->emailSender = new SwiftMailerEmailSender($this->mailer, $this->logger);
    }

    public function testSendSuccessfully(): void
    {
        $message = new Email();
        $this->mailer->expects($this->once())->method('send')->with($message);

        $this->emailSender->send($message);
    }

    public function testSendLogsErrorIfExceptionThrown(): void
    {
        $message = new Email();

        $exceptionMessage = 'An error occurred';

        $this->mailer->expects($this->once())
            ->method('send')
            ->with($message)
            ->willThrowException(new \Exception($exceptionMessage));

        $this->logger->expects($this->once())
            ->method('info')
            ->with(
                'Email was not sent!',
                [
                    'to' => $message->getTo(),
                    'exception' => $exceptionMessage,
                ]
            );

        $this->emailSender->send($message);
    }
}
