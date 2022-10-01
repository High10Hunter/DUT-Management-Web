<?php

namespace App\Http\Controllers;

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
        $lecturerId = auth()->user()->id;
        date_default_timezone_set("Asia/Bangkok");
        $currentWeekday = now()->isoFormat('E') + 1;

        $modules = Module::query()
            ->with(['subject:id,name'])
            ->where(
                [
                    'lecturer_id' => $lecturerId,
                    'status' => 1,
                ]
            )
            ->whereJsonContains('schedule', json_encode($currentWeekday))
            ->get();


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

        date_default_timezone_set("Asia/Bangkok");
        $currentWeekday = now()->isoFormat('E') + 1;

        $search = $request->get('q');
        $maxExcused = 3;
        $teachedLessons = $this->model->where('module_id', $moduleId)->count();

        $modules = Module::query()
            ->with(['subject:id,name'])
            ->where(
                [
                    'lecturer_id' => $lecturerId,
                    'status' => 1,
                ]
            )
            ->whereJsonContains('schedule', json_encode($currentWeekday))
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
            $countStatus = PeriodAttendanceDetail::getTotalStatusOfCurrentPeriod($attendance->id);
        }

        $query = Student::query()->studentAttendanceOverallStatus($moduleId, $attendance);

        if (!is_null($search)) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('student_code', $search);
        }

        $students = $query->get();

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
        $remainingLessons = $request->get('remaining_lessons');
        $statusArr = $request->get('status');

        if (!is_null($statusArr)) {
            try {
                $period = $this->model
                    ->where([
                        'module_id' => (int)$moduleId,
                        'date' => date('Y-m-d'),
                        'lecturer_id' => $lecturerId,
                    ])
                    ->first();

                if (is_null($period) && $remainingLessons > 0) {
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

                $students = Student::query()
                    ->studentAttendanceOverallStatus($moduleId, $period)
                    ->get();

                $totalNotAttendedArr = [];
                $totalExcusedArr = [];
                foreach ($students as $student) {
                    $totalNotAttendedArr[$student->id] = $student->not_attended_count + $student->late_count * 0.5;
                    $totalExcusedArr[$student->id] = $student->excused_count;
                }

                $teachedLessons = Period::query()
                    ->where('module_id', $moduleId)->count();

                $data = [$totalNotAttendedArr, $totalExcusedArr, $teachedLessons];
            } catch (\Throwable $th) {
                return $this->errorResponse('Không thể điểm danh !');
            }
        } else {
            return $this->errorResponse('Không thể điểm danh, vui lòng chọn trạng thái đi học của sinh viên !');
        }

        return $this->successResponse($data, 'Đã cập nhật điểm danh');
    }
}
