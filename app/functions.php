<?php

use App\Enums\UserRoleEnum;

if (!function_exists('getRoleByKey')) {
    function getRoleByValue($val): string
    {
        return strtolower(UserRoleEnum::getKeys((int)$val)[0]);
    }
}
