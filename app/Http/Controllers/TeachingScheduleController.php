<?php

namespace App\Http\Controllers;

use App\Enums\TimeSlotEnum;
use App\Models\Module;
use Carbon\Carbon;

class TeachingScheduleController extends Controller
{
    public function index()
    {
        return view('lecturers.schedules.index');
    }

    public function getSchedules()
    {
        $modules = Module::query()
            ->where('lecturer_id', auth()->user()->id)
            ->get();

        $schedule = [];
        foreach ($modules as $module) {
            $beginDate = $module->begin_date;
            $scheduleDays = $module->schedule;
            $lessons = $module->lessons;
            $moduleName = $module->name;

            $startSlot = Carbon::createFromFormat('H:i:s', TimeSlotEnum::getStartTimeBySlotId($module->start_slot));
            $endSlot = $startSlot->copy()->addMinutes(TimeSlotEnum::DURATION);

            $beginDate = Carbon::createFromFormat('Y-m-d', $beginDate);

            $schedule[] = [
                "title" => $moduleName,
                "begin_date" => $beginDate,
                "start_slot" => $startSlot->format('H:i:s'),
                "end_slot" => $endSlot->format('H:i:s'),
            ];
            --$lessons;
            while ($lessons > 0) {
                for ($i = 0; $i < count($scheduleDays); $i++) {
                    $current = end($schedule)['begin_date'];
                    $currentDayOfWeek = $current->dayOfWeek + 1;
                    $offset = (int)$scheduleDays[$i] - $currentDayOfWeek;

                    $next = $current->copy()->addDays($offset);
                    $schedule[] = [
                        "title" => $moduleName,
                        "begin_date" => $next,
                        "start_slot" => $startSlot->format('H:i:s'),
                        "end_slot" => $endSlot->format('H:i:s'),
                    ];
                    --$lessons;
                }

                if ($lessons > 0) {
                    $startDayOfWeekIndex = (count($schedule) - 1) - (count($scheduleDays) - 1);
                    $startDayOfWeek = $schedule[$startDayOfWeekIndex]['begin_date'];
                    $next = $startDayOfWeek->copy()->addDays(7);
                    $schedule[] = [
                        "title" => $moduleName,
                        "begin_date" => $next,
                        "start_slot" => $startSlot->format('H:i:s'),
                        "end_slot" => $endSlot->format('H:i:s'),
                    ];
                    --$lessons;
                }
            }
        }


        $schedule = array_map(function ($each) {
            return [
                "title" => $each['title'],
                "start" => $each['begin_date']->format('Y-m-d') . ' ' . $each['start_slot'],
                "end" => $each['begin_date']->format('Y-m-d') . ' ' . $each['end_slot'],
            ];
        }, $schedule);

        return response()->json($schedule);
    }
}
