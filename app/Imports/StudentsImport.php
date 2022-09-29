<?php

namespace App\Imports;

use App\Enums\UserRoleEnum;
use App\Models\_Class;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\Major;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToArray, WithHeadingRow
{
    public int $studentsPerClass;

    public function __construct($studentsPerClass)
    {
        $this->studentsPerClass = $studentsPerClass;
    }

    public function array(array $arr)
    {
        try {
            $studentArr = [];
            foreach ($arr as $each) {
                $major = $each['nganh'];
                $studentArr[$major][] = $each;
            }


            foreach ($studentArr as $major => $students) {
                $i = 0;
                foreach (array_chunk($students, $this->studentsPerClass) as $index => $studentsChunk) {
                    $majorId = Major::query()
                        ->where('name', $major)
                        ->value('id');

                    $courseId = Course::firstOrCreate([
                        'name' => $students[0]['khoa'],
                    ])->id;

                    $class = _Class::create([
                        'name' => $this->generateClassName($major) . ($index + 1),
                        'major_id' => $majorId,
                        'course_id' => $courseId,
                    ]);

                    foreach ($studentsChunk as $student) {
                        $i++;
                        $user = User::create([
                            'name' => $student['ten'],
                            'username' => "STU" . $this->generateStudentCode($courseId, $majorId, $i),
                            'password' => Hash::make("STU" . $this->generateStudentCode($courseId, $majorId, $i)),
                            'gender' => $student['gioi_tinh'],
                            'birthday' => $student['ngay_sinh'],
                            'email' => $student['email'],
                            'phone_number' => $student['sdt'],
                            'role' => UserRoleEnum::STUDENT,
                        ]);

                        Student::insert([
                            'student_code' => $this->generateStudentCode($courseId, $majorId, $i),
                            'name' => $student['ten'],
                            'birthday' => $student['ngay_sinh'],
                            'gender' => $student['gioi_tinh'],
                            'email' => $student['email'],
                            'phone_number' => $student['sdt'],
                            'class_id' => $class->id,
                            'user_id' => $user->id,
                        ]);
                    }
                }
            }
        } catch (\Throwable $th) {
            // dd($each, $th->getMessage());
            return $th->getMessage();
        }
    }

    public function generateClassName($majorName): string
    {
        //make sure the major name is an ascii type 
        $majorName = Str::ascii($majorName);

        $str = explode(' ', $majorName);
        $className = "";
        foreach ($str as $each) {
            $each = Str::ucfirst($each);
            $className .= $each[0];
        }

        return $className;
    }

    public function generateStudentCode($courseId, $majorId, $index)
    {
        $studentCode = "";

        $studentCode .= $courseId . $majorId . $index;
        $studentCode = (int)$studentCode;

        return $studentCode;
    }
}
