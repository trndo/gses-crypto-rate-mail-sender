<?php

declare(strict_types=1);

namespace App\Utils\Mail\Factory;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mime\Email;

class PlainTextEmailMessageFactory
{
    public function __construct(
        private ParameterBagInterface $parameterBag
    ) {
    }

    public function create(array|string $to, string $body, string $from = null): Email
    {
        $email = new Email();

        if (is_array($to)) {
            $email->to(...$to);
        } else {
            $email->to($to);
        }

        $from = $from ?? (string) $this->parameterBag->get('default_email');

        return $email->from($from)->text($body);
    }
}
