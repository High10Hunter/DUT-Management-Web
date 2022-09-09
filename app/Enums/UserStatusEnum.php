<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserStatusEnum extends Enum
{
    public const ACTIVE = 1;
    public const OFF = 0;

    public static function getStatusForFilter(): array
    {
        return [
            'Hoạt động' => self::ACTIVE,
            'Nghỉ' => self::OFF,
        ];
    }
}
