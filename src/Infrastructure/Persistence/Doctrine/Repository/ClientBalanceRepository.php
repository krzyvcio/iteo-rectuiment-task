<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Model\Client\ClientBalance;
use App\Domain\Model\Client\ClientId;
use App\Domain\Repository\ClientBalanceRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ClientBalanceRepository extends ServiceEntityRepository implements ClientBalanceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientBalance::class);
    }

    public function findByClientId(ClientId $clientId): ?ClientBalance
    {
        return $this->findOneBy(['clientId' => $clientId]);
    }

    public function save(ClientBalance $clientBalance): void
    {
        $this->getEntityManager()->persist($clientBalance);
        $this->getEntityManager()->flush();
    }

    public function update(ClientBalance $clientBalance): void
    {
        $this->getEntityManager()->flush();
    }
}
