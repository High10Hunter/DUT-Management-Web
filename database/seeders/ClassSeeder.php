<?php

namespace Database\Seeders;

use App\Models\_Class;
use App\Models\Course;
use App\Models\Major;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
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
        $majors = Major::query()->pluck('id')->toArray();
        $courses = Course::query()->pluck('id')->toArray();

        for ($i = 1; $i <= 50; $i++) {
            $arr[] = [
                'name' => $faker->firstName . rand(10, 99),
                'major_id' => $majors[array_rand($majors)],
                'course_id' => $courses[array_rand($courses)],
            ];

            if ($i % 10 === 0) {
                _Class::insert($arr);
                $arr = [];
            }
        }
    }
}
