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
            'Quản trị viên' => self::ADMIN,
            'Giáo vụ' => self::EAO_STAFF,
            'Giảng viên' => self::LECTURER,
            'Sinh viên' => self::STUDENT,
        ];
    }

    public static function getRoleForAuthentication($val): string
    {
        if ($val == self::ADMIN)
            return "AD";
        else if ($val == self::EAO_STAFF)
            return "EAO";
        else if ($val == self::LECTURER)
            return "LEC";
        else if ($val == self::STUDENT)
            return "STU";
    }
}
