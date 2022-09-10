<?php

use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Storage;

if (!function_exists('getRoleByKey')) {
    function getRoleByValue($val): string
    {
        return strtolower(UserRoleEnum::getKeys((int)$val)[0]);
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
