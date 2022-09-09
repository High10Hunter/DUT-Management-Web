<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserRoleEnum extends Enum
{
    public const ADMIN = 0;
    public const EAO_STAFF = 1;
    public const LECTURER = 2;
    public const STUDENT = 3;

    public static function getRolesForFilter(): array
    {
        return [
            'Admin' => self::ADMIN,
            'Giáo vụ' => self::EAO_STAFF,
            'Giảng viên' => self::LECTURER,
            'Sinh viên' => self::STUDENT,
        ];
    }
}
