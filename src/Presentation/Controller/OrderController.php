<?php

namespace App\Presentation\Controller;

use App\Application\Command\PlaceOrderCommand;
use App\Application\Service\OrderService;
use App\Domain\Validator\OrderValidator;
use App\Presentation\Validator\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @Route("/api/orders", methods={"POST"})
     * @throws ValidationException
     */
    public function createOrder(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);

            OrderValidator::validate($data);


            $orderCommand = new PlaceOrderCommand(
                $this->orderService->createOrder($data)
            );
            //sprawdzenie zamowienia klienta

            // jesli poprawne to odejmujemy od salda

            //jesli nie to blokujemy klienta

            return new Response('Order placed', Response::HTTP_CREATED);
        } catch (
        ValidationException $e
        ) {
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

    }
}