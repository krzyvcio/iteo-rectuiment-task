<?php

namespace App\Tests\Application\Factory;

use App\Application\Command\PlaceOrderCommand;
use App\Application\Factory\PlaceOrderCommandFactory;
use App\Domain\Model\Client\ClientId;
use App\Domain\Model\Order\OrderId;
use App\Domain\Model\Order\OrderItem;
use App\Domain\Model\Product\ProductId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PlaceOrderCommandFactoryTest extends TestCase
{
    public function testCreateFromArray(): void
    {
        $factory = new PlaceOrderCommandFactory();

        $data = [
            'orderId' => Uuid::uuid4()->toString(),
            'clientId' => Uuid::uuid4()->toString(),
            'products' => [
                [
                    'productId' => Uuid::uuid4()->toString(),
                    'quantity' => 2,
                    'price' => 100.0,
                    'weight' => 1.0
                ],
                [
                    'productId' => Uuid::uuid4()->toString(),
                    'quantity' => 1,
                    'price' => 200.0,
                    'weight' => 2.0
                ]
            ]
        ];

        $command = $factory->createFromArray($data);

        $this->assertInstanceOf(PlaceOrderCommand::class, $command);
        $this->assertEquals(OrderId::fromString($data['orderId']), $command->getOrderId());
        $this->assertEquals(ClientId::fromString($data['clientId']), $command->getClientId());

        $items = $command->getItems();
        $this->assertCount(2, $items);

        $this->assertInstanceOf(OrderItem::class, $items[0]);
        $this->assertEquals(ProductId::fromString($data['products'][0]['productId']), $items[0]->getProductId());
        $this->assertEquals($data['products'][0]['quantity'], $items[0]->getQuantity());
        $this->assertEquals($data['products'][0]['price'], $items[0]->getPrice());
        $this->assertEquals($data['products'][0]['weight'], $items[0]->getWeight());

        $this->assertInstanceOf(OrderItem::class, $items[1]);
        $this->assertEquals(ProductId::fromString($data['products'][1]['productId']), $items[1]->getProductId());
        $this->assertEquals($data['products'][1]['quantity'], $items[1]->getQuantity());
        $this->assertEquals($data['products'][1]['price'], $items[1]->getPrice());
        $this->assertEquals($data['products'][1]['weight'], $items[1]->getWeight());
    }
}