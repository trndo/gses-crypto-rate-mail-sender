<?php

declare(strict_types=1);

namespace App\Utils\Mail\Sender;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SwiftMailerEmailSender implements MailSenderInterface
{
    public function __construct(
        private MailerInterface $mailer,
        private LoggerInterface $logger
    ) {}

    public function send(Email $message): void
    {
        try {
            $this->mailer->send($message);
        } catch (\Throwable $exception) {
            $this->logger->info(
                'Email was not sent!',
                [
                    'to' => $message->getTo(),
                    'exception' => $exception->getMessage(),
                ]
            );
        }
    }
}