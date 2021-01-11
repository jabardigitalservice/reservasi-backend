<?php

namespace App\Enums;

use Spatie\Enum\Enum;

final class ReservationStatus extends Enum
{
    public static function NOT_YET_APPROVED(): ReservationStatus
    {
        return new class() extends ReservationStatus {
            public function getValue(): string
            {
                return 'not_yet_approved';
            }
        };
    }

    public static function ALREADY_APPROVED(): ReservationStatus
    {
        return new class() extends ReservationStatus {
            public function getValue(): string
            {
                return 'already_approved';
            }
        };
    }

    public static function REJECTED(): ReservationStatus
    {
        return new class() extends ReservationStatus {
            public function getValue(): string
            {
                return 'rejected';
            }
        };
    }
}
