<?php

namespace App\Domain\Model\Order;

use App\Domain\Model\Client\ClientId;

class Order
{
    private OrderId $id;
    private ClientId $clientId;
    private array $items;
    private \DateTimeImmutable $createdAt;

    public function __construct(OrderId $id, ClientId $clientId, array $items)
    {
        $this->id = $id;
        $this->clientId = $clientId;
        $this->items = $items;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): OrderId
    {
        return $this->id;
    }

    public function getClientId(): ClientId
    {
        return $this->clientId;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function calculateTotalPrice(): float
    {
        $totalPrice = 0;
        foreach ($this->items as $item) {
            $totalPrice += $item->getPrice() * $item->getQuantity();
        }
        return $totalPrice;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toString(),
            'clientId' => $this->clientId->toString(),
            'items' => array_map(fn(OrderItem $item) => $item->toArray(), $this->items),
        ];
    }
}
