<?php

namespace App\Imports;

use App\Models\Module;
use App\Models\ModuleStudent;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ModuleStudentImport implements ToArray, WithHeadingRow
{
    public int $moduleId;

    public function __construct($moduleId)
    {
        $this->moduleId = $moduleId;
    }

    public function array(array $arr)
    {
        try {
            foreach ($arr as $each) {
                $studentId = Student::query()
                    ->where('student_code', $each['ma_so_sinh_vien'])
                    ->value('id');

                ModuleStudent::insert([
                    'module_id' => $this->moduleId,
                    'student_id' => $studentId,
                ]);
            }

            Module::query()
                ->where('id', $this->moduleId)
                ->update([
                    'status' => 1
                ]);
        } catch (\Throwable $th) {
            dd($each, $th->getMessage());
            return $th->getMessage();
        }
    }
}
