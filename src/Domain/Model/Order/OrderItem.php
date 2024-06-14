<?php

namespace App\Domain\Model\Order;

use App\Domain\Model\Product\ProductId;

class OrderItem
{
    private ProductId $productId;
    private int $quantity;
    private float $price;
    private float $weight;

    public function __construct(ProductId $productId, int $quantity, float $price, float $weight)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->weight = $weight;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getSubtotal(): float
    {
        return $this->price * $this->quantity;
    }
}
