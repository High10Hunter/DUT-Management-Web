<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MajorsSampleExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'Tên ngành',
            'Tên khoa',
        ];
    }

    public function array(): array
    {
        return [
            ["test1", "test1"],
            ["test2", "test2"],
        ];
    }
}
