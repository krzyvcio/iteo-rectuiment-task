<?php

namespace App\Presentation\Controller;

use App\Application\Service\CrmOrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CrmResponseController extends AbstractController
{
    private CrmOrderService $crmOrderService;

    public function __construct(CrmOrderService $crmOrderService)
    {
        $this->crmOrderService = $crmOrderService;
    }

    #[Route('/crm/order-response', name: 'crm_order_response', methods: ['POST'])
    public function handleOrderResponse(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $this->crmOrderService->handleOrderResponse($data);

            return new JsonResponse(['message' => 'Order response handled successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
