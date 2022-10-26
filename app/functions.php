<?php

use App\Enums\UserRoleEnum;
use App\Models\Config;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

if (!function_exists('getRoleByKey')) {
    function getRoleByValue($val): string
    {
        return strtolower(UserRoleEnum::getKeys((int)$val)[0]);
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin(): bool
    {
        return (auth()->user()->role === UserRoleEnum::ADMIN);
    }
}

if (!function_exists('isEAOStaff')) {
    function isEAOStaff(): bool
    {
        return (auth()->user()->role === UserRoleEnum::EAO_STAFF);
    }
}

if (!function_exists('isLecturer')) {
    function isLecturer(): bool
    {
        return (auth()->user()->role === UserRoleEnum::LECTURER);
    }
}

if (!function_exists('isStudent')) {
    function isStudent(): bool
    {
        return (auth()->user()->role === UserRoleEnum::STUDENT);
    }
}

if (!function_exists('getTotalAbsentLessons')) {
    function getTotalAbsentLessons($notAttendedCount, $lateCount, $lateCoefficient)
    {
        return $notAttendedCount + $lateCount * (float)$lateCoefficient;
    }
}

if (!function_exists('checkBanFromExam')) {
    function checkBanFromExam(
        $notAttendedCount,
        $lateCount,
        $lateCoefficient,
        $teachedLessons,
        $examBanCoefficient
    ): bool {
        $totalAbsentLessons = getTotalAbsentLessons(
            $notAttendedCount,
            $lateCount,
            $lateCoefficient
        );
        return ($totalAbsentLessons > $teachedLessons * (float)$examBanCoefficient);
    }
}

if (!function_exists('checkWarningExam')) {
    function checkWarningExam(
        $notAttendedCount,
        $lateCount,
        $lateCoefficient,
        $teachedLessons,
        $examWarningCoefficient
    ): bool {
        $totalAbsentLessons = getTotalAbsentLessons(
            $notAttendedCount,
            $lateCount,
            $lateCoefficient
        );
        return ($totalAbsentLessons > $teachedLessons * (float)$examWarningCoefficient);
    }
}

if (!function_exists('getRemainingAbsentDays')) {
    function getRemainingAbsentDays(
        $notAttendedCount,
        $lateCount,
        $lateCoefficient,
        $examBanCoefficient,
        $moduleLessons,
        $teachedLessons
    ) {
        $totalAbsentLessons = getTotalAbsentLessons(
            $notAttendedCount,
            $lateCount,
            $lateCoefficient
        );

        $possibleAbsentLessons = (float)$moduleLessons * $examBanCoefficient;
        $remainingLessons = $moduleLessons - $teachedLessons;
        $remainingAbsentDays = (float)$possibleAbsentLessons - (float)$totalAbsentLessons;

        if ($remainingLessons <= $remainingAbsentDays)
            return $remainingLessons;
        else if ($remainingAbsentDays < 0)
            return 0;
        else {
            return (int)$remainingAbsentDays;
        }
    }
}

if (!function_exists('createPasswordByBirthday')) {
    function createPasswordByBirthday($birthday): string
    {
        $birthdayReformat = Carbon::createFromFormat('Y-m-d', $birthday)->format('d-m-Y');
        $birthdayPassword = explode('-', $birthdayReformat);
        $birthdayPassword = implode('', $birthdayPassword);

        return $birthdayPassword;
    }
}

if (!function_exists('manuallyPaginate')) {
    function manuallyPaginate($items, $perPage = 3, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $options = [
            'path' => url()->current(),
            'pageName' => 'page',
        ];
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
