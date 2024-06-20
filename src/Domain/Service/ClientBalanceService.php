<?php

namespace App\Domain\Service;

use App\Domain\Model\Client\ClientBalance;
use App\Domain\Model\Client\ClientId;
use App\Domain\Repository\ClientBalanceRepositoryInterface;
use App\Domain\Repository\ClientRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class ClientBalanceService implements ClientBalanceServiceInterface
{

    public function __construct(
        private readonly ClientBalanceRepositoryInterface $clientBalanceRepository,
        private readonly ClientRepositoryInterface        $clientRepository,
        private readonly EntityManagerInterface           $entityManager,
    )
    {
    }

    public function getClientBalance(ClientId $clientId): ?ClientBalance
    {
        return $this->clientBalanceRepository->findByClientId($clientId);
    }

    public function addBalance(ClientId $clientId, float $amount): void
    {
        $clientBalance = $this->getClientBalance($clientId);

        $this->entityManager->beginTransaction();
        try {
            if ($clientBalance === null) {
                $clientBalance = new ClientBalance($clientId);
                $clientBalance->addBalance($amount);
                $this->clientBalanceRepository->save($clientBalance);
            } else {
                $clientBalance->addBalance($amount);
                $this->clientBalanceRepository->update($clientBalance);
            }
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function subtractBalance(ClientId $clientId, float $amount): void
    {
        $clientBalance = $this->getClientBalance($clientId);

        if ($clientBalance === null) {
            throw new \InvalidArgumentException('Client balance not found');
        }

        $this->entityManager->beginTransaction();
        try {
            $clientBalance->subtractBalance($amount);
            $this->clientBalanceRepository->update($clientBalance);
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    public function hasEnoughBalance(ClientId $clientId, float $amount): bool
    {
        $clientBalance = $this->getClientBalance($clientId);

        if ($clientBalance === null) {
            return false;
        }

        return $clientBalance->getBalance() >= $amount;
    }

    public function getBalance(ClientId $clientId): float
    {
        $clientBalance = $this->getClientBalance($clientId);

        if ($clientBalance === null) {
            throw new \InvalidArgumentException('Client balance not found');
        }

        return $clientBalance->getBalance();
    }

    /**
     * @throws \Exception
     */
    public function topupBalance(ClientId $clientId, int $getAmount): void
    {

        $clientBalance = $this->getClientBalance($clientId);

        if ($clientBalance === null) {
            throw new \InvalidArgumentException('Client balance not found');
        }

        $this->entityManager->beginTransaction();
        try {
            $clientBalance->addBalance($getAmount);
            $this->clientBalanceRepository->update($clientBalance);
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}
