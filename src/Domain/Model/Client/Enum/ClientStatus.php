<?php

namespace App\Domain\Model\Client\Enum;

enum ClientStatus: string
{
    case ACTIVE = 'active';
    case BLOCKED = 'blocked';
}
