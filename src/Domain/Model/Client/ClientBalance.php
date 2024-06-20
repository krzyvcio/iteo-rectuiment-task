<?php

namespace App\Domain\Model\Client;

use App\Infrastructure\Doctrine\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity, ORM\Table(name: "client_balances")]
class ClientBalance extends ClientId
{

    use TimestampableTrait;

    #[ORM\Id, ORM\Column(type: "string")]
    private string $id;

    #[ORM\Column(type: "uuid", unique: true)]
    private ClientId $clientId;

    #[ORM\Column(type: "float")]
    private float $balance = 0.0;

    #[ORM\Column(type: "string")]
    private string $currency;


    public function __construct(ClientId $clientId, int $balance)
    {
        parent::__construct($clientId->toString());
        $this->balance = $balance;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getClientId(): ClientId
    {
        return $this->clientId;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function addBalance(float $amount): void
    {
        $this->balance += $amount;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }


    public function subtractBalance(float $amount): void
    {
        $this->balance -= $amount;
    }

    public function setCurrency(string $string): void
    {
        $this->currency = $string;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function __toString(): string
    {
        return $this->balance . ' ' . $this->currency;
    }

    public static function fromIdAndBalance(string $id, int $balance): self
    {
        $uuid = Uuid::fromString($id);  // Ensure this returns a UuidInterface
        return new self(new ClientId($uuid), $balance);
    }

}
