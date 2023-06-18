<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Utils\CurrencyRateComparator\Currency;
use App\Utils\CurrencyRateComparator\CurrencyRateComparatorInterface;
use App\Utils\Mail\Factory\PlainTextEmailMessageFactory;
use App\Utils\Mail\Sender\MailSenderInterface;
use App\Utils\Subscription\DataProvider\SubscriptionDataProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class CurrencyRateEmailController
{
    public function __construct(
        private CurrencyRateComparatorInterface $rateComparator,
        private MailSenderInterface $mailSender,
        private PlainTextEmailMessageFactory $emailMessageFactory,
    ) {
    }

    #[Route('/sendEmails', methods: 'POST')]
    public function sendRates(SubscriptionDataProviderInterface $dataProvider): JsonResponse
    {
        $rate = $this->rateComparator->compare(Currency::BTC, Currency::UAH);
        $subscribers = $dataProvider->getAll();

        if (empty($subscribers)) {
            return new JsonResponse(
                [
                    'Emails were not found!',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $message = $this->emailMessageFactory->create(
            $subscribers,
            'Dear subscriber, the current Bitcoin exchange rate in Hryvnia (UAH) is '.$rate,
        );
        $this->mailSender->send($message);

        return new JsonResponse(
            [
                'message' => 'Message was sent!',
            ]
        );
    }
}
