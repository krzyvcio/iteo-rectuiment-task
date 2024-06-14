<?php

namespace App\Application\Command;

use Ramsey\Uuid\UuidInterface;

class PlaceOrderCommand
{
    private UuidInterface $orderId;
    private UuidInterface $clientId;
    private array $products;

    public function __construct(UuidInterface $orderId, UuidInterface $clientId, array $products)
    {
        $this->orderId = $orderId;
        $this->clientId = $clientId;
        $this->products = $products;
    }

    public function getOrderId(): UuidInterface
    {
        return $this->orderId;
    }

    public function getClientId(): UuidInterface
    {
        return $this->clientId;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            \Ramsey\Uuid\Uuid::fromString($data['orderId']),
            \Ramsey\Uuid\Uuid::fromString($data['clientId']),
            array_map(function ($productData) {
                return [
                    'productId' => $productData['productId'],
                    'quantity' => $productData['quantity'],
                    'price' => $productData['price'],
                    'weight' => $productData['weight'],
                ];
            }, $data['products'])
        );
    }
}
