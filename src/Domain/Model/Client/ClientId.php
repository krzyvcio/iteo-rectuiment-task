<?php

namespace App\Domain\Model\Client;

use App\Infrastructure\Doctrine\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Embeddable]
class ClientId
{
    use TimestampableTrait;

    #[ORM\Column(type: "string", unique: true)]
    private UuidInterface $id;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
        $this->prePersist();
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

    public function setId(string $toString): self
    {
        $this->id = Uuid::fromString($toString);
        return $this;
    }

    public function __toString(): string
    {
        return $this->id->toString();
    }
    
}