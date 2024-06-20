<?php

namespace App\Application\Factory;

use App\Application\Command\PlaceOrderCommand;
use App\Domain\Model\Client\ClientId;
use App\Domain\Model\Order\Order;
use App\Domain\Model\Order\OrderId;
use App\Domain\Model\Order\OrderItem;
use App\Domain\Model\Product\ProductId;

class PlaceOrderCommandFactory
{
    public function createFromArray(array $data): PlaceOrderCommand
    {
        $orderId = OrderId::fromString($data['orderId']);
        $clientId = ClientId::fromString($data['clientId']);
        $items = [];

        foreach ($data['products'] as $productData) {
            $productId = ProductId::fromString($productData['productId']);
            $quantity = $productData['quantity'];
            $price = $productData['price'];
            $weight = $productData['weight'];

            $items[] = new OrderItem($productId, $quantity, $price, $weight);
        }

        return new PlaceOrderCommand($orderId, $clientId, $items);
    }

    public function createFromOrder(Order $order): PlaceOrderCommand
    {
        return new PlaceOrderCommand($order->getId(), $order->getClientId(), $order->getItems());
    }
}
