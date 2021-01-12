<?php

namespace App\Enums;

use Spatie\Enum\Enum;

final class ReservationStatusEnum extends Enum
{
    /**
     * NOT_YET_APPROVED
     *
     * @return string
     */
    public static function NOT_YET_APPROVED(): string
    {
        return 'not_yet_approved';
    }

    /**
     * ALREADY_APPROVED
     *
     * @return string
     */
    public static function ALREADY_APPROVED(): string
    {
        return 'already_approved';
    }

    /**
     * REJECTED
     *
     * @return string
     */
    public static function REJECTED(): string
    {
        return 'rejected';
    }
}
