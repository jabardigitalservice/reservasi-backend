<?php

namespace App\Enums;

use Spatie\Enum\Enum;

final class UserRoleEnum extends Enum
{
    /**
     * EMPLOYEE
     *
     * @return string
     */
    public static function EMPLOYEE(): string
    {
        return 'employee';
    }

    /**
     * ADMIN
     *
     * @return string
     */
    public static function ADMIN(): string
    {
        return 'admin';
    }
}
