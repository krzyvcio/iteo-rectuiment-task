<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Model\Client\Client;
use App\Domain\Model\Client\ClientId;
use App\Domain\Repository\ClientRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class ClientRepository implements ClientRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findById(ClientId $clientId): ?Client
    {
        return $this->entityManager->getRepository(Client::class)->findOneBy(['id' => $clientId->toString()]);
    }

    public function save(Client $client): void
    {
        $this->entityManager->persist($client);
        $this->entityManager->flush();
    }

    public function update(Client $client): void
    {
        // Doctrine automatically tracks changes made to entities, so we just need to flush them
        $this->entityManager->flush();
    }

    public function delete(Client $client): void
    {
        $this->entityManager->remove($client);
        $this->entityManager->flush();
    }
}