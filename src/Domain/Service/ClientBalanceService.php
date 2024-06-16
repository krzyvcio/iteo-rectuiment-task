<?php

namespace App\Domain\Service;

use App\Domain\Model\Client\ClientBalance;
use App\Domain\Model\Client\ClientId;
use App\Domain\Repository\ClientBalanceRepositoryInterface;

class ClientBalanceService implements ClientBalanceServiceInterface
{
    private ClientBalanceRepositoryInterface $clientBalanceRepository;

    public function __construct(ClientBalanceRepositoryInterface $clientBalanceRepository)
    {
        $this->clientBalanceRepository = $clientBalanceRepository;
    }

    public function getClientBalance(ClientId $clientId): ?ClientBalance
    {
        return $this->clientBalanceRepository->findByClientId($clientId);
    }

    public function addBalance(ClientId $clientId, float $amount): void
    {
        $clientBalance = $this->getClientBalance($clientId);

        if ($clientBalance === null) {
            $clientBalance = new ClientBalance($clientId);
            $clientBalance->addBalance($amount);
            $this->clientBalanceRepository->save($clientBalance);
        } else {
            $clientBalance->addBalance($amount);
            $this->clientBalanceRepository->update($clientBalance);
        }
    }

    public function subtractBalance(ClientId $clientId, float $amount): void
    {
        $clientBalance = $this->getClientBalance($clientId);

        if ($clientBalance === null) {
            throw new \InvalidArgumentException('Client balance not found');
        }

        $clientBalance->subtractBalance($amount);
        $this->clientBalanceRepository->update($clientBalance);
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
}
