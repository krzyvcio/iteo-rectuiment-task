<?php

namespace App\Domain\Model\Client;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: "client_balances")]
class ClientBalance
{
    #[ORM\Id, ORM\Column(type: "string")]
    private string $id;

    #[ORM\Column(type: "uuid")]
    private string $clientId;

    #[ORM\Column(type: "float")]
    private float $balance = 0.0;

    #[ORM\Column(type: "string")]
    private string $currency;

    #[ORM\Column(type: "datetime")]
    private \DateTime $updatedAt;

    #[ORM\Column(type: "datetime")]
    private \DateTime $createdAt;

    public function __construct(
        string $id,
        string $clientId,

    )
    {
        $this->id = $id;
        $this->clientId = $clientId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getClientId(): string
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

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function subtractBalance(float $amount)
    {
        $this->balance -= $amount;
    }
}
