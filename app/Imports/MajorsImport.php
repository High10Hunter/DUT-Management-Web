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
                $facultyName = $each['ten_khoa'];

                if (!is_null($facultyName)) {
                    $facultyId = Faculty::firstOrCreate([
                        'name' => trim($facultyName),
                    ])->id;
                }

                if (!is_null($majorName) && !is_null($facultyId)) {
                    Major::create([
                        'name' => $majorName,
                        'faculty_id' => $facultyId,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return $th->getMessage();
        }
    }
}
