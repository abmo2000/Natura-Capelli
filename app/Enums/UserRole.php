<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'customer';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toAssociativeArray(): array
    {
        return array_column(self::cases(), 'value', 'value');
    }
}
