<?php

namespace App\Domain\Model\Order;

use App\Domain\Model\Product\ProductId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class OrderId extends \App\Domain\Model\Order\Order
{
    private UuidInterface $id;

    /**
     * @var OrderItem[]
     */
    private array $items;

    private function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $id): self
    {
        return new self(Uuid::fromString($id));
    }

    public function toString(): string
    {
        return $this->id->toString();
    }

    public function equals(OrderId $other): bool
    {
        return $this->id->equals($other->id);
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): void
    {
        $this->items[] = $item;
    }

    public function removeItem(OrderItem $item): void
    {
        $this->items = array_filter($this->items, fn(OrderItem $i) => $i->getProductId()->equals($item->getProductId()));
    }

    public function updateItem(OrderItem $item): void
    {
        $this->items = array_map(fn(OrderItem $i) => $i->getProductId()->equals($item->getProductId()) ? $item : $i, $this->items);
    }

    public function getItemByProductId(ProductId $productId): ?OrderItem
    {
        foreach ($this->items as $item) {
            if ($item->getProductId()->equals($productId)) {
                return $item;
            }
        }
        return null;
    }


}
