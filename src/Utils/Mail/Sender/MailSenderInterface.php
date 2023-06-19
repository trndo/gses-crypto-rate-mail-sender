<?php

declare(strict_types=1);

namespace App\Utils\Mail\Sender;

use Symfony\Component\Mime\Email;

interface MailSenderInterface
{
    public function send(Email $email): void;
}
