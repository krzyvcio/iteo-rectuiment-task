<?php

namespace App\Infrastructure\Http\Controller;

use App\Application\Factory\PlaceOrderCommandFactory;
use App\Application\Service\OrderService;
use App\Domain\Exception\InsufficientBalanceException;
use App\Domain\Exception\InvalidOrderDataException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    public function __construct(
        private OrderService             $orderService,
        private PlaceOrderCommandFactory $placeOrderCommandFactory
    )
    {
    }

    /**
     * @Route("/order", name="place_order", methods={"POST"})
     */
    public function placeOrder(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $command = $this->placeOrderCommandFactory->createFromArray($data);
            $this->orderService->placeOrder($command);

            return $this->json(['message' => 'Order placed successfully'], Response::HTTP_OK);
        } catch (InvalidOrderDataException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (InsufficientBalanceException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
