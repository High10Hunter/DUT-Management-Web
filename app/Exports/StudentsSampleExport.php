<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsSampleExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'Tên',
            'Ngày sinh',
            'Giới tính',
            'Email',
            'SĐT',
            'Ngành',
            'Khoá'
        ];
    }

    public function array(): array
    {
        return [
            ["test1", "2003-08-23", "0", "test1@gmail.com", "020-181-263", "BIM", "K23"],
            ["test2", "2002-07-18", "1", "test2@gmail.com", "019-191-233", "BIM", "K23"],
        ];
    }
}
