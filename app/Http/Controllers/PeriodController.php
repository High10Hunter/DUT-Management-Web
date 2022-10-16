<?php

namespace App\Http\Controllers;

use App\Models\Config;
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
        $currentWeekday = now()->isoFormat('E') + 1;

        $modules = Module::query()
            ->with(['subject:id,name'])
            ->where(
                [
                    'lecturer_id' => $lecturerId,
                    'status' => 1,
                ]
            )
            // ->whereJsonContains('schedule', json_encode($currentWeekday))
            ->get();


        return view("lecturers.$this->table.index", [
            'modules' => $modules,
            'currentWeekday' => $currentWeekday,
        ]);
    }

    public function form(Request $request, $moduleId = null)
    {
        $moduleId ??= $request->get('module_id');
        $module = Module::getModule($moduleId);
        $moduleLessons = $module->lessons;

        $periods = $module->periods()->get();
        $periodsId = $periods->pluck('id');

        $lecturerId = auth()->user()->id;

        $currentWeekday = now()->isoFormat('E') + 1;

        $search = $request->get('q');

        $configs = Config::getAndCache();

        $teachedLessons = $this->model->where('module_id', $moduleId)->count();

        $modules = Module::query()
            ->with(['subject:id,name'])
            ->where(
                [
                    'lecturer_id' => $lecturerId,
                    'status' => 1,
                ]
            )
            // ->whereJsonContains('schedule', json_encode($currentWeekday))
            ->get();

        $period = $this->model
            ->where([
                'module_id' => $moduleId,
                'date' => date('Y-m-d'),
                'lecturer_id' => $lecturerId
            ])
            ->first();

        $countStatus = [];
        if (!is_null($period)) {
            $countStatus = PeriodAttendanceDetail::getTotalStatusOfCurrentPeriod($period->id);
        }

        $query = Student::query()->studentAttendanceOverallStatus($moduleId, $period, $periodsId);

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
            'period' => $period,
            'currentWeekday' => $currentWeekday,
            'countStatus' => $countStatus,
            'configs' => $configs,
            'teachedLessons' => $teachedLessons,
        ]);
    }

    public function attendance(Request $request): JsonResponse
    {
        $moduleId = $request->get('module_id');
        $module = Module::getModule($moduleId);
        $periods = $module->periods()->get();
        $periodsId = $periods->pluck('id');

        $lecturerId = auth()->user()->id;
        $remainingLessons = $request->get('remaining_lessons');
        $statusArr = $request->get('status');
        $lateCoefficient = $request->get('late_coefficient');

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
                    ->studentAttendanceOverallStatus($moduleId, $period, $periodsId)
                    ->get();

                $totalNotAttendedArr = [];
                $totalExcusedArr = [];
                foreach ($students as $student) {
                    $totalNotAttendedArr[$student->id] = getTotalAbsentLessons(
                        $student->not_attended_count,
                        $student->late_count,
                        $lateCoefficient
                    );

                    $totalExcusedArr[$student->id] = $student->excused_count;
                    if ($totalExcusedArr[$student->id] > 3) {
                        PeriodAttendanceDetail::query()
                            ->where([
                                'period_id' => $period->id,
                                'student_id' => $student->id,
                            ])
                            ->update([
                                'status' => 3,
                            ]);
                        return $this->errorResponse("Sinh viên đã hết lần nghỉ phép");
                    }
                }

                $teachedLessons = Period::query()
                    ->where('module_id', $moduleId)->count();

                $data = [$totalNotAttendedArr, $totalExcusedArr, $teachedLessons];
            } catch (\Throwable $th) {
                return $this->errorResponse('Không thể điểm danh !');
            }
        } else {
            return $this->errorResponse('Không thể điểm danh, vui lòng chọn trạng thái đi học của sinh viên');
        }
        return $this->successResponse($data, 'Đã cập nhật điểm danh');
    }

    public function historyAttendance(Request $request, $moduleId)
    {
        $module = Module::getModule($moduleId);
        $periods = $module->periods()->get();
        $search = $request->get('q');

        $configs = Config::getAndCache();

        $periodsId = $periods->pluck('id');
        $periodsDate = $periods->pluck('period_date');

        $query = Student::query()
            ->getStudentsHistoryAttendance($moduleId, $periodsId);

        if (!is_null($search)) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('student_code', $search);
        }

        $historyAttendances = $query->get()
            ->map(function ($each) {
                $each->class_name = $each->class->name;
                unset($each->class);
                return $each;
            });

        return view('lecturers.periods.history-attendance', [
            'module' => $module,
            'periodsDate' => $periodsDate,
            'historyAttendances' => $historyAttendances,
            'search' => $search,
            'configs' => $configs,
        ]);
    }

    public function updateHistoryAttendance(Request $request): JsonResponse
    {
        $periodId = $request->get('period_id');
        $studentId = $request->get('student_id');
        $status = $request->get('status');
        $statusArr = [
            1 =>  [
                "text" => '.',
                'displayClass' => 'text-success',
            ],
            0 => [
                "text" => 'N',
                'displayClass' => 'text-danger',
            ],
            2 => [
                "text" => 'P',
                'displayClass' => 'text-primary',
            ],
            3 => [
                "text" => 'M',
                'displayClass' => 'text-warning',
            ],
        ];
        try {
            PeriodAttendanceDetail::upsert(
                [
                    'period_id' => $periodId,
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

            return $this->successResponse([$statusArr[$status]], "Đã cập nhật điểm danh, tải lại trang để thấy sự thay đổi");
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse("Không thể cập nhật điểm danh");
        }
    }
}
