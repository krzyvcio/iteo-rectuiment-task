<?php

namespace App\Application\Command;

use App\Domain\Model\Order\Order;

class PlaceOrderCommand
{
    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getOrderId(): string
    {
        return $this->order->getId()->toString();
    }

    public function getProducts()
    {
        $products = [];
        foreach ($this->order->getItems() as $item) {
            $products[] = [
                'productId' => $item->getProductId()->toString(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice(),
                'weight' => $item->getWeight(),
                'subtotal' => $item->getSubtotal()
            ];
        }
        return $products;
    }
}