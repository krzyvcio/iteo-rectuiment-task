<?php

namespace Tests\Application\Service;

use App\Application\Service\ClientContractContractService;
use App\Domain\Repository\ClientRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ClientContractContractServiceTest extends TestCase
{
    private $clientRepository;
    private $clientContractContractService;

    protected function setUp(): void
    {
        $this->clientRepository = $this->createMock(ClientRepositoryInterface::class);
        $this->clientContractContractService = new ClientContractContractService($this->clientRepository);
    }

    public function testCreateClient(): void
    {
        //
    }
}