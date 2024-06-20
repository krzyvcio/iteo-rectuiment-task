<?php

namespace App\Presentation\Controller;

use App\Application\Command\CreateClientCommand;
use App\Application\Service\ClientContractContractService;
use App\Domain\Validator\CreateClientContractValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CrmResponseContractController extends AbstractController
{

    public function __construct(private ClientContractContractService $clientService,
                                private CreateClientContractValidator $validator)
    {

    }

    /**
     * @Route("/api/clients", name="create_client", methods={"POST"})
     */
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

}
