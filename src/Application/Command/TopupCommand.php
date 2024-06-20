<?php

namespace App\Application\Command;

class TopupCommand
{
    private int $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    public static function fromArray(mixed $data)
    {
        return new self($data['amount']);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

}