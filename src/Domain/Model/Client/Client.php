<?php

namespace App\Domain\Model\Client;

class Client
{
    private ClientId $id;
    private string $name;
    private string $email;
    private bool $isBlocked;

    public function __construct(ClientId $id, string $name, string $email, bool $isBlocked = false)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->isBlocked = $isBlocked;
    }

    public function getId(): ClientId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
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
}
