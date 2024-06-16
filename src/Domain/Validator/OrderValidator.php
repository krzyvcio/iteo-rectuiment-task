<?php

namespace App\Domain\Validator;

use App\Domain\Model\Order\Order;
use App\Presentation\Validator\ValidationException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class OrderValidator
{
    /**
     * @throws ValidationException
     */
    public function validate(Order $order): void
    {
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

        $violations = $validator->validate($order->toArray(), $constraint);

        if (count($violations) > 0) {
            throw new \App\Presentation\Validator\ValidationException($violations);
        }
    }
}
