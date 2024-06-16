<?php

namespace App\Domain\Model\Product;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ProductId
{
    private UuidInterface $id; //zakładamy, że identyfikator produktu to UUID

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

    public function equals(ProductId $other): bool
    {
        return $this->id->equals($other->id);
    }
}
