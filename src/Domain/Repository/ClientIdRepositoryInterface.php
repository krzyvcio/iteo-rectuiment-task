<?php

namespace App\Domain\Repository;

use App\Domain\Model\Client\ClientId;

interface ClientIdRepositoryInterface
{

    public function findById(string $clientId): ?ClientId;
}