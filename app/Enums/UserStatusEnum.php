<?php

namespace App\Enums;

enum UserStatusEnum : int
{
    case DEACTIVE = 0;
    case ACTIVE = 1;

    public function toString(): string
    {
        return match ($this) {
            self::DEACTIVE => 'deactive',
            self::ACTIVE => 'active',
        };
    }
}
