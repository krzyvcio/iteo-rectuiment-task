<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Model\Client\ClientId;
use App\Domain\Repository\ClientIdRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class ClientIdRepository implements ClientIdRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }


    public function findById(string $clientId): ?ClientId
    {
        return $this->entityManager
            ->getRepository(ClientId::class)
            ->findOneBy(['id' => $clientId]);
    }

}