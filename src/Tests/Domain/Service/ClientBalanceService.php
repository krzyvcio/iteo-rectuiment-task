<?php

namespace Tests\Domain\Service;

use App\Domain\Model\Client\ClientBalance;
use App\Domain\Model\Client\ClientId;
use App\Domain\Repository\ClientBalanceRepositoryInterface;
use App\Domain\Service\ClientBalanceService;
use PHPUnit\Framework\TestCase;

class ClientBalanceServiceTest extends TestCase
{
    private $repository;
    private $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ClientBalanceRepositoryInterface::class);
        $this->service = new ClientBalanceService($this->repository);
    }

    public function testGetBalance()
    {
        $clientId = ClientId::fromString('some-client-id');
        $clientBalance = new ClientBalance($clientId, 100);

        $this->repository->expects($this->once())
            ->method('findById')
            ->with($clientId)
            ->willReturn($clientBalance);

        $balance = $this->service->getBalance($clientId);

        $this->assertEquals(100, $balance);
    }

    public function testAddBalance()
    {
        $clientId = ClientId::fromString('some-client-id');
        $clientBalance = new ClientBalance($clientId, 100);

        $this->repository->expects($this->once())
            ->method('findById')
            ->with($clientId)
            ->willReturn($clientBalance);

        $this->repository->expects($this->once())
            ->method('update')
            ->with($clientBalance);

        $this->service->addBalance($clientId, 50);

        $this->assertEquals(150, $clientBalance->getBalance());
    }

    public function testSubtractBalance()
    {
        $clientId = ClientId::fromString('some-client-id');
        $clientBalance = new ClientBalance($clientId, 100);

        $this->repository->expects($this->once())
            ->method('findById')
            ->with($clientId)
            ->willReturn($clientBalance);

        $this->repository->expects($this->once())
            ->method('update')
            ->with($clientBalance);

        $this->service->subtractBalance($clientId, 30);

        $this->assertEquals(70, $clientBalance->getBalance());
    }

    public function testHasEnoughBalance()
    {
        $clientId = ClientId::fromString('some-client-id');
        $clientBalance = new ClientBalance($clientId, 100);

        $this->repository->expects($this->once())
            ->method('findById')
            ->with($clientId)
            ->willReturn($clientBalance);

        $this->assertTrue($this->service->hasEnoughBalance($clientId, 80));
        $this->assertFalse($this->service->hasEnoughBalance($clientId, 120));
    }
}
