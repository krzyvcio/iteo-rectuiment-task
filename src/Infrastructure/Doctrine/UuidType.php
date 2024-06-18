<?php

namespace App\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class UuidType extends Type
{
    const UUID = 'uuid';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || !\Ramsey\Uuid\Uuid::isValid($value)) {
            return null;
        }

        return \Ramsey\Uuid\Uuid::fromString($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \Ramsey\Uuid\UuidInterface) {
            return $value->toString();
        }

        if (\Ramsey\Uuid\Uuid::isValid($value)) {
            return $value;
        }

        throw ConversionException::conversionFailed($value, self::UUID);
    }

    public function getName()
    {
        return self::UUID;
    }
}