<?php

namespace App\Application\Command;

use App\Domain\Model\Client\ClientId;
use App\Domain\Model\Order\Order;
use App\Domain\Model\Order\OrderId;
use App\Domain\Model\Order\OrderItem;

class PlaceOrderCommand
{
    private OrderId $orderId;
    private ClientId $clientId;
    private array $items;

    public function __construct(OrderId $orderId, ClientId $clientId, array $items)
    {
        $this->orderId = $orderId;
        $this->clientId = $clientId;
        $this->items = $items;
    }

    public function getOrderId(): OrderId
    {
        return $this->orderId;
    }

    public function getOrder(): Order
    {
        return new Order($this->orderId, $this->clientId, $this->items);
    }

    public function getClientId(): ClientId
    {
        return $this->clientId;
    }

    /**
     * @return OrderItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getProducts(): array
    {
        return $this->items;
    }
}
