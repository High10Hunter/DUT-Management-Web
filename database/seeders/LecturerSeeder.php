<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class LecturerSeeder extends Seeder
{
    public function run()
    {
        $arr = [];
        $faker = \Faker\Factory::create('vi_VN');
        $faculties = Faculty::query()->pluck('id')->toArray();
        $users = User::query()->pluck('id')->toArray();

        for ($i = 1; $i <= 30; $i++) {
            $arr[] = [
                'name' => $faker->firstName . ' ' . $faker->lastName,
                'avatar' => null,
                'username' => Str::random(10),
                'password' => $faker->password,
                'gender' => $faker->boolean,
                'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'email' => $faker->email,
                'phone_number' => $faker->phoneNumber,
                'role' => 2,
                'status' => $faker->boolean,
                'faculty_id' => $faculties[array_rand($faculties)],
                'user_id' => $users[array_rand($users)],
            ];
        }
        Lecturer::insert($arr);
    }
}
