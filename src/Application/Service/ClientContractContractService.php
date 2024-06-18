<?php

namespace App\Application\Service;

use App\Application\Command\CreateClientCommand;
use App\Domain\Model\Client\Client;
use App\Domain\Model\Client\ClientBalance;
use App\Domain\Model\Client\ClientId;
use App\Domain\Repository\ClientRepositoryInterface;

class ClientContractContractService implements ClientContractServiceInterface
{
    private ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function createClient(CreateClientCommand $command): Client
    {
        $clientBalance = new ClientBalance($command->getBalance());
        $clientId = ClientId::fromString($command->getClientId()->toString());

        $client = new Client($clientId);
        $client->setName($command->getName());
        $client->setBalance($clientBalance);


        $this->clientRepository->save($client);

        return $client;
    }

    public function getClientById(ClientId $clientId): ?Client
    {
        return $this->clientRepository->findById($clientId);
    }
}
