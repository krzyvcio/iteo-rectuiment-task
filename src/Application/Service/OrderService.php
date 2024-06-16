<?php

namespace App\Application\Service;

use App\Application\Command\PlaceOrderCommand;
use App\Domain\Model\Client\ClientId;
use App\Domain\Model\Order\Order;
use App\Domain\Model\Order\OrderId;
use App\Domain\Model\Order\OrderItem;
use App\Domain\Model\Product\ProductId;
use App\Domain\Repository\ClientRepositoryInterface;
use App\Domain\Repository\OrderRepositoryInterface;
use App\Domain\Service\ClientBalanceServiceInterface;

class OrderService
{
    private OrderRepositoryInterface $orderRepository;
    private ClientRepositoryInterface $clientRepository;
    private ClientBalanceServiceInterface $clientBalanceService;

    public function __construct(
        OrderRepositoryInterface      $orderRepository,
        ClientRepositoryInterface     $clientRepository,
        ClientBalanceServiceInterface $clientBalanceService
    )
    {
        $this->orderRepository = $orderRepository;
        $this->clientRepository = $clientRepository;
        $this->clientBalanceService = $clientBalanceService;
    }

    public function placeOrder(PlaceOrderCommand $command): void
    {
        $clientId = ClientId::fromString($command->getClientId());
        $client = $this->clientRepository->findById($clientId);

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

        if (!$this->isOrderValid($order)) {
            throw new \InvalidArgumentException('Invalid order');
        }

        $totalPrice = $order->calculateTotalPrice();

        if (!$this->clientBalanceService->hasEnoughBalance($clientId, $totalPrice)) {
            throw new \InvalidArgumentException('Insufficient client balance');
        }

        $this->clientBalanceService->subtractBalance($clientId, $totalPrice);
        $this->orderRepository->save($order);

        // TODO: Send order to CRM system
    }

    private function isOrderValid(Order $order): bool
    {
        $orderItems = $order->getItems();

        if (count($orderItems) < 5) {
            return false;
        }

        $totalWeight = 0;
        foreach ($orderItems as $orderItem) {
            $totalWeight += $orderItem->getWeight() * $orderItem->getQuantity();
        }

        if ($totalWeight > 24000) { // 24 tons in kilograms
            return false;
        }

        return true;
    }

    public function createOrder(array $data): Order
    {
        return new Order(
            OrderId::fromString($data['id']),
            $data['clientId'],
            $data['items']
        );
    }


}
