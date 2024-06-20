<?php

namespace App\Tests\Application\Service;

use App\Application\Command\PlaceOrderCommand;
use App\Application\Service\OrderService;
use App\Domain\Model\Client\Client;
use App\Domain\Model\Client\ClientId;
use App\Domain\Model\Order\OrderId;
use App\Domain\Repository\ClientRepositoryInterface;
use App\Domain\Repository\OrderRepositoryInterface;
use App\Domain\Service\ClientBalanceServiceInterface;
use App\Domain\Validator\OrderValidator;
use App\Presentation\Validator\ValidationException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcher;

class OrderServiceTest extends TestCase
{
    private $orderRepository;
    private $clientRepository;
    private $clientBalanceService;
    private $orderValidator;

    private $eventDispatcher;

    protected function setUp(): void
    {
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);
        $this->clientRepository = $this->createMock(ClientRepositoryInterface::class);
        $this->clientBalanceService = $this->createMock(ClientBalanceServiceInterface::class);
        $this->orderValidator = $this->createMock(OrderValidator::class);
        $this->eventDispatcher = $this->createMock(EventDispatcher::class);

    }

    /**
     * @throws ValidationException
     */
    public function testPlaceOrder()
    {
        // Create an instance of the service with mocks as dependencies
        $orderService = new OrderService(
            $this->orderRepository,
            $this->clientRepository,
            $this->clientBalanceService,
            $this->orderValidator,
            $this->eventDispatcher
        );

        // Create input data for the placeOrder method
        $orderId = $this->createMock(OrderId::class);
        $orderId->method('toString')->willReturn(Uuid::uuid4()->toString()); // Use Ramsey\Uuid to generate a UUID
        $clientId = $this->createMock(ClientId::class);
        $clientId->method('toString')->willReturn(Uuid::uuid4()->toString()); // Use Ramsey\Uuid to generate a UUID
        $items = [
            ['productId' => Uuid::uuid4(), 'quantity' => 6, 'price' => 100.0, 'weight' => 1000],
            ['productId' => Uuid::uuid4(), 'quantity' => 6, 'price' => 200.0, 'weight' => 2000],
            ['productId' => Uuid::uuid4(), 'quantity' => 9, 'price' => 300.0, 'weight' => 3000],
            ['productId' => Uuid::uuid4(), 'quantity' => 5, 'price' => 400.0, 'weight' => 4000],
            ['productId' => Uuid::uuid4(), 'quantity' => 6, 'price' => 500.0, 'weight' => 5000],
        ];
        $placeOrderCommand = new PlaceOrderCommand($orderId, $clientId, $items); // Pass the OrderId mock here

        // Configure mocks
        $client = $this->createMock(Client::class);
        $this->clientRepository->method('findByClientId')->willReturn($client);
        $this->orderValidator->method('validateInput')->willReturn(true);
        $client->method('isBlocked')->willReturn(false);
        $this->clientBalanceService->method('hasEnoughBalance')->willReturn(true);

        // Call the method we're testing
        $orderService->placeOrder($placeOrderCommand);


        // Check if methods on mocks were called the correct number of times
        $this->orderRepository->expects($this->once())->method('save');
        $this->clientBalanceService->expects($this->once())->method('subtractBalance')->with($clientId, $this->greaterThan(0));
    }
}