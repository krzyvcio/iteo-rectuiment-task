<?php

namespace App\Domain\Validator;


use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class CreateClientContractValidator
{

    public function validate(array $data): array
    {
        $validator = Validation::createValidator();

        $constraints = new Assert\Collection([
            'clientId' => new Assert\Uuid(),
            'name' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 2, 'max' => 50]),
                new Assert\Uuid(),
            ],
            'balance' => [
                new Assert\NotBlank(),
                new Assert\Type('integer'),
            ]

        ]);

        $violations = $validator->validate($data, $constraints);

        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }
}