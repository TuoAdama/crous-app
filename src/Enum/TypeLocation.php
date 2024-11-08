<?php

namespace App\Enum;

enum TypeLocation: string
{
    case INDIVIDUAL = 'Individual';
    case COLOCATION = 'Colocation';
    case COUPLE = 'Couple';

    public static function getTypesLocation(): array {
        return [
            self::INDIVIDUAL->value => strtolower(self::INDIVIDUAL->value),
            self::COLOCATION->value => strtolower(self::COLOCATION->value),
            self::COUPLE->value => strtolower(self::COUPLE->value),
        ];
    }
}
