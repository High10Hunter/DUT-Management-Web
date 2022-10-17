<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TimeSlotEnum extends Enum
{
    public const SLOT_1 = "07:00:00";
    public const SLOT_2 = "08:00:00";
    public const SLOT_3 = "09:00:00";
    public const SLOT_4 = "10:00:00";
    public const SLOT_5 = "11:00:00";
    public const SLOT_6 = "12:30:00";
    public const SLOT_7 = "13:30:00";
    public const SLOT_8 = "14:30:00";
    public const SLOT_9 = "15:30:00";
    public const SLOT_10 = "16:30:00";
    public const DURATION = 50;

    public static function getStartTimeBySlotId($slotId)
    {
        $slotName = "SLOT_" . $slotId;
        return self::getValue($slotName);
    }
}
