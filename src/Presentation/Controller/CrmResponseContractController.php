<?php

namespace App\Presentation\Controller;

use App\Application\Command\CreateClientCommand;
use App\Application\Command\TopupCommand;
use App\Application\Service\ClientContractContractService;
use App\Domain\Repository\ClientIdRepositoryInterface;
use App\Domain\Service\ClientBalanceServiceInterface;
use App\Domain\Validator\CreateClientContractValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CrmResponseContractController extends AbstractController
{


    public function __construct(
        private ClientContractContractService $clientService,
        private CreateClientContractValidator $validator,
        private ClientBalanceServiceInterface $clientBalanceService,
        private ClientIdRepositoryInterface $clientIdService,
    )
    {

    }


    #[Route('/api/clients', name: 'create_client', methods: ['POST'])]
    public function createClient(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $errors = $this->validator->validate($data);

        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $command = CreateClientCommand::fromArray($data);

        try {
            $this->clientService->createClient($command);

            return new JsonResponse(['message' => 'Client created successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/api/clients/{clientId}/balance/topup', name: 'topup_client_balance', methods: ['POST'])]
    public function topupClientBalance(Request $request, string $clientId): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Walidacja danych doładowania
        $errors = $this->validator->validateTopupData($data);

        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        // Deserializacja danych do obiektu TopupCommand
        $command = TopupCommand::fromArray($data);

        try {
            $clientId =  $this->clientIdService->findById($clientId)
            // Wywołanie serwisu do obsługi doładowania salda klienta
            $this->clientBalanceService->topupBalance($clientId, $command->getAmount());

            return new JsonResponse(['message' => 'Client balance topped up successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }


}
