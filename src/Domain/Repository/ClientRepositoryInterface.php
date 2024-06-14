<?php

namespace App\Domain\Repository;

use App\Domain\Model\Client\Client;
use App\Domain\Model\Client\ClientId;

interface ClientRepositoryInterface
{
    public function findById(ClientId $clientId): ?Client;

    public function save(Client $client): void;

    public function update(Client $client): void;

    public function delete(Client $client): void;
}
