<?php

namespace Database\Seeders;

use App\Models\_Class;
use App\Models\Faculty;
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
        $faculties = Faculty::query()->pluck('id')->toArray();
        $classes = _Class::query()->pluck('id')->toArray();

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
                'faculty_id' => $faker->boolean ? null : ($faculties[array_rand($faculties)]),
                'class_id' => $faker->boolean ? null : $classes[array_rand($classes)],
            ];
            if ($i % 10 === 0) {
                User::insert($arr);
                $arr = [];
            }
        }
    }
}
