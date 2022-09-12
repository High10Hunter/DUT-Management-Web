<?php

namespace App\Imports;

use App\Models\Faculty;
use App\Models\Major;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MajorsImport implements ToArray, WithHeadingRow
{
    public function array(array $array)
    {
        try {
            foreach ($array as $each) {
                $majorName = $each['ten_nganh'];
                $facultyName = $each['khoa'];

                $faculty_id = Faculty::firstOrCreate([
                    'name' => $facultyName,
                ])->id;

                $storeArr['name'] =  $majorName;
                $storeArr['faculty_id'] = $faculty_id;

                Major::create([
                    'name' => $majorName,
                    'faculty_id' => $faculty_id,
                ]);
            }
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
}
