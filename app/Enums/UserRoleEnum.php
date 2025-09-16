<?php

namespace App\Enums;

enum UserRoleEnum : int
{
    case USER = 0;
    case ADMIN = 1;

    public function toString(): string
    {
        return match ($this) {
            self::USER => 'user',
            self::ADMIN => 'admin',
        };
    }
}
