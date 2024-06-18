<?php

namespace App\Application\Command;

use App\Domain\Model\Client\ClientBalance;
use App\Domain\Model\Client\ClientId;

class CreateClientCommand
{
    private ClientId $clientId;
    private string $name;
    private ClientBalance $balance;

    public function __construct(ClientId $clientId, string $name)
    {
        $this->clientId = $clientId;
        $this->name = $name;
    }

    public function getClientId(): ClientId
    {
        return $this->clientId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setClientBalance(ClientBalance $balance): void
    {
        $this->balance = $balance;
    }

    public function getBalance(): ClientBalance
    {
        return $this->balance;
    }
}