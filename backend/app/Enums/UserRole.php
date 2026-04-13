<?php

namespace App\Enums;

enum UserRole: string
{
    case Cashier = 'cashier';
    case Admin = 'admin';
    case Superadmin = 'superadmin';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $role) => $role->value, self::cases());
    }
}
