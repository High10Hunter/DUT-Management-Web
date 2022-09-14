<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Major;
use App\Models\MajorSubject;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class MajorSubjectSeeder extends Seeder
{
    public function run()
    {
        $arr = [];
        $majors = Major::query()->pluck('id')->toArray();
        $subjects = Subject::query()->pluck('id')->toArray();
        $courses = Course::query()->pluck('id')->toArray();

        for ($i = 1; $i < 5; $i++) {
            $arr[] = [
                'major_id' => $majors[array_rand($majors)],
                'subject_id' => $subjects[array_rand($subjects)],
                'course_id' => $courses[array_rand($courses)],
                'number_of_credits' => rand(1, 4),
            ];

            MajorSubject::insert($arr);
        }
    }
}
