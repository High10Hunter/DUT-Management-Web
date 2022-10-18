<?php

namespace App\Http\Controllers;

use App\Enums\TimeSlotEnum;
use App\Models\Module;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class TeachingScheduleController extends Controller
{
    private string $title = "Xem lịch dạy";

    public function __construct()
    {
        View::share('title', $this->title);
    }


    public function index()
    {
        return view('lecturers.schedules.index');
    }

    public function getSchedules()
    {
        $lecturerId = auth()->user()->lecturer->id;
        $modules = Module::query()
            ->where('lecturer_id', $lecturerId)
            ->with('subject:id,name')
            ->get();

        $colors = [
            'red',
            '#3788d8',
            'green',
            'purple',
            '#fe5a1d', //Giants Orange
            'brown',
            '#9dc209', //Limerick
            '#c71585', //Violet
            '#48d1cc', //Aquamarine
            '#daa520',
        ];

        $schedule = [];
        foreach ($modules as $index => $module) {
            $beginDate = $module->begin_date;
            $beginDate = Carbon::createFromFormat('Y-m-d', $beginDate);

            $scheduleDays = $module->schedule;

            $lessons = $module->lessons;
            $moduleName = $module->subject->name . ' - ' . $module->name;

            $startSlot = Carbon::createFromFormat('H:i:s', TimeSlotEnum::getStartTimeBySlotId($module->start_slot));
            $duration = ($module->end_slot - $module->start_slot) * TimeSlotEnum::DURATION;
            $endSlot = $startSlot->copy()->addMinutes($duration);


            $schedule[] = [
                "title" => $moduleName,
                "begin_date" => $beginDate,
                "start_slot" => $startSlot->format('H:i:s'),
                "end_slot" => $endSlot->format('H:i:s'),
                "color" => $colors[$index],
            ];
            --$lessons;
            while ($lessons > 0) {
                for ($i = 1; $i < count($scheduleDays); $i++) {
                    $current = end($schedule)['begin_date'];
                    $currentDayOfWeek = $current->dayOfWeek + 1;
                    $offset = (int)$scheduleDays[$i] - $currentDayOfWeek;

                    $next = $current->copy()->addDays($offset);
                    $schedule[] = [
                        "title" => $moduleName,
                        "begin_date" => $next,
                        "start_slot" => $startSlot->format('H:i:s'),
                        "end_slot" => $endSlot->format('H:i:s'),
                        "color" => $colors[$index],
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
                        "color" => $colors[$index],
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
                "color" => $each['color'],
            ];
        }, $schedule);

        return response()->json($schedule);
    }
}
