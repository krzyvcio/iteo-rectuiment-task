<?php

namespace App\Domain\Model\Client;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ClientId
{
    private UuidInterface $id;

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

    public function equals(ClientId $other): bool
    {
        return $this->id->equals($other->id);
    }
}
