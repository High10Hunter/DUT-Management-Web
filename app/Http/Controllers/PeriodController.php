<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\Module;
use App\Models\PeriodAttendanceDetail;
use App\Models\Student;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodController extends Controller
{
    private string $title = "Điểm danh sinh viên";
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Period::query();
        $this->table = (new Period())->getTable();
        View::share('title', $this->title);
    }

    public function index()
    {
        $lecturer_id = auth()->user()->id;
        $modules = Module::query()
            ->with(['subject:id,name'])
            ->where('lecturer_id', $lecturer_id)
            ->get();

        date_default_timezone_set("Asia/Bangkok");
        $currentWeekday = now()->isoFormat('E');

        return view("lecturers.$this->table.index", [
            'modules' => $modules,
            'currentWeekday' => $currentWeekday,
        ]);
    }

    public function form(Request $request)
    {
        $moduleId = $request->get('module_id');
        $lecturerId = auth()->user()->id;
        $modules = Module::query()
            ->with(['subject:id,name'])
            ->where('lecturer_id', $lecturerId)
            ->get();

        $attendance = $this->model
            ->where([
                'module_id' => $moduleId,
                'date' => date('Y-m-d'),
                'lecturer_id' => $lecturerId
            ])
            ->first();

        date_default_timezone_set("Asia/Bangkok");
        $currentWeekday = now()->isoFormat('E');

        $students = Student::query()
            ->whereRelation('modules', 'module_id', $moduleId)
            ->with([
                'attendance' => function ($query) use ($attendance) {
                    $query->where('period_id', optional($attendance)->id);
                }
            ])
            ->get();

        return view("lecturers.$this->table.index", [
            'modules' => $modules,
            'moduleId' => $moduleId,
            'students' => $students,
            'attendance' => $attendance,
            'currentWeekday' => $currentWeekday,
        ]);
    }

    public function attendance(Request $request)
    {
        $moduleId = $request->get('module_id');
        $lecturerId = auth()->user()->id;
        $statusArr = $request->get('status');

        if (!is_null($statusArr)) {
            $period = Period::query()
                ->where([
                    'module_id' => (int)$moduleId,
                    'date' => date('Y-m-d'),
                    'lecturer_id' => $lecturerId,
                ])
                ->first();

            if (is_null($period)) {
                $period = Period::create([
                    'module_id' => (int)$moduleId,
                    'date' => date('Y-m-d'),
                    'lecturer_id' => $lecturerId,
                ]);
            }

            foreach ($statusArr as $studentId => $status) {
                PeriodAttendanceDetail::upsert(
                    [
                        'period_id' => $period->id,
                        'student_id' => $studentId,
                        'status' => (int)$status,
                    ],
                    [
                        'period_id',
                        'student_id',
                    ],
                    [
                        'status',
                    ]
                );
            }
        } else {
            dd("Không thể điểm danh, vui lòng chọn trạng thái đi học của sinh viên !");
        }
    }
}
