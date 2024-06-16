<?php

namespace Tests\Domain\Repository;

use App\Domain\Model\Client\ClientBalance;
use App\Domain\Model\Client\ClientId;
use App\Domain\Repository\ClientBalanceRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ClientBalanceRepositoryTest extends TestCase
{
    private $repository;
    private $clientBalance;

    protected function setUp(): void
    {
        // Create a mock of the ClientBalanceRepositoryInterface
        $this->repository = $this->createMock(ClientBalanceRepositoryInterface::class);

        // Create a sample ClientBalance for testing
        //TODO: baza testowa
        $clientId = ClientId::fromString('some-client-id');
        $this->clientBalance = new ClientBalance($clientId, 100);
    }

    public function testFindById()
    {
        $clientId = $this->clientBalance->getClientId();

        // Set up the repository mock to return the sample ClientBalance when findById is called with the correct ClientId
        $this->repository->expects($this->once())
            ->method('findById')
            ->with($clientId)
            ->willReturn($this->clientBalance);

        $result = $this->repository->findById($clientId);

        // Assert that the result is an instance of ClientBalance
        $this->assertInstanceOf(ClientBalance::class, $result);

        // Assert that the returned ClientBalance has the correct ClientId
        $this->assertEquals($clientId, $result->getClientId());

        // Assert that the returned ClientBalance has the correct balance
        $this->assertEquals(100, $result->getBalance());
    }

    public function testSave()
    {
        // Set up the repository mock to expect the save method to be called once with the sample ClientBalance
        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->clientBalance);

        $this->repository->save($this->clientBalance);
    }

    public function testUpdate()
    {
        // Set up the repository mock to expect the update method to be called once with the sample ClientBalance
        $this->repository->expects($this->once())
            ->method('update')
            ->with($this->clientBalance);

        $this->repository->update($this->clientBalance);
    }

    public function testDelete()
    {
        // Set up the repository mock to expect the delete method to be called once with the sample ClientBalance
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($this->clientBalance);

        $this->repository->delete($this->clientBalance);
    }
}
