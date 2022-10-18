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

        $schedules = Module::getSchedules($modules, $colors);

        return response()->json($schedules);
    }
}
