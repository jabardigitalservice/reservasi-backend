<?php

namespace App\Enums;

use Spatie\Enum\Enum;

final class AssetStatusEnum extends Enum
{
    public static function ACTIVE(): string
    {
        return 'active';
    }

    public static function NOT_ACTIVE(): string
    {
        return 'not_active';
    }
}
