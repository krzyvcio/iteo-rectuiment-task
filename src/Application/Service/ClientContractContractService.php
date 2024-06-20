<?php

namespace App\Application\Service;

use App\Application\Command\CreateClientCommand;
use App\Domain\Model\Client\Client;
use App\Domain\Model\Client\ClientBalance;
use App\Domain\Model\Client\ClientId;
use App\Domain\Repository\ClientRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class ClientContractContractService implements ClientContractServiceInterface
{

    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
        private readonly EntityManagerInterface    $entityManager

    )
    {

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
        return $this->clientRepository->findByClientId($clientId);
    }

    /**
     * @throws \Exception
     */
    public function blockClient(ClientId $clientId): void
    {
        $client = $this->clientRepository->findByClientId($clientId);

        if ($client === null) {
            throw new \InvalidArgumentException('Client not found');
        }

        $this->entityManager->beginTransaction();
        try {
            $client->block();
            $this->clientRepository->update($client);
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}
