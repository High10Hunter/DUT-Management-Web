<?php

namespace Database\Seeders;

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

        for ($i = 1; $i < 10; $i++) {
            $arr[] = [
                'major_id' => $majors[array_rand($majors)],
                'subject_id' => $subjects[array_rand($subjects)],
            ];
        }
    }
}
