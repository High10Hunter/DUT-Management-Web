<?php

namespace App\Models;

use App\Enums\TimeSlotEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_id',
        'lecturer_id',
        'schedule',
        'start_slot',
        'end_slot',
        'begin_date',
        'end_date',
        'status',
    ];

    public $timestamps = false;

    protected $casts = [
        'schedule' => 'array',
    ];

    public function getSlotRangeAttribute()
    {
        return $this->start_slot . ' - ' . $this->end_slot;
    }

    public function getStudyTimeAttribute()
    {
        $beginDate = Carbon::parse($this->begin_date)->format('d/m');

        return $beginDate;
    }

    public function getStatusNameAttribute()
    {
        return ($this->status === 1) ? 'Đang học' : 'Chưa mở';
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'module_student');
    }

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class);
    }

    public static function getModule($moduleId)
    {
        $periods = self::query()
            ->where('id', $moduleId)
            ->with([
                'periods',
            ])
            ->firstOrFail();

        return $periods;
    }

    public static function getSchedules($modules, $colors): array
    {
        $schedules = [];
        foreach ($modules as $index => $module) {
            $beginDate = $module->begin_date;
            $beginDate = Carbon::createFromFormat('Y-m-d', $beginDate);

            $scheduleDays = $module->schedule;

            $lessons = $module->lessons;
            $moduleName = $module->subject->name . ' - ' . $module->name;

            $startSlot = Carbon::createFromFormat('H:i:s', TimeSlotEnum::getStartTimeBySlotId($module->start_slot));
            $duration = ($module->end_slot - $module->start_slot) * TimeSlotEnum::DURATION;
            $endSlot = $startSlot->copy()->addMinutes($duration);


            $schedules[] = [
                "title" => $moduleName,
                "begin_date" => $beginDate,
                "start_slot" => $startSlot->format('H:i:s'),
                "end_slot" => $endSlot->format('H:i:s'),
                "color" => $colors[$index],
            ];
            --$lessons;
            while ($lessons > 0) {
                for ($i = 1; $i < count($scheduleDays); $i++) {
                    $current = end($schedules)['begin_date'];
                    $currentDayOfWeek = $current->dayOfWeek + 1;
                    $offset = (int)$scheduleDays[$i] - $currentDayOfWeek;

                    $next = $current->copy()->addDays($offset);
                    $schedules[] = [
                        "title" => $moduleName,
                        "begin_date" => $next,
                        "start_slot" => $startSlot->format('H:i:s'),
                        "end_slot" => $endSlot->format('H:i:s'),
                        "color" => $colors[$index],
                    ];
                    --$lessons;
                }

                if ($lessons > 0) {
                    $startDayOfWeekIndex = (count($schedules) - 1) - (count($scheduleDays) - 1);
                    $startDayOfWeek = $schedules[$startDayOfWeekIndex]['begin_date'];
                    $next = $startDayOfWeek->copy()->addDays(7);
                    $schedules[] = [
                        "title" => $moduleName,
                        "begin_date" => $next,
                        "start_slot" => $startSlot->format('H:i:s'),
                        "end_slot" => $endSlot->format('H:i:s'),
                        "color" => $colors[$index],
                    ];
                    --$lessons;
                }
            }
        }

        $schedules = array_map(function ($each) {
            return [
                "title" => $each['title'],
                "start" => $each['begin_date']->format('Y-m-d') . ' ' . $each['start_slot'],
                "end" => $each['begin_date']->format('Y-m-d') . ' ' . $each['end_slot'],
                "color" => $each['color'],
                'extendedProps' => [
                    'start' => Carbon::parse($each['start_slot'])->format('H:i'),
                    'end' => Carbon::parse($each['end_slot'])->format('H:i'),
                ]
            ];
        }, $schedules);

        return $schedules;
    }
}
