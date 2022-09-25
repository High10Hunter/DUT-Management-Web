<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\ModuleStudent;
use App\Models\Student;
use Illuminate\Database\Seeder;

class ModuleStudentSeeder extends Seeder
{
    public function run()
    {
        $arr = [];
        $modules = Module::query()->pluck('id')->toArray();
        $students = Student::query()->pluck('id')->toArray();

        for ($i = 1; $i <= 10; $i++) {
            $arr[] = [
                'module_id' => $modules[array_rand($modules)],
                'student_id' => $students[array_rand($students)],
            ];

            ModuleStudent::insert($arr);
        }
    }
}
