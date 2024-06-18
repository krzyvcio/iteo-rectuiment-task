<?php

namespace App\Application\Service;

use App\Application\Command\PlaceOrderCommand;
use App\Domain\Event\OrderPlacedEvent;
use App\Domain\Model\Order\Order;
use App\Domain\Model\Order\OrderId;
use App\Domain\Model\Order\OrderItem;
use App\Domain\Model\Product\ProductId;
use App\Domain\Repository\ClientRepositoryInterface;
use App\Domain\Repository\OrderRepositoryInterface;
use App\Domain\Service\ClientBalanceServiceInterface;
use App\Domain\Validator\OrderValidator;
use App\Presentation\Validator\ValidationException;
use Symfony\Component\EventDispatcher\EventDispatcher;

class OrderService
{
    private OrderRepositoryInterface $orderRepository;
    private ClientRepositoryInterface $clientRepository;
    private ClientBalanceServiceInterface $clientBalanceService;
    private EventDispatcher $eventDispatcher;

    private OrderValidator $orderValidator;

    public function __construct(
        OrderRepositoryInterface      $orderRepository,
        ClientRepositoryInterface     $clientRepository,
        ClientBalanceServiceInterface $clientBalanceService,
        OrderValidator                $orderValidator
    )
    {
        $this->orderRepository = $orderRepository;
        $this->clientRepository = $clientRepository;
        $this->clientBalanceService = $clientBalanceService;
    }

    /**
     * @throws ValidationException
     */
    public function placeOrder(PlaceOrderCommand $command): void
    {
        $clientId = $command->getOrder()->getClientId();
        $client = $this->clientRepository->findById($clientId);

        //walidacja
        $this->orderValidator->validate($command->getOrder());

        if ($client === null) {
            throw new \InvalidArgumentException('Client not found');
        }

        if ($client->isBlocked()) {
            throw new \InvalidArgumentException('Client is blocked');
        }

        $orderId = OrderId::generate();
        $orderItems = [];

        foreach ($command->getProducts() as $productData) {
            $productId = ProductId::fromString($productData['productId']);
            $quantity = $productData['quantity'];
            $price = $productData['price'];
            $weight = $productData['weight'];

            $orderItems[] = new OrderItem($productId, $quantity, $price, $weight);
        }

        $order = new Order($orderId, $clientId, $orderItems);

        if (!$this->orderValidator->isOrderValid($order)) {
            throw new \InvalidArgumentException('Invalid order');
        }

        $totalPrice = $order->calculateTotalPrice();

        if (!$this->clientBalanceService->hasEnoughBalance($clientId, $totalPrice)) {
            throw new \InvalidArgumentException('Insufficient client balance');
        }

        $this->clientBalanceService->subtractBalance($clientId, $totalPrice);
        $this->orderRepository->save($order);

        $this->eventDispatcher->dispatch(new OrderPlacedEvent($order));
    }

}
