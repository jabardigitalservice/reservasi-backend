<?php

namespace App\Enums;

use Spatie\Enum\Enum;

final class ReservationStatus extends Enum
{
    public static function NOT_YET_APPROVED(): string
    {

        return 'not_yet_approved';
    }

    public static function ALREADY_APPROVED(): string
    {
        return 'already_approved';

    }

    public static function REJECTED(): string
    {
        return 'rejected';
    }
}
