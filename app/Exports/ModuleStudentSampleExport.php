<?php

namespace App\Exports;

use App\Models\ModuleStudent;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ModuleStudentSampleExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'Mã số sinh viên',
        ];
    }

    public function array(): array
    {
        return [
            ["1156"],
            ["1615"],
            ["1615"],
        ];
    }
}
