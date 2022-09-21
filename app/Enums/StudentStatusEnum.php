<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class StudentStatusEnum extends Enum
{
    public const DROP_OUT = 0;
    public const ACTIVE = 1;
    public const RESERVED = 2;
}
