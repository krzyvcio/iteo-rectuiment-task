<?php

namespace App\Domain\Event;

use App\Domain\Model\Order\Order;
use Symfony\Contracts\EventDispatcher\Event;

class OrderPlacedEvent extends Event
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
}
