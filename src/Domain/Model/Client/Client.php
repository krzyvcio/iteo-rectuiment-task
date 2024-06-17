<?php

namespace App\Domain\Model\Client;

class Client
{
    private ClientId $id;
    private string $name;

    private ClientBalance $balance;


    private bool $isBlocked;

    public function __construct(ClientId $id)
    {
        $this->id = $id;
        $this->isBlocked = false;

    }

    public function getId(): ClientId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function isBlocked(): bool
    {
        return $this->isBlocked;
    }

    public function block(): self
    {
        $this->isBlocked = true;
    }

    public function unblock(): self
    {
        $this->isBlocked = false;
    }

    public function getBalance(): ClientBalance
    {
        return $this->balance;
    }

    public function setBalance(ClientBalance $balance): self
    {
        $this->balance = $balance;
    }

    public function setName(string $getName): self
    {
        $this->name = $getName;
    }
}
