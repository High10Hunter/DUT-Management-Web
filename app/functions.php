<?php

use App\Enums\UserRoleEnum;
use App\Models\Config;
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

if (!function_exists('moveAvatarToUserIDFolderWhenCreate')) {
    function moveAvatarToUserIDFolderWhenCreate($userId, $storagePathPrefix, $tempPath, $newPathPrefixToStore): string
    {
        //create folder with userID
        $userAvatarStoragePath = $storagePathPrefix . $userId;
        Storage::makeDirectory($userAvatarStoragePath);

        //take the avatar stored current path 
        $oldPath = Storage::files($storagePathPrefix);

        //get avatar name
        $userAvatarName = explode('/', $tempPath)[2];
        $newPath = $userAvatarStoragePath . '/' . $userAvatarName;

        //move avatar to folder with userID
        Storage::move($oldPath[0], $newPath);

        // rename new path
        $newPath = $newPathPrefixToStore . $userId . '/' . $userAvatarName;

        return $newPath;
    }
}

if (!function_exists('moveAvatarToUserIDFolderWhenUpdate')) {
    function moveAvatarToUserIDFolderWhenUpdate($userId, $oldPathStore, $newPathPrefix, $newPathPrefixToStore): string
    {
        //get old avatar path
        $oldPath = 'public/' . $oldPathStore;

        //get avatar name
        $userAvatarName = explode('/', $oldPathStore)[2];
        $newPath = $newPathPrefix . $userId . '/' . $userAvatarName;

        //move avatar to folder with userID
        Storage::move($oldPath, $newPath);

        // rename new path
        $newPath = $newPathPrefixToStore . $userId . '/' . $userAvatarName;

        return $newPath;
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
