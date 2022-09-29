<?php

namespace App\Imports;

use App\Models\Module;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ModulesImport implements ToArray, WithHeadingRow
{
    public function array(array $arr)
    {
        try {
            $moduleArr = [];
            foreach ($arr as $each) {
                $subject = $each['ma_mon'];
                $moduleArr[$subject][] = $each;
            }

            foreach ($moduleArr as $module) {
                foreach ($module as $index => $each) {
                    $subjectId = $each['ma_mon'];
                    $moduleName = $this->generateModuleName($subjectId, $index);
                    $lecturerId = $each['ma_giang_vien'];
                    $schedule =  explode(',', $each['lich_hoc']);
                    $startSlot = $each['tiet_bat_dau'];
                    $endSlot = $each['tiet_ket_thuc'];
                    $beginDate = $each['ngay_bat_dau'];
                    $lessons = $each['so_tiet'];

                    Module::insert([
                        'name' => $moduleName,
                        'subject_id' => $subjectId,
                        'lecturer_id' => $lecturerId,
                        'schedule' => json_encode($schedule),
                        'start_slot' => $startSlot,
                        'end_slot' =>  $endSlot,
                        'begin_date' =>  $beginDate,
                        'lessons' =>  $lessons,
                        'status' => 1,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            dd($moduleArr, $th->getMessage());
            return $th->getMessage();
        }
    }

    public function generateModuleName($subjectId, $index)
    {
        return $subjectId . '.' . now()->format('Y') . '.' . 'Nh' . ($index + 1);
    }
}
