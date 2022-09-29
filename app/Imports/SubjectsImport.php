<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Major;
use App\Models\MajorSubject;
use App\Models\Subject;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubjectsImport implements ToArray, WithHeadingRow
{
    public function array(array $array)
    {
        try {
            foreach ($array as $each) {
                $subjectName = $each['ten_mon'];
                $majorName = $each['ten_nganh'];
                $facultyName = $each['ten_khoa'];
                $number_of_credits = $each['so_tin_chi'];
                $courseName = $each['khoa'];

                $facultyId = Faculty::where('name', $facultyName)->firstOrFail()->id;

                if (!is_null($facultyId) && !is_null($majorName)) {
                    $majorId = Major::firstOrCreate([
                        'name' => trim($majorName),
                        'faculty_id' => $facultyId,
                    ])->id;
                }

                if (!is_null($courseName)) {
                    $courseId = Course::firstOrCreate([
                        'name' => trim($courseName),
                    ])->id;
                }

                if (!is_null($number_of_credits) && !is_null($subjectName)) {
                    $subjectId = Subject::firstOrCreate([
                        'name' => trim($subjectName),
                    ])->id;
                }

                if (!is_null($subjectId) && !is_null($majorId)) {
                    MajorSubject::create([
                        'subject_id' => $subjectId,
                        'major_id' => $majorId,
                        'number_of_credits' => $number_of_credits,
                        'course_id' => $courseId,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
