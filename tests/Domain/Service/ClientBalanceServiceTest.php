<?php

namespace App\Tests\Domain\Service;

use App\Domain\Model\Client\ClientBalance;
use App\Domain\Model\Client\ClientId;
use App\Domain\Repository\ClientBalanceRepositoryInterface;
use App\Domain\Repository\ClientRepositoryInterface;
use App\Domain\Service\ClientBalanceService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ClientBalanceServiceTest extends TestCase
{
    private $clientBalanceRepository;
    private $clientBalanceService;
    private $entityManager;
    private $clientRepository;

    protected function setUp(): void
    {
        $this->clientBalanceRepository = $this->createMock(ClientBalanceRepositoryInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->clientRepository = $this->createMock(ClientRepositoryInterface::class);

        $this->clientBalanceService = new ClientBalanceService(
            $this->clientBalanceRepository,
            $this->clientRepository,
            $this->entityManager
        );
    }

    public function testGetClientBalance()
    {
        $clientId = $this->createMock(ClientId::class);
        $expectedBalance = 100.0;

        $clientBalance = $this->createMock(ClientBalance::class);
        $clientBalance->expects($this->once())
            ->method('getBalance')
            ->willReturn($expectedBalance);

        $this->clientBalanceRepository->expects($this->once())
            ->method('findByClientId')
            ->with($clientId)
            ->willReturn($clientBalance);

        $actualBalance = $this->clientBalanceService->getClientBalance($clientId)->getBalance();

        $this->assertSame($expectedBalance, $actualBalance);
    }

    public function testAddBalance()
    {
        $clientId = $this->createMock(ClientId::class);
        $amount = 100.0;

        $clientBalance = $this->createMock(ClientBalance::class);

        $this->clientBalanceRepository->expects($this->exactly(2))
            ->method('findByClientId')
            ->with($clientId)
            ->willReturn($clientBalance);

        $clientBalance->expects($this->once())
            ->method('addBalance')
            ->with($amount);

        $this->clientBalanceService->addBalance($clientId, $amount);


        $this->assertSame($clientBalance, $this->clientBalanceService->getClientBalance($clientId));
    }

    public function testSubtractBalance()
    {
        $clientId = $this->createMock(ClientId::class);
        $amount = 100.0;

        $clientBalance = $this->createMock(ClientBalance::class);

        $this->clientBalanceRepository->expects($this->exactly(2))
            ->method('findByClientId')
            ->with($clientId)
            ->willReturn($clientBalance);

        $clientBalance->expects($this->once())
            ->method('subtractBalance')
            ->with($amount);

        $this->clientBalanceService->subtractBalance($clientId, $amount);

        $this->assertSame($clientBalance, $this->clientBalanceService->getClientBalance($clientId));

    }

    public function testHasEnoughBalance()
    {
        $clientId = $this->createMock(ClientId::class);
        $amount = 100.0;

        $clientBalance = $this->createMock(ClientBalance::class);
        $clientBalance->expects($this->once())
            ->method('getBalance')
            ->willReturn($amount);

        $this->clientBalanceRepository->expects($this->once())
            ->method('findByClientId')
            ->with($clientId)
            ->willReturn($clientBalance);

        $this->assertTrue($this->clientBalanceService->hasEnoughBalance($clientId, $amount));
    }

    public function testGetBalance()
    {
        $clientId = $this->createMock(ClientId::class);
        $amount = 100.0;

        $clientBalance = $this->createMock(ClientBalance::class);
        $clientBalance->expects($this->once())
            ->method('getBalance')
            ->willReturn($amount);

        $this->clientBalanceRepository->expects($this->once())
            ->method('findByClientId')
            ->with($clientId)
            ->willReturn($clientBalance);

        $this->assertSame($amount, $this->clientBalanceService->getBalance($clientId));
    }

    public function testTopupBalance(): void
    {
        $clientId = $this->createMock(ClientId::class);
        $amount = 100.0;

        $clientBalance = $this->createMock(ClientBalance::class);

        $this->clientBalanceRepository->expects($this->exactly(2))
            ->method('findByClientId')
            ->with($clientId)
            ->willReturn($clientBalance);

        $clientBalance->expects($this->once())
            ->method('addBalance')
            ->with($amount);

        $this->clientBalanceService->topupBalance($clientId, $amount);

        $this->assertSame($clientBalance, $this->clientBalanceService->getClientBalance($clientId));
    }
}