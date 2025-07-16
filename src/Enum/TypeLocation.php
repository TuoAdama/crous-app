<?php

namespace App\Enum;

enum TypeLocation: string
{
    case INDIVIDUAL = 'alone';
    case COLOCATION = 'house_sharing';
    case COUPLE = 'couple';

    public static function getTypesLocation(): array {
        return [
            ucfirst(strtolower(TypeLocation::INDIVIDUAL->name)) => strtolower(self::INDIVIDUAL->value),
            ucfirst(strtolower(TypeLocation::COLOCATION->name)) => strtolower(self::COLOCATION->value),
            ucfirst(strtolower(TypeLocation::COUPLE->name)) => strtolower(self::COUPLE->value),
        ];
    }
}
