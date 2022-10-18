<?php

namespace Database\Seeders;

use App\Models\EAO_staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class EAOStaffSeeder extends Seeder
{
    public function run()
    {
        $arr = [];
        $faker = \Faker\Factory::create('vi_VN');
        $users = User::query()->pluck('id')->toArray();

        for ($i = 1; $i <= 50; $i++) {
            $arr[] = [
                'name' => $faker->firstName . ' ' . $faker->lastName,
                'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'gender' => $faker->boolean,
                'email' => $faker->email,
                'phone_number' => $faker->phoneNumber,
                'status' => $faker->boolean,
                'user_id' => $users[array_rand($users)],
            ];
        }
        EAO_staff::insert($arr);
    }
}
