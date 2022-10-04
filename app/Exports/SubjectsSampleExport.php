<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubjectsSampleExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'Tên môn',
            'Tên ngành',
            'Tên khoa',
            'Số tín chỉ',
            'Khoá',
        ];
    }

    public function array(): array
    {
        return [
            ["test1", "test1", "Công nghệ thông tin", 3, "K18"],
            ["test2", "test2", "Công nghệ thông tin", 3, "K18"],
        ];
    }
}
