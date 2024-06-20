<?php

namespace App\Domain\Validator;

use App\Domain\Model\Order\Order;
use App\Domain\Model\Order\OrderItem;
use App\Presentation\Validator\ValidationException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class OrderValidator
{
    /**
     * @throws ValidationException
     */
    public function validateInput(Order $order): bool
    {
        $validator = Validation::createValidator();

        $constraint = new Assert\Collection([
            'id' => new Assert\Uuid(),
            'clientId' => new Assert\Uuid(),
            'items' => new Assert\All([
                new Assert\Collection([
                    'productId' => new Assert\Uuid(),
                    'quantity' => new Assert\GreaterThan(0),
                    'price' => new Assert\GreaterThan(0),
                    'weight' => new Assert\GreaterThan(0),
                ]),
            ]),
        ]);

        $orderItems = array_map(function ($itemData) {
            return new OrderItem(
                ProductId::fromString($itemData['productId']),
                $itemData['quantity'],
                $itemData['price'],
                $itemData['weight']
            );
        }, $order->getItems());

        $data = [
            'id' => $order->getId()->toString(),
            'clientId' => $order->getClientId()->toString(),
            'items' => array_map(function (OrderItem $item) {
                return [
                    'productId' => $item->getProductId()->toString(),
                    'quantity' => $item->getQuantity(),
                    'price' => $item->getPrice(),
                    'weight' => $item->getWeight(),
                ];
            }, $orderItems),
        ];

        $violations = $validator->validate($data, $constraint);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
            }
            throw new ValidationException(implode(', ', $errors));
        }

        return true;
    }


    public function isOrderValid(Order $order): bool
    {
        $orderItems = $order->getItems();

        if (count($orderItems) < 5) {
            return false;
        }

        // summary weight of all products in order
        $totalWeight = 0;
        /* @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {
            $totalWeight += $orderItem->getWeight() * $orderItem->getQuantity();
        }

        if ($totalWeight > 24000) { // 24 tons in kilograms
            return false;
        }

        return true;
    }
}