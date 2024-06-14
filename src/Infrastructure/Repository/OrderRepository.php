<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Order\Order;
use App\Domain\Model\Order\OrderId;
use App\Domain\Repository\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findById(OrderId $orderId): ?Order
    {
        return $this->entityManager->find(Order::class, $orderId);
    }

    public function save(Order $order): void
    {
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    public function update(Order $order): void
    {
        $this->entityManager->flush();
    }

    public function delete(Order $order): void
    {
        $this->entityManager->remove($order);
        $this->entityManager->flush();
    }
}
