<?php

namespace App\Imports;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToArray, WithHeadingRow
{
    public function array(array $array)
    {
        try {
            $storeArr = [];
            foreach ($array as $each) {
                $storeArr['name'] = $each['ten'];
                $storeArr['birthday'] = $each['ngay_sinh'];
                $storeArr['gender'] = $each['gioi_tinh'];

                if (!is_null($each['email']))
                    $storeArr['email'] = $each['email'];
                else
                    $storeArr['email'] = null;

                if (!is_null($each['sdt']))
                    $storeArr['phone_number'] = $each['sdt'];
                else
                    $storeArr['phone_number'] = null;

                $storeArr['role'] = $each['vai_tro'];
                $storeArr['username'] = UserRoleEnum::getRoleForAuthentication((int)$storeArr['role']);
                $storeArr['password'] = UserRoleEnum::getRoleForAuthentication((int)$storeArr['role']);

                if (!is_null($each['ma_khoa']))
                    $storeArr['faculty_id'] = $each['ma_khoa'];
                else
                    $storeArr['faculty_id'] = null;

                if (!is_null($each['ma_lop']))
                    $storeArr['class_id'] = $each['ma_lop'];
                else
                    $storeArr['class_id'] = null;

                $newUser = User::create($storeArr);
                $newUser::query()
                    ->where('id', $newUser->id)
                    ->update([
                        'username' => $newUser->username . $newUser->id,
                        'password' => Hash::make($newUser->password . $newUser->id),
                    ]);
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
