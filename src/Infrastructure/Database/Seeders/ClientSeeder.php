<?php

namespace App\Infrastructure\Database\Seeders;

use App\Domain\Model\Client\Client;
use App\Domain\Model\Client\ClientBalance;
use App\Domain\Model\Client\ClientId;
use Doctrine\ORM\EntityManagerInterface;

class ClientSeeder
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $clientId = ClientId::generate();
            $client = new Client($clientId);
            $client->setName('Client ' . $i);

            $clientBalance = new ClientBalance($clientId);
            $clientBalance->setId($clientId->toString()); // ustawienie id jako string
            $clientBalance->setBalance(1000.0 * $i); // ustawienie salda
            $clientBalance->setCurrency('USD');
            $client->setBalance($clientBalance);

            $this->entityManager->persist($client);
            $this->entityManager->persist($clientBalance);
        }

        $this->entityManager->flush();
    }
}