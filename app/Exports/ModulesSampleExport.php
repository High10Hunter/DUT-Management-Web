<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ModulesSampleExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'Mã môn',
            'Mã giảng viên',
            'Lịch học',
            'Tiết bắt đầu',
            'Tiết kết thúc',
            'Ngày bắt đầu',
            'Số tiết'
        ];
    }

    public function array(): array
    {
        return [
            ["10", "26", "3,5,7", "1", "3", "2022-03-19", 5],
            ["8", "9", "2,4,6", "1", "3", "2022-03-19", 5],
        ];
    }
}
