<?php

declare(strict_types=1);

namespace App\EventSubscriber\Exception;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ParameterBagInterface $parameterBag
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = new JsonResponse(
            [
                'message' => $exception->getMessage(),
                'trace' => 'dev' === $this->parameterBag->get('env')
                    ? $exception->getTraceAsString()
                    : null,
            ],
            method_exists($exception, 'getStatusCode')
                ? $exception->getStatusCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR
        );

        $event->setResponse($response);
    }
}
