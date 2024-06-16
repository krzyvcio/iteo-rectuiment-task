<?php

namespace App\Infrastructure\Messaging\EventSubscriber;

use App\Application\Factory\PlaceOrderCommandFactory;
use App\Application\Service\CrmOrderService;
use App\Domain\Event\OrderPlacedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendOrderToCrmSubscriber implements EventSubscriberInterface
{
    private CrmOrderService $crmOrderService;
    private PlaceOrderCommandFactory $placeOrderCommandFactory;

    public function __construct(
        CrmOrderService          $crmOrderService,
        PlaceOrderCommandFactory $placeOrderCommandFactory
    )
    {
        $this->crmOrderService = $crmOrderService;
        $this->placeOrderCommandFactory = $placeOrderCommandFactory;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderPlacedEvent::class => 'onOrderPlaced',
        ];
    }

    public function onOrderPlaced(OrderPlacedEvent $event): void
    {
        $order = $event->getOrder();

        $placeOrderCommand = $this->placeOrderCommandFactory->createFromOrder($order);

        $this->crmOrderService->sendOrder($placeOrderCommand);
    }
}
