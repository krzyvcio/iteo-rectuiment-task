<?php

namespace App\Presentation\Controller;

use App\Application\Service\OrderService;
use App\Domain\Model\Order\Order;
use App\Domain\Model\Order\OrderId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class OrderController extends AbstractController
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @Route("/api/orders", methods={"POST"})
     */
    public function createOrder(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $validator = Validation::createValidator();
        $constraint = new Assert\Collection([
            'id' => new Assert\Uuid(),
            'clientId' => new Assert\Uuid(),
            'items' => new Assert\All([
                new Assert\Collection([
                    'id' => new Assert\Uuid(),
                    'quantity' => new Assert\GreaterThan(0),
                ]),
            ]),
        ]);

        $violations = $validator->validate($data, $constraint);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }

            return new Response(json_encode($errors), Response::HTTP_BAD_REQUEST);
        }

        $order = new Order(
            OrderId::fromString($data['id']),
            $data['clientId'],
            $data['items']
        );

        $this->orderService->placeOrder($order);

        return new Response('', Response::HTTP_CREATED);
    }
}