<?php

namespace App\Domain\Model\Client;

use App\Infrastructure\Doctrine\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "clients")]
class Client
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: "string")]
    private ClientId $id;

    #[ORM\Column(type: "string")]
    private string $name;

    #[ORM\OneToOne(targetEntity: "ClientBalance", cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(name: "balance_id", referencedColumnName: "id")]
    private ClientBalance $balance;

    #[ORM\Column(type: "boolean")]
    private bool $isBlocked;

    public function __construct(ClientId $id)
    {
        $this->id = $id;
        $this->isBlocked = false;
        $this->prePersist();
    }

    public function getId(): ClientId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function isBlocked(): bool
    {
        return $this->isBlocked;
    }

    public function block(): void
    {
        $this->isBlocked = true;
    }

    public function unblock(): void
    {
        $this->isBlocked = false;
    }

    public function getBalance(): ClientBalance
    {
        return $this->balance;
    }

    public function setBalance(ClientBalance $balance): void
    {
        $this->balance = $balance;
    }

    public function setName(string $getName): void
    {
        $this->name = $getName;
    }

    public function __toString(): string
    {
        return $this->id->toString();
    }

    public function getBalanceAmount(): float
    {
        return $this->balance->getBalance();
    }

}
