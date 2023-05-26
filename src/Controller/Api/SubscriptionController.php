<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Utils\Subscription\Persister\DataPersisterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class SubscriptionController
{
    public function __construct(
        private DataPersisterInterface $dataPersister,
        private ValidatorInterface $validator,
    ) {}

    #[Route('/subscribe', methods: 'POST')]
    public function write(Request $request): JsonResponse
    {
        //TODO add validation
        $email = $request->request->get('email');

        $isStored = $this->dataPersister->store($email);

        return $isStored
            ? new JsonResponse(
                [
                    'message' => 'Email was added',
                ],
            )
            : new JsonResponse(
                [
                    'message' => 'Email is already added',
                ],
                Response::HTTP_CONFLICT
            );
    }
}