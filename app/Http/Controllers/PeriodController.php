<?php

namespace App\Http\Controllers;

use App\Enums\PeriodAttendanceStatusEnum;
use App\Models\Period;
use App\Models\Module;
use App\Models\PeriodAttendanceDetail;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    use ResponseTrait;
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

    public function form(Request $request, $moduleId = null)
    {
        $moduleId ??= $request->get('module_id');
        $moduleLessons = Module::query()
            ->where('id', $moduleId)->value('lessons');
        $lecturerId = auth()->user()->id;
        $search = $request->get('q');
        $maxExcused = 3;
        $teachedLessons = $this->model->where('module_id', $moduleId)->count();

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

        $countStatus = [];
        if (!is_null($attendance)) {
            $countStatus['attended'] = PeriodAttendanceDetail::query()
                ->where([
                    'period_id' => $attendance->id,
                    'status' => PeriodAttendanceStatusEnum::ATTENDED,
                ])
                ->count();

            $countStatus['notAttended'] = PeriodAttendanceDetail::query()
                ->where([
                    'period_id' => $attendance->id,
                    'status' => PeriodAttendanceStatusEnum::NOT_ATTENDED,
                ])
                ->count();

            $countStatus['excused'] = PeriodAttendanceDetail::query()
                ->where([
                    'period_id' => $attendance->id,
                    'status' => PeriodAttendanceStatusEnum::EXCUSED,
                ])
                ->count();

            $countStatus['late'] = PeriodAttendanceDetail::query()
                ->where([
                    'period_id' => $attendance->id,
                    'status' => PeriodAttendanceStatusEnum::LATE,
                ])
                ->count();
        }

        date_default_timezone_set("Asia/Bangkok");
        $currentWeekday = now()->isoFormat('E');

        $students = Student::query()->clone()
            ->whereRelation('modules', 'module_id', $moduleId)
            ->with([
                'attendance' => function ($query) use ($attendance) {
                    $query->where('period_id', optional($attendance)->id);
                },
                'class:id,name',
            ])
            ->withCount([
                'attendances as not_attended_count' => function ($query) {
                    $query->where('status', PeriodAttendanceStatusEnum::NOT_ATTENDED);
                },
                'attendances as excused_count'  => function ($query) {
                    $query->where('status', PeriodAttendanceStatusEnum::EXCUSED);
                },
                'attendances as late_count'  => function ($query) {
                    $query->where('status', PeriodAttendanceStatusEnum::LATE);
                },
            ]);

        if (!is_null($search)) {
            $students->where('name', 'like', '%' . $search . '%')
                ->orWhere('student_code', $search);
        }

        $students = $students->get();

        return view("lecturers.$this->table.index", [
            'modules' => $modules,
            'search' => $search,
            'moduleId' => $moduleId,
            'moduleLessons' => $moduleLessons,
            'students' => $students,
            'attendance' => $attendance,
            'currentWeekday' => $currentWeekday,
            'countStatus' => $countStatus,
            'maxExcused' => $maxExcused,
            'teachedLessons' => $teachedLessons,
        ]);
    }

    public function attendance(Request $request): JsonResponse
    {
        $moduleId = $request->get('module_id');
        $lecturerId = auth()->user()->id;
        $statusArr = $request->get('status');

        if (!is_null($statusArr)) {
            $period = $this->model
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

            $students = Student::query()->clone()
                ->whereRelation('modules', 'module_id', $moduleId)
                ->with([
                    'attendance' => function ($query) use ($period) {
                        $query->where('period_id', optional($period)->id);
                    },
                ])
                ->withCount([
                    'attendances as not_attended_count' => function ($query) {
                        $query->where('status', PeriodAttendanceStatusEnum::NOT_ATTENDED);
                    },
                    'attendances as excused_count'  => function ($query) {
                        $query->where('status', PeriodAttendanceStatusEnum::EXCUSED);
                    },
                    'attendances as late_count'  => function ($query) {
                        $query->where('status', PeriodAttendanceStatusEnum::LATE);
                    },
                ])->get();

            $totalNotAttendedArr = [];
            $totalExcusedArr = [];
            foreach ($students as $student) {
                $totalNotAttendedArr[$student->id] = $student->not_attended_count + $student->late_count * 0.5;
                $totalExcusedArr[$student->id] = $student->excused_count;
            }

            $teachedLessons = Period::query()
                ->where('module_id', $moduleId)->count();

            $data = [$totalNotAttendedArr, $totalExcusedArr, $teachedLessons];
        } else {
            return $this->errorResponse('Không thể điểm danh, vui lòng chọn trạng thái đi học của sinh viên !');
        }

        return $this->successResponse($data, 'Đã cập nhật điểm danh');
    }
}
