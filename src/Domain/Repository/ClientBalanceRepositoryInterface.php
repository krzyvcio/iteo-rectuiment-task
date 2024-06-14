<?php

namespace App\Domain\Repository;

use App\Domain\Model\Client\ClientBalance;
use App\Domain\Model\Client\ClientId;

interface ClientBalanceRepositoryInterface
{
    public function findByClientId(ClientId $clientId): ?ClientBalance;

    public function save(ClientBalance $clientBalance): void;

    public function update(ClientBalance $clientBalance): void;

    public function delete(ClientBalance $clientBalance): void;

    
}
