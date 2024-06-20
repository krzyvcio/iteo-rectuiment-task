<?php

namespace App\Application\Service;

use App\Application\Command\PlaceOrderCommand;
use App\Domain\Event\OrderPlacedEvent;
use App\Domain\Exception\InvalidOrderDataException;
use App\Domain\Model\Order\Order;
use App\Domain\Model\Order\OrderId;
use App\Domain\Model\Order\OrderItem;
use App\Domain\Model\Product\ProductId;
use App\Domain\Repository\ClientRepositoryInterface;
use App\Domain\Repository\OrderRepositoryInterface;
use App\Domain\Service\ClientBalanceServiceInterface;
use App\Domain\Validator\OrderValidator;
use App\Presentation\Validator\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class OrderService
{


    public function __construct(
        private readonly OrderRepositoryInterface      $orderRepository,
        private readonly ClientRepositoryInterface     $clientRepository,
        private readonly ClientBalanceServiceInterface $clientBalanceService,
        private readonly OrderValidator                $orderValidator,
        private readonly EventDispatcher               $eventDispatcher,
        private readonly EntityManagerInterface        $entityManager,
    )
    {

    }

    /**
     * @throws ValidationException
     * @throws InvalidOrderDataException
     */
    public function placeOrder(PlaceOrderCommand $command): void
    {
        $clientId = $command->getOrder()->getClientId();
        $client = $this->clientRepository->findByClientId($clientId);

        //walidacja
        $validate = $this->orderValidator->validateInput($command->getOrder());

        if (!$validate) {
            throw new InvalidOrderDataException('Invalid input order');
        }

        if ($client === null) {
            throw new \InvalidArgumentException('Client not found');
        }

        if ($client->isBlocked()) {
            throw new \InvalidArgumentException('Client is blocked');
        }

        $orderId = OrderId::generate();
        $orderItems = [];

        foreach ($command->getProducts() as $productData) {
            $productId = ProductId::fromString($productData['productId']);
            $quantity = $productData['quantity'];
            $price = $productData['price'];
            $weight = $productData['weight'];

            $orderItems[] = new OrderItem($productId, $quantity, $price, $weight);
        }

        $order = new Order($orderId, $clientId, $orderItems);

        $isOrderValid = $this->orderValidator->isOrderValid($order);

        if (!$isOrderValid) {
            throw new InvalidOrderDataException('Invalid order');
        }

        $totalPrice = $order->calculateTotalPrice();

        if (!$this->clientBalanceService->hasEnoughBalance($clientId, $totalPrice)) {
            throw new \InvalidArgumentException('Insufficient client balance');
        }

        //robię tranzakcje
        $this->entityManager->beginTransaction();
        try {
            $this->clientBalanceService->subtractBalance($clientId, $totalPrice);
            $this->orderRepository->save($order);
            $this->entityManager->commit();
            $this->eventDispatcher->dispatch(new OrderPlacedEvent($order));
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }

    }

}
