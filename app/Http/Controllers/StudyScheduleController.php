<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Module;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class StudyScheduleController extends Controller
{
    private string $title = "Quản lý học tập";

    public function __construct()
    {
        View::share('title', $this->title);
    }

    public function index()
    {
        return view('students.index');
    }

    public function getSchedules()
    {
        $studentId = auth()->user()->student->id;
        $modules = Module::query()
            ->whereRelation('students', 'student_id', $studentId)
            ->with('subject:id,name')
            ->with('lecturer:id,name')
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

        $schedules = Module::getSchedulesForStudent($modules, $colors);
        return response()->json($schedules);
    }

    public function history()
    {
        $studentId = auth()->user()->student->id;
        $currentDate = now()->format('Y-m-d');

        $modules = Module::query()
            ->whereRelation('students', 'student_id', $studentId)
            ->where(function ($q) use ($currentDate) {
                $q->whereRelation('exam', 'date', '>', $currentDate);
                $q->orWhereDoesntHave('exam');
            })
            ->with(['subject:id,name'])
            ->get();

        return view('students.history-attendance', [
            'modules' => $modules,
        ]);
    }

    public function historyAttendanceOfOneStudent(Request $request, $moduleId = null)
    {
        $moduleId ??= $request->get('module_id');
        $module = Module::getModule($moduleId);
        $moduleLessons = $module->lessons;
        $periods = $module->periods()->get();

        $studentId = auth()->user()->student->id;
        $currentDate = now()->format('Y-m-d');

        $modules = Module::query()
            ->whereRelation('students', 'student_id', $studentId)
            ->where(function ($q) use ($currentDate) {
                $q->whereRelation('exam', 'date', '>', $currentDate);
                $q->orWhereDoesntHave('exam');
            })
            ->with(['subject:id,name'])
            ->get();

        $configs = Config::getAndCache();

        $periodsId = $periods->pluck('id');
        $periodsDate = $periods->pluck('period_date');


        $query = Student::query()
            ->where('id', $studentId)
            ->getOneStudentHistoryAttendance($studentId, $periodsId);

        $historyAttendances = $query->get()
            ->map(function ($each) {
                $each->class_name = $each->class->name;
                unset($each->class);
                return $each;
            });

        return view('students.history-attendance', [
            'modules' => $modules,
            'moduleId' => $moduleId,
            'module' => $module,
            'periodsDate' => $periodsDate,
            'moduleLessons' => $moduleLessons,
            'historyAttendances' => $historyAttendances,
            'configs' => $configs,
        ]);
    }
}
