<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [];
        $faker = \Faker\Factory::create('vi_VN');

        for ($i = 1; $i <= 100; $i++) {
            $arr[] = [
                'name' => $faker->firstName . ' ' . $faker->lastName,
                'avatar' => null,
                'username' => Str::random(10),
                'password' => $faker->password,
                'gender' => $faker->boolean,
                'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'email' => $faker->email,
                'phone_number' => $faker->boolean ? $faker->phoneNumber : null,
                'role' => rand(0, 3),
                'status' => $faker->boolean,
            ];
            if ($i % 10 === 0) {
                User::insert($arr);
                $arr = [];
            }
        }
    }
}
