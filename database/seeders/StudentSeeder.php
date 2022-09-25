<?php

namespace Database\Seeders;

use App\Models\_Class;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class StudentSeeder extends Seeder
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
        $classes = _Class::query()->pluck('id')->toArray();
        $users = User::query()->pluck('id')->toArray();

        for ($i = 1; $i <= 30; $i++) {
            $arr[] = [
                'student_code' => rand(1000, 3000),
                'name' => $faker->firstName . ' ' . $faker->lastName,
                'avatar' => null,
                'gender' => $faker->boolean,
                'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'email' => $faker->email,
                'phone_number' => $faker->phoneNumber,
                'status' => $faker->boolean,
                'class_id' => $classes[array_rand($classes)],
                'user_id' => $users[array_rand($users)],
            ];
        }
        Student::insert($arr);
    }
}
