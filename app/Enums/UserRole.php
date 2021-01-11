<?php

namespace App\Enums;

use Spatie\Enum\Enum;

final class UserRole extends Enum
{
    public static function EMPLOYEE(): UserRole
    {
        return new class() extends UserRole {
            public function getValue(): string
            {
                return 'employee';
            }
        };
    }
    public static function ADMIN(): UserRole
    {
        return new class() extends UserRole {
            public function getValue(): string
            {
                return 'admin';
            }
        };
    }
}
