<?php

namespace App\Domain\Service;

use App\Domain\Model\Client\ClientBalance;
use App\Domain\Model\Client\ClientId;

interface ClientBalanceServiceInterface
{
    public function getClientBalance(ClientId $clientId): ?ClientBalance;

    public function addBalance(ClientId $clientId, float $amount): void;

    public function subtractBalance(ClientId $clientId, float $amount): void;

    public function hasEnoughBalance(ClientId $clientId, float $amount): bool;

    public function topupBalance(ClientId $clientId, int $getAmount);
}
