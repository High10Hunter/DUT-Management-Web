<?php

namespace App\Models;

use App\Enums\PeriodAttendanceStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodAttendanceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'student_id',
        'status',
    ];

    public $timestamps = false;

    public static function getTotalStatusOfCurrentPeriod($periodId): array
    {
        $countStatus = [];
        $countStatus['attended'] = 0;
        $countStatus['notAttended'] = 0;
        $countStatus['excused'] = 0;
        $countStatus['late'] = 0;

        $query = self::query()
            ->where('period_id', $periodId)
            ->get();
        $groups = $query->groupBy('status')->all();

        foreach ($groups as $status => $group) {
            if ($status == PeriodAttendanceStatusEnum::ATTENDED) {
                $countStatus['attended'] = $group->count();
            } else if ($status == PeriodAttendanceStatusEnum::NOT_ATTENDED) {
                $countStatus['notAttended'] = $group->count();
            } else if ($status == PeriodAttendanceStatusEnum::EXCUSED) {
                $countStatus['excused'] = $group->count();
            } else if ($status == PeriodAttendanceStatusEnum::LATE) {
                $countStatus['late'] = $group->count();
            }
        }
        return $countStatus;
    }
}
