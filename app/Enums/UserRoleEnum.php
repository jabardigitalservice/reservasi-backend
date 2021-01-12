<?php

namespace App\Enums;

use Spatie\Enum\Enum;

final class UserRoleEnum extends Enum
{
    public static function EMPLOYEE(): string
    {
        return 'employee';
    }

    public static function ADMIN(): string
    {
        return 'admin';
    }
}
