<?php

namespace App\Application\Service;

use App\Application\Command\CreateClientCommand;
use App\Domain\Model\Client\Client;
use App\Domain\Model\Client\ClientId;

interface ClientServiceInterface
{
    public function createClient(CreateClientCommand $command): Client;

    public function getClientById(ClientId $clientId): ?Client;
}
