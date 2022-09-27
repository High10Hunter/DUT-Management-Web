<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PeriodAttendanceStatusEnum extends Enum
{
    public const NOT_ATTENDED = 0;
    public const ATTENDED = 1;
    public const EXCUSED = 2;
    public const LATE = 3;
}
