<?php

namespace App\Exports;

use App\Models\Exam;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExamStudentsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    public function headings(): array
    {
        return [
            'MSSV',
            'Tên sinh viên',
            'Lớp',
        ];
    }

    public function __construct(int $moduleId)
    {
        $this->moduleId = $moduleId;

        return $this;
    }

    public function map($student): array
    {
        return [
            $student->student_code,
            $student->name,
            $student->class->name,
        ];
    }

    public function query()
    {
        $query = Exam::find($this->moduleId)
            ->students()
            ->with('class:id,name');

        return $query;
    }
}
