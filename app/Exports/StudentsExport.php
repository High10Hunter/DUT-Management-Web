<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    public function headings(): array
    {
        return [
            'MSSV',
            'Tên sinh viên',
            'Ngày sinh',
            'Giới tính',
            'Email',
            'SĐT',
        ];
    }

    public function __construct(int $courseId = null, int $majorId = null, int $classId = null)
    {
        $this->course_id = $courseId;
        $this->major_id = $majorId;
        $this->class_id = $classId;

        return $this;
    }

    public function map($student): array
    {
        return [
            $student->student_code,
            $student->name,
            $student->birthday,
            $student->gender_name,
            $student->email,
            $student->phone_number,
        ];
    }

    public function query()
    {
        if (!is_null($this->course_id)) {
            $query = Student::query()
                ->whereRelation('class', 'course_id', $this->course_id)
                ->orderBy('name');
            if (!is_null($this->major_id)) {
                $query = Student::query()->clone()
                    ->whereRelation('class', 'major_id', $this->major_id)
                    ->orderBy('name');
            } else {
                $query = Student::query()->clone()
                    ->with(['class:id,name'])
                    ->orderBy('name');
            }
        }

        if (!is_null($this->major_id)) {
            $query = Student::query()
                ->whereRelation('class', 'major_id', $this->major_id)
                ->orderBy('name');
        } else {
            $query = Student::query()->clone()
                ->with(['class:id,name'])
                ->latest();
        }

        if (!is_null($this->class_id)) {
            $query->where('class_id', $this->class_id);
        }
        return $query;
    }
}
