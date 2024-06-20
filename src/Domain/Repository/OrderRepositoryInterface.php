<?php

namespace App\Domain\Repository;

use App\Domain\Model\Order\Order;
use App\Domain\Model\Order\OrderId;

interface OrderRepositoryInterface
{

    public function findByOrderId(OrderId $orderId): ?Order;

    public function save(Order $order): void;

    public function update(Order $order): void;

    public function delete(Order $order): void;

}