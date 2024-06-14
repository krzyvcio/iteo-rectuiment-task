<?php

namespace App\Infrastructure\Http\Controller;

use App\Application\Command\PlaceOrderCommand;
use App\Application\Service\OrderService;
use App\Domain\Service\ClientBalanceServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private OrderService $orderService;
    private ClientBalanceServiceInterface $clientBalanceService;

    public function __construct(OrderService $orderService, ClientBalanceServiceInterface $clientBalanceService)
    {
        $this->orderService = $orderService;
        $this->clientBalanceService = $clientBalanceService;
    }

    /**
     * @Route("/order", name="place_order", methods={"POST"})
     */
    public function placeOrder(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $clientId = $data['clientId'];
        $products = $data['products'];

        if (!$this->clientBalanceService->hasEnoughBalance($clientId, $this->calculateTotalPrice($products))) {
            return $this->json(['error' => 'Insufficient client balance'], Response::HTTP_BAD_REQUEST);
        }

        $command = new PlaceOrderCommand($clientId, $products);
        $this->orderService->placeOrder($command);

        return $this->json(['message' => 'Order placed successfully'], Response::HTTP_OK);
    }

    private function calculateTotalPrice(array $products): float
    {
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $product['price'] * $product['quantity'];
        }
        return $totalPrice;
    }
}
