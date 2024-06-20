<?php

namespace App\Application\Command;

use App\Domain\Model\Client\ClientBalance;
use App\Domain\Model\Client\ClientId;

class CreateClientCommand
{
    private ClientId $clientId;
    private string $name;
    private ClientBalance $clientBalance;

    private function __construct(ClientId $clientId, string $name)
    {
        $this->clientId = $clientId;
        $this->name = $name;
    }

    public static function fromArray(array $data): self
    {
        $clientId = ClientId::fromString($data['clientId']);
        $command = new self($clientId, $data['name']);
        $clientBalance = ClientBalance::fromIdAndBalance($clientId, $data['balance']);
        $command->setClientBalance($clientBalance);

        return $command;
    }

    public function getClientId(): ClientId
    {
        return $this->clientId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getClientBalance(): ClientBalance
    {
        return $this->clientBalance;
    }

    public function setClientBalance(ClientBalance $clientBalance): void
    {
        $this->clientBalance = $clientBalance;
    }
}
